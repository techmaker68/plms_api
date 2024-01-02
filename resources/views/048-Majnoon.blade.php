<!--
  ***********************************
  @author Syed, Umair, Majid
  @create_date 10-10-2023
  ***********************************
-->
<head></head>
<style>
    body {
        font-family: 'Inter','sans-serif';
    }

    .highlight {
        /* background-color: #ffff00; */
        font-weight: 500;
    }

    .sidebar--moreInfoSidebar__large {
        width: 35rem;
    }

    .direction {
        text-align: right;
        direction: rtl !important;
    }

    .form-check {
        padding-left: 1.5em;
    }

    th .form-check {
        padding-bottom: 0;
    }

    .batch-list {
        position: relative;
        margin: 2rem -1.5rem;
        padding: 1rem 2rem;
    }

    .batch-list::after {
        content: "";
        position: absolute;
        border-bottom: 1px solid #dadada;
        width: 100%;
        left: 0;
        bottom: 0;
    }

    main {
        width: calc(100% - 53rem);
    }

    .label-value li label {
        width: 40%;
    }

    .label-value li.col-md-12 label {
        width: 19.5%;
    }

    .Addpassport .form-control {
        padding: 1rem;
        border-radius: 3px;
    }

    .Addpassport .form-label {
        color: #000;
    }

    .nav.nav-tabs .nav-link {
        padding: 0.5rem 1.5rem;
    }

    .doc-wrapper {
        border-collapse: collapse;
    }
</style>

<body>
    <div class="pdfDocView">
        <table class="noHover" cellpadding="0" cellspacing="0"
            style="border-collapse: collapse; width: 100%; background-color: white">
            <tbody>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                            <tbody>
                                <tr>
                                    <td
                                        style=" text-align: left; border-bottom: 2px solid #1e2678; padding-bottom: 5px; font-size: 12px; color: #000; ">
                                        <a href="www.antonoil.com" style="text-decoration: none; color: #1f4e78">
                                            www.antonoil.com </a> </td>
                                    <td style=" border-bottom: 2px solid #bf0022; padding-bottom: 5px; ">
                                        <!-- <img src="@/assets/img/antonLogo.png" style="display: block; margin-left: auto" /> -->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" class="doc-wrapper">
                            <tbody>
                                <tr>
                                    <td style="width: 45%; vertical-align: top; padding-top: 10px">
                                        <table cellpadding="0" cellspacing="0"
                                            style="border-collapse: collapse; width: 100%">
                                            <tbody>
                                                <tr>
                                                    <td style="font-size: 12px; color: #000"> Ref. No: <strong
                                                            class="highlight"> {{ $data->majnoon_ref ??"" }} </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 12px; color: #000"> Date: <strong
                                                            class="highlight"> {{ $data->majnoon_date }} </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom: 12px;"> <br><br><strong style="font-size: 13px; color: #000"> Re: Multi Entry
                                                            Visa ({{ $data->getLoiType() }}) Months Issuance Request
                                                        </strong><br> </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom: 30px"> <strong
                                                            style="font-size: 13px; color: #000"> To: Majnoon Oil Field
                                                            Production and Development Authority </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 12px; color: #000"> You are kindly requested
                                                        to secure official approvals to issue multi entry visa
                                                        ({{ $data->getLoiType() }}) for the individuals listed in the
                                                        attached table, whom working at AntonOil Services DMCC in
                                                        Majnoon Oilfield According to the contract
                                                        No.<strong>MFD-BOC-001.</strong> </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 12px; color: #000; padding-bottom: 8px; ">
                                                        List of names starts with </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style=" font-size: 12px; color: #000; padding-left: 10px; padding-bottom: 8px; ">
                                                        <strong class="highlight"> 1-
                                                            {{ $data->getSmallestIdRecord()->pax->eng_full_name ??'' }}
                                                        </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 12px; color: #000; padding-bottom: 8px; ">
                                                        Ends with </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style=" font-size: 12px; color: #000; padding-left: 10px; padding-bottom: 8px; ">
                                                        @if (count($data->applicants) > 0)
                                                            <strong class="highlight"> {{ count($data->applicants) }} -
                                                                {{ $data->getLargestIdRecord()->pax->eng_full_name ??'' }}
                                                            </strong>
                                                            @endif <div> Kindly inform the Iraqi embassies\ consulates
                                                                in both of <strong>Dubai and their countries.</strong>
                                                            </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 12px; color: #000; padding: 15px 0; "> We
                                                        also would like to confirm that the personnel listed in the
                                                        attached table cannot be replaced with local workers. </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 12px; color: #000; padding: 15px 0; "> With
                                                        regards, </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 12px; color: #000; padding-top: 30px; ">
                                                        <strong>
                                                            <div>YANG, CHENG</div>
                                                            <div>Assistant Managing Director</div>
                                                            <div>AntonOil Services DMCC</div>
                                                        </strong> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td style="width: 45%; vertical-align: top; padding-top: 10px">
                                        <table cellpadding="0" cellspacing="0"
                                            style="border-collapse: collapse; width: 100%" dir="rtl">
                                            <tbody>
                                                <tr>
                                                    <td class="direction"
                                                        style="font-size: 12px; color: #000; text-align:right ">  العدد: <strong
                                                            style="text-align:right">
                                                            {{ $data->majnoon_ref ? $data->majnoon_ref : '' }} </strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style="font-size: 12px; color: #000; text-align:right "> 
                                                        التاريخ: <strong
                                                            style="text-align:right">
                                                            {{ $data->majnoon_date ? $data->majnoon_date : '' }} </strong> </td> 
                                                </tr>
                                                <tr>
                                                    <td
                                                        style="padding-top: 30px; padding-bottom: 30px; text-align: right">
                                                        <strong class="direction"
                                                            style="font-size: 13px; color: #000; text-align:right">
                                                            الموضوع: طلب منح سمات دخول متعددة (لمدة
                                                            {{ $data->getLoiTypeArabic() }}) </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style="padding-bottom: 30px; text-align: right"> <strong
                                                            style="font-size: 13px; color: #000; text-align:right"> الى:
                                                            هيئة انتاج وتطوير حقل مجنون النفطي </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style="font-size: 12px; color: #000 ;text-align: right"> يرجى
                                                        التفضل بإستحصال الموافقات الاصولية على إصدار سمات دخول متعددة
                                                        (لمدة {{ $data->getLoiTypeArabic() }}) للافراد المدرجة اسمائهم
                                                        في القوائم المرفقة طياً والعاملين في شركة انطونويل سيرفيز
                                                        م.د.م.س العاملة في حقل مجنون النفطي وفق العقد المرقم <strong
                                                            style="text-align: right">MFD-BOC-001 .</strong> </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding-bottom: 8px; text-align:right ">
                                                        قائمة تبدأ بالتسلسل </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding-right: 10px; padding-bottom: 8px; ">
                                                        <strong class="highlight" style="text-align: right"> ١
                                                            -{{ $data->getSmallestIdRecord()->latestPassport->full_name ??'' }}
                                                        </strong> </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding-bottom: 8px; text-align: right; ">
                                                        وتنتهي بالتسلسل </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding-right: 10px; padding-bottom: 8px; ">
                                                        @if ($data && count($data->applicants) > 0)
                                                            <strong class="highlight" style="text-align: right"> {{ $data->convertToArabicNumber()}}-
                                                                {{ $data->getLargestIdRecord()->latestPassport->full_name ??'' }}
                                                                <!-- -14 محمد محسن العبد الرحيم --> </strong>
                                                        @endif
                                                        <div class="direction" style="text-align: right"> والتفضل بإشعار
                                                            السفارات/ القنصليات العراقية في كلاً من <strong>دبي
                                                                وبلدانهم.</strong> </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding: 15px 0; text-align: right ">
                                                        كما نود التأكيد على انه لايمكن إستبدال الموظفين المدرجة اسمائهم
                                                        في القوائم المرفقة بالعمالة المحلية. </td>
                                                </tr>
                                                <tr>
                                                    <td class="direction"
                                                        style=" font-size: 12px; color: #000; padding: 15px 0; text-align:right ">
                                                        مع التقدير </td>
                                                </tr>
                                                <tr>
                                                    <td
                                                        style=" font-size: 12px; color: #0f243e; padding-top: 30px; text-align: right ">
                                                        <strong>
                                                            <div class="direction">يانك جينك</div>
                                                            <div class="direction">مساعد المدير العام</div>
                                                            <div class="direction">انطونويل سيرفيز م.د.م.س</div>
                                                        </strong> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
