<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Login - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="./home">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="brand-logo" />
        <span class="brand-text">DIGIBARANGAY</span>
      </a>
    </div>
  </header>

  <main>
    <section class="container form-section">
      <h1>Resident Portal Login</h1>
      <form action="{{ url('/resident/login') }}" method="POST" id="loginForm" novalidate>
        @csrf
        <label>Email address
          <input name="email" type="email" placeholder="resident@example.com" required />
        </label>
        <label>Password
          <input name="password" type="password" required />
        </label>
        <label class="inline"><input type="checkbox" id="remember" /> Remember me</label>
        <div class="form-actions">
          <button class="btn primary" type="submit">LOGIN</button>
          <a class="btn" href="./register">REGISTER</a>
          <a class="btn" href="./home">BACK</a>
        </div>
        <p class="muted"><a href="#" id="forgotPasswordLink">Forgot password</a></p>
        <p class="muted">Security reminder: Do not share your login credentials.</p>
      </form>
    </section>
  </main>

  <div id="forgotModal" class="modal-overlay" hidden>
    <div class="modal" style="max-width:430px;">
      <button class="modal-close" id="forgotCloseBtn" type="button">✕</button>
      <div class="modal-header">
        <h2>Forgot Your Password?</h2>
      </div>
      <div class="modal-body">
        <p style="margin-top:0; color:#4b5563;">
          Enter your email address and we will send you instructions to reset your password.
        </p>
        <input
          type="email"
          id="forgotEmailInput"
          placeholder="Enter your email address"
          style="width:100%;padding:10px;margin:10px 0 14px;border-radius:6px;border:1px solid #d1d5db;"
        />
        <div style="display:flex;gap:10px;">
          <button class="btn primary" id="forgotContinueBtn" type="button" style="flex:1;">Continue</button>
          <button class="btn" id="forgotCancelBtn" type="button" style="flex:1;">Cancel</button>
        </div>
        <p id="forgotMessage" style="display:none;margin-top:10px;font-size:.92rem;"></p>
      </div>
    </div>
  </div>

  <script>
    const FORGOT_REQUESTS_KEY = 'digibarangay_forgot_requests_v1';
    const form = document.getElementById('loginForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const forgotModal = document.getElementById('forgotModal');
    const forgotCloseBtn = document.getElementById('forgotCloseBtn');
    const forgotCancelBtn = document.getElementById('forgotCancelBtn');
    const forgotContinueBtn = document.getElementById('forgotContinueBtn');
    const forgotEmailInput = document.getElementById('forgotEmailInput');
    const forgotMessage = document.getElementById('forgotMessage');

    function openForgotModal() {
      forgotModal.hidden = false;
      forgotModal.classList.add('open');
      forgotMessage.style.display = 'none';
      forgotMessage.textContent = '';
      forgotEmailInput.value = '';
      forgotEmailInput.focus();
    }

    function closeForgotModal() {
      forgotModal.classList.remove('open');
      forgotModal.hidden = true;
    }

    forgotPasswordLink.addEventListener('click', (e) => {
      e.preventDefault();
      openForgotModal();
    });

    forgotCloseBtn.addEventListener('click', closeForgotModal);
    forgotCancelBtn.addEventListener('click', closeForgotModal);

    forgotModal.addEventListener('click', (e) => {
      if (e.target === forgotModal) {
        closeForgotModal();
      }
    });

    forgotContinueBtn.addEventListener('click', async () => {
      const email = String(forgotEmailInput.value || '').trim().toLowerCase();
      if (!email) {
        forgotMessage.style.display = 'block';
        forgotMessage.style.color = '#dc2626';
        forgotMessage.textContent = 'Please enter your email address.';
        return;
      }

      try {
        const res = await fetch('/resident/forgot-password', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ email })
        });

        const body = await res.json().catch(() => ({}));
        if (!res.ok) {
          forgotMessage.style.display = 'block';
          forgotMessage.style.color = '#dc2626';
          forgotMessage.textContent = body.message || 'Unable to send reset instructions.';
          return;
        }

        // Keep a local trail of forgot-password requests for the admin table.
        try {
          const existing = JSON.parse(localStorage.getItem(FORGOT_REQUESTS_KEY) || '[]');
          const list = Array.isArray(existing) ? existing : [];
          const now = new Date().toLocaleString();
          const idx = list.findIndex((x) => String(x?.email || '').toLowerCase() === email);
          const item = { email, date: now };
          if (idx >= 0) {
            list[idx] = item;
          } else {
            list.unshift(item);
          }
          localStorage.setItem(FORGOT_REQUESTS_KEY, JSON.stringify(list));
        } catch (storageErr) {
          console.error('Unable to store forgot-password request', storageErr);
        }

        forgotMessage.style.display = 'block';
        forgotMessage.style.color = '#15803d';
        forgotMessage.textContent = body.message || 'Reset instructions sent successfully.';
      } catch (err) {
        forgotMessage.style.display = 'block';
        forgotMessage.style.color = '#dc2626';
        forgotMessage.textContent = 'Network error. Please try again.';
      }
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitForm = e.currentTarget;
      const email = String(form.email.value || '').trim().toLowerCase();
      const pw = String(form.password.value || '').trim();
      if (!email || !pw) {
        alert('Please enter email and password.');
        return;
      }

      try {
        const res = await fetch(submitForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({ email, password: pw })
        });

        const body = await res.json().catch(() => ({}));

        if (!res.ok) {
          alert(body.message || 'Login failed.');
          return;
        }

        const loggedInUser = body.user || {};
        const displayName = loggedInUser.fullname || loggedInUser.name || loggedInUser.username || email;
        const displayEmail = loggedInUser.email || email;
        const normalizedUser = {
          id: loggedInUser.id || null,
          fullname: displayName,
          name: displayName,
          username: loggedInUser.username || '',
          email: displayEmail,
          role: loggedInUser.role || 'resident',
          user_key: String(
            loggedInUser.id
            || loggedInUser.email
            || loggedInUser.username
            || displayEmail
            || displayName
          ).trim().toLowerCase()
        };

        localStorage.setItem('digibarangay_logged_in', '1');
        localStorage.setItem('digibarangay_user', JSON.stringify(normalizedUser));
        localStorage.setItem('digibarangay_registered_user', JSON.stringify(normalizedUser));

        window.location.replace('/docs');
      } catch (err) {
        alert('Network error. Please try again.');
      }
    });
  </script>
</body>
</html>
