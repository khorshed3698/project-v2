@if(count($home_slider_image) > 0)
    <div id="myCarousel" class="carousel slide homeSlider" data-ride="carousel" data-interval="15000">
        <ol class="carousel-indicators">
            <?php for($j = 0; $j < count($home_slider_image); $j++){
            if($j == '0'){
            ?>
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <?php }else{  ?>
            <li data-target="#myCarousel" data-slide-to="<?php echo $j; ?>"></li>
            <?php } } ?>
        </ol>
        <div class="carousel-inner">
            <?php
            $i = 0;
            ?>
            @foreach($home_slider_image as $home_slider_image)
                @if($i == '0')
                    <div class="item active">
                        <a href="{{ !empty($home_slider_image->link) ? $home_slider_image->link : '#' }}" {{ !empty($home_slider_image->link) ? 'target="_blank"' : '' }}>
                            <img src="{{ $home_slider_image->slider_image }}" alt="{{ $home_slider_image->name }}"
                                 style="width:100%; height: 285px;" onerror="this.src=`{{asset('/assets/images/photo_default.png')}}`">
                        </a>
                    </div>
                @else
                    <div class="item">
                        <a href="{{ !empty($home_slider_image->link) ? $home_slider_image->link : '#' }}" {{ !empty($home_slider_image->link) ? 'target="_blank"' : '' }}>
                            <img src="{{ $home_slider_image->slider_image }}" alt="{{ $home_slider_image->name }}"
                                 style="width:100%; height: 285px;" onerror="this.src=`{{asset('/assets/images/photo_default.png')}}`">
                        </a>
                    </div>
                @endif
                <?php $i++; ?>
            @endforeach
        </div>

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
@else
    <div id="myCarousel" class="carousel slide homeSlider" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active">
                <img src="{{asset('/uploads/sliderImage/')}}/slider_not_found.jpg" alt="Los Angeles"
                     style="width:100%; height: 285px;">
            </div>
        </div>
    </div>
@endif


