<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Register - DIGIBARANGAY</title>
 
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="./home.html">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="brand-logo" />
        <span class="brand-text">DIGIBARANGAY</span>
      </a>
    </div>
  </header>

  <main>
@if ($errors->any())
    <div style="
        background-color: #fff5f5; 
        border-left: 5px solid #f56565; 
        color: #c53030; 
        padding: 16px; 
        margin-bottom: 24px; 
        border-radius: 4px; 
        font-family: sans-serif;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    " role="alert">
        
        <div style="font-weight: bold; margin-bottom: 8px; font-size: 16px;">
            Please correct the following errors:
        </div>

        <ul style="
            margin: 0; 
            padding-left: 20px; 
            list-style-type: disc;
            font-size: 14px;
            line-height: 1.5;
        ">
            @foreach ($errors->all() as $error)
                <li style="margin-bottom: 4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <section class="container form-section">
      <h1>Create Resident Account</h1>
      <form action="{{ route('register') }}" method="post" id="registerForm">
        @csrf
        <fieldset>
          <legend>Account Information</legend>
          <label>Username ()
            <input name="username" type="text"  />
          </label>
          <label>Email address ()
            <input name="email" type="email"  />
          </label>
          <label>Password (min 6 characters)
            <input name="password" type="password" minlength="6"  />
          </label>
          <label>Confirm password
            <input name="password_confirmation" type="password" minlength="6"  />
          </label>
        </fieldset>

        <fieldset>
          <legend>Personal Information</legend>
          <label>Full name ()
            <input name="fullname" type="text"  />
          </label>
          <label>Contact number (optional, 11 digits)
            <input name="contact" type="tel" pattern="^\d{11}$" placeholder="09123456789" />
          </label>
          <label>Age ()
            <input name="age" type="number" min="1"  />
          </label>
          <label>Complete address (example: House No., Street, Barangay 192)
            <input name="address" type="text" placeholder="House No., Street, Barangay 192"  />
          </label>
        </fieldset>

        <div class="form-actions">
          <button type="submit" class="btn primary">Register Account</button>
          <a class="btn" href="./login">Back to Login</a>
        </div>

        <p id="registerSuccess" class="muted" style="display:none;color:#166534;font-weight:600;">Account registered successfully. Redirecting to login...</p>
        <p id="registerError" class="muted" style="display:none;color:#b91c1c;font-weight:600;"></p>

        <p class="muted">Before registering you must accept the <a href="./terms.html">Terms & Conditions</a>.</p>
      </form>
    </section>
  </main>

  <script>
    const registerForm = document.getElementById('registerForm');
    const registerSuccess = document.getElementById('registerSuccess');
    const registerError = document.getElementById('registerError');

    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();

      registerSuccess.style.display = 'none';
      registerError.style.display = 'none';

      const submitBtn = registerForm.querySelector('button[type="submit"]');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Registering...';

      try {
        const csrf = registerForm.querySelector('input[name="_token"]')?.value || '';
        const formData = new FormData(registerForm);

        const res = await fetch(registerForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf
          },
          body: formData
        });

        const body = await res.json().catch(() => ({}));
        if (!res.ok) {
          let msg = body.message || 'Registration failed.';
          if (body.errors && typeof body.errors === 'object') {
            msg = Object.values(body.errors).flat().join(' ');
          }
          registerError.textContent = msg;
          registerError.style.display = 'block';
          return;
        }

        registerSuccess.style.display = 'block';
        registerForm.reset();
        setTimeout(() => {
          window.location.href = '/login';
        }, 1200);
      } catch (err) {
        registerError.textContent = 'Network error. Please try again.';
        registerError.style.display = 'block';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register Account';
      }
    });
  </script>
</body>
</html>
