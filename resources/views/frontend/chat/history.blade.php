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

                                                    <div class="d-flex align-items-center gap-2">
                                                        <button class="btn btn-sm btn-info text-white view-chat-btn"
                                                            data-bs-toggle="modal" data-bs-target="#chatMessagesModal"
                                                            data-chat-id="{{ $chat->id }}">
                                                            <i class="fas fa-comment-dots me-1"></i> View Chat
                                                        </button>

                                                        @if(!$rating)
                                                            <button class="btn btn-sm btn-outline-primary rate-btn"
                                                                data-bs-toggle="modal" data-bs-target="#ratingModal"
                                                                data-astrologer-id="{{ $chat->astrologer_id }}"
                                                                data-request-id="{{ $chat->id }}"
                                                                data-type="ChatRequest">
                                                                Rate Now
                                                            </button>
                                                        @else
                                                            <div class="text-warning small">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fa{{ $i <= $rating->rating ? 's' : 'r' }} fa-star"></i>
                                                                @endfor
                                                            </div>
                                                        @endif
                                                    </div>
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

    <!-- Chat Messages Modal -->
    <div class="modal fade" id="chatMessagesModal" tabindex="-1" aria-labelledby="chatMessagesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title" id="chatMessagesModalLabel">
                        <i class="fas fa-history me-2"></i>Chat Conversation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light p-4" id="chatMessagesContent" style="min-height: 400px;">
                    <div class="text-center py-5 loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading messages...</p>
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

    @push('styles')
        <style>
            .message-bubble {
                max-width: 80%;
                padding: 12px 16px;
                border-radius: 15px;
                margin-bottom: 15px;
                position: relative;
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .message-user {
                background-color: #007bff;
                color: white;
                margin-left: auto;
                border-bottom-right-radius: 2px;
            }

            .message-astrologer {
                background-color: #fff;
                color: #333;
                margin-right: auto;
                border-bottom-left-radius: 2px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .message-sender {
                font-size: 0.75rem;
                font-weight: bold;
                margin-bottom: 4px;
                display: block;
            }

            .message-time {
                font-size: 0.7rem;
                opacity: 0.8;
                margin-top: 4px;
                display: block;
                text-align: right;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function () {
                // View Chat Messages
                $('.view-chat-btn').on('click', function () {
                    const chatId = $(this).data('chat-id');
                    const modalBody = $('#chatMessagesContent');

                    // Reset content and show spinner
                    modalBody.html(`
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading messages...</p>
                        </div>
                    `);

                    $.ajax({
                        url: `/chat/history/${chatId}/messages`,
                        method: 'GET',
                        success: function (response) {
                            if (response.success && response.messages.length > 0) {
                                let html = '<div class="chat-container d-flex flex-column">';
                                response.messages.forEach(msg => {
                                    const isUser = msg.sender_identity === response.user_identity;
                                    const senderName = isUser ? 'You' : response.astrologer_name;
                                    const bubbleClass = isUser ? 'message-user' : 'message-astrologer';
                                    const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                                    html += `
                                        <div class="message-bubble ${bubbleClass}">
                                            <span class="message-sender">${senderName}</span>
                                            ${msg.body}
                                            <span class="message-time">${time}</span>
                                        </div>
                                    `;
                                });
                                html += '</div>';
                                modalBody.html(html);
                            } else {
                                modalBody.html(`
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                        <p>No messages found for this chat session.</p>
                                    </div>
                                `);
                            }
                        },
                        error: function (xhr) {
                            modalBody.html(`
                                <div class="alert alert-danger mx-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Failed to load messages. Please try again.
                                </div>
                            `);
                        }
                    });
                });

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