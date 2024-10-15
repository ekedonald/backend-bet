@extends('layouts.mail')
@section('content')
<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e;">Dear <span style="text-transform: uppercase;">{{ $user->fullname() }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e;">We wish to inform you that a {{ $transaction->type }} transaction has been recorded in your account</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; text-align: center;">
                <span style="color: #34495e;">
                    <strong><span style="font-size: 48px;"></span>Transaction details</strong>
                </span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Transaction ID:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->transaction_id }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Amount:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->amount }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Transaction type:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->type }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Status:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->status }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Token:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->sending_currency }}</span></span>
            </p>
        </div>
    </td>
</tr>

<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e">Transaction Date:</span></span>
            </p>
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial; margin-top: 7px"">
                <span style="font-size: 14px; color: #34495e; font-weight: bold;">{{ $transaction->created_at }}</span></span>
            </p>
        </div>
    </td>
</tr>

@endsection
