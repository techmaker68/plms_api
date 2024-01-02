<!--
  ***********************************
  @author Syed, Umair, Majid
  @create_date 10-10-2023
  ***********************************
-->
<head>
    <style>
      body {
        font-family: "Inter", sans-serif !important;
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

      table {
        width: 100%;
      }

      .footer-row {
        display: flex !important;
        flex-direction: column;
        justify-content: flex-start;
        align-items: stretch;
        gap: 1rem;
        margin-top: 4rem;
        padding: 4rem 1rem;
        border-top: 1px dashed black;
        font-size: 125%;
      }

      .footer-row p {
        font-size: 125%;
      }

      .footer-row .signature-container {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: flex-start;
        font-size: 125%;
      }

      .footer-row .signature-container .signature-column {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .footer-row .signature-container .signature-column .signature-info-row {
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: flex-start;
        text-align: start;
        gap: 8px;
      }

      .footer-row .signature-container .signature-column .signature-info-row span {}

      .footer-row .signature-container .signature-column .signature-info-row label {
        color: #000;
      }

      .footer-row .signature-container .signature-column .signature-info-row label:after {
        content: ":";
      }

      .applicantHeader {
        display: flex !important;
        flex-direction: column !important;
      }

      .applicantHeader .logo {
        position: relative;
        margin: 1rem 0;
      padding: 0 0 2rem 0;
      padding-left: 45%; /* Center the element horizontally */
      display: flex;
      align-items: center !important;
      justify-content: center !important;
      }
      .applicantHeader .logo::before {
      width: 40%;
      height: 0.1rem;
      background: #0f1f78;
      position: absolute;
      content: "";
      left: 0;
      bottom: 0;
  }

  .applicantHeader .logo::after {
      width: calc(60% - 2px);
      height: 0.1rem;
      background: #c41030;
      position: absolute;
      content: "";
      left: calc(40% + 2px);
      bottom: 0;
  }
    </style>
  </head>

  <body>
    <div class="name-list-loi letter-view ">
      <div class="applicantListHtml" style="margin-bottom: 10px">
        <div class="applicantHeader">
          <div class="logo">
            <img src="var:logo" alt="Anton Logo" />
          </div>
        </div>
      </div>
      <div class="pdfDocView">
        <table class="noHover" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; width: 100%; background-color: #ffffff" dir="rtl">
          <tr>
            <td>
              <table dir="rtl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                <tr>
                  <td style="vertical-align: top">
                    <table dir="rtl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                      <tr>
                        <td style="
                          text-align: center;
                          padding-top: 5px;
                          padding-bottom: 5px;
                          font-size: 12px;
                          color: #000;
                        ">
                          استمارة طلب سمات الدخول للشركات المتعاقدة مع الدولة
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <table dir='rtl' cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                            <tr>
                              <td>
                                <table dir='rtl' cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ١-
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      اسم الشركة:
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٢-
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      عنوان الشركة داخل العراق :
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٣-
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      الغاية من الدخول :
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٤-
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      مدة البقاء المتوقعه داخل العراق:
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٥-
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      نوع سمة الدخول:
                                    </td>
                                  </tr>
                                </table>
                              </td>
                              <td>
                                <table dir="rtl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{ $data->getCompanyName() }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{
                                        $data->company_address_iraq_ar
                                    }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{
                                      $data->entry_purpose
                                    }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                      direction:rtl

                                    ">
                                      {{
                                      $data->getLoiTypeArabic()
                                    }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      متعددة الدخول
                                      {{
                                        $data->getLoiTypeArabic()
                                    }}
                                    </td>
                                  </tr>
                                </table>
                              </td>
                              <td>
                                <table dir="rtl" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%">
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٦- جنسية الشركة
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{ $data->company->country->country_name_short_ar ??'' }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٧- عنوان الشركة
                                      خارج العراق
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{
                                          $data->company_address_ar
                                    }}
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      ٨- تاريخ انتهاء
                                      العقد
                                    </td>
                                    <td class="direction" style="
                                      padding-bottom: 5px;
                                      font-size: 12px;
                                      color: #000;
                                    ">
                                      {{
                                         $data->contract_expiry
                                    }}
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <div>
                      <img src="var:imageProfile" alt="Profile" style="display: block; width: 80px; height: 100px">
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <table dir="rtl" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="direction" style="
                    border: 1px solid #000;
                    text-align: center;
                    width: 5%;
                    font-weight: bold;
                  ">
                    تسلسل
                  </td>
                  <td class="direction" style="
                    border: 1px solid #000;
                    text-align: center;
                    width: 25%;
                    font-weight: bold;
                  ">
                    الاسم
                  </td>
                  <td class="direction" style="
                    border: 1px solid #000;
                    text-align: center;
                    width: 5%;
                    font-weight: bold;
                  ">
                    الجنسية
                  </td>
                  <td class="direction" style="
                    border: 1px solid #000;
                    text-align: center;
                    width: 10%;
                    font-weight: bold;
                  ">
                    رقم الجواز
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center; width: 10%">
                    عنوان الاقامة داخل العراق
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center; width: 10%">
                    اسم المنفذ الحدودي للدخول
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center; width: 10%">
                    المهنة والوظيفة
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center; width: 5%">
                    بلد الاقامة
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center; width: 25%">
                    التأمينات
                  </td>
                </tr>
                @if(count($data->applicants)>0)
                @foreach($data->applicants as $applicant)
                <tr>
                  <td style="
                      border: 1px solid #000;
                      text-align: center;
                      font-weight: bold;
                    ">
                    {{ $applicant->sequence_no}}
                  </td>
                  <td class="direction" style="
                      border: 1px solid #000;
                      text-align: center;
                      font-weight: bold;
                    ">
                    @if($applicant->latestPassport)
                    {{
                        $applicant->latestPassport->full_name ?? ''
                      }}
                    @endif
                  </td>
                  <td class="direction" style="
                      border: 1px solid #000;
                      text-align: center;
                      font-weight: bold;
                    ">

                    @if($applicant->pax)
                    {{
                        $applicant->pax->country->nationality_ar ?? ''
                    }}
                    @endif
                  </td>
                  <td class="direction" style="
                      border: 1px solid #000;
                      text-align: center;
                      font-weight: bold;
                    ">


                    {{ $applicant->latestPassport->passport_no ??'' }}
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center">
                    حقل مجنون - شركة انطونويل
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center">
                    مطار بغداد الدولي / مطار البصرة الدولي
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center">


                    {{
                        $applicant->pax->arab_position ?? ''
                    }}

                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center">

                    {{
                      $applicant->pax->country->country_name_short_ar ?? ''

                    }}
                  </td>
                  <td class="direction" style="border: 1px solid #000; text-align: center">
                    <!-- {{ $applicant->remarks }} -->
                    {{$applicant->loi_payment_receipt_no != null ?  'تم دفع التأمينات حسب الوصل المرقم' . '(' . $applicant->loi_payment_receipt_no . ')' . '(ص)(بتأريخ ' . $applicant->loi_payment_date . ')' :   'غير مؤمن'}}
                  </td>
                </tr>
                @endforeach
                @else
                <tr>
                  <td colspan="9" style="
                    border: 1px solid #000;
                    text-align: center;
                    padding: 10px;
                  ">
                    No Available Data
                  </td>
                </tr>
                @endif
              </table>

              <div class=" direction" style="display: flex;">
                <br>
                <br>
                <p>
                  أني المخول (ليث حسين محمد ) اتعهد بعدم التصرف باوراق الشركة دون
                  علمها او أضافة او تغير او تعديل بيانات المعلومات اعلاه وعدم اخفاء
                  اي معلومة عن مديرية شؤون الأقامة وبخلاف ذلك اتحمل كافة التبعات
                  القانونية.
                </p>
                <div class="signature-container" style="display: flex; ">
                  <table dir="rtl" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <div class="signature-column align-items-center">
                          <span class="direction">ختم و توقيع مدير الشركة</span>
                          <br>
                          <span>Yang, Cheng</span>
                          <br>
                          <span>Assistant Managing Director</span>
                        </div>
                      </td>
                      <td>
                        <div class="signature-column ">
                          <div class="signature-info-row">
                            <label class="direction">التوقيع</label>
                          </div>
                          <div class="signature-info-row">
                            <label class="direction">اسم ممثل الشركة</label>
                            <span class="direction">ليث حسين محمد</span>
                          </div>
                          <div class="signature-info-row">
                            <label class="direction">رقم الهوية</label>
                            <span>199040682061</span>
                          </div>
                          <div class="signature-info-row">
                            <label class="direction">محل و تاريخ دائرة الأحوال</label>
                            <span class="direction">بغداد - 7\12\2021</span>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </body>
  </html>
