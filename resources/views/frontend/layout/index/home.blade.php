<!-- Home -->

<div class="home">

    <!-- Home Slider -->
    <div class="home_slider_container">
        <div class="owl-carousel owl-theme home_slider">

            @foreach($home_images as $home_image)
                <!-- Slide -->
                <div class="owl-item">
                    <div class="background_image img-fluid">
                        <img src="{{ asset($home_image->images) }}" style="height: 100%;">
                    </div>
                    <div class="home_container">
                        <div class="home_content_container" style="text-align: -webkit-center; left: 0">
                            <div class="home_content" style="max-width: 1247px">
                                <h1 style="font-size: 30px;color:black;word-break: break-word;text-transform: uppercase;">{{ $home_image->description }}</h1>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <!-- Home Slider Navigation -->
        <div class="home_slider_nav home_slider_prev trans_200"><div class=" d-flex flex-column align-items-center justify-content-center"><img src="{{ asset('aStar/images/prev.png') }}"></div></div>
        <div class="home_slider_nav home_slider_next trans_200"><div class=" d-flex flex-column align-items-center justify-content-center"><img src="{{ asset('aStar/images/next.png') }}"></div></div>

    </div>
</div>
