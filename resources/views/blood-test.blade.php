<body>
    <div class="letter-content">
        <table class="noHover" cellpadding="0" cellspacing="0" style="
         border-collapse: collapse;
         text-align: center;
         width: 100%;
         font-family: sans-serif;
         font-size: 14px;
         ">
            <tbody>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                            <tbody>
                                <tr>
                                    <td style="
                              text-align: left;
                              border-bottom: 2px solid #1e2678;
                              padding-block-end: 5px;
                              font-size: 12px;
                              color: #000;
                              ">
                                        <a href="www.antonoil.com" style="text-decoration: none; color: #1f4e78">
                                            www.antonoil.com
                                        </a>
                                    </td>
                                    <td style="
                              border-bottom: 2px solid #bf0022;
                              padding-block-end: 5px;
                              ">
                                        <img src="var:logo" style="display: block" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="headings text-right">
                        <p style="font-size: 16px; margin-block-start: 16px; text-align: right;">
                            {{ $submission_info['batch_no'] }} :العدد
                        </p>
                        <p style="font-size: 16px; text-align: right;">
                            {{ $submission_info['submit_date'] }} :التاريخ
                        </p>
                        <p>الى: دائرة صحة البصرة</p>
                        <p>قسم الصحة العامة</p>
                        <p>شعبة السيطرة على العوز المناعي</p>

                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <div>
                                <p style="font-size: 16px; line-height: 1.25">م/ تأكيد</p>
                                <div style="height: 2px; background-color: black"></div>
                            </div>
                        </div>
                        <p style="text-align: center; margin-block-start: 12px">
                            نحن شركة انطونويل نؤيد لكم صحة اسماء و ارقام جوازاتهم المدرجة
                            ادناه لموضفينا علما هم يعملون لدى شركتنا
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="text-align: center; margin-block-start: 8px" cellpadding="10" cellspacing="0" width="100%">
            <thead class="row-bordered">
                <tr>
                    <th bgcolor="#ddd">#</th>
                    <th bgcolor="#ddd"> Name in Passport</th>
                    <th bgcolor="#ddd">Passport No</th>
                    <th bgcolor="#ddd">Nationality</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bloodApplicants as $applicant)
                <tr class="row-bordered">
                    <td>
                        {{ $loop->index }}
                    </td>
                    <td class="cursor-pointer" @click="emitRow(item)">
                        {{ $applicant->pax->eng_full_name ??'' }}
                    </td>
                    <td>
                        {{ $applicant->pax->latestPassport->passport_no ??'' }}
                    </td>
                    <td>
                        {{ $applicant->pax->countryResidence->country_name_short_en ??''}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex flex-column justify-content-center align-items-stretch" style="margin-block-start: 8px">
            <p style="text-align: center; font-size: 16px; direction: rtl">
                مع الشكر و التقدير.....
            </p>
            <p style="text-align: left; font-size: 16px">قسم لوجستيات الافراد</p>
        </div>
    </div>
</body>
<style>
    .row-bordered {
        border: 1px solid #ddd;
        text-align: center;
    }

    .row-bordered td {
        padding: 5px;
        text-align: center;
    }

    .row-bordered td:not(:last-child) {
        border-inline-end: 1px solid #ddd;
    }

    tr.row-bordered {
        height: 40px;
    }

    .letter-content p {
        margin: 0;
        font-family: sans-serif;
        font-size: 16px;
        font-weight: bold;
    }

    .letter-content .schedule-cell button {
        margin: 0 !important;
    }
</style>