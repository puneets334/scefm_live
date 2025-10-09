@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Utilities')
@section('heading', 'Utilities')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')
<script type="text/javascript" src="pspdfkit/pspdfkit.js" asyn></script>

<div class="uk-margin-small-top uk-border-rounded">
    <div class="uk-width">
        <!--<div class="uk-button-group">
            <a href="#" class="uk-button uk-background-secondary text-white">Document Editor</a>
        </div>-->
        <div class="uk-flex-between" uk-grid>
            <span class="uk-h3 uk-text-muted uk-text-large uk-text-bold">My Raw Files</span>
            <div uk-form-custom>
                <form action="utilities/pspdfkit-playground" method="POST" enctype="multipart/form-data">
                    <input type="file" name="pspdfkit" onchange="this.form.submit();">
                    <button class="uk-button uk-button-secondary uk-button-small" type="button" name="sdsdsd" tabindex="-1">Upload New File</button>
                </form>
            </div>
        </div>
        <table class="uk-table uk-table-divider uk-table-border uk-table-hover uk-hidden">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>Uploaded On</th>
            </thead>
            <tbody>
                <tr style="cursor: pointer;" onclick="javascript:loadPsPdfKit()">
                    <td>1.</td>
                    <td>Test PDF</td>
                    <td>06th Nov 2020 at 10:20</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="uk-grid-small uk-margin-small-top" data-uk-grid data-uk-height-viewport="offset-top:true">
    <div class="uk-width-expand">
        <div id="content-viewer-pspdfkit" class="uk-width-1-1 uk-height-1-1" style="margin-righ:5rem !important;borde: dotted;"></div>
        <!--7KVNE6PBJQB2TN6301B6DB2PMP-->
    </div>
</div>

<script type="text/javascript">
    <?php
        //$pspdfkit_document_id = '7KVNE6PBJQB2TN6301B6DB2PMP';
        $pspdfkit_document_id = 'scefm-pspdfkit-playground-document-for-user-id-'.$_SESSION['login']['id'];
        $pspdfkit_document_jwt = get_pspdfkit_jwt($pspdfkit_document_id);
    ?>
    var pspdfkitInstance = null;
    function loadPsPdfKit(){
        var documentId = '{{$pspdfkit_document_id}}';
        var documentJwt='{{$pspdfkit_document_jwt}}';
        try {
            //PSPDFKit.Options.ENABLE_INK_SMOOTH_LINES = false;
            PSPDFKit.Options.DISABLE_KEYBOARD_SHORTCUTS = false;
            PSPDFKit.Options.INK_EPSILON_RANGE_OPTIMIZATION = 1;
            //PSPDFKit.Options.MIN_INK_ANNOTATION_SIZE=1;

            const initialViewState = new PSPDFKit.ViewState({
                //zoom: PSPDFKit.ZoomMode.FIT_TO_WIDTH,
                //sidebarMode: PSPDFKit.SidebarMode.DOCUMENT_OUTLINE,
                readOnly: true,
                //scrollMode: PSPDFKit.ScrollMode.PER_SPREAD,
                //layoutMode: PSPDFKit.LayoutMode.DOUBLE,
                //keepFirstSpreadAsSinglePage: true
            });
            /*var initialToolbarItems = PSPDFKit.defaultToolbarItems;
            console.log(initialToolbarItems);
            initialToolbarItems = initialToolbarItems.filter(function (obj) {
                return obj.type !== 'sidebar-document-outline' && obj.type !== 'document-editor' && obj.type !== 'print' && obj.type !== 'ink-signature';
            });
            initialToolbarItems.unshift({type: 'sidebar-document-outline', 'title': 'Paper Book Index'});
            initialToolbarItems.push({ type: 'layout-config'});*/
            let initialToolbarItems = [
                { type: "sidebar-annotations", dropdownGrou: null},
                { type: "sidebar-document-outline", 'title': 'Paper Book Index', dropdownGrou: null },
                { type: "sidebar-thumbnails"},
                { type: "sidebar-bookmarks"},
                { type: "pager" },
                { type: "pan" },
                { type: "zoom-out" },
                { type: "zoom-in" },
                { type: "layout-config" },
                { type: "spacer" },
                /*{ type: "ink", dropdownGroup: null },
                { type: "highlighter" },
                { type: "text-highlighter" },
                { type: "ink-eraser", dropdownGroup: null },
                { type: "spacer" },*/
                /*{ type: "image" },
                { type: "note" },*/
                { type: "document-editor" },
                { type: "text" },
                { type: "line" },
                { type: "arrow" },
                { type: "rectangle" },
                { type: "ellipse" },
                { type: "search" },
                {
                    type: "custom",
                    id: "download-pdf-btn",
                    title: 'Download Edited PDF',
                    icon: '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" focusable="false"><path d="M3.5 17.5V19C3.5 19.8284 4.17157 20.5 5 20.5H20C20.8284 20.5 21.5 19.8284 21.5 19V17.5" stroke="currentColor" stroke-linecap="round"></path><path opacity="0.75" d="M12.5 16L12.1464 16.3536L12.5 16.7071L12.8536 16.3536L12.5 16ZM13 4C13 3.72386 12.7761 3.5 12.5 3.5C12.2239 3.5 12 3.72386 12 4H13ZM9.35355 12.1464C9.15829 11.9512 8.84171 11.9512 8.64645 12.1464C8.45118 12.3417 8.45118 12.6583 8.64645 12.8536L9.35355 12.1464ZM16.3536 12.8536C16.5488 12.6583 16.5488 12.3417 16.3536 12.1464C16.1583 11.9512 15.8417 11.9512 15.6464 12.1464L16.3536 12.8536ZM13 16V4H12V16H13ZM8.64645 12.8536L12.1464 16.3536L12.8536 15.6464L9.35355 12.1464L8.64645 12.8536ZM12.8536 16.3536L16.3536 12.8536L15.6464 12.1464L12.1464 15.6464L12.8536 16.3536Z" fill="currentColor"></path></svg>',
                    onPress: function (event) {
                        parent.initDownloadPdf();
                    }
                }
            ];

            //console.log(PSPDFKit.defaultAnnotationPresets);
            const annotationPresets = PSPDFKit.defaultAnnotationPresets;
            annotationPresets.ink.lineWidth = 1;

            PSPDFKit.load({
                container: "#content-viewer-pspdfkit",
                documentId: documentId,
                authPayload: {jwt: documentJwt},
                instant: false,
                autoSaveMode: PSPDFKit.AutoSaveMode.IMMEDIATE,
                initialViewState: initialViewState,
                toolbarItems: initialToolbarItems,
                annotationPresets: annotationPresets
                /*disableForms: true,
                disableHighQualityPrinting: true,
                disableIndexedDBCaching: true,
                editableAnnotationTypes: [],*/
                //editableAnnotationTypes: [PSPDFKit.Annotations.InkAnnotation],
                //headless: true,
                //preventTextCopy: true,
            })
                .then(function (instance) {
                    console.log("PSPDFKit loaded", instance);
                    pspdfkitInstance = instance;
                })
                .catch(function (error) {
                    console.error(error.message);
                });
        } catch (e) {
        }
    }
    async function initDownloadPdf(){
        const buffer = await pspdfkitInstance.exportPDF();
        const supportsDownloadAttribute = HTMLAnchorElement.prototype.hasOwnProperty("download");
        const blob = new Blob([buffer], { type: "application/pdf" });
        if (navigator.msSaveOrOpenBlob) {
            navigator.msSaveOrOpenBlob(blob, "download.pdf");
        } else if (!supportsDownloadAttribute) {
            const reader = new FileReader();
            reader.onloadend = function() {
                const dataUrl = reader.result;
                downloadPdf(dataUrl);
            };
            reader.readAsDataURL(blob);
        } else {
            const objectUrl = window.URL.createObjectURL(blob);
            downloadPdf(objectUrl);
            window.URL.revokeObjectURL(objectUrl);
        }
    }
    function downloadPdf(blob) {
        const a = document.createElement("a");
        a.href = blob;
        a.style.display = "none";
        a.download = "SC-eFM_Edited_Document.pdf";
        a.setAttribute("download", "SC-eFM_Edited_Document.pdf");
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    $(function(){
        <?php
            try{
                $document_size = @json_decode(@curl_get_contents(PSPDFKIT_SERVER_URI.'/api/documents/'.'scefm-pspdfkit-playground-document-for-user-id-'.$_SESSION['login']['id'].'/properties', ['Authorization: Token token="secret"']))->data->byteSize;
                if ($document_size > 0) {
                    echo 'loadPsPdfKit();';
                }
            } catch(Exception $e){}
        ?>
    });
</script>

@endsection