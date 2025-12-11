<div class="panel panel-default">
    <div class="panel-body">
        <div id="docTabs" style="margin:10px;">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#tabs-auth" data-toggle="tab">File/Document</a>
                </li>
            </ul><!-- Tab panes -->

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tabs-auth">
                    @if(isset($upload_doc))
                        <?php
                        $fileUrl = public_path() . '/users/upload/' . $upload_doc;
                        if (file_exists($fileUrl)) {
                        ?>
                        <object style="display: block; margin: 0 auto;" width="1000" height="1260" type="application/pdf"
                                data="/users/upload/<?php echo $upload_doc; ?>">
                        </object>
                        <?php } else { ?>
                        <div class="">No such file is existed!</div>
                        <?php } ?> {{-- checking file is existed --}}
                    @elseif(isset($notice_doc))
                        <?php
                        $fileUrl = public_path() . '/uploads/' . $notice_doc;
                        if (file_exists($fileUrl)) {
                        ?>
                        <object style="display: block; margin: 0 auto;width:100%;"  height="1260" type="application/pdf"
                                data="/uploads/<?php echo $notice_doc; ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH">
                        </object>
                        <?php } else { ?>
                        <div class="">No such file is existed!</div>
                        <?php } ?> {{-- checking notice file is existed --}}
                    @else()
                        <div class="">No file found!</div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
