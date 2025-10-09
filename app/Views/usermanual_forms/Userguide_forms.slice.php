@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'E-Filing User Guide')
@section('heading', 'E-Filing User Guide')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<style>
    fieldset
    {
        border: 1px solid #ddd !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        padding-left:10px!important;
        margin-left: 1%;
        margin-right: 1%;
    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        width: 35%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #ffffff;
    }
    .fontcolor
    {
        color: black;
    }
</style>
<script type="text/javascript" src="pspdfkit/pspdfkit.js" asyn></script>

<div class="uk-margin-small-top uk-border-rounded" style="margin-left: 6%;">
    <div class="uk-width">
        <!--<div class="uk-button-group">
            <a href="#" class="uk-button uk-background-secondary text-white">Document Editor</a>
        </div>-->
        <div class="uk-flex-between" uk-grid>
            <fieldset style="width: 37%;">
                <legend>
            <span class="uk-h3 uk-text-muted uk-text-large uk-text-bold"><h5 style="font-weight: bold">USER GUIDE</h5></span></legend>
            <ol>
                <li>
            <a href="assets/downloads/User_manual_e_filing_Supreme_Court.pdf" target="_blank" >
                    <span class="fontcolor">E-Filing User Manual</span>
            </a></li></ol>
            </fieldset>

        </div>
        <br/>



        <div class="uk-flex-between" uk-grid>
            <fieldset>
                <legend>
            <span class="uk-h3 uk-text-muted uk-text-large uk-text-bold"><h5 style="font-weight: bold">FORMS</h5></span></legend>
            <ol>
                <li>
            <a href="assets/downloads/Forms/ACCEPTANCE_OF_SUM_PAID_INTO_COURT.pdf" target="_blank" >
                    <span class="fontcolor"> Acceptance of sum paid into court</span>
            </a>  </li>
            <br/> <li>
            <a href="assets/downloads/Forms/Affidavit_of_Service_of_Summons.pdf" target="_blank" >

                <span class="fontcolor"> Affidavit of Service of Summons</span>
            </a>  </li>
            <br/> <li>
            <a href="assets/downloads/Forms/Affidavit_of_Service_of_Summons_by_post.pdf" target="_blank" >

                <span class="fontcolor"> Affidavit of Service of Summons by post</span>
            </a>  </li>
            <br/> <li>
            <a href="assets/downloads/Forms/Affidavit_of_Service_of_Summons_by _post-MAILING.pdf" target="_blank" >

                <span class="fontcolor"> Affidavit of Service of Summons by post-MAILING</span>
            </a> </li>
            <br/> <li>
            <a href="assets/downloads/Forms/Affidavit_of_Service_of_Summons_by_post-plaintiff_defendant.pdf" target="_blank" >

                <span class="fontcolor"> Affidavit of Service of Summons by post-plaintiff defendant</span>
            </a>  </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Appearance_Slip.pdf" target="_blank" >

                <span class="fontcolor"> Appearance Slip</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Application_for_production_of_Record.pdf" target="_blank" >

                <span class="fontcolor"> Application for production of Record</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Application_for_registration_of_clerk.pdf" target="_blank" >

                <span class="fontcolor"> Application for registration of clerk</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/CERTIFICATE_OF_CUSTODY_FINAL.pdf" target="_blank" >

                <span class="fontcolor"> Certificate of Custody-Final</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/CERTIFICATE_OF_SURRENDER_FINAL.pdf" target="_blank" >

                <span class="fontcolor"> Certificate of Surrender-Final</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/checklist_0001.pdf" target="_blank" >

                <span class="fontcolor"> checklist </span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/COVER_PAGE_OF_WRIT_PETITION_CIVIL.pdf" target="_blank" >

                <span class="fontcolor"> Cover Page of Writ Petition Civil</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/DEPOSITS_BY_AOR.pdf" target="_blank" >

                <span class="fontcolor"> Deposits by AOR</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/MANDATORY_CHECK_LIST.pdf" target="_blank" >

                <span class="fontcolor"> Mandatory Check List</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Memorandum_of_Appearance_in_Person.pdf" target="_blank" >

                <span class="fontcolor">Memorandum of Appearance in Person</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Memorandum_of_Appearance_through_Advocate-on-Record.pdf" target="_blank" >

                <span class="fontcolor"> Memorandum of Appearance through Advocate-on-Record</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Memorandum_of_Appearance_through_Advocate-on-Record-ORIGINAL.pdf" target="_blank" >

                <span class="fontcolor">Memorandum of Appearance through Advocate-on-Record -ORIGINAL</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Notice_of_Appearance.pdf" target="_blank" >

                <span class="fontcolor"> Notice of Appearance</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Notice_of_Appearance-1.pdf" target="_blank" >

                <span class="fontcolor"> Notice of Appearance - 1</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Notice_of_Motion.pdf" target="_blank" >

                <span class="fontcolor"> Notice of Motion</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/Notice_of_Payment_of_Money_into_Court.pdf" target="_blank" >

                <span class="fontcolor"> Notice of Payment of Money into Court</span>
            </a>
                </li>
                <br/> <li>
            <a href="assets/downloads/Forms/NOTICE_OF_PAYMENT_OF_MONEY_INTO_COURT-1.pdf" target="_blank" >

                <span class="fontcolor">Notice of Payment of Money into Court-1</span>
            </a> </li> </ol>

            </fieldset>
        </div>

    </div>
</div>



@endsection