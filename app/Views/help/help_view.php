<!DOCTYPE html>
<html>
    <head>
        <style>
            body{
                color:#555;
                background:#E7E7E7;
                font-size:18px;
                text-align: justify;
                min-width: 650px;
            }

            b{
                text-transform: uppercase;
                color:#3c3c3c;
                font-size: 22px;
            }
            div.details{
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                border:1px solid #ccc;
                line-height: 20px;

                margin: 20px;

            }
            pre{
                white-space: pre-wrap;
                text-align: justify;
            }
            .thumbnails_img{ 
                width: 150px;
                height: 100px;
            }

            img#lightbox-image { width: 100%; }
            #lightbox-container-image-box{
                width: 70% !important;
                height: auto !important;
                overflow: hidden !important;
            }
            div#lightbox-container-image-data-box{
                width: 70% !important;
                padding: 0 !important;
            }

            @media only screen and (max-width: 650px) {
                #lightbox-container-image-box{
                    width: 95% !important;
                    height: auto !important;
                    overflow: hidden !important;
                }
                div#lightbox-container-image-data-box{
                    width: 95% !important;
                    padding: 0 !important;
                }
            }
        </style>
    </head>
    <?php $help_base_url = 'help/View/info/'; ?>
    <body>
        <?php if ($help_page == $help_base_url . 'dashboard') { ?>
            <br>
        <center><b>eFile</b></center><div class='details'>
            <pre >
                        While filing new case, it is necessary to select the Court establishment where the cases is intended to be filed. You will find opition of <b>“My Courts”</b> on the screen while filing new case. In My Courts, list of Courts wherein cases are already filed by the user will be displayed. Instead of doing the entire process again, if the desired Court establishment is selected, it saves time of the user. 

                        When you are filing new case in Lower Courts, it is necessary to select State, District and Court establishment. It is mandatory to choose whether the case desired to be filed is Civil or Criminal type (if you forget to select civil or criminal then you will not see case types) . If the case desired to be filed is motor accident claim related case, please mark selection to show that it is MACP related case. If you have any kind of urgency you may select that the case is urgent or otherwise. 
                        There is an option named <b>“Matter Type”</b> wherein options to be selected are 

                        a) Original, 
                        b) Appeal, and, 
                        c) Application. 

                        Original means when case is filed for the first time in any Court(not being an appeal or revision). Appeal means any appeal revision or like proceedings wherein order of the Court below or any authority is impugned. Application means interim application which are filed in already existing original or appeal cases. 

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.
                                            
            </pre> 
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/case_file/type/92') { ?>
        <br>
        <center><b>Where to file</b></center><div class='details'>
            <pre >
                        File Reply, Written Statement or Documents in existing case: When a case is already filed in the Court and something (Application reply or documents) needs to be eFiled in such case facility of documents need to be used. 

                        eFile>>document is path to reach this menu

                        You can search desired case by entering CNR Number or Case Type, Case Number and Year. When CNR Number or Case Number is entered, party names are displayed. If the application or reply or documents are to be filed on behalf of the petitioner/s then automatically all the petitioners will be selected. However, if the application or reply or documents are to be filed on bhealf of the repondents then select Respondents and choose desired respondents for whom the concerned advocate is appearing. Click save and next. Now select only those respondents who are intending to file present application or reply or documents. (In case you are filing for three respondents and the present application or reply or document is filed only by one respondent out of three in such case you have choose the one who is filing present application or document or reply.)

                        Choose sign method. (Remember that while uploading documents if you want to change method of signing, you may have to return to this tab and start over again.) Click save after choosing sign method. 

                        Select appropriate name from the dropdown list of documents. You can give specific name to selected entry of document. (For example : Revenue record is document selected from the list in that case Document titile can be 7/12 extract, kharsa, or khatavni along with year or date as per your wish. If document is given correct title it becomes easy for you to search such documents based on its title.) Click save and if some more documents are to be uploaded repeat the same process. After you are done with uploading documents, click next. 

                        Select appropirate Fee Type from the dropdown list. 

                        Enter amount of fee intended to be deposited. 

                        When you select Cheque, DD or challan you may have to upload the document and enter addtional details like Bank Name, Challan or cheque or DD details and its date. If “Stamp” is payment type selected in that case you may not have to enter Bank Name, Date and number. In any case do not forget to select name of party paying the fees. 

                        Certificate: Digital Token Method: It is obvious that original documents cannot be filed and scanned documents cannot be connoted as originals. Therefore, when documents are filed in any case, it requires certificate of Advocate that he has seen originals or certified copy and the same is in conformity with the uploaded documents. When eSign mode is selected, the certificate will be autogenrated whereas for Digital Signature Token mode certificate needs to be downloaded, and then needs to be digitally signed and then uploaded. Do not forget to save and click next. 

                        Certificate: eSign Method: enter your name as mentioned in Aadhar. After clicking eSign you will be redirected to eSign webserver. Enter you Aadhar details, your document will be signed and you will revert to eFiling. If your eSign fails continously for any reason whatsoever, you will be allowed to file the case using OTP. 

                        View is final tab. Here you verify the information before finally submitting. Do not skip this step casually. This may be final opportunity to correct your errors, if any. After verifying the information you will find Final submit button on top right corner is green colour. After this step your request will be directed to eFiling Admin of the concerned Court Establishment. 

            </pre>

            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/case_file/type/43') { ?>
        <br>
        <center><b> CDE </b></center><div class='details'>
            <pre >
                        CDE means Case Data Entry.The Court Establishments where eFiling is not made operational can certain use CDE feature to process their cases expedioutsly.
                        CDE does not require you to upload documents whereas under eFiling it is mandatory to upload documents while filing a case.   
                        Under CDE user has to make data entry of the case and submit the information to the Court.  

                        It needs to be ensured that all the information required or advisable to be filled in is entered before submitting the same to Court. 
                        After the information is submitted, acknowledgement receipt is generated which needs to be printed and annexed along with the case. 

                        Case papers need to be filed physically before Court as is done ordinarily. However, along with the papers acknowledgement needs to be annexed with the case papers.   
                        You may not have to wait on the filing counter.  

                        You can remove all objections online.  It is not necessary for you to visit any Court Officials for any compliances or for removing objections.  
                        All the objections can be removed online and there will be proper record of objections and its compliance. 
                        The record can be searched at any time.   

                        All data fed remains in your account which is very useful during the progress of the case so also after its disposal.   
                        The most important part is you start getting automatic updates and alerts about the case without searching for it. 
                        This is significant in managing your business in the court using management tools provided.  
            </pre>

            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation') { ?>
        <br>
        <center><b>MY CASES </b></center><div class='details'>
            <pre >My Cases: This is interface for managing your own cases. List of sucessfully eFiled cases (not draft) and saved cases which are physically filed on the counter is shown in My Cases.
                                                                                                            
                            The information is primarily divided into two categories 1. Pending Cases 2. Disposed Cases.
                            Each saved case number can be clicked to show detailed case history as recorded in CIS.
                            Each efiled case pleadings and documents can be viewed on the efiled icon. 

                            In the list shown, besides case number and names of main parties, next date, recent previous date, Court number used in CIS and designation of Judge etc. are shown.
                            It is possible to save releant citations to the case. On the last column provision to add citations in the given case is provided. Once citation is added, automatically another symbol is created to view the citation.
                            It is possible to save your notes in the case.

                            On the last column provision to add notes in the given case is provided. Once note is added, automatically another symbol is created to view the notes.
                            There is facility to  share current cases status with litigant or any desired person through whatsapp. After clicking whatsapp icon, QR Code appearing on the screen needs to be scaned. Thereafter, whatsapp desktop interface is opened. Please search for desired name and send the case status to selected person/s
                            There is facility to mail the case information or documents or order through automated mailing service of eCommittee, Supreme Court of India. 

                            Name of the registered user and mail address will be shown to identify the credentials of sender. 
                            Additionally, it is possible to send text SMS informing case status to the desired person using messaging service provided by eCommittee, Supreme Court of India. 
                            While sending text SMS or mail you can select the case contact, if saved for the case concerned.

                            On the top right corner one can see symbol of filters. Click that symbol to apply filters. 
                            You can apply any of the filters mentioned there. Given filters are 

                            a) Choose period within which cases are listed.
                            b) choose period within which next dates are assigned.
                            c) choose one or multiple purposes for which the cases are posted before the Court. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'clerk') { ?>
        <br>
        <center><b>Add Clerk</b></center><div class='details'>
            <pre >
                        This facility is provided only for Advocate users.It is frequently seen that Advocate offices consist clerks and junior advocates. 
                        Often they are entrusted with responsibilites and workload is apportioned among the existing human resources.   
                        Advocates are mostly busy in Court rooms, at the same time it becomes necessary that somebody from the Advocate’s office tracks developments occuring in various cases.

                        It is also necessary that such clerk or junior is able to complete all the process keeping final submission only through the registered Advocate.   
                        Enter name of the Clerk and his/her details.   You can restrict use of the Clerk’s account to track development.  
                        If you deem it fit you can allow him to access filing of the cases and filing of document.

                        If this permission is given to file cases or documents in that case, such clerk will be able to file cases or documents however, 
                        final submission of the case or document to the Court will be only through the account of the main registered advocate.  
                        Needless to mention that esign to be used while filing cases should be of registered advocate only and not of the clerk or junior advocate.

                        It needs to be reiterated that only registered account holder needs to eSign document or pleadings at the time of efiling and no other person should eSign such documents or pleadings while eFiling cases or documents.  
                        Main registered advocate will be able to track as to how many cases are processed by particular clerk.  
                        Take a case that clerk goes to my cases and adds cases of the office. 

                        The cases which are saved by clerk will be automatically added to main registered advocates account and he need not have to add those cases again in his own account. 
                        Even though cases are added by clerk, registered account holder advocate will be able to see the developments in those added cases.</pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'assistance/notice_circulars') { ?>
        <br>
        <center><b>Notice and Circulars</b></center><div class='details'>
            <pre >
                        This facility is provided only for Advocate users.It is frequently seen that Advocate offices consist clerks and junior advocates. 
                        Often they are entrusted with responsibilites and workload is apportioned among the existing human resources.   
                        Advocates are mostly busy in Court rooms, at the same time it becomes necessary that somebody from the Advocate’s office tracks developments occuring in various cases.

                        It is also necessary that such clerk or junior is able to complete all the process keeping final submission only through the registered Advocate.   
                        Enter name of the Clerk and his/her details.   You can restrict use of the Clerk’s account to track development.  
                        If you deem it fit you can allow him to access filing of the cases and filing of document.

                        If this permission is given to file cases or documents in that case, 
                        such clerk will be able to file cases or documents however, 
                        final submission of the case or document to the Court will be only through the account of the main registered advocate. 

                        Needless to mention that esign to be used while filing cases should be of registered advocate only and not of the clerk or junior advocate.
                        It needs to be reiterated that only registered account holder needs to eSign document or pleadings at the time of efiling and no other person should eSign such documents or pleadings while eFiling cases or documents.  
                        Main registered advocate will be able to track as to how many cases are processed by particular clerk.  
                        Take a case that clerk goes to my cases and adds cases of the office. 

                        The cases which are saved by clerk will be automatically added to main registered advocates account and he need not have to add those cases again in his own account. 
                        Even though cases are added by clerk, registered account holder advocate will be able to see the developments in those added cases.</pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'assistance/notice/listview/') { ?>
        <br>
        <center><b>Notice & Forms</b></center><div class='details'>
            <pre >
                        The forms which are frequently required by advocates or litigants in Court proceedings will be available here for download or online data entry.
                         Certain Notices which advocate or litigant requires on an often will also be made available here.  

                        Forms and Notices which can be downloaded such forms and notices will have printed and filled in. 
                        After making signature of the advocate or litigant or other person such forms and notices may have to uploaded as 
                        compliance to the court by regular method of fiing documents. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/whereToFile/type/73') { ?>
        <br>
        <center><b>IA :- WHERE TO FILE </b></center><div class='details'>
            <pre >
                        There are two options to file fresh interim application. Either you can choose Case Number / CNR number or you can use eFiling number.
                        Appearing For: Whichever method you choose, you will get names of the parties. If your name is already shown for any of the parties, you can file Interim Application for that party only. If your name is not shown against any of the parties, in that case you can choose parties. You can also add new parties, if found necessary. 

                        Filing Party: It may happen that out of the parties for whom you are appearing, only a few out of them desire file certain interim application. In such situation you may have to select which parties are filing the current Interim Application. 

                        Applying party and Against Party:  If IA is to be filed on behalf of the petitioner/s then automatically all the petitioners will be selected. However, if IA is to be filed on bhealf of the repondents then select Respondents and choose desired respondents for whom you are appearing. Click save and next. Now select only those respondents who are intending to file present IA. (for example if you are appearing for three respondents and the present application or reply or document is filed only by one respondent out of three in such case you have choose the one who is filing present application or document or reply.)

                        If you are appearing for the petitioners and only one or few out of many are filing IA, in that case Against parties will be all respondents. However, if you are one of the respondents and in that case respondents will be all remaining respondents and petitioners. Of course you can remove if some of the parties are not required on the basis of relief claimed. 

                        IA Details: Select IA type from dropdown list. Here you will get those IA types which are configured in your Case Information System. If you require specific IA type in that case you may have to request the concerned Principal Judge of the Court Establishment. Thereafter, you will have select IA nature (Type of Interim application i.e. Amendment, interim injunction etc.) Select appropirate Act and relevant section of the selected Act. Please select purpose of IA. (Mostly for urgent hearing or hearing). Select appropirate prayer and click >> button to see the text of the prayer. You can edit the text of the prayer as per your requirement. Please keep in mind entering correct details will help you in long run to view relevant details without further verification. 

                        Choose sign method. (Remember that while uploading documents if you want to change method of signing, you may have to return to this tab and start over again.) Click save after choosing sign method. 

                        Select appropriate IA name or type document to be annexed with application from the dropdown list. You can give specific name to selected entry of IA or document. (For example : You selected application from the dropdown list and uploaded application. You can give name the document “amendment.rca12.2019.sanajy”. On the screen you can add documents in support of the application. If you scroll down you will find total documents uploaded in the case. If required, you can remove uploaded documents. In support of IA if Revenue record is document selected from the list in that case Document titile can be 7/12 extract, kharsa, or khatavni along with year or date as per your wish. If document is given correct title it becomes easy for you to search such documents based on its title.) Click save and if some more documents are to be uploaded repeat the same process. After you are done with uploading documents, click next. 

                        Select appropirate Fee Type from the dropdown list. 

                        Enter amount of fee intended to be deposited. 

                        When you select Cheque, DD or challan you may have to upload the document and enter addtional details like Bank Name, Challan or cheque or DD details and its date. If “Stamp” is payment type selected in that case you may not have to enter Bank Name, Date and number. In any case do not forget to select name of party paying the fees. 

                        Certificate: It is obvious that original documents cannot be filed and scanned documents cannot be connoted as originals. Therefore, when documents are filed in any case, it requires certificate of Advocate that he has seen originals or certified copy and the same is in conformity with the uploaded documents. When eSign mode is selected, the certificate will be autogenrated whereas for Digital Signature Token mode certificate needs to be downloaded, and then needs to be digitally signed and then uploaded. Do not forget to save and click next. 


                        View is final tab. Here you verify the information before finally submitting. Do not skip this step casually. This may be final opportunity to correct your errors, if any. After verifying the information you will find Final submit button on top right corner is green colour. After this step your request will be directed to eFiling Admin of the concerned Court Establishment. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/whereToFile/type/73') { ?>
        <br>
        <center><b>Listed tomorrow</b></center><div class='details'>
            <pre >
                        All the cases which are listed tomorrow will be shown here. 
                        It is possible to print  this report. It is possible to click case number to view entire case hisotry. 
                                                            
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/2515831578781581') { ?>
        <br>
        <center><b>Listed tomorrow</b></center><div class='details'>
            <pre >
                        All the cases which are listed tomorrow will be shown here. It is possible to print  this report. It is possible to click case number to view entire case hisotry.   
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/159850559725521534') { ?>
        <br>
        <center><b>Under Objections</b></center><div class='details'>
            <pre >
                        All the cases in which objections are notified will be shown here. It is possible to print this report. 
                        It is possible to click case number to view entire case hisotry.      
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/4160971583523439') { ?>
        <br>
        <center><b>Listed for Next 7 days</b></center><div class='details'>
            <pre >
                        All the cases which are listed for upcoming week will be shown here. It is possible to print this report. It is possible to click case number to view entire case hisotry. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/8689255586') { ?>
        <br>
        <center><b>With Next date</b></center><div class='details'>
            <pre >
                        All the saved cases in which next date is updated will be shown here. 
                        It is possible to print this report. It is possible to click case number to view entire case hisotry.                     
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/41348689255586') { ?>
        <br>
        <center><b>Without Next date</b></center><div class='details'>
            <pre >
                        All the saved or efiled cases in which next date is not updated will be shown here. 
                        It is possible to print this report. It is possible to click case number to view entire case hisotry.     
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/785539') { ?>
        <br>
        <center><b>Registered</b></center><div class='details'>
            <pre >
                        All the saved or efiled cases which are registered in CIS will be shown here. 
                        Cases which are pending on the stage of scruitny, objections or compliance will not be shown unless they are registered.
                        It is possible to print this report. It is possible to click case number to view entire case hisotry.     
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/updation/4134785539') { ?>
        <br>
        <center><b>Unregistered</b></center><div class='details'>
            <pre >
                        All the cases in which objections are notified and are not finally registered in CIS will be shown here.
                        It is possible to print this report. It is possible to click case number to view entire case hisotry.     
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/cause_list') { ?>
        <br>
        <center><b>CAUSELIST</b></center><div class='details'>
            <pre >
                        Cause list: Cause List is very useful and frequently required facility. 
                        Click cause list icon. Cause list generation for Court Establishment where you ordinarily practice has been made easy, 
                        You will find list of the Courts. You can generate the list of the specific courts which are of any relevance.   

                        It may happen that a user may need to generate or save a cause list of any other Court where he or she does not practice ordinarily. 
                        For such Courts there is facility to select State, District and Court Establishment. 
                        Thereafter select name of Judge or Designation and then select date of the cause list. 
                        Thereafter select Civil or  Criminal and click submit. You will find that causelist is generated.  

                        Once cause list is generated you can search further on the basis of name, place, name of advocate, 
                        purpose name and urgency.   A user can download the causelist in PDF so that you can obtain print of the same or it
                        may be easy to share such cause list through mail or messaging platoform.   
                        My Case Schedule: There is one more facility to generate advocate specific case list. 

                        This case list will generate list of cases concerning the user advocate listed before different Courts in the Court 
                        Establishments. Therefore, this personal case list generates only those cases in which user advocate is appearing for 
                        any of the parties or has saved in my cases. 

                        There is facility to select date <b>“Today” “Tomorrow”</b> or select the date from the calender. This is very useful feature which enables 
                        advocates to generate his case list of all the Courts in a single printable pdf file. 
                        The same file can be searched and data can be copied.  
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'mycases') { ?>
        <br>
        <center><b>ADD CASE</b></center><div class='details'>
            <pre >
                        + Add Case: You can save any case pending or disposed in any of the District or Taluka Courts in India.   
                        First make select as to whether the desired case is to be searched by CNR Number or Case Number or Filing Number. 
                        For CNR number it is not necessary to select State District or Court Establishment.   

                        For Case Number of High Court, select High Court, select desired case type, case number and year.   
                        For Case Number of Lower Court, First make selection of State, District and Court Establishment. 
                        Thereafter make selection of Case Type, Case Number and Year.   

                        Enter captcha and click <b>“Search”</b>.  In both the methods, if the details are correct, you will be shown case 
                        number and names of the parties.   Click Get details. Now you will be shown names of the petitioners. 
                        If you are petitioners or appearing for the petitioners select all the names. 

                        If you are respondents or appearing for respondents click desired name or names from the list shown on the screen. 
                        Finally click on the add case button. Now case will be saved in My Cases and you will be able track the details of the case.  
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix">

                </ul>
            </div>

        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/import_file') { ?>
        <br>
        <center><b>IMPORT CNR</b></center><div class='details'>
            <pre >
                        Almost every litigant or advocate has “eCourts Services” mobile app. 
                        There is facility to save cases in the mobile app. All those saved cases can be imported in the efiling application.  
                        First go to mobile appplication. From the left side menu (horizontal three lines one below other) click export. 

                        Your cases will be saved in downloads. From that location copy of file <b>“Mycases.txt” or Mycases_HC.txt”</b> Send these files through any medium on your PC. 
                        Now click on browse and select files <b>“Mycases.txt” or Mycases_HC.txt”</b>. All your cases which are saved in mobile app

                        will be imported in the eFiling application and you will
                        be able to see and track the developments of all those cases which are saved in your mobile application.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/work_done') { ?>
        <br>
        <center><b>ADD DEPARTMENT</b></center><div class='details'>
            <pre >
                        A facility to add Institutions/Organizations/Departments is provided under eFiling.  At the time of registering a user one can choose Department user.   
                        Such user can save own cases to track development.  
                        Department user can complete entire filing process by uploading documents. 

                        However final verification and submission of the efiled case to the Court can be done by Panel Lawyer only.  
                        Panel Lawyers can be added as per Court Establishment.   Department user can add sub office or branch office or Section under him for further decentralization.  
                        All the cases filed or saved by department user can be seen by all the users above him as per hirarchy tree created.   

                        Take a case that all the Collector office users are created at Secretriat and all the collectors further created users 
                        under them. All the cases saved by each user can be automatically seen and moniotored at Secretriat.  

                        While creating user it is always possible to limit the access of the user only to view certain information or user can be allowed to file cases to his panel lawyers.   
                        It is possible to send mail communications to panel lawyers through the facilities provided under eFiling.

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'supadmin/change_case_status') { ?>
        <br>
        <center><b>CHANGE CASE STATUS</b></center><div class='details'>
            <pre >
                        <b>Change Case Status: </b>Admins have power to process the cases from one stage to another. 
                        A new filed cases can be shown not accepted for some defect, it be notified that case has some deficit Court Fess to be paid, case if has complied all the requirements it can be transferred for filing and registration section. 
                        If the case remains without any action for so many days it can be shown that the cases has remained idle or unprocessed for so many days.   

                        If while doing above actions, if erroneously some wrong action has been taken which needs to be corrected or reversed, 
                        in such situation <b>“Change Case Status”</b> option needs to be used.
                        If case be notifed as not accepted but now it needs to be shown be again shown at the stage of new efiling such action needs 
                        to be performed through <b>“Change Case Status”</b>. Similarly if deficit court fees remarks has been wrongly notified it can be withdrawn using this facility.  

                        Admin has to enter efiling number. Correct efiling number will display current status of the case with provision to make changes. The only thing to be remembered is – everytime case status is changed giving correct reson is mandatory. 
                        Develop a habit to note correct actual reason for changing status. It will avoid all future untoward consequences.   
                        If wrong efiling number is searched you will alert that efiling number is invalid.  

                        If efiling number is valid but it is not on the stages viz. Not Accepted, Deficit Court Fee Awaited, Transfer to Section, Idle/Unprocessed in that case alert will notify you that the case is not above mentioned stages and as 
                        such cannnot be processed under <b>“Change Case Status”</b>.  However, if the efiled data is consumed in CIS,
                        thereafter, the this feature cannot be used to revert the stage. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/user') { ?>
        <br>
        <center><b>CREATE ADMIN</b></center><div class='details'>
            <pre >
                        There are four different login for Admin Users:-   
                        Superadmin(State admin) (A) :-  Super Admin can create 
                        High court admin  :-  The High Court Admin has privilege  to handle all efiled cases under the selected high court .    
                        District admin :- The District Admin has privilege to create admin for Court Establishments under the selected District only. 
                        It will not handle any efiled cases.  

                        Establishment admin :-  The Establishment Admin has privilege to handle efiled cases of the selected establishment.  
                        High Court Admin (B):- The High Court Admin has privilege  to handle all efiled cases under the selected high court .    
                        District Admin(C) :-  The District Admin has privilege to create admin for Court Establishments under the selected 
                        District only.  It will not handle any efiled cases.  

                        Establishment Admin(D) :- The Establishment Admin has privilege to handle efiled cases of the selected establishment.  
                        Note :- Super Admin (State Admin) and District Admin do not handle work of efiled cases. 
                        These two admin have administrative privileges to create further admins. High Court Admin and Establishment 
                        Admin are realted with actual work of handling eFiled cases.   First fill in the personal details of the Admin to be created.  
                         Then select whether Admin needs to be created for High Court or District or Court Establishment.   

                        If you select District in that case the newly created Admin will be for entire District which can monitor all the 
                        Court Establishments attached to that District.  There can be only District Admin for one District and one 
                        District Admin cannot be assigned work of another District.   If you are creating establishment admin in that case after
                         filling personal details of the Admin, first select “Court Establishment” option from the available choices.   
                        Select State, District and Court Establishment.   Now you will see that there are two options 

                        1. Single Admin 
                        2. Multiple Admins.   

                        Single Admin / Core Admin : when only one admin has to handle all efiled cases in the selected establishment, 
                        Core Admin type privileges needs to be given to High Court or Establishment Admin.  
                        All the business of efiling administration will be handled by Core Admin.  
                        Core Admin has authority to accept or reject newly registered users or advocates.  

                        Multiple Admins : When Court Establishment has heavy efiling in such it may be necessary to have multiple admins.  
                        It may be necessary to have section wise admins in Court establishment like High Court. 
                        Whenever multiple admin is chosen please remember that there shall be one Master Admin and multiple Action Admins. 
                        If one Master Admin is created you will find only Action Admin in the dropdown list of Admin Privilages.  

                        Master Admin(F) :-  Master Admin can only create  
                        Action Admins(G). There shall be only one Master Admin for each Court Establishment, if created.
                        If Master Admin is created authority to accept or reject newly registered users is given to Master Admin.   
                        Action Admin(G) :-  Action Admin actually handles business of efiled cases for the selected establishment. 

                        There can be one or more Action admins.  Action Admin has authority to accept or reject files, he can mark objections and he can send file to CIS after initial scrutiny.  
                        Take a case that initally while starting efiling in an establishment requirement of only one admin was felt necessary and, 
                        therefore, 

                        core admin(E) account was created. However, after passage of time, need is felt for more admin accounts to handle business of efiled cases or work of admin needs to be separated for each branch or section.  
                        In such situation, it is necessary to first create a 

                        master admin(F). In the event of creating Master Admin when Core admin already exists, 
                        existing Core Admin will be automatically shown as 
                        Action Admin(G). Master Admin(F) can further create more Action Admins(G) 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'supadmin/department') { ?>
        <br>
        <center><b>CREATE Department</b></center><div class='details'>
            <pre > 
                        CREATE Department
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/contact') { ?>
        <br>
        <center><b>CREATE CONTACT</b></center><div class='details'>
            <pre > 
                        It is necessary for the users to contact efiling admin for various reasons. 
                        Therefore, a provision is made to show email address of authorised person where users can send emails for queries and grievances. 
                        For this purpose email address need to be entered and the same will be showed as authorised contact to all the users. 

                        Needless to mention that there are two choices <b>“High Court”</b> and <b>“lower Court”</b> as such while creating contact you 
                        can select appropriate High Court and by select State, District Court and Court Establishment 
                        you can select appropriate lower court. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'news_event/view') { ?>
        <br>
        <center><b>CREATE NEWS AND EVENTS</b></center><div class='details'>
            <pre > 
                        Many a times High Court or District Court issue various circulars relating to eFiling. 
                        There are various notices or public messages which given by the Courts. 
                        Standard Operating Procedure, Rules, Notifications etc are also required to be displayed for the benefit of users. 
                        All such and like things can be handled through News and Events. There are two types of News events that can be 
                        created using this feature e.g. <b>“Public view”</b> is one which can be viewed by one and all, 
                        whereas, <b>“Private view”</b> is one where view can be restricted to Admins only. 

                        News item can be uploaded and showed for the chosen period.  Give title to the news item or event.   
                        Upload the file of news item or event. Please keep in mind that only pdf files can be uploaded.   
                        Decide whether the uploaded file can be viewed by public or should be made available only for Admins.  
                        Decided the date on which such news item needs to be deactivated so that it will disappear from the 
                        News and Events Section of the efiling portal. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'notice/view') { ?>
        <br>
        <center><b>UPLOAD NOTICE AND FORMS</b></center><div class='details'>
            <pre > 
                        Notice and forms are documents which are frequently required by advocates and lawyers to be submitted to
                        Court or Administration in connection with the cases which are pending or disposed by the Court.   
                        Generally these Notice and Forms need to be downloaded, filled in and again scanned and uploaded. 
                        It is part of access to justice. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/case_type') { ?>
        <br>
        <center><b>ADD CIS CASE TYPES</b></center><div class='details'>
            <pre > 
                        It may happen that High Court or District Court Administration may take a decision to allow 
                        eFiling for specific case types only. In such situation this facility is provided for admins to enable efiling only for 
                        specified case types.   The Case Types shown here are taken from your CIS Court establishment master database. 
                        Therefore it is possible that you may have fill in complete details in CIS master database before showing the case
                         types to public. Please read following remarks to understand the situation.   

                        While filing cases through efiling, litigant or advocate needs to select appropriate Case Type. 
                        When clicks Case Type fields a drop-down list appears. Please ensure that short from and description of 
                        Case Type is properly filled in your Case Type Master. If the details are not filled in user may not be 
                        able to make proper selection of Case Types. More particularly, party in person may not understand short forms 
                        of Case Types, therefore, it is necessary to ensure that all the details in Case Types are filled in Correctly.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'supadmin/establishment') { ?>
        <br>
        <center><b>ADD COURT ESTABLISHMENT</b></center><div class='details'>
            <pre > 
                        Using this facility Admin will be able to add new Court Establishment so as to enable the same for eFiling.   
                        Admin has to select Court Establishment and enter its Application Server IP.  
                        Those who want to go live, after updating efiling patch from release server, please run this command on terminal of application server.  
                        curl -k https://efiling.ecourts.gov.in/test_access.php  Sample result for IP 10.21.147.12 (CIS server IP) 

                        should be like this :   Curl Request to eFiling Server : SUCCESS  Request IP : 10.21.147.12 Please inform this 
                        IP as your application server IP to State Admin to enter the same for enabling your establishment for efiling.  
                        The IP shown here needs to be entered as Application Server IP through State Admin.  
                        If you are not getting SUCCESS result, in that case follow following course of action  Enable Curl and libssl-dev 

                        on live server using these commands 1. sudo apt-get install libssl-dev 2. sudo apt-get install curl Please 
                        restart apache server after these commands  Case Types  Many a times at the time of enabling eFiling High 
                        Court or District Court may give specific field availability as requirement. Sometimes some fields are made 
                        mandatory whereas some fileds are kept optional. However, requirements of District Court or High Court may 
                        differ from what is provided.  Therefore, a facility is given to the Admin to choose which fileds are required as 
                        mandatory while efiling a case. As per the requirements selected here user will be notified to fill in details.   

                        Following are the fields which State Admin can select so as to make them madatory or optional. If the fields are 
                        not selected by Admin the below mentioned fields will not be mandatory.   Petitioner State Petitioner District 
                        Petitioner Email Petitioner Mobile Petitioner Extra Party State Petitioner Extra Party District Petitioner Extra Party 
                        Email Petitioner Extra Party Mobile Respondent State Respondent District Respondent Email Respondent Mobile 
                        Respondent Extra Party State Respondent Extra Party District Respondent Extra Party Email Respondent Extra Party 
                        Mobile IA Purpose  Payment Method:  At the same time, Admin will have to select payment method.  

                        There are two types of payment method 1. Online 2. Offline  When ePayment is enabled for the concerned 
                        Court Establishment then you can choose online payment method. If ePayment is not integrated with 
                        eFiling in that case it is better that offline mode of payment is selected.   
                        By offline mode user will have to upload the challan, eChallan, receipt, cheque, 
                        stamp or like instrument as proof of payment of Court Fee or other payable amount 
                        by giving required details.   Whereas in the case of online payments details are fetched 

                        and acknowledgement receipt is generated automatically and the information is sent from 
                        efiling portal or CIS including unique code for defacement, if made available.   Make Changes or view add 
                        establishment:  ON the given screen if one scrolls down he or she will get the list of the 
                        Court Establishments added so far.  One can view details and current status of the 
                        Court Establishment is concerned.  One can revok the establishment from efiling use or make changes 
                        through this option. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'supadmin/check_payment') { ?>
        <br>
        <center><b>ADD PAYMENT GATEWAY</b></center><div class='details'>
            <pre >  
                        This is facility to test the online payment of court fees or other fees.   
                        First select type of Court i.e. <b>“High Court”</b> or <b>“Lower Court”</b>  If it is lower Court then select State, 
                        District and Court Establishment  If it is High Court then select name of the High Court.  
                        Select type of payment.  Enter amount, enter name of the person paying the fees.  
                        Click Payment, it will take you on the payment gateway.   If you can make payment, 

                        it means that facility can be used advocates and litigants as like you.  This is only to test facility of 
                        online payment therefore while making payment make sure that you enter token amount of Rs. 1.  
                        To know the success of payment you would see the tabulated information at the botton of your screen.  
                        In this table third column is Order Number/Date <b>“Order Number”</b> is numerical value generated by efiling 

                        software so as to make your payment transaction unique and identifiable.   Whereas, next column is details 
                        received from Bank or Government. This reference/receipt number indicates whether your payment is received 
                        by which number and at what date, time etc.   Fifth Column is Bank through which your payment has been made 
                          Sixth column shows the amount and seventh column shows whether it deficit payment of court fees or not.   
                        Final column shows whether payment is success or failed or pending.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'supadmin/go_live') { ?>
        <br>
        <center><b>GO LIVE</b></center><div class='details'>
            <pre > 
                                         A checklist is given on the screen. First complete the process and then click yes for every activity. If all the activities are answered yes after actually performing them you can click Go Live button.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/new_registration') { ?>
        <br>
        <center><b>New Request</b></center><div class='details'>
            <pre >There are three types of users in eFiling.   

                        1. Advocate 
                        2. Party in person 
                        3. Department/Organization/Institution  

                        This facility is used only party in person. When any party in person will register, 
                        his requested can be viewed here. Admin needs to go through the personal details of the 
                        user and documents submitted by him.   If he has completed eKYC, in that case it may not be necessary 
                        for him undergo physical verification process.   However, if he has tendered documents for physical verification,

                        admin needs to complete the process of verification as per guidelines issued by High Court or District Court, 
                        as may be the case.   Once physical process is complete and approved the concerned competent authority of 
                        the Court in that case such request needs to be approved. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/new_registration/3455819078557041556925') { ?>
        <br>
        <center><b>New Request</b></center><div class='details'>
            <pre >There are three types of users in eFiling.   

                        1. Advocate 
                        2. Party in person 
                        3. Department/Organization/Institution  

                        This facility is used only party in person. When any party in person will register, 
                        his requested can be viewed here. Admin needs to go through the personal details of the 
                        user and documents submitted by him.   If he has completed eKYC, in that case it may not be necessary 
                        for him undergo physical verification process.   However, if he has tendered documents for physical verification,

                        admin needs to complete the process of verification as per guidelines issued by High Court or District Court, 
                        as may be the case.   Once physical process is complete and approved the concerned competent authority of 
                        the Court in that case such request needs to be approved. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/new_registration/899725526289255586') { ?>
        <br>
        <center><b>ACTIVATED USERS</b></center><div class='details'>
            <pre >
                                        Activated users facility only gives list of the users. Admin may see the information of any user in the event he has reason to know more details about any user.   Admin can search all activated users. Data of users can be searched on the basis of name,place, any text or number or mail.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin/new_registration/7855505597255586') { ?>
        <br>
        <center><b>REJECTED USERS</b></center><div class='details'>
            <pre >
                        This is list of rejected user.  Admin can see details of any rejected user. 
                        List can be searched on multiple counts such as name, any text or number.  
                        In the event any user is rejected, he would receive opportunity to cure the defects through the mail notifications.   

                        Only when request is approved, such users will be able to file the cases otherwise access to such users will 
                        be kept limited to Registration. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'report/progress/court_fee_details') { ?>
        <br>
        <center><b>REPORTS :- FEES PAID</b></center><div class='details'>
            <pre >
                        A facility is provided to Action admin to view, monitor and generate fees paid report.   
                        To know the success of payment you would see the tabulated information at the botton of your screen.  
                        In this table there is one column named Order Number/Date “Order Number” is numerical value generated by efiling software so as to make your payment transaction unique and identifiable.   

                        Whereas, next column is details received from Bank or Government. 
                        This reference/receipt number indicates whether your payment is received by which number and at what date, time etc.  
                        The table shows information of the Bank through which your payment has been made   
                        Admin can easily see what is the payment made and whether payment is deficit and if yes, what is deificit amount.   

                        This table helps Action Admin to monitor details of fees paid. In case there is any issue about fees paid, 
                        these details can be used.  It is needless to mention that admin can generate the desired report by selecting period range.
                         It is possible to search in the generated report. You can always search the report on the basis of text, 
                        number or combination.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'report/progress/efiling_done') { ?>
        <br>
        <center><b>eFiling Done</b></center><div class='details'>
            <pre >
                        The only difference between earlier seen work done report and eFiled report is – one case can be shown multiple times under eFiling Done report, if it has passed many stages in a day however under work done report single case will be shown only under one stage. 
                        By default current month (from the first date of current month till Today) developments for all the Court Establishments under Admin will be shown.

                        However, if required, Admin can select the period.
                        Admin can generate report for specific efiling type i.e. New eFiled Cases, Inerim Applications, eFiled documents or Payment of deficient court fee. 
                        Report can be generated for Civil or Criminal or Both. (by default report will be shown for both types).

                        Admin can generate separate report for High Court or Lower Court.
                        After selecting High Court no other selection is necessary.
                        However after selecting Lower Court, it may be necessary to select District. After selecting District if one needs cases relating  to specific establishments in that it may be necessary to select the desired Court Establishment.

                        Thus, Admin can always generate reports with a view to monitor the progress of efiling as per his choice or requirement.
                        Needless to mention that generated report can be searched on the basis of text or number or combination.
                        You can always change number entries which are shown on the screen. On left side, above the tabulated information, you can select number entries to be shown the screen. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'report/progress/transaction_details') { ?>
        <br>
        <center><b>Transaction Details</b></center><div class='details'>
            <pre >
                        Transaction details provided information relating to status of the payment. It clearly shows whether payment is successful, pending or failed. 
                        To know the success of payment you would see the tabulated information at the botton of your screen.
                        First column shows field in which transaction is made by the user. Second Column is fee type. 

                        In this table third column is Order Number/Date “Order Number” is numerical value generated by efiling software so as to make your payment transaction unique and identifiable.
                         Whereas, next column is details received from Bank or Government. Here you may find short form :GRN: which means Government receipt number. This reference/receipt number indicates by which number your payment is received and at what date, time etc. 
                        Table also shows details of the Bank through which your payment has been made 

                        Last but one column shows transaction amount. 
                        Final column shows whether payment is success or failed or pending.
                        Admin can always search the report on the basis of any text or number or combination consisted in the table contents. 
                        Admin can generate desired report by entering specific range of period by selecting appropriate dates. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'admin') { ?>
        <br>
        <center><b>Home</b></center><div class='details'>
            <pre >
                        This is list of rejected user. 
                        Admin can see details of any rejected user.
                        List can be searched on multiple counts such as name, any text or number.
                        In the event any user is rejected, he would receive opportunity to cure the defects through the mail notifications. 

                        Only when request is approved, such users will be able to file the cases otherwise access to such users will be kept limited to Registration. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'contact_us/contact/district_admin_contact_details') { ?>
        <br>
        <center><b>District Admin</b></center><div class='details'>
            <pre >
                        In the evet Action Admin requires District Admin to contact on urgent basis, this is assistance provided in the form of quick reference to contact details of District Admin.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'contact_us/contact/super_admin_contact_details') { ?>
        <br>
        <center><b>Super Admin</b></center><div class='details'>
            <pre >
                        In the evet Action Admin requires State Admin to contact on urgent basis, this is assistance provided in the form of quick reference to contact details of State Admin.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/73') { ?>
        <br>
        <center><b>NEW FILING</b></center><div class='details'>
            <pre >
                        when any user files a new case or documents or Interim Application after final submission 
                        it reaches here for further action by Admin user.  From the dashboard if newfiling is clicked, it opens this screen.  
                        It shows cases filed by different users pending for action by the admin user.  

                        Second column shows efiling number,  third column shows type of efiling such new case, misc. Documents, interim application, deficit court fees etc.   
                        Fourth column shows case details such CNR Number, Filing Number, Registration Number and names of the parties.

                        Fifith column shows date and time of filing.  Fifth column given Action button. 
                        When you click action button you can view the submitted file and data. 
                        After examining the same admin can take necessary action provided there.  
                        When admin clicks action, information of the case will be shown to him.  

                        He has three actions to be taken a. Approve b. Disapprove c. Deficit court fee.   
                        When click approve, the case will be ready for transfer to CIS.  When you click disapprove a pop up window 
                        will open and you may have choose appropriate objections mentioned in the list shown on pop-up page.  
                        Along with objections you can mark deficit Court fees by writing the amount of deficit court fee.   

                        In case reason of objection or defect is not listed on the page, admin can choose free text given at the 
                        bottom of the page. On the free text portion admin can write his objection along with compliance 
                        expected from the user.  Once case is disapproved, it will start reflecting in “For Compliance” for user 
                        as well as for admin.   If the eFiled case has only deficit court fee in that case admin can select deficit court 
                        fee option on the top right corner of the screen.   

                        Here admin has to enter amount of deficit court fee and 
                        the user will be notified about the objection of deficit court fee.  
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/48') { ?>
        <br>
        <center><b>FOR COMPLIANCE</b></center><div class='details'>
            <pre >
                        When any defect is flagged in any eFiled case, the file automatically goes on the stage of compliance.   
                        It shows cases filed by different users pending for compliance by the concerned user.  
                        Second column shows efiling number, third column shows type of efiling such new case, misc. 

                        Documents, interim application, deficit court fees etc.   Fourth column shows names of the parties.   
                        Fifith column shows date and time of raising objection.  Admin can click on eFiling number and see the details of 
                        each case, if desired.  After click of “action” button, admin can always see efiling histroy 
                        button on the top right side of the screen which can be used to know entire efiling history logs. 
                                          
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/84') { ?>
        <br>
        <center><b>FOR COMPLIED OBJECTIONS</b></center><div class='details'>
            <pre >
                        When new cases or documents or interim applications are filed and on scrutiny admin marks objections. 
                        All such cases are shown for compliance of the user.   
                        When user makes good the defects or complies with the objections raised and submits the very 
                        case or application or document again, all such reuqests are listed before Admin under complied requests.   
                        Second column shows efiling number, third column shows type of efiling such new case, misc. Documents, 

                        interim application, deficit court fees etc.   Fourth column shows case details such CNR Number, Filing Number, 
                        Registration Number and names of the parties.   Fifith column shows date and time of filing.  
                        Fifth column given Action button. When you click action button you can view the submitted file and data. 
                        After examining the same admin can take necessary action provided there. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/9233') { ?>
        <br>
        <center><b>PAID DEFICIT COURT FEE</b></center><div class='details'>
            <pre >
                        When in any case deficit court fee objection is marked and user complies the objecction by making
                        payment of deficit court fee all such cases after filing and registration will be shown here.  
                        Table shows as to when i.e date and time deficit court fee is paid by the user in each listed case.   

                        This facility does not demand any action from the admin. The facility is given only for assitance so that all the 
                        efiled documents can be seen and searched at one place and report generation will be possible.   
                        Admin user can always click on efilng number or CNR number, registration number to know details in the case. 
                        All such details are available on click of the filing or registration numbers. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/33') { ?>
        <br>
        <center><b>TRANSFER TO CIS</b></center><div class='details'>
            <pre >
                        When cases, documents or interim applications filed through eFiling are scrutized and approved, 
                        they need to be transferred to CIS at respective Court Establishment.   
                        This facility can be used to transfer case, document or interim application CIS.  
                        Please make sure that the case is ready in all respects to be transferred to CIS.   

                        Efiling number can be clicked to know entire details of the case including documents uploaded by the user.   
                        Third column in the table is “Type” which indicates whether user has filed new case or document or interim 
                        application or paid deficit court fee.   Time of updation indicates as to when user has finally submitted the 
                        case or document or application.  Final column shown button. On click of this button, case can be 
                        transferred to CIS through efiling portal. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/43') { ?>
        <br>
        <center><b>GET CIS STATUS</b></center><div class='details'>
            <pre >
                        When case, document or interim application is transferred to CIS user needs to know whether his case is accepted in CIS. 
                        If case of the user is accepted in that case he needs to know what CNR number is given to the case, 
                        what is filing and registratin number.   All such details can be fetched from Get CIS Status. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/17') { ?>
        <br>
        <center><b>PENDING SCRUTINY</b></center><div class='details'>
            <pre >
                        Admin may have impression that “For Compliance” and “Pending Scrutiny” are identical stages. However, 
                        “For Compliance” is stage when case has not reached CIS and objections are raised when case is on efiling portal. 
                        All these objections are raised by efiling admin. However, “Pending Scrutiny” 

                        stage indicates that case is pending of scrutiny at the office after data is consumed through CIS. 
                        This difference need to be kept in mind.   Pending scrutiny indicates that Office has not gone through 
                        the file and has not applied its mind to list objections. Thus the case still pending with the office for scrutiny.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/9236') { ?>
        <br>
        <center><b>DEFECTIVE</b></center><div class='details'>
            <pre >
                        Cured defects can be seen here. It means that the office has listed objections for the efiled case. 
                        The user has gone through the objections listed by the office and he made good those objections. 
                        Hence case is shown in defective cured category.   Once the cured defects are examined by the office, 
                        the case can be processed for transfer to CIS, if everything is in order. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/9292') { ?>
        <br>
        <center><b>Cured Defects </b></center><div class='details'>
            <pre >
                        Cured defects can be seen here. It means that the office has listed objections for the efiled case. The user has gone through the objections listed by the office and he made good those objections. Hence case is shown in defective cured category. 

                        Once the cured defects are examined by the office, the case can be processed for transfer to CIS, if everything is in order. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/9273') { ?>
        <br>
        <center><b>EFILED CASES </b></center><div class='details'>
            <pre >
                        In this screen you will see the list of the cases which are successully filed through eFiling.   
                        This will give birds eye view to know all the efiled cases together at one place with facility to know 
                        more about their case history and logs. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/9248') { ?>
        <br>
        <center><b>EFILED DOCUMENTS </b></center><div class='details'>
            <pre >
                        When documents or misc. Interim applications are filed through efiling, all such misc. 
                        Documents and inerim application will be shown here.   In the case details one can see CNR number, 
                        filing number, registration number.   One can see all the details of the case once a case is selected.   

                        All the list of the efiled documents can be searched on the basis of text or number.   
                        This facility does not demand any action from the admin. 
                        The facility is given only for assitance so that all the efiled documents can be seen and searched 
                        at one place and report generation will be possible.   
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/7332') { ?>
        <br>
        <center><b>PAID DEFICIT COURT FEE </b></center><div class='details'>
            <pre >
                        When in any case deficit court fee objection is marked and user complies the objecction by 
                        making payment of deficit court fee all such cases after filing and registration will be shown here.  
                        Table shows as to when i.e date and time deficit court fee is paid by the user in each listed case.   

                        This facility does not demand any action from the admin. The facility is given only for assitance so that 
                        all the efiled documents can be seen and searched at one place and report generation will be possible.  
                         Admin user can always click on efilng number or CNR number, registration number to know details in the case. 
                        All such details are available on click of the filing or registration numbers. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/7311') { ?>
        <br>
        <center><b>IA </b></center><div class='details'>
            <pre >
                        All Interim Applications filed so far will be listed here.   
                        Table shown as to when each interim application is updated i.e. date and time.   
                        This facility does not demand any action from the admin. The facility is given only for assitance so 
                        that all the efiled documents can be seen and searched at one place and report generation will be possible.

                        Admin user can always click on efilng number or CNR number, registration number to know details in the case. 
                        All such details are available on click of the filing or registration numbers.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/7336') { ?>
        <br>
        <center><b>REJECTED CASES </b></center><div class='details'>
            <pre >
                        Rejected cases are those where scrutiny section of the court after going through the case has rejected the case.  
                        Either user can comply the defect and resubmit the case.  
                        If the users chooses to comply with the requirements, with the same efiling number or CNR number, 
                        user will be able to refile this case.  The list shown under Rejected cases given details of eFiling Number, 
                        filing number, CNR number and registratin number, if any. Each number can be clicked to know more details 

                        in the case including documents uploaded.   Each case shown in the list can be seen as to whether it was new case, 
                        or document or interim application. It be easily seen as to when i.e date and time when the case is rejected along 
                        with person or section which rejected the case.   The facility does not require any action from the admin user. 
                        It only provides searchable interface and in the event more details are necessary each number can be clicked. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/admin_stages/view/7373') { ?>
        <br>
        <center><b>IDLE/UNPROCESSED </b></center><div class='details'>
            <pre >
                        Admin will be able to see whether filing section of the Court has kept any cases pending. 
                        Admin can always track as to how many cases are idle or unprocessed by the filing section of the Court. 
                        He can know since when such cases are idle.   This interface gives monitoring interface and ability to correct mistakes by
                         proper coordination with office or filing section.

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/petitioner') { ?>
        <br>
        <center><b>PETITIONER </b></center><div class='details'>
            <pre >
                        Here you are supposed to enter details of the Main petitioner or petitioner who is first in order of chronology of petitioners.

                        Organisation: What is organization? In legal terminology whenever any case is filed by any legal entity other than natural person e.g. Company, Bank, Government Department, University, Institution, Organization, Private or public company, coproration etc. in that case organization check box need to be checked. 
                        Already there are prefilled entries in orgainization which can be serached by simply typing three or more letters of name of organization. After typing some text existing entries are shown. You can select the existing entries and proceed ahead. 
                        After searching name of the desired organization if no matching results are found, then check not in the list check box and enter details of organization which you wish to insert. While writing details of organization please ensure that name of the authorised person who can file case for and on behalf of the organization is mentioned succinctly in the field Complainant / Petitioner *

                        Please ensure that while writing name of the petitioner full name is entered. It always a good practice to entere email ID and mobile number of petitioners so that they start receiving eCourts services.

                        Relation: Many times a user is confused as to what is relation and what he is supposed to fill in the field named “Relation of Complainant / Petitioner with relative name *” and “Relative name” . In the Court Proceedings parties are often referred as ABC Son of PQR or ABC wife of PQR and like wise. For this purpose, initially one has to select the relation of person whose name you are likely to write as father, mother, husband or wife etc. After selecting relation one has to write name of the person as per relation selected. 

                        Address * : While writing address it is always a good practice to only mention house number, road, colony etc in address field, whereas, details like District, Taluka, Town, Village or Ward are mentioned in the fields given below address. It is not necessary to write District, Taluka or village in the 

                        Police Station: Whenever in private criminal compliants or domestic violence applications processes are served through police, in such cases police station details are required. These police stations are not relating to incident but nearby police station to the address of the person entered here. Meaning thereby which police station should be requested to serve processes on the given address, if need there be.

                        eFiling Number: Once the information relating to main petitioner is complete and saved, automatically efiling number will be generated. You will notice efiling number on top right side of the screen in red coloured rectangle. eFiling number is 18 digit unique number. It will be separate number for each efiling. Now eventhough you log out or logged out of efiling login you can continue from the place you left. Just click home and select the details or efiling number. Click view and you can restart from the place you left. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/respondent') { ?>
        <br>
        <center><b>REPONDENT </b></center><div class='details'>
            <pre >
                        Here you are supposed to enter details of the Main Respondent or respondent who is first in order of chronology of petitioners.

                        Organisation: What is organization? In legal terminology whenever any case is filed against any legal entity other than natural person e.g. Company, Bank, Government Department, University, Institution, Organization, Private or public company, coproration etc. in that case organization check box need to be checked. 
                        Already there are prefilled entries in orgainization which can be serached by simply typing three or more letters of name of organization. After typing some text existing entries are shown. You can select the existing entries and proceed ahead. 
                        After searching name of the desired organization if no matching results are found, then check not in the list check box and enter details of organization which you wish to insert. While writing details of organization please ensure that name of the authorised person who can be sued for and on behalf of the organization is mentioned succinctly in the field Complainant / Petitioner *

                        Please ensure that while writing name of the respondent full name is entered. It always a good practice to entere email ID and mobile number of respondents only when you knew them to be correct otherwise leave the fields empty. Providing incorrect details of mail and mobile number of respondent may invite problems. 

                        Relation: Many times a user is confused as to what is relation and as to what he is supposed to fill in the field named “Relation of respondent with relative name *” and “Relative name” . In the Court Proceedings parties are often referred as “ABC Son of PQR” or “ABC wife of PQR” and like wise. For this purpose, initially one has to select the relation of person whose name you are likely to write as father, mother, husband or wife etc. After selecting relation one has to write name of the person as per relation selected. 

                        Address * : While writing address it is always a good practice to only mention house number, road, colony etc in address field, whereas, details like District, Taluka, Town, Village or Ward are mentioned in the fields given below address. It is not necessary to write District, Taluka or village in the address filed. However, when respondent is resident of other State which cannot be selected from the dropdown list in such cases you can write “District: Kolkata, State: West Bengal”. However, as far as possible write name of District, Taluka and village through dropdown list. This type of selection has added advantages to you while using management tools. 

                        Police Station: Whenever in private criminal compliants or domestic violence applications processes are served through police, in such cases police station details are required. These police stations are not relating to incident but nearby police station to the address of the person entered here. Meaning thereby which police station should be requested to serve processes on the given address, if need there be. 

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/extra_info') { ?>
        <br>
        <center><b>EXTRA INFO </b></center><div class='details'>
            <pre >
                        It is recommended to fill in this information. Filling this information is not mandatory but it is good practice to do so. In the event of some urgent situations, this information can be used by court administration to contact you. 

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/extra_party') { ?>
        <br>
        <center><b>EXTRA PARTY </b></center><div class='details'>
            <pre >
                        What is Extra Party ?
                        For technology purposes parties which are first in chronological order on the side of petitioner or respondents are referred to as “Main Petitioner” and “Main Respondent” other petitioners are referred to as “Extra Petitioners” and other respondents are referred to as “Extra respondents”.
                        You will find two selection at the begining. “Complainant / Petitioner” need to be selected for adding Extra Petitioners and “Accused / Respondent” need to be selected for adding Extra Respondents. 

                        Organisation: What is organization? In legal terminology whenever any case is filed by any legal entity other than natural person e.g. Company, Bank, Government Department, University, Institution, Organization, Private or public company, coproration etc. in that case organization check box need to be checked. 
                        Already there are prefilled entries in orgainization which can be serached by simply typing three or more letters of name of organization. After typing some text existing entries are shown. You can select the existing entries and proceed ahead. 
                        After searching name of the desired organization if no matching results are found, then check not in the list check box and enter details of organization which you wish to insert. While writing details of organization please ensure that name of the authorised person who can file case for and on behalf of the organization is mentioned succinctly in the field Complainant / Petitioner * 

                        Please ensure that while writing name of the petitioner full name is entered. It always a good practice to entere email ID and mobile number of petitioners. Similarly it is also better to skip details of email ID and mobile number, if you are personally not sure of its correctness. Feeding incorrect data may invite problem.
                        Relation: Many times a user is confused as to what is relation and what he is supposed to fill in the field named “Relation of Complainant / Petitioner with relative name *” and “Relative name” . In the Court Proceedings parties are often referred as ABC Son of PQR or ABC wife of PQR and like wise. For this purpose, initially one has to select the relation of person whose name you are likely to write as father, mother, husband or wife etc. After selecting relation one has to write name of the person as per relation selected. 

                        Address * : While writing address it is always a good practice to only mention house number, road, colony etc in address field, whereas, details like District, Taluka, Town, Village or Ward are mentioned in the fields given below address. It is not necessary to write District, Taluka or village in the 

                        Police Station: Whenever in private criminal compliants or domestic violence applications processes are served through police, in such cases police station details are required. These police stations are not relating to incident but nearby police station to the address of the person entered here. Meaning thereby which police station should be requested to serve processes on the given address, if need there be. 

                        After entering name of each party First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/add_LRs') { ?>
        <br>
        <center><b>LR'S </b></center><div class='details'>
            <pre >
                        While fililng an appeal, often it becomes necessary to show parties including the parties who died and whose legal heirs or representatives have been brought on record. For this purpose, it becomes necessary to add names of the parties who died during the pendency of litigation before the trial court and parties whose names are brought on record in the trial court. 

                        Please ensure that while writing name of the petitioner full name is entered. It always a good practice to entere email ID and mobile number of petitioners. Similarly it is also better to skip details of email ID and mobile number, if you are personally not sure of its correctness. Feeding incorrect data may invite problem.
                        Relation: Many times a user is confused as to what is relation and what he is supposed to fill in the field named “Relation of Complainant / Petitioner with relative name *” and “Relative name” . In the Court Proceedings parties are often referred as ABC Son of PQR or ABC wife of PQR and like wise. For this purpose, initially one has to select the relation of person whose name you are likely to write as father, mother, husband or wife etc. After selecting relation one has to write name of the person as per relation selected. 

                        Address * : While writing address it is always a good practice to only mention house number, road, colony etc in address field, whereas, details like District, Taluka, Town, Village or Ward are mentioned in the fields given below address. It is not necessary to write District, Taluka or village in the 

                        Police Station: Whenever in private criminal compliants or domestic violence applications processes are served through police, in such cases police station details are required. These police stations are not relating to incident but nearby police station to the address of the person entered here. Meaning thereby which police station should be requested to serve processes on the given address, if need there be. 

                        After entering name of each legal representative First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/case_detail') { ?>
        <br>
        <center><b>CASE DETAILS </b></center><div class='details'>
            <pre >
                        It is recommended to fill in this information. Writing cause of action and prayers have added advantage. It also helps the Court Administration to process your case without keeping it pending for small things to be verified. It is therefore advisable to enter details of cause of action and prayer.

                        Claim Amount: Claim amount means valuation of the subject matter. 

                        Prayer: In your Case Information System, already list of prayers is given. When prayer field is clicked it shows dropdown list of prayers already contained in CIS is shown. You have select desired prayer entries. Every time prayer is selected please click arrow button adjacent to the prayer filed. You will find that text of the prayers is appearing Relief Claimed field. You can edit this text and change it according to you requirement. You can add more than one prayers.

                        Place of Dispute: Select location where subject matter of property is situate OR select place where contract is entered into OR select place which will help to determine jurisdiction. 

                        Hide Parties : When subject of matter of case demands please check hide parties box so that names of the parties will not be shown on the portal, orders will not be available on portal.

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/act_section') { ?>
        <br>
        <center><b>ACT SECTION </b></center><div class='details'>
            <pre >
                        Entering information relating to Acts and Sections is most important information to be filled. It is not only mandatory but it immensly help advocates and litigants to manage their cases effectively. It also helps them to save their case notes and case laws. Therefore please ensure that information relating to Acts and Sections is fully and completely entered whiling filing cases. 

                        Generally it seen that information of only Act is filled in just because entry of minimum one field is compulsory. However, it is necessary to enter information of all the Acts and Sections. While filing civil cases please do not select Civil Procedure Code is Act, rather enter Acts like Specific Relief Act, Transfer of Property Act, Registration Act, Indian Succession Act, Indian Contract Act, Sale of Goods of Act, Partnership Act, Limitation Act, Hindu Marriage Act, Hindu Succession Act etc. Do not miss to mention relevant sections of the select act.

                        It would not be out of place to mention that sections are comma separated values without space. 

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/subordinate_court') { ?>
        <br>
        <center><b>SUB ORDINATE COURT </b></center><div class='details'>
            <pre >
                        Subordinate Court information: This information is required to be filled only when you are filing appeal type case i.e. case which challanges order or judgment passed by the lower court in Higher forum. Therefore, it is necessary to enter information of the case, order under challange and name, designation of judge of lower court. 

                        For entering this information either you can enter CNR number of the case of lower court and date of passing court to be challanged or you can enter State, District and Court Establishment of the lower court which passed order to be impugned. After selecting this information, select case type case number and year. Now enter date of impugned order or judgment. Once this information is filled in rest of the details will be automatically fetched. 

                        Enter date when certified copy is applied for and date on which certified copy is ready to be delivered. This will help in calcuating period of limitation in filing appeal. 

                        This information is very important as this will create connection between case pending or disposed in the Lower Court and case to be filed in appeal. This will make you possible to get all the information in the court below just a click away. 

                        If this information is entered you will get realtime updates of the case pending in the Lower Court. This will also give access to all interim orders passed by the Lower Court in the case in question. 

                        Do not skip this information, it is one of the must kind of the exercises. While selecting matter type initially select appeal so that this tab is shown. If you do not select appeal case type initially this tab will not be generated and you may not be able to fill in the information relating to subordinate court. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/upload_mvc') { ?>
        <br>
        <center><b>MVC </b></center><div class='details'>
            <pre >
                        Motor Vehicle Case : When any case relates to Motor Accident Claims, initially it is necessary to select that case in concerned with Motor Vehicle (MVC).  

                        Only when the option of  “Motore Vehicle Case related (MVC) case” is selected, MVC tab is shown. If this option is not selected, it may not be possible to enter details of Motor Vehicle or Accident. 

                        You can enter multiple vehicle details in a single case. After entering information of first vehicle do not forget to save the data before click next. 

                        Fill in the desired information about motor vehicle. Some of the fields are mandatory and certain filed like FIR Type are optional keep this in view while entering the information. 

                        First save and then click next. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/sign_method') { ?>
        <br>
        <center><b>SIGN METHOD </b></center><div class='details'>
            <pre >
                        If you have your own digital signature token, then first sign your pdf files and then upload the files. 

                        Ecommittee, Supreme Court of India has provided free eSigning facility which uses Aadhar number to verify identity of the person. You have to enter you Aadhar (UIDAI) number, thereafter you will receive OTP on your registered mobile number, enter the received OTP on the efiling portal, documents will be successfully signed. 

                        For using eSign method first document need to be uploaded and then you can eSign the uploaded documents. However, if you have your own token of Digital Signature issued by Competent Authority under IT Act, then you may have to sign the documents first and then upload the same. 

                        Hence before uploading documents it is necessary to make choice of method of signing the uploaded documents. After selecting method do not forget to click SAVE before clicking NEXT. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/upload_docs') { ?>
        <br>
        <center><b>UPLOAD DOCS </b></center><div class='details'>
            <pre >
                        UPLOAD DOCUMENT :  After document field is clicked, a dropdown list is shown containing names of documents. The entries shown in the list can be searched or scrolled. 

                        Only PDF documents can be uploaded. The document chosen for upload can be generated PDF file or scanned PDF file.

                        Ordinarily generated PDF files from word processors are less in size compared to scanned documents.

                        In one attempt you can upload PDF file of maximum size of 20 MB. If your file size is more than 20 MB then you can upload the very file is parts of 20MB each. 

                        While uploading documents please ensure that you select proper options for what purpose document is filed in the Court. 

                        Please mention which documents are appened in support of the pleadings and which documents are uploaded in support of the application.

                        Same applied to written statement, affidavit in reply, Rejoinder, Sur-Rejoinder etc. 

                        First select name of the document, write document title making it case specific pleadings. Click browse select desired document to be uploaded and click upload button on the screen. You will find that a table is created giving name of the document and hash value of the uploaded pdf document. This hash value will be preserved to check authenticity of the document in future. 

                        You can go on adding documents one after another and list of the document will go on showing one after another document along with its hash value. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/courtFee') { ?>
        <br>
        <center><b>COURT FEE </b></center><div class='details'>
            <pre >

                        Offline Mode of Court Fee Payment : There are various modes of payment of Court Fee through eFiling portal. 

                        1. Select type of Fee you wish to deposit.

                        2. Enter amount of fee you intend to deposit as per the requirements. 

                        3. Now select mode of payment.

                        Please note that after selecting modes viz. Challan, Cheque, DD, eChallan you may have to give all the details mentioned and you will have upload copy of the receipt or challan generated. 

                        4. If you select mode of payment as Stamp in that case you need not have to enter details or upload any receipt.

                        5. Enter Bank name if required under the selected  mode of payment.

                        6. Enter Challan/Cheque/DD/eChallan Number and Date.

                        Click upload.

                        Online Court Fee Payment : 

                        1. Select type of the fee intended to be deposited in the Court. 

                        2. Enter the amount of the fee.

                        3. Select name of the party who is paying the Court Fee or other fee selected. 

                        3. Click Make Payment

                        4. you will be redirected to the payment gateway.

                        5. fill in desired details on the gateway and make payment

                        6. You will be redirected back to eFiling Portal.

                        7. You can get print acknowledgement of payment on the efiling portal. Apart from that you will receive SMS and mail notification.  

                        8. You can check payment of fees any time through report. Online payments made through efiling portal can be checked through report section at any time. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'efile/new_case/affirmation') { ?>
        <br>
        <center><b>AFFIRMATION </b></center><div class='details'>
            <pre >
                        Already sign method is chosen. This affirmation facility will behave as per sign method chosen by you earlier.

                        eSign Method (Affirmation): At the very beginning an option will be asked whether person (Litigant) filing case is ready to share is Aadhar for making eSign on the uploaded document. (It needs to be made clear that Aadhar number shared on efiling is not stored with efiling). After entering Aadhar number of the litigant, document will be sent to Aadhar Server where identity of the Aadhar holder will be verified by sending OTP on registered mobile number. After entering OTP you will be redirected to efiling with eSigned document.

                        In case client does not have Aadhar or is not inclined to share Aadhar, in that case affirmation form will be downloaded and physically signed by the litigant/s and thereafter the very form will be scanned and uploaded. The uploaded form will be esigned by the Advocate. 

                        eSign method (Verification by Advocate): After affirmation, the advocate has to identify that the litigant signing petition is his client and pleadings and documents uploaded on efiling are verified and confirmed by him. Again same process will be followed when advocate will enter his aadhar number for eSigning the document of verification. 

                        Digital Signature with token for Affirmation : If chosen sign method is digital signature token in that case efiling software automatically identifies whether a document is digitally signed. Here affirmation form of the litigant will be downloaded. The form will be pritned and physically signed by the litigant. The said document will be scanned and pdf will be signed by the Advocate. 

                        Digital Signature with token for Verification: After affirmation, the advocate has to identify that the litigant signing petition is his client and pleadings and documents uploaded on efiling are verified and confirmed by him. Again same process of signing through token will be followed when advocate will sign the document of verification after downloading the same. The digitally signed verification using token will be uploaded by the Advocate.

                        In case it happens that you select eSign using Aadhar as mode of signing document and uploaded certain documents and at the time of affirmation you felt that the mode of signing needs to be changed. In such situation you need to first go to uploading of documents and remove the uploaded documents first. Thereafter you need to go to eSign method and click on reset the method of signing. This aspect may be noted permanently in your mind. 

                        After completing digital signatures of affirmation by litigant and verification by advocate you will find next button visible. Click next to go to final tab of efiling a case
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'new_case/view') { ?>
        <br>
        <center><b>VIEW </b></center><div class='details'>
            <pre >
                        Final view and final submit: All the information entered can be seen here at glance. Please verify every information carefully before you click final submit. If found necessary, you can go back to respective tab to edit or  modify the information already filled in. All the tabs shown here are expandable. For expanding information of each tab, you need to click + sign located on extreme right side of your screen. To view all the tabs in expandable form you may see button named “Exapnd All”. 

                        Download: Sometimes you require pritable copy of the information filled in. Sometimes users feel to save the information filled in as a matter of record for future purposes. For all such requirements, you can click “Download PDF” button. You will get pdf copy of the information entered in the case desired to be efiled. 

                        You will find that on the top right side efiling number is shown in red colour. Adjacent to eFiling number you will find button to see the filing history. You can click on “eFiling History” at any time while accessing any tab. eFiling History will shown you the date and time wise entry of information in the given case. 

                        Back: You will find back button on the top right side of the screen. This back button will take  you one tab back for entering information. You can go to previous tab by entering previous tab as well as by clicking on the relevant tab. 

                        Trash: On top right corner of your screen you will find “Trash” button. This button to delete the information need to be used cautiously as it will delete the information entered for the respective case record. You will be cautioned through alert and then the information will be deleted. 

                        Submit: You will notice that adjacent to trash button there is one more button named “Submit”. This is final submit button. Once you successfully click submit, the concerned efile case data will be sent to efiling admin for verification and scrutiny. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/92') { ?>
        <br>
        <center><b>Draft </b></center><div class='details'>
            <pre >
                                        N/A
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/73') { ?>
        <br>
        <center><b>Pending Approval </b></center><div class='details'>
            <pre >
                                        N/A
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/48') { ?>
        <br>
        <center><b>For compliance : Waiting Re-submit</b></center><div class='details'>
            <pre >
                                        When any defect is flagged in any eFiled case, the file automatically goes on the stage of compliance. 

                                        It shows cases filed by different users pending for compliance by the concerned user.

                                        Second column shows efiling number, third column shows type of efiling such new case, misc. Documents, interim application, deficit court fees etc. 

                                        Fourth column shows names of the parties. 

                                        Fifith column shows date and time of raising objection.

                                        Admin can click on eFiling number and see the details of each case, if desired.

                                        After click of “action” button, admin can always see efiling histroy button on the top right side of the screen which can be used to know entire efiling history logs. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/9233') { ?>
        <br>
        <center><b>Deficit Fee </b></center><div class='details'>
            <pre > 
                                        When in any case deficit court fee objection is marked and user complies the objecction by making payment of deficit court fee all such cases after filing and registration will be shown here.

                                        Table shows as to when i.e date and time deficit court fee is paid by the user in each listed case. 

                                        This facility does not demand any action from the admin. The facility is given only for assitance so that all the efiled documents can be seen and searched at one place and report generation will be possible. 

                                        Admin user can always click on efilng number or CNR number, registration number to know details in the case. All such details are available on click of the filing or registration numbers. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/43') { ?>
        <br>
        <center><b>Pending Scrutiny </b></center><div class='details'>
            <pre >
                                        Admin may have impression that “For Compliance” and “Pending Scrutiny” are identical stages. However, “For Compliance” is stage when case has not reached CIS and objections are raised when case is on efiling portal. All these objections are raised by efiling admin. However, “Pending Scrutiny” stage indicates that case is pending of scrutiny at the office after data is consumed through CIS. This difference need to be kept in mind. 

                                        Pending scrutiny indicates that Office has not gone through the file and has not applied its mind to list objections. Thus the case still pending with the office for scrutiny.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/9236') { ?>
        <br>
        <center><b>Defective </b></center><div class='details'>
            <pre >
                                        When office marks objection during scrutiny after the case is consumed through CIS, such cases are shown as “defective”. 

                                        Objections to be listed while disapproving any case and objections to be listed during the stage of scrutiny are identical. Therefore, it is possible to carry out process of scrutiny at efiling. It is more easy for the advocates to remove objections and comply with the requirements using efiling facility. During initial scrutiny user can change all parameters however if the case is shown “Defective” the user can correct the defects in documents and can pay the deifict court fee. 

                                        User will be able to see the objections listed by the office and he can cure/remove those objections online by making necessary compliance. Once compliance is made the case is shown in “Defects cured” stage. 


            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/9273') { ?>
        <br>
        <center><b>e-Filed Cases</b></center><div class='details'>
            <pre >
                                        In this screen you will see the list of the cases which are successully filed through eFiling. 

                                        This will give birds eye view to know all the efiled cases together at one place with facility to know more about their case history and logs. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/9248') { ?>
        <br>
        <center><b>eFiled documents </b></center><div class='details'>
            <pre >
                                        When documents or misc. Interim applications are filed through efiling, all such misc. Documents and inerim application will be shown here. 

                                        In the case details one can see CNR number, filing number, registration number. 

                                        One can see all the details of the case once a case is selected. 

                                        All the list of the efiled documents can be searched on the basis of text or number. 

                                        This facility does not demand any action from the admin. The facility is given only for assitance so that all the efiled documents can be seen and searched at one place and report generation will be possible. 

            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/7332') { ?>
        <br>
        <center><b> deficit Court Fee </b></center><div class='details'>
            <pre >
                                        When in any case deficit court fee objection is marked and user complies the objecction by making payment of deficit court fee all such cases after filing and registration will be shown here.

                                        Table shows as to when i.e date and time deficit court fee is paid by the user in each listed case. 

                                        This facility does not demand any action from the admin. The facility is given only for assitance so that all the efiled documents can be seen and searched at one place and report generation will be possible. 

                                        Admin user can always click on efilng number or CNR number, registration number to know details in the case. All such details are available on click of the filing or registration numbers. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/7311') { ?>
        <br>
        <center><b>E-Filed IA </b></center><div class='details'>
            <pre >
                                        All Interim Applications filed so far will be listed here. 

                                        Table shown as to when each interim application is updated i.e. date and time. 

                                        This facility does not demand any action from the admin. The facility is given only for assitance so that all the efiled documents can be seen and searched at one place and report generation will be possible. 

                                        Admin user can always click on efilng number or CNR number, registration number to know details in the case. All such details are available on click of the filing or registration numbers. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/7336') { ?>
        <br>
        <center><b>Rejected eFiling No.(s) </b></center><div class='details'>
            <pre >
                                        Rejected cases are those where scrutiny section of the court after going through the case has rejected the case.

                                        Either user can comply the defect and resubmit the case.

                                        If the users chooses to comply with the requirements, with the same efiling number or CNR number, user will be able to refile this case.

                                        The list shown under Rejected cases given details of eFiling Number, filing number, CNR number and registratin number, if any. Each number can be clicked to know more details in the case including documents uploaded. 

                                        Each case shown in the list can be seen as to whether it was new case, or document or interim application. It be easily seen as to when i.e date and time when the case is rejected along with person or section which rejected the case. 

                                        The facility does not require any action from the admin user. It only provides searchable interface and in the event more details are necessary each number can be clicked. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'list/user_stages/view/7373') { ?>
        <br>
        <center><b>Trashed </b></center><div class='details'>
            <pre >
                                        N/A
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'bar_council/new_register/3455819078557041556925') { ?>
        <br>
        <center><b>New Request </b></center><div class='details'>
            <pre >
                                        There are three types of users in eFiling.   

                                        1. Advocate 
                                        2. Party in person 
                                        3. Department/Organization/Institution  

                                        This facility is used only party in person. When any party in person will register, 
                                        his requested can be viewed here. Admin needs to go through the personal details of the 
                                        user and documents submitted by him.   If he has completed eKYC, in that case it may not be necessary 
                                        for him undergo physical verification process.   However, if he has tendered documents for physical verification,
                                         
                                        admin needs to complete the process of verification as per guidelines issued by High Court or District Court, 
                                        as may be the case.   Once physical process is complete and approved the concerned competent authority of 
                                        the Court in that case such request needs to be approved.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'bar_council/new_register/8960607815625586') { ?>
        <br>
        <center><b>Activated Users </b></center><div class='details'>
            <pre >
                                        Activated users facility only gives list of the users. Admin may see the information of any user in the event he has reason to know more details about any user.   Admin can search all activated users. Data of users can be searched on the basis of name,place, any text or number or mail.
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'bar_council/new_register/7855505597255586') { ?>
        <br>
        <center><b>Rejected Registration Requests </b></center><div class='details'>
            <pre >
                                        This is list of rejected user.  Admin can see details of any rejected user. 
                                        List can be searched on multiple counts such as name, any text or number.  
                                        In the event any user is rejected, he would receive opportunity to cure the defects through the mail notifications.   

                                        Only when request is approved, such users will be able to file the cases otherwise access to such users will 
                                        be kept limited to Registration. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'bar_council/new_register/153429157286') { ?>
        <br>
        <center><b>Registration Requests under On Hold </b></center><div class='details'>
            <pre >
                                        N/A
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'bar_council/new_register/159850559725521534') { ?>
        <br>
        <center><b>Registration Requests under Objection </b></center><div class='details'>
            <pre >
                                        All the cases in which objections are notified will be shown here. It is possible to print this report. It is possible to click case number to view entire case hisotry. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'cases/mycases/calendar') { ?>
        <br>
        <center><b>My Court Schedule - Calendar</b></center><div class='details'>
            <pre >
                                        This a frequently required facility of calendar. Every day advocates require to know their case schedule in different courts. It becomes necessary to know cases listed before the Court on a given day or date. It not sufficient that you get only figure of cases listed on that day. You need to know as to how many cases are fixed for judgement, argument, evidence, steps, compliances, hearing etc. My Court Schedule gives this facility at your finger tips. It shows cases as per its stages. When you click on the date it shows all the details which you may wish to see.

                                        On Top left side of your screen you must have seen two “< >” buttons. These buttons can be used to scroll dates forward and backward. When you go ahead or behind current month, or day or week “Today” button automatically becomes active. When you wll click on today button it will restore your position to current date. 

                                        Three views of calendar are made available. One is monthwise view, another is week wise view and third is day wise view. You can change these views as per your requirement from top right corner of the screen. 

                                        If your mail address belongs to domain “.gmail.com” in that case a facility is given to sync your calendar with your mobile device. You will a button to sync your cases with your calendar. Click that button. It will ask you your google account verification. After verification is completed all the cases which are shown in your account can be seen on your mobile device as well. However, one time verification is necessary. (Please do not revoke permissions frequently. If you do so you may have undergo process of verification time and again.)
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } elseif ($help_page == $help_base_url . 'profile') { ?>
        <br>
        <center><b>profile</b></center><div class='details'>
            <pre >
                                        N/A
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div> 
    <?php } elseif ($help_page == $help_base_url . 'adminDashboard') { ?>
        <br>
        <center><b>Dashboard</b></center><div class='details'>
            <pre >
                        While filing new case, it is necessary to select the Court establishment where the cases is intended to be filed. You will find opition of <b>“My Courts”</b> on the screen while filing new case. In My Courts, list of Courts wherein cases are already filed by the user will be displayed. Instead of doing the entire process again, if the desired Court establishment is selected, it saves time of the user. 

                        When you are filing new case in Lower Courts, it is necessary to select State, District and Court establishment. It is mandatory to choose whether the case desired to be filed is Civil or Criminal type (if you forget to select civil or criminal then you will not see case types) . If the case desired to be filed is motor accident claim related case, please mark selection to show that it is MACP related case. If you have any kind of urgency you may select that the case is urgent or otherwise. 
                        There is an option named <b>“Matter Type”</b> wherein options to be selected are 

                        a) Original, 
                        b) Appeal, and, 
                        c) Application. 

                        Original means when case is filed for the first time in any Court(not being an appeal or revision). Appeal means any appeal revision or like proceedings wherein order of the Court below or any authority is impugned. Application means interim application which are filed in already existing original or appeal cases. 

                        Once you are done entering information under this head First Click SAVE and then you can click NEXT or PREVIOUS. If you forget to click save, the entered data will not saved with your case.
                                            
            </pre> 
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div> 
    <?php } elseif ($help_page == $help_base_url . 'newcase/caseDetails') { ?>
        <br>
        <center><b>New Case</b></center><div class='details'>
            <pre >
                           when any user files a new case or documents or Interim Application after final submission 
                        it reaches here for further action by Admin user.  From the dashboard if newfiling is clicked, it opens this screen.  
                        It shows cases filed by different users pending for action by the admin user.  

                        Second column shows efiling number,  third column shows type of efiling such new case, misc. Documents, interim application, deficit court fees etc.   
                        Fourth column shows case details such CNR Number, Filing Number, Registration Number and names of the parties.

                        Fifith column shows date and time of filing.  Fifth column given Action button. 
                        When you click action button you can view the submitted file and data. 
                        After examining the same admin can take necessary action provided there.  
                        When admin clicks action, information of the case will be shown to him.  

                        He has three actions to be taken a. Approve b. Disapprove c. Deficit court fee.   
                        When click approve, the case will be ready for transfer to CIS.  When you click disapprove a pop up window 
                        will open and you may have choose appropriate objections mentioned in the list shown on pop-up page.  
                        Along with objections you can mark deficit Court fees by writing the amount of deficit court fee.   

                        In case reason of objection or defect is not listed on the page, admin can choose free text given at the 
                        bottom of the page. On the free text portion admin can write his objection along with compliance 
                        expected from the user.  Once case is disapproved, it will start reflecting in “For Compliance” for user 
                        as well as for admin.   If the eFiled case has only deficit court fee in that case admin can select deficit court 
                        fee option on the top right corner of the screen.   

                        Here admin has to enter amount of deficit court fee and 
                        the user will be notified about the objection of deficit court fee. 
            </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div> 
    <?php } else { ?>
        <script>
            function linkopen(event) {
                event.preventDefault();
                window.close("<?php echo site_url('help/view/') . $get_valu; ?>", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=700");
            }
            $(window).on('load', function () {
                $('#myModal').modal('hide');
            });</script>
        <br>
        <center><b> eFiling Help ?</b></center><div class='details'> 
            <pre>Users Help for Manage eFiling System </pre>
            <hr style="background:#ccc;height:1px;border:0;"><div id="thumbnails">
                <ul class="clearfix"> 
                </ul>
            </div> 
        </div>
    <?php } ?>
    <script type='text/javascript'>
        $(function () {
            $('#thumbnails a').lightBox();
        });
    </script>
</body>
</html>