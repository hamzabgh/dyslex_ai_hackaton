<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

@include('layouts.base.header')

<body>
  <main class="main" id="top">
    <div class="container">
      <div class="row flex-center min-vh-100 py-5">
        <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3"><a class="d-flex flex-center text-decoration-none mb-4" href="#">
            <div class="d-flex align-items-center fw-bolder fs-5 d-inline-block"><img src="{{asset('assets/admin/img/icons/logo.png')}}" alt="phoenix" width="58" />
            </div>
          </a>
          <div class="text-center mb-7">
            <h3 class="text-1000">Sign In</h3>
            <p class="text-700">Get access to your account</p>
          </div>
          <button class="btn btn-phoenix-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs--1"></span>Sign in with google</button>
          <button class="btn btn-phoenix-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs--1"></span>Sign in with facebook</button>
          <div class="position-relative">
            <hr class="bg-200 mt-5 mb-4" />
            <div class="divider-content-center">or use email</div>
          </div>
          <form id="login-form">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label" for="email">Email address</label>
                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" value="{{ old('email') }}" />
                <span id="email-error" class="text-danger"></span>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" placeholder="Password" />
                <span id="password-error" class="text-danger"></span>
            </div>
            <button type="button" class="btn btn-primary w-100 mb-3" onclick="submitLoginForm()">Sign In</button>
        </form>
        </div>
      </div>
    </div>
  </main>       


    @include('layouts.base.footer')

    <!-- ... (existing JavaScript code) ... -->
    <script>
      function submitLoginForm() {
        const formData = {
          email: getValue('email'),
          password: getValue('password'),
        };
    
        fetch('/api/auth/login', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken(),
          },
          body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.access_token) {
            alert('Login successful');
            // You can redirect or perform any other action here
            redirectToUserProfile();
          } else {
            handleErrors(data.error);
          }
        })
        .catch(error => console.error('Error:', error));
      }
    
      function redirectToUserProfile() {
        window.location.href = '/';
      }
    
      function getValue(elementId) {
        return document.getElementById(elementId).value;
      }
    
      function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      }
    
      function handleErrors(errorMessage) {
        document.getElementById('email-error').innerText = errorMessage;
      }
    </script>

</body>

</html>