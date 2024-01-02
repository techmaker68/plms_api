<!--
  ***********************************
  @author Syed, Umair, Majid
  @create_date 10-10-2023
  ***********************************
-->
<head></head>
<style>
    body {
        font-family: 'Noto Naskh Arabic', 'DejaVu Sans', Arial;
    }

    .highlight {
        /* background-color: #ffff00; */
        font-weight: 500;
    }

    .direction {
        text-align: right;
        direction: rtl !important;
    }

    .sidebar--moreInfoSidebar__large {
        width: 35rem;
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
        margin-left: 30px !important;
        margin-right: 30px !important;
    }
</style>

<body>
    <div class="pdfDocView">
        <table class="noHover" cellpadding="0" cellspacing="0"
            style="border-collapse: collapse; width: 100%; background-color: white" dir="rtl">
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                        <tr>
                            <td style="border-bottom: 2px solid #bf0022; padding-bottom: 5px">
                                <!-- <img src="@/assets/img/antonLogo.png" style="display: block; margin-left: auto" /> -->
                            </td>
                            <td
                                style=" text-align: left; border-bottom: 2px solid #1e2678; padding-bottom: 5px; font-size: 12px; color: #000; ">
                                <a href="www.antonoil.com" style="text-decoration: none; color: #1f4e78">
                                    www.antonoil.com </a> </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0" class="doc-wrapper">
                        <tbody>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 15px"> العدد:
                                    <strong v-if="application" class="highlight"> {{ $data->moo_ref }} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000"> التاريخ: <strong
                                        v-if="application" class="highlight"> {{ $data->moo_date }} </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 50px"> <strong>
                                        الى/ وزارة النفط/ الدائرة الادارية/ قسم العلاقات العامة </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 10px"> <strong>
                                        الموضوع/<u>طلب إصدارسمات دخول متعددة ({{ $data->getLoiTypeArabic() }})</u>
                                    </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 30px"> يرجى
                                    التفضل بإستحصال الموافقات الاصولية على اصدار سمات دخول متعددة للافراد المدرجة
                                    اسمائهم في القوائم المرفقة طياً والعاملين في شركة انطونويل سيرفيز م.د.م.س العاملة في
                                    حقل مجنون النفطي وفق العقد المرقم (MFD-BOC-001)، وإعلام السفارات والقنصليات العراقية
                                    في كلاً من دبي وبلدانهم. </td>
                            </tr>
                            <tr>
                                <td class="direction"
                                    style=" font-size: 14px; color: #000; padding-right: 15px; padding-top: 50px; ">
                                    <strong class="highlight" v-if="application && applicants.length > 0"> ١ -
                                        {{ $data->getSmallestIdRecord()->latestpassport->full_name ??'' }} </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 10px"> وتنتهي
                                    بالتسلسل </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-right: 15px"> <strong
                                        class="highlight" v-if="application && applicants.length > 0">
                                        {{ $data->convertToArabicNumber() }}-
                                        {{ $data->getLargestIdRecord()->latestpassport->full_name ??'' }} </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 15px"> كما نود
                                    التأكيد على انه لايمكن إستبدال الموظفين المدرجة اسمائهم في القوائم المرفقة بالعمالة
                                    المحلية. </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #000; padding-top: 30px"> مع
                                    التقدير. </td>
                            </tr>
                            <tr>
                                <td class="direction" style="font-size: 14px; color: #0f243e; padding-top: 80px">
                                    <strong>
                                        <div>يانك جينك</div>
                                        <div>مساعد المدير العام</div>
                                        <div>انطونويل سيرفيز م.د.م.س</div>
                                    </strong> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="padding-top: 40px; padding-bottom: 5px"> <u> المرفقات :
                                    </u> </td>
                            </tr>
                            <tr>
                                <td class="direction" style="padding-right: 40px; padding-bottom: 5px"> - قائمة باسماء
                                    الموظفين </td>
                            </tr>
                            <tr>
                                <td class="direction" style="padding-right: 40px; padding-bottom: 5px"> - قائمة باسماء
                                    الموظفين </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
