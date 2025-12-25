<header class="header">
  <nav class="navbar navbar-expand-lg customStyleNav">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('frontend/images/logo.png') }}" /></a>
      <!-- Toggle button for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('astrologer.index') }}">Chat with
              Astrologer</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('astrologer.index') }}">Talk to
              Astrologer</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Free Kundli</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Kundli Matching</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Horoscopes</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Blogs</a></li>
          <li class="nav-item"><a class="nav-link btn navboginbtn" href="#"><i class="fa-solid fa-user"></i>Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>