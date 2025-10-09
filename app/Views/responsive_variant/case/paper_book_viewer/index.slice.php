@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('header') @endsection
@section('title', (@$case->registration_number_display ?: 'D. No. '.$case->diary_number.'/'.$case->diary_year) .' - Paper Book Viewer')
@section('heading', (@$case->registration_number_display ?: 'D. No. '.$case->diary_number.'/'.$case->diary_year))
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')
<style>
    html,body{
        overflow-y: hidden;;
    }
</style>
<script type="text/javascript" src="/pspdfkit/pspdfkit.js" asyn></script>

<div class="uk-grid-collapse" uk-grid>
    <div class="uk-width-1-3 uk-overflow-auto" style="height: calc(100vh - 70px);"><!--old-118px with header enabled-->
        @if(count($case_file_paper_books)>0)
            <ul class="uk-nav uk-nav-primary" style="margin-bottom: 5rem !important;">
                <li class="uk-nav-divider uk-margin-remove-vertical"></li>
                @foreach($case_file_paper_books as $index=>$case_file_paper_book)
                    <li>
                        <a href="#" ng-click="load_pspdfkit_viewer('{{$case_file_paper_book->pspdfkit_document_id}}', '{{$case_file_paper_book->pspdfkit_document_jwt}}')" style="padding: 10px 0;fontsize: 16px;" ng-class="cfpbPspdfkitDocumentId == '{{$case_file_paper_book->pspdfkit_document_id}}' ? 'uk-active uk-background-muted' : ''" ng-style="cfpbPspdfkitDocumentId == '{{$case_file_paper_book->pspdfkit_document_id}}' ? {} : {'font-size': '16px'}">
                            <span class="uk-label uk-label-outline" style="background: transparent;color: #333">{{($index+1)}}</span>
                            &nbsp;{{$case_file_paper_book->display_name}}
                        </a>
                    </li>
                    <li class="uk-nav-divider uk-margin-remove-vertical"></li>
                @endforeach
            </ul>
        @else
            <b class="uk-text-danger">This case is not yet digitized.</b>
        @endif
    </div>
    <div class="uk-width-2-3 uk-overflow-auto" style="height: calc(100vh - 70px)"><!--old-118px with header enabled-->
        <div id="pspdfkit-viewer" class="uk-width-1-1 uk-height-1-1" style=""></div>
    </div>
</div>

<script type="text/javascript">
    $('body').attr('ng-app','caseFilePaperBookViewerApp').attr('ng-controller','caseFilePaperBookViewerController');

    var mainApp = angular.module("caseFilePaperBookViewerApp", []);
    mainApp.directive('onFinishRender', function ($timeout) {
        return {
            restrict: 'A',
            link: function (scope, element, attr) {
                if (scope.$last === true) {
                    $timeout(function () {
                        scope.$emit(attr.onFinishRender);
                    });
                }
            }
        }
    });
    mainApp.directive('ngFocusModel',function($timeout){
        return {
            link:  function(scope, element, attrs){
                element.bind('click',function(){
                    $timeout(function () {
                        jQuery('[ng-model="'+attrs.ngFocusModel+'"]').focus();
                    });
                })
            }
        }
    });
    mainApp.directive('ngFocus',function($timeout){
        return {
            link:  function(scope, element, attrs){
                element.bind('click',function(){
                    $timeout(function () {
                        jQuery(attrs.setFocus).focus();
                    });
                })
            }
        }
    });
    mainApp.controller('caseFilePaperBookViewerController', function ($scope, $http, $filter, $interval, $compile) {
        $scope.cfpbPspdfkitDocumentId = null;

        $scope.load_pspdfkit_viewer = function(documentId, documentJwt){
            $scope.cfpbPspdfkitDocumentId = documentId;

            try{
                PSPDFKit.unload('#pspdfkit-viewer');
            } catch(e){}

            try {
                const initialViewState = new PSPDFKit.ViewState({
                    //zoom: PSPDFKit.ZoomMode.FIT_TO_WIDTH,
                    sidebarMode: PSPDFKit.SidebarMode.DOCUMENT_OUTLINE,
                    readOnly: true,
                    //scrollMode: PSPDFKit.ScrollMode.PER_SPREAD,
                    //layoutMode: PSPDFKit.LayoutMode.DOUBLE,
                    //keepFirstSpreadAsSinglePage: true
                });

                var initialToolbarItems = PSPDFKit.defaultToolbarItems;
                console.log(initialToolbarItems);
                initialToolbarItems = initialToolbarItems.filter(function (obj) {
                    return obj.type !== 'sidebar-annotations' && obj.type !== 'sidebar-bookmarks' && obj.type !== 'sidebar-document-outline' && obj.type !== 'sidebar-thumbnails';
                });
                initialToolbarItems.unshift({type: 'sidebar-thumbnails', dropdownGroup: null});
                initialToolbarItems.unshift({type: 'sidebar-document-outline', 'title': 'Paper Book Index', dropdownGroup: null});

                PSPDFKit.load({
                    container: "#pspdfkit-viewer",
                    documentId: documentId,
                    authPayload: {jwt: documentJwt},
                    instant: false,
                    initialViewState: initialViewState,
                    toolbarItems: initialToolbarItems,
                    /*disableForms: true,
                    disableHighQualityPrinting: true,
                    disableIndexedDBCaching: true,
                    editableAnnotationTypes: [],*/
                    //editableAnnotationTypes: [PSPDFKit.Annotations.InkAnnotation],
                    //headless: true,
                    //preventTextCopy: true,
                })
                .then(function (instance) {

                })
                .catch(function (error) {
                    console.error(error.message);
                });
            } catch (e) {
            }
        };
        @if(count($case_file_paper_books)>0)
            $scope.load_pspdfkit_viewer('{{$case_file_paper_books[0]->pspdfkit_document_id}}', '{{$case_file_paper_books[0]->pspdfkit_document_jwt}}');
        @endif
    });

    $(function () {
        ngScope = angular.element($('[ng-app="caseFilePaperBookViewerApp"]')).scope();
    });
</script>
@endsection