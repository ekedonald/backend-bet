@extends('layouts.mail')
@section('content')
<tr>
    <td align="left" style="font-size: 0px; padding: 15px 15px 15px 15px; word-break: break-word;">
        <div style="font-family: Ubuntu, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; text-align: left; color: #000000;">
            <p style="font-size: 11px; font-family: Ubuntu, Helvetica, Arial;">
                <span style="font-size: 14px; color: #34495e;">
                    Welcome back {{ $user->name }}. New login alert from.
                </span>
                <h6 style="font-size: 14px; color: #34495e;">
                    Device Name: {{ $loginDevice["device"] }}
                </h6>
                <h6 style="font-size: 14px; color: #34495e;">
                    IP Address: {{ $loginDevice["ip"] }}
                </h6>
            </p>
        </div>
    </td>
</tr>
@endsection
