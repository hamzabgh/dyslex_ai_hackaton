<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

@include('layouts.base.header')


<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
  <div class="container">
    <div class="row flex-center min-vh-100 py-5">
      <div class="col-sm-10 col-md-8 col-lg-5 col-xl-5 col-xxl-3">
        <a class="d-flex flex-center text-decoration-none mb-4" href="../../../index.html">
          <div class="d-flex align-items-center fw-bolder fs-5 d-inline-block">
            <img src="{{asset('assets/img/icons/logo.png')}}" alt="phoenix" width="58" />
          </div>
        </a>
        <div class="text-center mb-7">
          <h3 class="text-1000">Sign Up</h3>
          <p class="text-700">Create your account today</p>
        </div>
        <button class="btn btn-phoenix-secondary w-100 mb-3"><span class="fab fa-google text-danger me-2 fs--1"></span>Sign up with google</button>
        <button class="btn btn-phoenix-secondary w-100"><span class="fab fa-facebook text-primary me-2 fs--1"></span>Sign up with facebook</button>
        <div class="position-relative mt-4">
          <hr class="bg-200" />
          <div class="divider-content-center">or use email</div>
        </div>
        <form id="signup-form">
          @csrf

          <div class="mb-3 text-start">
            <label class="form-label" for="name">Name</label>
            <input class="form-control" id="name" name="name" type="text" placeholder="Name" />
            <span id="name-error" class="text-danger"></span>
          </div>
          <div class="mb-3 text-start">
            <label class="form-label" for="email">Email address</label>
            <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" />
            <span id="email-error" class="text-danger"></span>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label" for="password">Password</label>
              <input class="form-control form-icon-input" id="password" name="password" type="password" placeholder="Password" />
              <span id="password-error" class="text-danger"></span>
            </div>
            <div class="col-sm-6">
              <label class="form-label" for="confirm_password">Confirm Password</label>
              <input class="form-control form-icon-input" id="confirm_password" name="confirm_password" type="password" placeholder="Confirm Password" />
              <span id="confirm_password-error" class="text-danger"></span>
            </div>
          </div>
          <button type="button" class="btn btn-primary w-100 mb-3" onclick="submitForm()">Sign up</button>
          <div class="text-center"><a class="fs--1 fw-bold" href="#">Sign in to an existing account</a></div>
        </form>
      </div>
    </div>
  </div>
</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
@include('layouts.base.footer')
<!--
<script>
  function submitForm() {
    var formData = {
      name: document.getElementById('name').value,
      email: document.getElementById('email').value,
      password: document.getElementById('password').value,
      password_confirmation: document.getElementById('confirm_password').value,
    };



    fetch('/api/auth/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
      // Handle the response from the server
      if (data.message === 'User successfully registered') {
        alert('User successfully registered');
        // You can redirect or perform any other action here
      } else {
        // Handle errors from the server
        document.getElementById('name-error').innerText = data.errors.name;
        document.getElementById('email-error').innerText = data.errors.email;
        document.getElementById('password-error').innerText = data.errors.password;
        document.getElementById('confirm_password-error').innerText = data.errors.confirm_password;
      }
    })
    .catch(error => console.error('Error:', error));
  }
</script>
-->
  <script>

      function submitForm() {
          const formData = {
            name: getValue('name'),
            email: getValue('email'),
            password: getValue('password'),
            password_confirmation: getValue('confirm_password'),
          };

          fetch('/api/auth/register', {
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
            if (data.message === 'User successfully registered') {
              alert('User successfully registered');
              redirectToLogin(); 
            } else {
              handleErrors(data.errors);
            }
          })
          .catch(error => console.error('Error:', error));
      }

      function getValue(elementId) {
        return document.getElementById(elementId).value;
      }

      function getCSRFToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      }

      function handleErrors(errors) {
        Object.keys(errors).forEach(key => {
          const errorMessage = errors[key];
          document.getElementById(`${key}-error`).innerText = errorMessage;
        });
      }
      function redirectToLogin() {
        window.location.href = '/auth/login';
      }

  </script>
</body>

</html>
