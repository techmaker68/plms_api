<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Test Schedule</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    padding: 20px;
    max-width: 100%;
    margin: auto;
    display: flex;           /* Step 1: Make the body act as a flex container */
    flex-direction: column;  /* Stack children vertically */
    min-height: 100vh;       /* At least full viewport height */
}

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        td,
        th {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .note{
            font-size: 12px;
            margin: 20px 30px;
        }
        .note-p{
            font-size: 13px;
            margin: 20px 30px;
            color: red;
            font-weight: bold;
        }
        h1{
            font-size: 12px;
            font-weight: lighter;
            font-style: italic;
        }
        .header,
        .footer {
            margin-bottom: 20px;
        }
        .head-message{
            margin-left: 29px;
            font-size: 12px;
            /* text-wrap: balance; */
        }
        .footer {
    margin-top: auto;        /* Step 2: Push footer to the bottom */
    /* ... other styles ... */
}
    </style>
</head>

<body>
    <div class="header">
        <h1>Dear All</h1>
        <div class="head-message">
            <p>
                Kindly be informed that the blood test application has been processed, therefore, you are requested to attend
                the blood test on the below-mentioned date in  <span style="font-size: 16px;
                font-weight: bold;
                background: #61d575;">{{$batch->venue_name ?? ' '}} </span> {{' '}} as illustrated in the attached Map. All blood
                test <br /> participants are requested to attend in the time window given to them as mentioned in the below table
                to keep maximum social distance.
            </p>
        </div>
        <div>
            <p class="note">
                <span style="background: yellow">Note/</span> it is Mandatory to keep Social distancing of at least 1 Meters at all times.
            </p>
        </div>
        <div>
            <p class="note-p">
                <span style="background: yellow">
                Please note: As we have been instructed from the health department, fixers of Basra health department for
                all companies must attend with their employees for the Blood test just in case.
                </span>
            </p>
            <h2><span style="font-size: 16px;
                margin: 20px 30px;
                font-weight: bold;
                background: #61d575;">{{$batch->test_date ?? ' '}}</span> </h2>
        </div>
    </div>
    <div class="table-section" style="margin: 20px 29px 26px 26px;">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name as per Passport</th>
                    <th>Test Place</th>
                    <th>Time</th>
                    <th>Appoint Date</th>
                    <th>Company</th>
                </tr>
            </thead>
            <tbody>
                <!-- First row as an example; similar rows follow for other records -->
                @foreach($applicants as $index => $applicant)
                <tr>
                    <td>{{++$index}}</td>
                    <td>{{$applicant->pax ? $applicant->pax->eng_full_name : $applicant->full_name}}</td>
                    <td>{{$batch->venue_name ?? ' '}}</td>
                    <td>{{$applicant->appoint_time}}</td>
                    <td>{{$batch->test_date ?? ' '}}</td>
                    <td>{{$applicant->pax && $applicant->pax->comany ? $applicant->pax->comany->name : $applicant->employer}}</td>
                </tr>
                @endforeach
                <!-- Add other rows similarly -->
            </tbody>
        </table>
        <div>
            <p style="color: brown">Thank you,</p>
        </div>
    </div>
    <div>
        <p>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Blood/ Exit Visa Team<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Mobile: +9647833042313<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;">Email Address: blood-exit-visa@majnoon-ifms.com<br></span>
            <span style="font-weight: bold;color: #635454;font-size: 17px;margin:20px 30px;"> Majnoon IFMS People Logistics Services Team</span>
            <div style="margin:20px 30px;"><img src="https://plmsapi.fieldsops.com/email/majnoon.png"  style="height: 40px" alt="Description"><img src="https://plmsapi.fieldsops.com/email/logo.png" style="height: 35px" alt="Description">
            </div>
        </p>
    </div>

    {{-- <div class="footer" style="margin-top: 80px;">
        <p class="confidential" style="margin: 20px 25px;">
            CONFIDENTIAL: This email and any files transmitted with it are confidential and intended solely for the use
            of the individual or entity to whom they are addressed. If you are not the named <br />addressee you should not
            disseminate, distribute or copy this e-mail. Please notify the sender or <a href="postmaster@majnoon-ifms.com">postmaster@majnoon-ifms.com</a>
            immediately by e-mail if you have received this e-mail by <br /> mistake and delete this e-mail from your system.
            If you are not the intended recipient you are notified that disclosing, copying, distributing or taking any
            action in reliance on the contents of <br />this information is strictly prohibited.
        </p>
    </div> --}}

</body>

</html>
