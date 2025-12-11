
<div class="col-md-12">
    @if(!empty($serviceDetails->terms_and_conditions))
        <?php
        $support_type = array('xls','xlsx', 'ppt','pptx','docx','doc');
        $http =URL::to('/').'/'.$serviceDetails->terms_and_conditions;
        ?>
        @if (in_array(pathinfo($serviceDetails->terms_and_conditions, PATHINFO_EXTENSION), $support_type))
            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{$http}}" frameborder="0" style="width:100%;min-height:640px;" title="Service info"></iframe>
        @elseif(pathinfo($serviceDetails->terms_and_conditions, PATHINFO_EXTENSION) == 'pdf')
            <?php
            $fileUrl = public_path() . '/' . $serviceDetails->terms_and_conditions;
            if(file_exists($fileUrl)) {
            ?>
            <object style="display: block; min-height: 900px; margin: 0 auto;" width="100%"
                    type="application/pdf"
                    data="/<?php echo $serviceDetails->terms_and_conditions ?>#toolbar=1&amp;navpanes=0&amp;scrollbar=1&amp;page=1&amp;view=FitH"></object>
            <?php } else { ?>
            <div class=""></div>
            {{--<div class="">No such file is existed!</div>--}}
            <?php } ?> {{-- checking file is existed --}}

        @else
            <div class="">No file found!</div>
        @endif
    @endif
</div>