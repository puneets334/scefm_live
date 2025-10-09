<?php declare(strict_types=1); ?>
@if(count($documents))
    <div class="title-comnt-bref doc-byme-tble">
        <div class="table-height-div">
            <div class="table-responsive">
                <table class="table table-striped custom-table ia">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Diary/Case No.</th>
                            @if(isset($type) && !empty($type) && $type=='adjournment_requests')
                            <?php // echo "<pre>Velocis"; print_r($type); die(); ?>
                                <th>Listing Details</th>
                            @else
                                <th>Document</th>
                            @endif
                            <th>Filed By/On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $index=>$document)
                            @if(isset($type) && !empty($type) && $type=='adjournment_requests')
                                <tr>
                                    <td data-key="Sr. No."><?php echo ($index+1); ?></td>
                                    <td data-key="Diary/Case No.">
                                        <?php if(isset($document->diary_number) && !empty($document->diary_number)) { echo (substr($document->diary_number,0,-4)."/".substr($document->diary_number,-4)); } ?>
                                        <br/>
                                        <?php if(isset($document->case_number) && !empty($document->case_number)) { echo $document->case_number; } ?>
                                    </td>
                                    <td data-key="Listing Details" class="uk-table-link">
                                        <!--<a href="https://main.sci.gov.in/supremecourt/2018/27951/27951_2018_Order_05-Oct-2018.pdf" target="_blank">-->
                                        <!--<span class="uk-text-uppercase md-bg-grey-700 md-color-grey-50 uk-text-small" style="padding:0.2rem;">{{$document->typeName}}</span>-->
                                            <div>
                                                <?php if(isset($document->listed_date) && !empty($document->listed_date)) { echo date_format(date_create($document->listed_date), 'D, jS M'); } ?>
                                                <br/>
                                                <?php
                                                if(isset($document->item_number) && !empty($document->item_number)) { echo $document->item_number.'in'; }
                                                if(isset($document->court_number) && !empty($document->court_number)) { echo $document->court_number; }
                                                ?>
                                            </div>
                                        <!--</a>-->
                                    </td>
                                    <td data-key="Filed By/On">
                                        <?php if(isset($document->first_name) && !empty($document->first_name)) {
                                            echo $document->first_name;
                                        }
                                        ?>
                                        <br>
                                        <?php if(isset($document->created_at) && !empty($document->created_at)) {
                                            echo date('d-m-Y h:i:s A', strtotime($document->created_at));
                                        }
                                        ?>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <?php // echo 'hello'; print_r($index); echo '<br>'; pr($document); ?>
                                    <td data-key="Sr. No."><?php echo ($index+1); ?></td>
                                    <td data-key="Diary/Case No.">
                                        <?php if(isset($document->diaryId) && !empty($document->diaryId)) {
                                            echo (substr($document->diaryId,0,-4)."/".substr($document->diaryId,-4));
                                        }
                                        ?>
                                    </td>
                                    <td data-key="Document" class="uk-table-link">
                                        <!--<a href="https://main.sci.gov.in/supremecourt/2018/27951/27951_2018_Order_05-Oct-2018.pdf" target="_blank">-->
                                            <span class="uk-text-uppercase md-bg-grey-700 md-color-grey-50 uk-text-small mb-3"><?php if(isset($document->typeName) && !empty($document->typeName)) { echo $document->typeName; } ?></span>
                                            <div><?php if(isset($document->title) && !empty($document->title)) { echo $document->title; } ?></div>
                                        <!--</a>-->
                                    </td>
                                    <td data-key="Filed By/On">
                                        <?php if(isset($document->filedBy) && !empty($document->filedBy)) { echo $document->filedBy; }?><br><?php if(isset($document->filedAt) && !empty($document->filedAt)) { echo date('d-m-Y h:i:s A', strtotime($document->filedAt)); } ?>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif