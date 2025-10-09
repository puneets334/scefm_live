<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="<?=base_url('assets/sci_logo.png')?>" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$page_title?></title>
    <meta Http-Equiv="Cache-Control" Content="no-cache">
    <meta Http-Equiv="Pragma" Content="no-cache">
    <meta Http-Equiv="Expires" Content="0">


    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">

</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper" ng-app="consentApp" ng-controller="consentCtrl">
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url() . 'index.php/Consent'; ?>"><b>Home</b></a></li>
                    <li class="active" style="color:#F08080;"><b>Cases for Consent</b></li>
                </ol>
                <div class="box-body no-padding">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <div class="col-xs-10"><h3 class="box-title" id="form-title">Your Cases for consent</h3></div>
                            <div class="col-xs-2"><div class="input-group"><input type="text" ng-model="projectList.search" class="form-control pull-right" id="projects_search" placeholder="Search"><span class="glyphicon glyphicon-search form-control-feedback"></span></div></div>
                        </div>
                    <table class="table table-striped">
                        <tbody><tr>
                            <th style="width: 20px">#</th>
                            <th>Diary No.</th>
                            <th>Registration Number</th>
                            <th>CauseTitle</th>
                            <th></th>
                        </tr>

                        <tr ng-repeat="x in cases  | filter:projectList.search">
                            <td>{{$index +1}}</td>
                            <td>{{x.diary_no}}</td>
                            <td>{{x.reg_no_display}}</td>
                            <td>{{x.pet_name}} vs {{x.res_name}}</td>
                            <td>
                                <label class="radio-inline"><input type="radio" name="row_{{x.id}}" ng-model="physical.row_{{x.id}}" value="P" required>Physical</label>
                                <label class="radio-inline"><input type="radio" name="row_{{x.id}}" ng-model="virtual.row_{{x.id}}" value="V" required>Virtual</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                {{physical}}{{virtual}}
                <button type="button" class="btn bg-olive btn-flat margin" ng-click="save_option()">Submit</button>
            </section>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="container">
        <strong>Copyright &copy;  <a href="#">SCI Computer Cell</a>.</strong> All rights
        reserved.
    </div>
    <!-- /.container -->
</footer>
</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular.min.js"></script>
<script>
    var app = angular.module('consentApp', []);
    app.filter("jsDate", function() {
        return function(x) {
            return new Date(x);
        };
    });
    app.controller('consentCtrl', function($scope, $http) {
        $scope.cases = <?=$cases?>;
        $scope.physical = null;
        $scope.virtual = null;
        
        $scope.clearForm = function(){
            $scope.fields ={};
            $scope.previous_applies();
        }

        function isEmpty(str) {
            return (!str || 0 === str.length);
        }

        $scope.save_option = function(){
            console.log($scope.physical);
            console.log($scope.virtual);
        }

    });
</script>
</body>
</html>
