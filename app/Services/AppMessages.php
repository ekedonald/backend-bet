<?php
namespace App\Services;

class AppMessages {

    public const LIST_FETCHED = "List has been fetched successfully";
    public const EMAIL_EXISTS = "Email Already Exists";
    public const EMAIL_AVALIABLE = "Email is avaliable";

    //Route
    public const ROUTE_NOT_FOUND = "The endpoint is not avaliable, try logging in may fix this error";

    //LOGIN
    public const LOGOUT_SUCCESSFUL = "You have been logged out succesfully";

    //TOKEN
    public const TOKEN_REFRESHED_SUCCESSFULLY = "Your token has been refreshed successfully";
    public const TOKEN_REFRESHED_UNSUCCESSFULLY = "You token cannot be refreshed";

    //User
    public const GET_AUTH_USER = "Authenticated user retrived successfuly";

    //OTP
    public const VERIFY_EMAIL = "Login successful, Please verify your email";
    public const OTP_EMAIL_SENT = "Otp has been sent to email";
    public const OLD_PASSWORD_WRONG = "Your old password is not correct";
    public const OLD_NEW_PASSWORD_NOT_SAME = "You cannot use your previous password";
    public const OTP_PHONE_SENT = "Otp has been sent to phone";
    public const WRONG_OTP_TYPE = "Use a valid otp type";
    public const FORGOT_EMAIL_NOT_FOUND = "We cannot find this email address in our database";
    public const TOKEN_EXPIRED = "Token expired or incorrect";
    public const FAIL_TO_RESEND_OTP = "OTP could not be resent, try again";
    public const OPT_VERIFIED = "OTP has been verified successfully";
    public const PHONE_VERIFIED = "Phone Number has been verified";
    public const EMAIL_VERIFIED = "Email has been verified";
    public const PASSWORD_RESET = "Password has been changed, login with new password now";
    public const NEW_PROFILE_PHOTO = "Profile photo updated";

    public const LOGIN_FAILED = "Login Failed";

    public const ACCOUNT_CREATED = "Account has been created successfully";
    public const WRONG_ACCOUNT_MATCH = "Invalid account match";
    public const INSUFFICENT_BALANCE = "Your Balance is too low to make transaction or amount is invalid";
    public const TRANSACTION_SUCCESSFULL = "Transaction is completed";
    public const CANNOT_SELF_TRANSFER = "You cannot transfer to yourself";

    // Welcome
    public const WELCOME_REGISTRATION_MAIL_SUBJECT = "Welcome to School Mag";


    public const OTP_VERIFY_EMAIL = "Please verify your email address";
    public const NEW_LOGIN_ALERT = "There is a new login to your account";
    public const NEW_TRANSACTION = "New Transaction";
    public const PRODUCT_DELETED = "Product Deleted";
    public const PRODUCT_CREATED = "Product Created";
    public const PRODUCT_UPDATED = "Product Updated";
    public const PRODUCT_NOT_FOUND = "Product Not Found";
    public const PRODUCT_FETECHED = "Product Feteched";
    public const CANNOT_PURCHASE_SELF_PRODUCT = "You cannot purchase your own product";
    public const CANNOT_CREATE_PRODUCT_NO_ACCOUNT = "Create your account number to store product";
    public const CUSTOMER_DELETED = "Customer Deleted";
    public const CUSTOMER_CREATED = "Customer Created";
    public const CUSTOMER_UPDATED = "Customer Updated";
    public const CUSTOMER_FETECHED = "Customer Feteched";

    //Permissions
    public const GET_PERMISSION_LIST = "Permission list retrived successfully";
    public const GET_PERMISSION = "Permission retrived successfully";
    public const CREATE_PERMISSION = "Permission has been created successfully";
    public const UPDATE_PERMISSION = "Permission has been updated successfully";
    public const PERMISSION_NOT_FOUND = "Permission cannot be found";
    public const PERMISSION_DELETED = "Permission has been deleted successfully";
    public const PERMISSION_NOT_DELETED = "Permission cannot be deleted";

    //Roles
    public const GET_ROLE_LIST = "Role list retrived successfully";
    public const GET_ROLE = "Role retrived successfully";
    public const CREATE_ROLE = "Role has been created successfully";
    public const UPDATE_ROLE = "Role has been updated successfully";
    public const ROLE_NOT_FOUND = "Role cannot be found";
    public const ROLE_DELETED = "Role has been deleted successfully";
    public const ROLE_NOT_DELETED = "Role cannot be deleted";
    public const ADD_ROLE_TO_USER = "Role has been assigned to user successfully";
    public const PERMISSION_FORBIDDEN = "Sorry, you do not have the permission or role of access this resources";

}
