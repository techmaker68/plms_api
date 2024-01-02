<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penalty Email</title>
</head>
<body>
    <div style="margin-bottom: 20px;">
    <h1>
        @if(isset($data['penalty_fee']) && $data['penalty_fee'] != '')
            Penalty Amount: {{ $data['penalty_fee'] }}
        @elseif(isset($data['visa_penalty_fee']) && $data['visa_penalty_fee'] != '')
            Visa Penalty Amount: {{ $data['visa_penalty_fee'] }}
        @else
            No penalty specified.
        @endif
    </h1>
        <div style="overflow: auto; white-space: pre-wrap; word-wrap: break-word;">{!! $data['penalty_remarks'] != ''  ? $data['penalty_remarks'] :  $data['visa_penalty_remarks'] !!}</div>
    </div>
    <div style="padding-top:20px;">
    <footer>
        <p>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Blood/ Exit Visa Team<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Mobile: +9647833042313<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Email Address: blood-exit-visa@majnoon-ifms.com<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;"> Majnoon IFMS People Logistics Services Team</span>
            <div style="margin:20px 30px;"><img src="https://plmsapi.fieldsops.com/email/majnoon.png"  style="height: 40px" alt="Description"><img src="https://plmsapi.fieldsops.com/email/logo.png" style="height: 35px" alt="Description">
            </div>
        </p>
    </footer>
    </div>
</body>
</html>
