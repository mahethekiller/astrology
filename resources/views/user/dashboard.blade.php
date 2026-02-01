@extends('frontend.layouts.app')

@section('title', 'Astrologer - Home')


@section('content')
    <div class="section5 ">
        <div class="container ">


            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body text-center">
                            <div class="profile-image-wrapper mb-3">
                                <img src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : asset('images/default-user.png') }}"
                                    class="rounded-circle img-thumbnail"
                                    style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                            </div>
                            <h5 class="card-title mb-1">{{ auth()->user()->name }}</h5>

                            <hr>
                            <div class="mb-3">
                                <h6>Wallet Balance</h6>
                                <h3 class="text-success fw-bold">
                                    ₹{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</h3>
                                <a href="{{ route('wallet.index') }}" class="btn btn-success btn-sm w-100 mt-1">
                                    <i class="bi bi-wallet2"></i> Add Money
                                </a>
                            </div>

                            <hr>
                            <a href="{{ route('chat.history') }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-clock-history"></i> Chat History
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Profile Settings</h5>
                        </div>
                        <div class="card-body">
                            {{-- Email Verification Status --}}
                            @if (auth()->user()->isUser() && !auth()->user()->hasVerifiedEmail())
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Email Not Verified!
                                    </h4>
                                    <p>Your email address is not verified. Please check your inbox for the verification link.
                                    </p>
                                    <hr>
                                    <p class="mb-0">
                                        Did not receive the email?
                                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-link p-0 m-0 align-baseline text-decoration-none fw-bold">Click here
                                            to resend</button>.
                                    </form>
                                    </p>
                                </div>
                                @if (session('status') == 'verification-link-sent')
                                    <div class="alert alert-success" role="alert">
                                        A new verification link has been sent to your email address.
                                    </div>
                                @endif
                            @endif

                            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" readonly name="phone" id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', auth()->user()->phone_number) }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="profile_image" class="form-label">Update Profile Image</label>
                                        <input type="file" name="profile_image" id="profile_image"
                                            class="form-control @error('profile_image') is-invalid @enderror">
                                        @error('profile_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">Short Bio</label>
                                    <textarea name="bio" id="bio" rows="4"
                                        class="form-control @error('bio') is-invalid @enderror">{{ old('bio', auth()->user()->bio) }}</textarea>
                                    @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection