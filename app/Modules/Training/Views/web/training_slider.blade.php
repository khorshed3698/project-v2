@if (count($training_slider_image) > 0)
    <div class="swiper panel  panel-info">
        <div class="panel-heading">
            <p style="margin: 10px 0; font-weight: bold; font-size: 14px;">Upcoming Training</p>
        </div>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <?php for($j = 0; $j < count($training_slider_image); $j++){
            if($j == '0'){
            ?>
                <li data-target="#myCarousel1" data-slide-to="0" class="active"></li>
                <?php }else{  ?>
                <li data-target="#myCarousel1" data-slide-to="<?php echo $j; ?>"></li>
                <?php } } ?>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <?php
                $i = 0;
                ?>
                @foreach ($training_slider_image as $training_slider)
                    @if ($i == '0')
                        <div class="item active">
                            <img src="{{ asset('/uploads/training/course/' . $training_slider->course_thumbnail_path) }}"
                                alt="{{ $training_slider->title }}"
                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                            <div class="carousel-caption">
                                <div class="details">
                                    <span
                                        class="slider_training_course_title">{{ $training_slider->course->course_title }}</span>
                                    <br>
                                    <span class=" text_reg_tag p_top_5"><strong>Registration Ongoing</strong></span>
                                    <br>
                                </div>
                                <div class="p_top_20 p_bottom_20">
                                    <a
                                        href="{{ url('bida/training-details/' . \App\Libraries\Encryption::encodeId($training_slider->id)) }}"><button
                                            class="m_top_10 btn home_slider_apply_btn">Details</button></a>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="item">
                            <img src="{{ asset('/uploads/training/course/' . $training_slider->course_thumbnail_path) }}"
                                alt="{{ $training_slider->title }}"
                                onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                            <div class="carousel-caption">
                                <div class="details">
                                    <span
                                        class=" slider_training_course_title">{{ $training_slider->course->course_title }}</span>
                                    <br>
                                    <span class=" text_reg_tag p_top_5"><strong>Registration Ongoing</strong></span>
                                    <br>
                                </div>
                                <div class="p_top_20 p_bottom_20">
                                    <a
                                        href="{{ url('bida/training-details/' . \App\Libraries\Encryption::encodeId($training_slider->id)) }}"><button
                                            class="m_top_10 btn home_slider_apply_btn">Details</button></a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <?php $i++; ?>
                @endforeach
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
@endif