@extends('frontend.layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Chat History</h2>
                <div class="card shadow">
                    <div class="card-body">
                        @if($chats->isEmpty())
                            <p class="text-center text-muted">No past chats found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Astrologer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($chats as $chat)
                                            <tr>
                                                <td>{{ $chat->astrologer->display_name }}</td>
                                                <td>{{ $chat->created_at->format('d M Y, h:i A') }}</td>
                                                <td><span class="badge bg-secondary">{{ ucfirst($chat->status) }}</span></td>
                                                <td>
                                                    @php
                                                        $rating = \App\Models\Rating::where('ratable_type', \App\Models\ChatRequest::class)
                                                            ->where('ratable_id', $chat->id)
                                                            ->first();
                                                    @endphp

                                                    @if(!$rating)
                                                        <button class="btn btn-sm btn-outline-primary rate-btn" data-bs-toggle="modal"
                                                            data-bs-target="#ratingModal"
                                                            data-astrologer-id="{{ $chat->astrologer_id }}"
                                                            data-request-id="{{ $chat->id }}" data-type="ChatRequest">
                                                            Rate Now
                                                        </button>
                                                    @else
                                                        <div class="text-warning small">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fa{{ $i <= $rating->rating ? 's' : 'r' }} fa-star"></i>
                                                            @endfor
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Your Consultation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        @csrf
                        <input type="hidden" name="astrologer_profile_id" id="modalAstrologerId">
                        <input type="hidden" name="ratable_id" id="modalRequestId">
                        <input type="hidden" name="ratable_type" id="modalType">

                        <div class="mb-4 text-center">
                            <label class="form-label d-block">Your Rating</label>
                            <div class="star-rating h3 text-warning" style="cursor: pointer;">
                                <i class="far fa-star rating-star" data-rating="1"></i>
                                <i class="far fa-star rating-star" data-rating="2"></i>
                                <i class="far fa-star rating-star" data-rating="3"></i>
                                <i class="far fa-star rating-star" data-rating="4"></i>
                                <i class="far fa-star rating-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" name="rating" id="selectedRating" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Review Comment (Optional)</label>
                            <textarea name="comment" class="form-control" rows="3"
                                placeholder="How was your experience?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Modal Data
                $('.rate-btn').on('click', function () {
                    $('#modalAstrologerId').val($(this).data('astrologer-id'));
                    $('#modalRequestId').val($(this).data('request-id'));
                    $('#modalType').val($(this).data('type'));
                    resetRating();
                });

                // Star Selection
                $('.rating-star').on('click', function () {
                    const rating = $(this).data('rating');
                    $('#selectedRating').val(rating);
                    updateStars(rating);
                });

                function updateStars(rating) {
                    $('.rating-star').each(function () {
                        const starRating = $(this).data('rating');
                        if (starRating <= rating) {
                            $(this).removeClass('far').addClass('fas');
                        } else {
                            $(this).removeClass('fas').addClass('far');
                        }
                    });
                }

                function resetRating() {
                    $('#selectedRating').val(0);
                    $('.rating-star').removeClass('fas').addClass('far');
                    $('#ratingForm')[0].reset();
                }

                // Form Submission
                $('#ratingForm').on('submit', function (e) {
                    e.preventDefault();

                    if ($('#selectedRating').val() == 0) {
                        alert('Please select a star rating.');
                        return;
                    }

                    $.ajax({
                        url: "{{ route('rating.store') }}",
                        method: "POST",
                        data: $(this).serialize(),
                        success: function (response) {
                            if (response.success) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            alert(xhr.responseJSON?.message || 'Error occurred while saving rating.');
                        }
                    });
                });
            });
        </script>
    @endpush
    </div>
    </div>
    </div>
@endsection