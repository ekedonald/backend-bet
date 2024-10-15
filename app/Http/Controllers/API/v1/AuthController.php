<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\v1\ResponseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProfilePhotoRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUser;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\GenerateTokenRequest;
use App\Http\Requests\GetUserReferalRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OTPRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ResetPasswordWithOptRequest;
use App\Http\Requests\ValidateEmailRequest;
use App\Models\RefreshToken;
use App\Models\User;
use App\Notifications\LoginNotification;
use App\Notifications\RegisterNotification;
use App\Services\AppConfig;
use Intervention\Image\Facades\Image;
use App\Services\AppMessages;
use App\Services\OTPService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function logout(){
        $user = auth()->user();
        $user->tokens()->delete();
        $user->deleteRefreshToken();
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::LOGOUT_SUCCESSFUL,
        ], Response::HTTP_OK);
    }


    public function generateAccessTokenFromRefreshToken(GenerateTokenRequest $request){
        $user_id = RefreshToken::where('token', $request->refresh_token)->first('user_id');
        if($user_id){
            $user = User::where('id', $user_id->user_id)->first();
            if($user){
                $user->tokens()->delete();
                $token =  $user->createToken($user->email)->accessToken;
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::TOKEN_REFRESHED_SUCCESSFULLY,
                    'token' => $token,
                    'user' => $user,
                ], Response::HTTP_OK);
            }else{
                return ResponseController::response(false,[
                    ResponseController::MESSAGE => AppMessages::TOKEN_REFRESHED_UNSUCCESSFULLY,
                ], Response::HTTP_FORBIDDEN);
            }
        }else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_REFRESHED_UNSUCCESSFULLY,
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function store(CreateUser $request) : mixed{
        $user = new User();
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = $user->createToken($user->email);
        $refreshtoken = $user->generateRefreshToken();
        // $otp = new OTPService();
        // $otp->generate(AppConfig::VERIFY_EMAIL, AppConfig::TOKEN_EXPIRE_TIME, $user);
        $user->assignRole(AppConfig::CUSTOMER);
        // Notification::send($user, new RegisterNotification($user));
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::ACCOUNT_CREATED,
            'user' => $user,
            'token' => [
                'accessToken' => $token->plainTextToken,
                'refreshToken' => $refreshtoken->token,
            ]
        ], Response::HTTP_OK);
    }

    public function forgotPassword(ForgotPasswordRequest $request): mixed {
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::FORGOT_EMAIL_NOT_FOUND,
            ], Response::HTTP_BAD_REQUEST);
        }
        return $this->generateOTP($user, AppConfig::VERIFY_EMAIL);
    }

    public function confirmOTP(OTPVerifyRequest $request): mixed {
        $otp = new OTPService();
        $user = $otp->getUserFromCode($request->code);
        if(!$user) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_EXPIRED,
            ], Response::HTTP_FORBIDDEN);
        };
        $check = $this->checkOTP($user, $request->type, $request->code);
        if(!$check) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_EXPIRED,
            ], Response::HTTP_FORBIDDEN);
        };

        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::OPT_VERIFIED,
        ], Response::HTTP_OK);
    }

    public function resetPasswordWithOtp(ResetPasswordWithOptRequest $request): mixed {
        $otp = new OTPService();
        $user = $otp->getUserFromCode($request->code);
        if(!$user) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_EXPIRED .' or '.AppMessages::WRONG_OTP_TYPE,
            ], Response::HTTP_FORBIDDEN);
        };
        $check = $this->checkOTP($user, $request->type, $request->code);
        if(!$check) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_EXPIRED .' or '.AppMessages::WRONG_OTP_TYPE,
            ], Response::HTTP_FORBIDDEN);
        };

        $user->password = bcrypt($request->password);

        $user->save();

        return ResponseController::response(false,[
            ResponseController::MESSAGE => AppMessages::PASSWORD_RESET,
        ], Response::HTTP_OK);
    }

    public function checkOTP(User $user, int $type, int $code): mixed {
        if(!$type === AppConfig::VERIFY_EMAIL || !$type === AppConfig::VERIFY_PHONE) {
            return false;
        }

        $otp = new OTPService();
        $otp_status = $otp->verify($user, (int)$code, $type);

        if(!$otp_status){
            return false;
        }

        return true;
    }

    public function verifyOTP(OTPVerifyRequest $request) : mixed{
        $user = auth()->user();
        $check = $this->checkOTP($user, $request->type, $request->code);
        if(!$check) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::TOKEN_EXPIRED .' or '.AppMessages::WRONG_OTP_TYPE,
            ], Response::HTTP_FORBIDDEN);
        };

        if($request->type === AppConfig::VERIFY_EMAIL){
            $user->email_verified_at = now();
            $user->save();
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::EMAIL_VERIFIED,
                'user' => $user
            ], Response::HTTP_OK);
        }
        if($request->type === AppConfig::VERIFY_PHONE){
            $user->phone_verified_at = now();
            $user->save();
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::PHONE_VERIFIED,
                'user' => $user
            ], Response::HTTP_OK);
        }
    }

    public function validateEmail(ValidateEmailRequest $request) : mixed{
        $check = User::where('email', $request->email)->exists();
        if($check){
            return ResponseController::response(
                false,
                AppMessages::EMAIL_EXISTS,
                Response::HTTP_NOT_FOUND
            );
        }else{
            return ResponseController::response(
                true,
                AppMessages::EMAIL_AVALIABLE,
                Response::HTTP_OK
            );
        }
    }

    public function getAuthUser() : mixed{
        $user = auth()->user();
        $user->roles = $user->roles;
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::GET_AUTH_USER,
            'user' => $user,
        ], Response::HTTP_OK);
    }

    public function getAuthUserBalance() {
        $balance = auth()->user()->balance;
        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::GET_AUTH_USER,
            'balance' => $balance,
        ], Response::HTTP_OK);
    }

    public function login(LoginRequest $request) : mixed{
        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $user = auth()->user();
            $token =  $user->createToken($user->email);
            $user->roles = $user->roles;
            $refreshtoken = $user->generateRefreshToken();
            // Notification::send($user, new LoginNotification($user, $request->loginDevice));
            if(!$user->email_verified_at){
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::VERIFY_EMAIL,
                    'user' => $user,
                    'token' => [
                        'accessToken' => $token->plainTextToken,
                        'refreshToken' => $refreshtoken->token,
                    ]
                ], Response::HTTP_OK);
            }else{
                return ResponseController::response(true,[
                    'user' => $user,
                    'token' => [
                        'accessToken' => $token->plainTextToken,
                        'refreshToken' => $refreshtoken->token,
                    ]
                ], Response::HTTP_OK);
            }
        }
        else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::LOGIN_FAILED,
            ], Response::HTTP_FORBIDDEN);
        }
    }

    public function resend_otp(OTPRequest $request) :mixed{
        return $this->generateOTP(auth()->user(), $request->type);
    }

    protected function generateOTP(User $user, int $type): mixed {
        $generate_otp = new OTPService();
        if($generate_otp->generate($type, AppConfig::TOKEN_EXPIRE_TIME, $user)){
            if($type === AppConfig::VERIFY_EMAIL){
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::OTP_EMAIL_SENT,
                ], Response::HTTP_OK);
            }
            if($type === AppConfig::VERIFY_PHONE){
                return ResponseController::response(true,[
                    ResponseController::MESSAGE => AppMessages::OTP_PHONE_SENT,
                ], Response::HTTP_OK);
            }
        }else{
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::FAIL_TO_RESEND_OTP,
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(ChangePasswordRequest $request) {
        $user = auth()->user();

        if (!Hash::check($request->oldPassword, $user->password)) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::OLD_PASSWORD_WRONG,
            ], Response::HTTP_BAD_REQUEST);
        }

        if (Hash::check($request->newPassword, $user->password)) {
            return ResponseController::response(false,[
                ResponseController::MESSAGE => AppMessages::OLD_NEW_PASSWORD_NOT_SAME,
            ], Response::HTTP_BAD_REQUEST);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::PASSWORD_RESET,
        ], Response::HTTP_OK);
    }

    public function addProfilePhoto(AddProfilePhotoRequest $request){
        $user = auth()->user();
        $file = $request->file('file');

        $fileName = Str::slug($user->email) . '.png';

        Image::make($file)
            ->resize(200, 200)
            ->encode('png', 90)
            ->save(storage_path('app/public/profile_photos/' . $fileName));

        $user->avatar = $fileName;
        $user->save();

        return ResponseController::response(true,[
            ResponseController::MESSAGE => AppMessages::NEW_PROFILE_PHOTO,
            'path' => asset('storage/profile_photos/'.$fileName)
        ], Response::HTTP_OK);
    }

}
