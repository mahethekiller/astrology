@extends('frontend.layouts.app')

@section('title', 'Astrologer - Home')

@section('content')
<!--header end-->

{{-- <div id="homeSlider" class="carousel slide homeSliderStyle">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#homeSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="{{ asset('frontend/images/banner.jpg') }}" class="d-block w-100" >
    </div>
    <div class="carousel-item">
        <img src="{{ asset('frontend/images/banner.jpg') }}" class="d-block w-100" >
    </div>
    <div class="carousel-item">
       <img src="{{ asset('frontend/images/banner.jpg') }}" class="d-block w-100" >
    </div>
  </div>

</div> --}}


<!-- Dynamic Slider -->
@include('frontend.components.slider', [
    'group' => 'home',
    'sliderId' => 'homeSlider'
])


<div class="section3 pt40">
  <div class="container ">
    <div class="row g-5">
      <div class="col-sm-6 col-lg-3">
        <div class="sec3Box">
          <a href="#">
          <img src="{{ asset('frontend/images/chat.png') }}" /><span class="sec3text">Chat with Astrologer</span><span class="readmorebtn"><img src="{{ asset('frontend/images/readmorearrow.png') }}" /></span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="sec3Box">
          <a href="#">
          <img src="{{ asset('frontend/images/talk.png') }}" /><span class="sec3text">Talk to Astrologer</span><span class="readmorebtn"><img src="{{ asset('frontend/images/readmorearrow.png') }}" /></span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-3">
        <div class="sec3Box">
          <a href="#">
          <img src="{{ asset('frontend/images/horoscop.png') }}" /><span class="sec3text">Horoscope</span><span class="readmorebtn"><img src="{{ asset('frontend/images/readmorearrow.png') }}" /></span>
          </a>
        </div>
      </div>
       <div class="col-sm-6 col-lg-3">
        <div class="sec3Box">
          <a href="#">
          <img src="{{ asset('frontend/images/kundli.png') }}" /><span class="sec3text">Kundli</span><span class="readmorebtn"><img src="{{ asset('frontend/images/readmorearrow.png') }}" /></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section5 ">
  <div class="container ">
    <h2 class="title2 "> Today's Astrology Prediction </h2>
    <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>
    <div class="row">
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/aries.png') }}" /></span>
        <span class="sec5text">Aries</span>
          </a>
        </div>
      </div>
   <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/taurus.png') }}" /></span>
        <span class="sec5text">Taurus</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/gemini.png') }}" /></span>
        <span class="sec5text">Gemini</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/cancer.png') }}" /></span>
        <span class="sec5text">Cancer</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/leo.png') }}" /></span>
        <span class="sec5text">Leo</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/virgo.png') }}" /></span>
        <span class="sec5text">Virgo</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/libra.png') }}" /></span>
        <span class="sec5text">Libra</span>
          </a>
        </div>
      </div>
       <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/scorpio.png') }}" /></span>
        <span class="sec5text">Scorpio</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/sagittarius.png') }}" /></span>
        <span class="sec5text">Sagittarius</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/capricorn.png') }}" /></span>
        <span class="sec5text">Capricorn</span>
          </a>
        </div>
      </div>
      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/aquarius.png') }}" /></span>
        <span class="sec5text">Aquarius</span>
          </a>
        </div>
      </div>


      <div class="col-sm-6 col-lg-2">
        <div class="sec5Box">
          <a href="#">
            <span class="sec5icon"><img src="{{ asset('frontend/images/pisces.png') }}" /></span>
        <span class="sec5text">Pisces </span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="section6">
  <div class="container ">
    <div class="row">
      <div class="col-lg-12">
        <div class="sec6box">
          <div class="row">
            <div class="col-md-4 col-lg-4">
              <div class="sec6textArea">
                <div><img src="{{ asset('frontend/images/total-astro.png') }}" /></div>
                <div class="pl15">
                  <div class="figcap1">50000+</div>
                  <div class="figcap2">Total Astrologers</div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-lg-4">
              <div class="sec6textArea">
                <div><img src="{{ asset('frontend/images/yearofex.png') }}" /></div>
                <div class="pl15">
                  <div class="figcap1">11+</div>
                  <div class="figcap2">Years Of Excellence</div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-lg-4">
              <div class="sec6textArea">
                <div><img src="{{ asset('frontend/images/happycustomer.png') }}" /></div>
                <div class="pl15">
                  <div class="figcap1">500+</div>
                  <div class="figcap2">Happy Customers</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>

<div class="section7 ">
  <div class="container ">
    <h2 class="title2 ">Latest Astrology Blogs </h2>
    <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>
    <div class="row pt40">
      <div class="col-lg-12">
        <div class="blogStyle owl-carousel">
          @foreach($blogs as $blog)
          <div class="item">
            <div class="blogBox">
              <div class="blogImg">
                  <a href="{{ route('blog.show', $blog->slug) }}">
                      @if($blog->image)
                          <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->image_alt ?? $blog->title }}" />
                      @else
                          <img src="{{ asset('frontend/images/blog1.jpg') }}" alt="Blog" />
                      @endif
                  </a>
              </div>
              <div class="dateandAstroaura">
                <div class="blogDate"><img src="{{ asset('frontend/images/date.png') }}" /> {{ $blog->published_at ? $blog->published_at->format('D, M d, Y') : '' }}</div>
                <div class="astroaura"><img src="{{ asset('frontend/images/astroicon.png') }}" /> {{ $blog->author }}</div>
              </div>
              <div class="blogTitle">
                <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
              </div>
              <div class="blogreadmorebtn">
                <a href="{{ route('blog.show', $blog->slug) }}"><img src="{{ asset('frontend/images/readmorearrow.png') }}" /></a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

    </div>
    <div class="viewAllBlog">
      <a class="customBtn" href="{{ route('blog.index') }}">VIEW ALL BLOGS <img
          src="{{ asset('frontend/images/readmorearrowwhite.png') }}" /></a>
    </div>
  </div>
</div>

<div class="section8 ">
  <div class="container ">
    <h2 class="title2 ">Testimonials</h2>
    <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>
     <div class="row pt40">
      <div class="col-lg-12">
        <div class="testimonials owl-carousel">
            @foreach($testimonials as $testimonial)
            <div class="item">
              <div class="testimonialBox">
                <div class="testimonialImg">
                    @if($testimonial->image)
                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" />
                    @else
                        <img src="{{ asset('frontend/images/testimonial-icon.png') }}" alt="" />
                    @endif
                </div>
                <div class="testimonialText">
                  {{ $testimonial->content }}
                </div>
                <div class="testimonialName">
                 {{ $testimonial->name }}
                </div>
              </div>
            </div>
            @endforeach
          </div>
      </div>

    </div>

  </div>
</div>

<div class="section7 ">
  <div class="container ">
    <h2 class="title2 ">Daily Horoscope Video</h2>
    <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>
    <div class="row pt40">
      <div class="col-lg-12">
        <div class="videoStyle owl-carousel ">
            <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video1.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">Guiding your path through the stars.</a>
                </div>

              </div>
            </div>

            <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video2.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">The future of the web — light, fast, and Astro.</a>
                </div>

              </div>
            </div>

            <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video1.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">Guiding your path through the stars.</a>
                </div>

              </div>
            </div>

            <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video2.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">The future of the web — light, fast, and Astro.</a>
                </div>

              </div>
            </div>
             <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video1.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">Guiding your path through the stars.</a>
                </div>

              </div>
            </div>

            <div class="item">
              <div class="blogBox">
                <div class="blogImg"><a href="#"><img src="{{ asset('frontend/images/video2.png') }}" alt="" /></a></div>

                <div class="videoTitle">
                 <a href="#">The future of the web — light, fast, and Astro.</a>
                </div>

              </div>
            </div>

          </div>
      </div>

    </div>
    <div class="viewAllBlog">
      <a class="customBtn" href="#">VIEW ALL <img src="{{ asset('frontend/images/readmorearrowwhite.png') }}" /></a>
    </div>
  </div>
</div>

<div class="section9 ">
  <div class="container ">
    <h3 class="title3 ">Unlock Your Future with Astrology: Horoscope Readings, Birth Chart Analysis, and More</h3>

<p>Astrology is a time-honored science that helps uncover the mysteries of an individual's personality, potential, and destiny. By studying the positions of the Sun, Moon, and planets at the moment of birth, astrologers can reveal deep insights into your strengths, weaknesses, and life path. Astroaura connects you with expert astrologers and diverse schools of astrology—including Vedic, Western, and Chinese astrology—empowering you to use astrological wisdom for self-discovery, personal growth, and well-being.</p>

<p>Your astrological chart, commonly known as your Horoscope, acts as a roadmap to your inner world, guiding you through future possibilities, personal preferences, and key life events.</p>

 <span class="more-text">
<h3 class="title3 ">The Ancient Wisdom of Vedic Astrology</h3>

<p>India has gifted the world the profound knowledge of Vedic astrology, an ancient system that studies the impact of celestial movements on human life. For centuries, astrology has guided people toward fulfillment, success, and clarity, offering light during times of uncertainty.</p>

<p>Astroaura brings this timeless wisdom to your fingertips. Through our platform, you can consult skilled astrologers who serve as trusted guides and advisors on your life's journey. We offer comprehensive services, including horoscope readings, birth chart analysis, and personalized astrology predictions, designed to help you make informed decisions and achieve balance in all aspects of life.</p>

<h3 class="title3 ">Western Astrology</h3>

<p>Western astrology follows an equatorial zodiac system, which aligns with the Earth's equator and the Sun's path. This system focuses on the exact time of birth to generate precise horoscopes and interpret planetary influences.</p>

<p>In Western astrology, the positions of the Sun, Moon, and planets within the 12 houses reveal your inner motivations, emotional tendencies, and personality traits. Unlike Vedic astrology, which emphasizes destiny, Western astrology often highlights psychological insights and personal development.</p>

<p>Astrologers use tools like transit analysis and progressions to forecast trends and guide you through upcoming life phases.</p>

   </span>
    <button  class="customBtn3 read-more-btn">Read More </button >

  </div>
</div>

<div class="section7 ">
  <div class="container ">
    <h2 class="title2 "> Frequently Asked Questions</h2>
    <div class="headingDeign"><img src="{{ asset('frontend/images/headingDesign.png') }}" /></div>
    <div class="row pt40">
      <div class="col-lg-12">
       <div class="accordion" id="faqAccordion">
      <div class="row row-cols-1 row-cols-lg-2 g-3">

        <div class="col">
          <div class="accordion-item">
            <h2 class="accordion-header" id="q1">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
                Why Is Astrology So Accurate?
              </button>
            </h2>
            <div id="a1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Astrology uses planetary positions and alignments to provide insights based on centuries of observations.
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="accordion-item">
            <h2 class="accordion-header" id="q2">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
                Why Should You Choose Astroverse For An Astrology Horoscope?
              </button>
            </h2>
            <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Astroverse provides expert astrologers and personalized horoscopes for accurate readings.
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="accordion-item">
            <h2 class="accordion-header" id="q3">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
                Is Astrology Prediction True?
              </button>
            </h2>
            <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                It depends on interpretation and individual belief, but many find its insights surprisingly relevant.
              </div>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="accordion-item">
            <h2 class="accordion-header" id="q4">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a4">
                How Can Online Astrology Help Me In Predicting The Future?
              </button>
            </h2>
            <div id="a4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Online astrology platforms give instant access to personalized insights anytime.
              </div>
            </div>
          </div>
        </div>

        <!-- Add more columns similarly -->
      </div>
    </div>
      </div>

    </div>

  </div>
</div>

@endsection
