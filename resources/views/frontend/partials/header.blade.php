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
          @if ($headerMenu && $headerMenu->items)
            @foreach ($headerMenu->items as $item)
              @if ($item->children->count() > 0)
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown{{ $item->id }}" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $item->title }}
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $item->id }}">
                    @foreach ($item->children as $child)
                      <li>
                        <a class="dropdown-item" href="{{ $child->type == 'route' ? route($child->route) : $child->url }}"
                          target="{{ $child->target }}">{{ $child->title }}</a>
                      </li>
                    @endforeach
                  </ul>
                </li>
              @else
                <li class="nav-item">
                  <a class="nav-link" href="{{ $item->type == 'route' ? route($item->route) : $item->url }}"
                    target="{{ $item->target }}">{{ $item->title }}</a>
                </li>
              @endif
            @endforeach
          @endif
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                @if (Auth::user()->hasRole('admin'))
                  <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                @elseif(Auth::user()->hasRole('astrologer'))
                  <li><a class="dropdown-item" href="{{ route('astrologer.dashboard') }}">Dashboard</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                @endif
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
        <div id="auth-error" class="alert alert-danger d-none"></div>
        <div id="auth-success" class="alert alert-success d-none"></div>

        <!-- Login Form -->
        <div id="login-container">
          <form id="login-form">
            @csrf
            <div class="mb-3">
              <label for="login_email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="login_email" name="email" required
                placeholder="Enter your email">
            </div>
            <div class="mb-3">
              <label for="login_password" class="form-label">Password</label>
              <input type="password" class="form-control" id="login_password" name="password" required
                placeholder="Enter your password">
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
              <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="login-btn">Login</button>
          </form>
          <div class="mt-3 text-center">
            <p>Don't have an account? <a href="#" id="show-register-link">Register here</a></p>
            <p><a href="{{ route('password.request') }}">Forgot your password?</a></p>
          </div>
        </div>

        <!-- Register Form -->
        <div id="register-container" class="d-none">
          <form id="register-form">
            @csrf
            <div class="mb-3">
              <label for="register_name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="register_name" name="name" required
                placeholder="Enter your full name">
            </div>
            <div class="mb-3">
              <label for="register_email" class="form-label">Email Address</label>
              <input type="email" class="form-control" id="register_email" name="email" required
                placeholder="Enter your email">
            </div>
            <div class="mb-3">
              <label for="register_password" class="form-label">Password</label>
              <input type="password" class="form-control" id="register_password" name="password" required
                placeholder="Enter your password">
            </div>
            <div class="mb-3">
              <label for="register_password_confirmation" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="register_password_confirmation"
                name="password_confirmation" required placeholder="Confirm your password">
            </div>
            <button type="submit" class="btn btn-success w-100" id="register-btn">Register</button>
          </form>
          <div class="mt-3 text-center">
            <p>Already have an account? <a href="#" id="show-login-link">Login here</a></p>
          </div>
        </div>

        <!-- OTP Verification Form -->
        <div id="otp-register-container" class="d-none">
          <form id="otp-register-form">
            @csrf
            <p class="text-center mb-3">Please enter the 6-digit OTP sent to your email.</p>
            <div class="mb-3">
              <label for="reg_otp" class="form-label">OTP Code</label>
              <input type="text" class="form-control" id="reg_otp" name="otp" required maxlength="6"
                placeholder="Enter 6-digit OTP">
            </div>
            <button type="submit" class="btn btn-primary w-100" id="verify-otp-btn">Verify & Register</button>
          </form>
          <div class="mt-3 text-center">
            <p><a href="#" id="back-to-register">Back to Register</a></p>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const loginContainer = document.getElementById('login-container');
    const registerContainer = document.getElementById('register-container');
    const otpContainer = document.getElementById('otp-register-container');

    const showRegisterLink = document.getElementById('show-register-link');
    const showLoginLink = document.getElementById('show-login-link');
    const backToRegister = document.getElementById('back-to-register');

    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const otpForm = document.getElementById('otp-register-form');

    const loginBtn = document.getElementById('login-btn');
    const registerBtn = document.getElementById('register-btn');
    const verifyOtpBtn = document.getElementById('verify-otp-btn');

    const errorAlert = document.getElementById('auth-error');
    const successAlert = document.getElementById('auth-success');

    function showError(msg) {
      errorAlert.textContent = msg;
      errorAlert.classList.remove('d-none');
      successAlert.classList.add('d-none');
    }

    function showSuccess(msg) {
      successAlert.textContent = msg;
      successAlert.classList.remove('d-none');
      errorAlert.classList.add('d-none');
    }

    function clearAlerts() {
      errorAlert.classList.add('d-none');
      successAlert.classList.add('d-none');
      errorAlert.textContent = '';
      successAlert.textContent = '';
    }

    // Toggle forms
    showRegisterLink.addEventListener('click', function (e) {
      e.preventDefault();
      loginContainer.classList.add('d-none');
      registerContainer.classList.remove('d-none');
      otpContainer.classList.add('d-none');
      clearAlerts();
    });

    showLoginLink.addEventListener('click', function (e) {
      e.preventDefault();
      registerContainer.classList.add('d-none');
      loginContainer.classList.remove('d-none');
      otpContainer.classList.add('d-none');
      clearAlerts();
    });

    backToRegister.addEventListener('click', function (e) {
      e.preventDefault();
      otpContainer.classList.add('d-none');
      registerContainer.classList.remove('d-none');
      clearAlerts();
    });

    // Handle Login
    loginForm.addEventListener('submit', function (e) {
      e.preventDefault();
      clearAlerts();
      loginBtn.disabled = true;
      loginBtn.textContent = 'Logging in...';

      const formData = new FormData(loginForm);
      const data = Object.fromEntries(formData.entries());

      axios.post('{{ route("login") }}', data, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
        .then(function (response) {
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          } else {
            // Fallback if no redirect_url provided
            window.location.href = '/';
          }
        })
        .catch(function (error) {
          console.error(error);
          let msg = 'Login failed. Please check your credentials.';
          if (error.response && error.response.data && error.response.data.errors) {
            // Take the first error
            msg = Object.values(error.response.data.errors)[0][0];
          } else if (error.response && error.response.data && error.response.data.message) {
            msg = error.response.data.message;
          }
          showError(msg);
        })
        .finally(function () {
          loginBtn.disabled = false;
          loginBtn.textContent = 'Login';
        });
    });

    // Handle Register
    registerForm.addEventListener('submit', function (e) {
      e.preventDefault();
      clearAlerts();
      registerBtn.disabled = true;
      registerBtn.textContent = 'Registering...';

      const formData = new FormData(registerForm);
      const data = Object.fromEntries(formData.entries());

      axios.post('{{ route("register") }}', data, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
        .then(function (response) {
          if (response.data.status === 'otp_sent') {
            showSuccess(response.data.message);
            registerContainer.classList.add('d-none');
            otpContainer.classList.remove('d-none');
          } else if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          } else {
            window.location.href = '/';
          }
        })
        .catch(function (error) {
          console.error(error);
          let msg = 'Registration failed.';
          if (error.response && error.response.data && error.response.data.errors) {
            msg = Object.values(error.response.data.errors)[0][0];
          } else if (error.response && error.response.data && error.response.data.message) {
            msg = error.response.data.message;
          }
          showError(msg);
        })
        .finally(function () {
          registerBtn.disabled = false;
          registerBtn.textContent = 'Register';
        });
    });

    // Handle OTP Verify
    otpForm.addEventListener('submit', function (e) {
      e.preventDefault();
      clearAlerts();
      verifyOtpBtn.disabled = true;
      verifyOtpBtn.textContent = 'Verifying...';

      const formData = new FormData(otpForm);
      const data = Object.fromEntries(formData.entries());

      axios.post('{{ route("register.verify-otp") }}', data, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
        .then(function (response) {
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          } else {
            window.location.href = '/';
          }
        })
        .catch(function (error) {
          console.error(error);
          let msg = 'Verification failed.';
          if (error.response && error.response.data && error.response.data.errors) {
            msg = Object.values(error.response.data.errors)[0][0];
          } else if (error.response && error.response.data && error.response.data.message) {
            msg = error.response.data.message;
          }
          showError(msg);
        })
        .finally(function () {
          verifyOtpBtn.disabled = false;
          verifyOtpBtn.textContent = 'Verify & Register';
        });
    });
  });
</script>