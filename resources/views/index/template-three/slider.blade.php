<div id="storeSlider" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
    <ol class="carousel-indicators">
        @foreach($sliders as $slider)
        <li data-target="#storeSlider" data-slide-to="{{ $loop->index }}" class="{{ $loop->iteration == 1 ? 'active' : ''}}"></li>
        @endforeach
    </ol>
    @foreach($sliders as $slider)
        <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : ''}}">
            <div class="carousel-img">
                <img class="d-block w-100"  src="{{ asset('img/uploads/'.$store->subdomain.'/slider/' . $slider->slider->image) }}" alt="">
            </div>
            <div class="carousel-caption d-none d-md-block">
                @if((isset($slider->slider->main_tagline) && $slider->slider->main_tagline !== "") || (isset($slider->slider->sub_tagline) && $slider->slider->sub_tagline !== ""))
                <div class="carousel-text ">
                    @if($slider->slider->main_tagline !== "")
                        <h3 class="text-uppercase" style="font-size: {{ $slider->slider->main_tagline_font_size }}; color: ">{{ $slider->slider->main_tagline }}</h3>
                    @endif

                    @if($slider->slider->sub_tagline !== "")
                        <p style="font-size: {{ $slider->slider->sub_tagline_font_size }}; color: ">{{ $slider->slider->sub_tagline }}</p>
                    @endif

                    @if((isset($slider->slider->cta_button_one_text) && $slider->slider->cta_button_one_text !== "")  && (isset($slider->slider->cta_button_one_link) && $slider->slider->cta_button_one_link !== ""))
                        <a href="{!! $slider->slider->cta_button_one_link !!}" target="_blank" class="btn btn-primary">{{ $slider->slider->cta_button_one_text }}</a>
                    @endif

                    @if((isset($slider->slider->cta_button_two_text) && $slider->slider->cta_button_two_text !== "")  && (isset($slider->slider->cta_button_two_link) && $slider->slider->cta_button_two_link !== ""))
                        <a href="{!! $slider->slider->cta_button_two_link !!}" target="_blank" class="btn btn-primary">{{ $slider->slider->cta_button_two_text }}</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    @endforeach
    </div>

    <a class="carousel-control-prev" href="#storeSlider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#storeSlider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>