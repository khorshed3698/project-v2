@if(count($home_slider_image) > 0)
<section class="home-slider">
    <div id="homeBannerSlider" class="carousel carousel-fade slide">
        <div class="carousel-inner">
            @foreach($home_slider_image as $key => $row)
            <?php
                $active = ($key == 0) ? 'active' : '';
            ?>
            <div class="carousel-item {{ $active }}" style="background-image: url({{asset($row->slider_image)}}" onerror="this.src=`{{asset('/assets/images/photo_default.png')}}`">
                <div class="slider-caption container">
                    <div class="caption-content">
                        <span class="caption-border animation-delay-1 animated bounceInLeft" data-animation="animated bounceInLeft"></span>
                        <h2 class="animation-delay-2" data-animation="animated bounceInDown">{!! $row->description !!}</h2>
                        <!-- <a class="animation-delay-3 home-slider-btn" data-animation="animated fadeInUp" href="#">
                            View All Services
                        </a> -->
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <a class="carousel-control-prev" href="#homeBannerSlider" role="button" data-bs-slide="prev" aria-label="Previous">
            <span class="carousel-control-prev-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 43 40" fill="none">
                    <path d="M28.9724 8.95455C28.311 8.27112 27.4953 7.92941 26.5253 7.92941C25.5553 7.92941 24.7396 8.27112 24.0782 8.95455L13.0001 19.9996L24.0782 31.0447C24.7616 31.7281 25.5773 32.0698 26.5253 32.0698C27.4733 32.0698 28.289 31.7281 28.9724 31.0447C29.6558 30.3613 29.9976 29.5456 29.9976 28.5976C29.9976 27.6496 29.6558 26.8339 28.9724 26.1505L22.7554 19.9996L28.9724 13.8488C29.6558 13.1433 29.9976 12.3276 29.9976 11.4017C29.9976 10.4757 29.6558 9.66002 28.9724 8.95455Z" fill="#0F6849"/>
                </svg>
            </span>
        </a>
        <a class="carousel-control-next" href="#homeBannerSlider" role="button" data-bs-slide="next" aria-label="Next">
            <span class="carousel-control-next-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 43 40" fill="none">
                    <path d="M14.0271 31.0455C14.6885 31.7289 15.5042 32.0706 16.4742 32.0706C17.4442 32.0706 18.2599 31.7289 18.9213 31.0455L29.9995 20.0004L18.9213 8.95532C18.2379 8.27189 17.4222 7.93018 16.4742 7.93018C15.5262 7.93018 14.7105 8.27189 14.0271 8.95532C13.3437 9.63874 13.002 10.4544 13.002 11.4024C13.002 12.3504 13.3437 13.1661 14.0271 13.8495L20.2441 20.0004L14.0271 26.1512C13.3437 26.8567 13.002 27.6724 13.002 28.5983C13.002 29.5243 13.3437 30.34 14.0271 31.0455Z" fill="#0F6849"/>
                </svg>
            </span>
        </a>
    </div>
</section>
@endif