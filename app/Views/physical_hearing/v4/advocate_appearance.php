<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$page_title?></title>
    <meta Http-Equiv="Cache-Control" Content="no-cache">
    <meta Http-Equiv="Pragma" Content="no-cache">
    <meta Http-Equiv="Expires" Content="0">


    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= base_url()?>assets/jsAlert/dist/sweetalert.css">

</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper"  ng-app="copyApp" ng-controller="copyCtrl" data-ng-init="clearForm()">
    <?php //include('template/top_navigation.html');?>
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url() . 'index.php/Appearance'; ?>"><b>Home</b></a></li>
                    <li class="active" style="color:#F08080;"><b>Party/Advocate</b></li>
                </ol>
                <div class="box box-solid">
                    <div class="box-body">

                        <p class="lead text-center"><strong>Main Case details:</strong>
                            {{case_info['main_case_reg_no']}}<br>{{case_info['cause_title']}}</p>
                        <p class="lead text-center" style="text-align: center">With</p>
                        <span class="lead text-center">{{case_info['case_no']}}
                            <!--({{case_info['diary_no'].slice(0, -4)}}/{{case_info['diary_no'].substring(case_info['diary_no'].length - 4)}})-->
                        </span>

                        <!-- {{aor_count['advocate_count']}}-->
                        <!-- <p class="text-green">Court No: {{case_info['court_number']}}</p>
                         <p class="text-red">Listing Date: {{case_info['listing_date'] | jsDate | date: 'dd-MM-yyyy'}}</p>
                         <p class="text-yellow">Item No: {{case_info['item_number']}}</p>-->
                        <!-- <div class="col-sm-12" style="font-size: medium" >
                             <div class="col-sm-4">
                                 <label  class="text-primary">
                                     Court No :  <span class="text-black" style="margin: 10px;" ng-bind="case_info['court_number']>30?(case_info['court_number']-30):case_info['court_number']">
                                     </span>
                                 </label>
                             </div>
                             <div class="col-sm-4">
                                 <label  class="text-info">
                                     Listing Date :  <span class="text-black">{{case_info['listing_date'] | jsDate | date: 'dd-MM-yyyy'}}</span>
                                 </label>
                             </div>
                             <div class="col-sm-4">
                                 <label class="text-primary">
                                     Item No : <span class="text-black">{{case_info['item_number']}}</span>
                                 </label>

                             </div>
                         </div>-->

                        <div class="col-sm-12" style="font-size: medium;background-color: #e0e0e0;margin-top: 10px;" >

                            <div class="col-sm-4">
                                <label  class="text-primary">
                                    Total AOR(s) in this case:  <span class="text-black" style="margin: 10px;">{{aor_count_info[0]['advocate_count']}}</span>
                                </label>
                            </div>
                            <div class="col-sm-4">
                                <label  class="text-info">
                                    <!--Working Capacity :  <span class="text-black">{{court_info[0]['seating_capacity']}}</span>-->
                                </label>
                            </div>
                            <div class="col-sm-4">

                                <label class="text-primary">
                                    Limit per AOR (Including Self) : <span class="text-black">
                                        <?php
                                        echo $x=($_SESSION['eachAORCapacity']==MAX_LIMIT_FOR_COURT_DIRECTED_PHYSICAL_CASE)?MAX_NOMINATION_LIMIT_PER_AOR:$_SESSION['eachAORCapacity'];
                                        ?>
                                   </span>
                                </label>
                            </div>
                        </div>


                    </div>
                </div>

                <form role="form" id="employee-form">
                    <input type="hidden" class="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="col-md-12">
                        <div class="well">

                            <div class="row"><span class="col-xs-12" style="color:red;">To get special hearing passes, you can add/remove attendee upto 8 AM on the day of hearing.</span></div> <br/>
                            <div class="row">
                                <div class="col-xs-4"><div class="input-group input-group-md"><span class="input-group-addon">Attendee Type</span><select class="form-control" id="ref_attendee_type_id" ng-model="fields.ref_attendee_type_id" ng-options="attendees_type.id as attendees_type.description for attendees_type in attendees_type_list track by attendees_type.id" required><option value="">Select Attendee Type</option></select></div>
                                </div>
                                <div class="col-xs-8"><div class="input-group input-group-md"><span class="input-group-addon">Name</span><input type="text" class="form-control" id="name" ng-model="fields.name" placeholder="Name" required></div></div>
                            </div><br/>

                            <div class="row">
                                <div class="col-xs-4"><div class="input-group input-group-md"><span class="input-group-addon">Email ID</span><input type="email" class="form-control" id="email" ng-model="fields.email" placeholder="Email ID" required></div></div>
                                <div class="col-xs-4"><div class="input-group input-group-md"><span class="input-group-addon">Mobile</span><input type="text" class="form-control" id="mobile" ng-model="fields.mobile" placeholder="Mobile" pattern="^((?!(0))[0-9]{10})$" maxlength="10" required></div></div>
                                <div class="col-xs-4"><div class="input-group input-group-md"><span class="input-group-addon">Confirm Mobile</span><input type="password" onpaste="return false;" class="form-control" id="mobile_confirm" ng-model="fields.mobile_confirm" placeholder="Mobile" pattern="^((?!(0))[0-9]{10})$" maxlength="10" required></div></div>
                            </div>
                            <br/>

                            <div class="row">

                                <div class="col-xs-offset-1 col-xs-6 col-xs-offset-3" ng-if="aor_count_info[0]['advocate_count'] <= court_info[0]['seating_capacity']" >
                                    <button type="submit" id="btn-shift-assign" class="btn bg-olive btn-flat pull-right" ng-click="addAttendee()"  ng-if="is_update_allow == 1"><i class="fa fa-save"></i> Add Attendees </button>

                                </div>
                                <br>
                                <div>
                                    <label  class=" col-xs-12 alert alert-danger" ng-if="aor_count_info[0]['advocate_count'] > court_info[0]['seating_capacity'] && is_update_allow == 1">
                                        Total Number of AOR Counsel is exceeded the limited capacity.Please Contact to Registrar Judicial for more information</label>

                                    <label  class=" col-xs-12 alert alert-info" ng-if="is_update_allow > 1">You cannot add/modify Counsels Nomination after 8 am of hearing date.</label>
                                </div>


                            </div>

                        </div>

                    </div>
                </form>


                <div class="col-md-12" ng-if="previous_applies_list.length > 0">
                    <div class="well">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Attendees</h3>
                                <span style="float:right;padding: 5px;font-weight: bold" class="alertd alert-warning" role="alert" ng-if="is_update_allow == 1">
                      * You can replace yourself with counsel by clicking on <span  class="glyphicon glyphicon-trash" style="color: red; "></span> button
                    </span>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody>
                                    <tr>

                                        <th style="width: 10px">#</th>
                                        <th>Attendee Type</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th></th>
                                    </tr>
                                    <tr ng-repeat="x in previous_applies_list" >

                                        <td class={{setRowBGClass(x.ref_attendee_type_id)}}>{{$index +1}}</td>
                                        <td class={{setRowBGClass(x.ref_attendee_type_id)}}>{{x.attendee_type}}</td>
                                        <td class={{setRowBGClass(x.ref_attendee_type_id)}}>{{x.name}}</td>
                                        <!--<td>{{ x.received_on | jsDate | date: 'dd-MM-yyyy hh:mm' }}</td>-->
                                        <td class={{setRowBGClass(x.ref_attendee_type_id)}}>{{x.email_id}}</td>
                                        <td class={{setRowBGClass(x.ref_attendee_type_id)}}>{{x.mobile}} </td>


                                        <td style='cursor:pointer;' class={{setRowBGClass(x.ref_attendee_type_id)}}  ng-if="is_update_allow == 1">

                                            <span ng-click='deleteAttendee(x.id)' ng-if="x.display=='Y'" class="glyphicon glyphicon-trash" style="color: red;"></span>
                                            <span ng-click='restoreAttendee(x.id)' ng-if="x.display=='N'"  style="color: red;">Restore</span>
                                            <!--<span ng-if="x.display!='Y'" style="color: red;">Deleted</span>-->
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-12 well" ng-if="(previous_applies_list.length==0 || Object.keys(previous_applies_list).length==0)">
                    <input type="checkbox" ng-model="copy_attendees" ng-change="copyAttendees()"> <strong>Continue Hearing with previous attendees</strong>
                </div>

            </section>
            <!-- /.content -->
        </div>
        <!-- /.container -->
    </div>
    <!-- /.content-wrapper -->
    <!--<footer class="main-footer">
        <div class="container">
            <strong>Copyright &copy; 2016-2017 <a href="#">SCI Computer Cell</a>.</strong> All rights
            reserved.
        </div>
    </footer>-->
</div>
<!-- ./wrapper -->
<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular-cookies.js"></script>
<script src="<?= base_url() ?>assets/js/angular-route.js"></script>
<script src="<?= base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
<script>


    var app = angular.module('copyApp', ['ngCookies', 'ngRoute']);
    app.factory('httpInterceptor', ['$q', '$location', '$cookies',
        function($q, $location, $cookies) {
            return {
                request: function(config) {
                    //include Token in Paylod
                    config.data = config.data || {};
                    //payload field name is: csrf_test_name
                    config.data['csrf_test_name'] = $cookies.get('csrf_cookie_name');

                    //also include token in Http header (recommended)
                    config.headers['X-Csrf-Token'] = $cookies.get('csrf_cookie_name');
                    return config;
                },
                responseError: function(response) {
                    //codes...
                    console.log(response);
                    return $q.reject(response);
                }

            }


        }
    ]);
    app.config(['$httpProvider', '$routeProvider',
        function($httpProvider, $routeProvider) {
            //inject Interceptor
            $httpProvider.interceptors.push('httpInterceptor');
        }
    ]);
    app.filter("jsDate", function() {
        return function(x) {
            return new Date(x);
        };
    });
    app.controller('copyCtrl', function($scope, $http) {


        $scope.case_info = <?=json_encode(json_decode(base64_decode($case_info), true))?>;
        $scope.attendees_type_list = <?=$attendee_types?>;
        $scope.aor_count_info = <?=$aor_count?>;
        $scope.court_info=<?=$court_capacity?>;
        $scope.previous_applies_list=[];
        $scope.is_update_allow=<?=$aud_nomination_status?>;


        $scope.clearForm = function(){
            $scope.fields ={};
            $scope.previous_applies();
        }

        $scope.setRowBGClass = function(input) {
            if(input==9)
                return 'bg-success';
            else if(input==5)
                return 'bg-info';
            else
                return 'bg-light';
        };

        $scope.setCourtNo = function(input) {
            if(input>=30)
                return ;
            else if(input==5)
                return 'bg-info';
            else
                return 'bg-light';
        };


        function isEmpty(str) {
            return (!str || 0 === str.length);
        }
        $scope.previous_applies = function(){
            $scope.previous_applies_list = [];
            $http.get('<?=base_url();?>index.php/Attendee/attendee_list/<?=$case_info?>').then(function successCallback(response) {
                var data = response.data;
                if(data.length==0){
                    //sweetAlert("", "Please try adding application by Diary Number", "error");
                }
                else{
                    console.log(data);
                    if(data=='2'){
                        swal("Error!", "You cannot add/modify Counsels Nomination after 8 am of hearing date.", "error");

                    }
                    //alert(response.data);
                    $scope.previous_applies_list = response.data['case_info'];
                }
            });
        }



        $scope.addAttendee = function(){
            $scope.fields.case_info = $scope.case_info;
            if($scope.fields['mobile']!=$scope.fields['mobile_confirm']){
                alert('Confirm mobile number is different');
            }
            if(!isEmpty($scope.fields['ref_attendee_type_id']) && !isEmpty($scope.fields['name'])  && !isEmpty($scope.fields['email']) && !isEmpty($scope.fields['mobile']) &&   $scope.fields['mobile']==$scope.fields['mobile_confirm']  ){
                console.log($scope.fields);
                $http.post('<?=base_url();?>index.php/Attendee/save', {
                        data    : $scope.fields
                    }
                ).then(function successCallback(response) {
                    console.log(response);
                    var data = response.data;

                    switch (data){
                        case '1':{swal("Error!", "You cannot add more than 1 Advocate Clerk.", "error"); break;}
                        case '2':{swal("Error!", "You cannot exceed the limit per AOR(including self) as determined in the matter", "error"); break;}
                        case '3':{swal("Error!", "Error. while saving data", "error");
                            break;}
                        case '4':{swal("Success!", "Data Save Successfully", "success");
                            break;}
                        case '5':{swal("Error!", "You cannot add/modify Counsels Nomination after 8 am of hearing date.", "error");
                            break;}
                        case '6':{swal("Error!", "Nomination cannot be done for past listing date", "error");
                            break;}
                        case '7':{swal("Error!", "Counsel with the same mobile no is already Nominated", "error");
                            break;}
                        case '8':{swal("Error!", "Visit could not be nominated, please try again later.", "error");
                            break;}
                        case '9':{swal("Error!", "Self Nomination already done", "error");
                            break;}
                        case '10':{swal("Error!", "You cannot Nominated Advocate Clerk to presence in the court ", "error");
                            break;}
                        case '99':{swal("Error!", "Confirm mobile number is different", "error");
                            break;}
                    }



                    $scope.clearForm();
                    $scope.previous_applies();
                    //alert(response.data);
                }, function errorCallback(response) {
                    // called asynchronously if an error occurs
                    // or server returns response with an error status.
                });
            }
        }




        $scope.deleteAttendee = function(id){
            $http.get('<?=base_url();?>index.php/Attendee/delete_attendee/'+id+'/<?=$case_info?>').then(function successCallback(response) {
                var data = response.data;
                if(data.length==0){
                    sweetAlert("error", "Please try again to delete attendee detail", "error");
                }
                else{
                    $scope.previous_applies();
                }
            });
        }
        $scope.restoreAttendee = function(id){
            $http.get('<?=base_url();?>index.php/Attendee/restore_attendee/'+id+'/<?=$case_info?>').then(function successCallback(response) {
                var data = response.data;
                if(data==0){
                    swal("Error!", "You cannot exceed the limit per AOR(including self) as determined in the matter.Please remove someone other counsel to restore yourself", "error");
                }
                else{
                    $scope.previous_applies();
                }
            });
        }

        $scope.copyAttendees = function () {
            $http.get('<?=base_url();?>index.php/Attendee/copy_attendee/<?=$case_info?>').then(function successCallback(response) {
                location.reload();
            });
        }



    });
</script>
</body>
</html>
