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
          <li class="nav-item"><a class="nav-link" href="{{ route('kundli.index') }}">Free Kundli</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Kundli Matching</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Horoscopes</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Blogs</a></li>
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}"> @csrf <button type="submit"
                      class="dropdown-item">Logout</button></form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link btn navboginbtn" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa-solid fa-user"></i> Login
              </a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>
</header>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Login / Sign Up</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Error Alert -->
        <div id="login-error" class="alert alert-danger d-none"></div>
        <!-- Test OTP Alert -->
        <div id="test-otp-display" class="alert alert-info d-none"></div>

        <!-- Step 1: Phone Number -->
        <div id="otp-step-1">
          <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" class="form-control" id="phone_number" placeholder="Enter your 10 digit number">
          </div>
          <button type="button" class="btn btn-primary w-100" id="send-otp-btn">Send OTP</button>
        </div>

        <!-- Step 2: Verify OTP -->
        <div id="otp-step-2" class="d-none">
          <p>OTP sent to <span id="display-phone" class="fw-bold"></span></p>
          <div class="mb-3">
            <label for="otp_input" class="form-label">Enter OTP</label>
            <input type="text" class="form-control" id="otp_input" placeholder="XXXXXX">
          </div>
          <button type="button" class="btn btn-primary w-100" id="verify-otp-btn">Verify & Login</button>
          <div class="mt-2 text-center">
            <a href="#" id="change-number-link">Change Number</a>
          </div>
        </div>

        <!-- Step 3: Register Name (New Users) -->
        <div id="otp-step-3" class="d-none">
          <div class="alert alert-success">Phone verified! Please complete your profile.</div>
          <div class="mb-3">
            <label for="user_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="user_name" placeholder="Enter your full name">
          </div>
          <button type="button" class="btn btn-success w-100" id="register-btn">Complete Account</button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const step1 = document.getElementById('otp-step-1');
    const step2 = document.getElementById('otp-step-2');
    const step3 = document.getElementById('otp-step-3');

    const phoneInput = document.getElementById('phone_number');
    const otpInput = document.getElementById('otp_input');
    const nameInput = document.getElementById('user_name');

    const sendBtn = document.getElementById('send-otp-btn');
    const verifyBtn = document.getElementById('verify-otp-btn');
    const registerBtn = document.getElementById('register-btn');
    const changeLink = document.getElementById('change-number-link');

    const errorAlert = document.getElementById('login-error');
    const otpDisplay = document.getElementById('test-otp-display');
    const displayPhone = document.getElementById('display-phone');

    let currentPhone = '';

    function showError(msg) {
      errorAlert.textContent = msg;
      errorAlert.classList.remove('d-none');
      setTimeout(() => {
        errorAlert.classList.add('d-none');
        errorAlert.textContent = '';
      }, 5000);
    }

    function hideError() {
      errorAlert.classList.add('d-none');
    }

    sendBtn.addEventListener('click', function () {
      currentPhone = phoneInput.value.trim();
      if (!currentPhone) {
        showError('Please enter a phone number');
        return;
      }

      sendBtn.disabled = true;
      sendBtn.textContent = 'Sending...';

      axios.post('{{ route("login.send-otp") }}', {
        phone_number: currentPhone
      })
        .then(function (response) {
          // Success
          step1.classList.add('d-none');
          step2.classList.remove('d-none');
          displayPhone.textContent = currentPhone;

          // SHOW OTP for testing
          if (response.data.otp) {
            otpDisplay.textContent = 'TESTING OTP: ' + response.data.otp;
            otpDisplay.classList.remove('d-none');
          }
        })
        .catch(function (error) {
          showError(error.response?.data?.error || 'Something went wrong');
        })
        .finally(function () {
          sendBtn.disabled = false;
          sendBtn.textContent = 'Send OTP';
        });
    });

    verifyBtn.addEventListener('click', function () {
      const otp = otpInput.value.trim();
      if (!otp) {
        showError('Please enter the OTP');
        return;
      }

      verifyBtn.disabled = true;
      verifyBtn.textContent = 'Verifying...';

      axios.post('{{ route("login.verify-otp") }}', {
        phone_number: currentPhone,
        otp: otp
      })
        .then(function (response) {
          if (response.data.status === 'success') {
            window.location.href = response.data.redirect_url;
          } else if (response.data.status === 'new_user') {
            // Show name step
            step2.classList.add('d-none');
            step3.classList.remove('d-none');
            otpDisplay.classList.add('d-none'); // Hide OTP display
          }
        })
        .catch(function (error) {
          showError(error.response?.data?.error || 'Invalid OTP');
        })
        .finally(function () {
          verifyBtn.disabled = false;
          verifyBtn.textContent = 'Verify & Login';
        });
    });

    registerBtn.addEventListener('click', function () {
      const name = nameInput.value.trim();
      const otp = otpInput.value.trim(); // Still need OTP for verification in backend

      if (!name) {
        showError('Please enter your name');
        return;
      }

      registerBtn.disabled = true;
      registerBtn.textContent = 'Creating Account...';

      axios.post('{{ route("login.register-otp") }}', {
        phone_number: currentPhone,
        otp: otp,
        name: name
      })
        .then(function (response) {
          window.location.href = response.data.redirect_url;
        })
        .catch(function (error) {
          showError(error.response?.data?.error || 'Registration failed');
        })
        .finally(function () {
          registerBtn.disabled = false;
          registerBtn.textContent = 'Complete Account';
        });
    });

    changeLink.addEventListener('click', function (e) {
      e.preventDefault();
      step2.classList.add('d-none');
      step1.classList.remove('d-none');
      otpDisplay.classList.add('d-none');
      otpInput.value = '';
    });
  });
</script>