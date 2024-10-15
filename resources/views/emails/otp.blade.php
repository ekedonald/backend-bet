@extends('layouts.mail')
@section('content')
<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e;">To verify your FlurryPay account, please use the following One Time Password (OTP)</span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; text-align: center;">
                <span style="color: #34495e;">
                    <strong><span style="font-size: 48px;">{{ $otp->code }}</span></strong>
                </span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; text-align: center;">
                {{--  <span style="font-size: 14px; color: #34495e;">This OTP expires in 15 minutes ({{ $expire}})</span>  --}}
            </p>
        </div>
    </td>
</tr>
@endsection
