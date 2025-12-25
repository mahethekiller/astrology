<div class="row">
    @forelse($astrologers as $astrologer)
        <div class="col-lg-4">
            <div class="topAstroBox">
                @if ($astrologer->is_online)
                    <span class="topAstroBoxactive"></span>
                @else
                    <span class="topAstroBoxactive" style="background-color: gray;"></span>
                @endif
                <div class="flowBtn"> <a class="" href="#">Follow </a></div>
                <div class="retingStar"><i class="fa-solid fa-star"></i> {{ number_format($astrologer->rating, 1) }}</div>
                <div class="topAstroBoxImg">
                    <a href="{{ route('astrologer.show', $astrologer->id) }}">
                        <img class="img-responsive" width="100" height="100" style="border-radius:50%; object-fit:cover;"
                            src="{{ asset('uploads/astrologers/' . $astrologer->profile_image) }}" />
                    </a>
                </div>
                <div class="astroDetails">
                    <div class="topAstroName text-truncate" title="{{ $astrologer->display_name }}">
                        {{ $astrologer->display_name }}</div>

                    <div class="astroLang text-truncate" title="{{ $astrologer->languages->pluck('name')->join(', ') }}">
                        {{ $astrologer->languages->pluck('name')->join(', ') }}</div>
                    <div class="topAstroWork text-truncate"
                        title="{{ $astrologer->specializations->pluck('name')->join(', ') }}">
                        {{ $astrologer->specializations->pluck('name')->join(', ') }}</div>
                    <div class="totalExp">Exp. : <strong>{{ $astrologer->experience_years }} Yrs</strong></div>
                    <div class="astroCallorChatbtn">
                        <a class="astrochaybtn" href="#">
                            <img src="{{ asset('images/live-chat.png') }}" />
                            @if($astrologer->chat_price > 0)
                                {{ number_format($astrologer->chat_price, 0) }}/Min
                            @else
                                NA
                            @endif
                        </a>
                        <a class="astrochaybtn" href="#">
                            <img src="{{ asset('images/live-call.png') }}" />
                            @if($astrologer->call_price > 0)
                                {{ number_format($astrologer->call_price, 0) }}/Min
                            @else
                                NA
                            @endif
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <h4>No astrologers found matching your criteria.</h4>
        </div>
    @endforelse
</div>

<div class="row">
    <div class="col-12 d-flex justify-content-center">
        {{ $astrologers->links('pagination::bootstrap-5') }}
    </div>
</div>