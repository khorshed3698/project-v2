@if(count($filestatus) > 0)
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="docTabs" style="margin:10px;">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <?php $i = 1; ?>
                    @foreach($filestatus as $row)
                        <li role="presentation" class="<?php if ($i == 1) {
                            echo 'active';
                        } ?>">
                            <a href="#tabs{{$i}}" data-toggle="tab">{{$row->form_name}}</a>
                        </li>
                        <?php $i++; ?>
                    @endforeach
                </ul>

                <!-- Tab panes -->
                <div class="tab-content" style="width: 100%;">
                    <?php $i = 1; ?>
                    @foreach($filestatus as $row)
                        <div role="tabpanel" class="tab-pane <?php if ($i == 1) {
                            echo 'active';
                        }?>" id="tabs{{$i}}">
                            @if(!empty($row->file))
{{--                                <h4 style="text-align: left;">{{$row->form_name}}</h4>--}}
                                <?php
                                $fileUrl = public_path() . '/uploads/' . $row->file;

                                if(file_exists($fileUrl)) {
                                ?>
                                <object style="display: block; margin: 0 auto;" width="100%" height="1260"
                                        type="application/pdf"
                                        data="/uploads/<?php echo  $row->file; ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
                                <?php } else { ?>
                                <div class="">No such file is existed!</div>
                                <?php } ?>

                            @else
                                <div class="">No file found!</div>
                            @endif
                        </div>
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif