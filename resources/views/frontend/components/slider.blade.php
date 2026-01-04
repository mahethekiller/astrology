@php
    // Get sliders for the specified group
    $sliders = \App\Models\Slider::active()->group($group)->ordered()->get();
@endphp

@if($sliders->count() > 0)
<div id="{{ $sliderId }}" class="carousel slide homeSliderStyle" data-bs-ride="carousel">
    @if($sliders->count() > 1)
    <div class="carousel-indicators">
        @foreach($sliders as $index => $slider)
        <button type="button" data-bs-target="#{{ $sliderId }}" data-bs-slide-to="{{ $index }}"
                class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}"></button>
        @endforeach
    </div>
    @endif

    <div class="carousel-inner">
        @foreach($sliders as $index => $slider)
        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <img src="{{ Storage::url($slider->image) }}" class="d-block w-100" alt="{{ $slider->title }}">
            @if($slider->title || $slider->description || $slider->button_text)
            <div class="carousel-caption d-none d-md-block">
                @if($slider->title)
                <h5>{{ $slider->title }}</h5>
                @endif
                @if($slider->description)
                <p>{{ $slider->description }}</p>
                @endif
                @if($slider->button_text && $slider->button_link)
                <a href="{{ $slider->button_link }}" class="btn btn-primary">{{ $slider->button_text }}</a>
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>

    @if($sliders->count() > 1)
    <button class="carousel-control-prev" type="button" data-bs-target="#{{ $sliderId }}" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#{{ $sliderId }}" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
    @endif
</div>
@else
<!-- Fallback if no sliders found -->
<div id="{{ $sliderId }}" class="carousel slide homeSliderStyle">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('frontend/images/banner.jpg') }}" class="d-block w-100" alt="Default Banner">
        </div>
    </div>
</div>
@endif
