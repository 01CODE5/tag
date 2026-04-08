<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - DIGIBARANGAY</title>
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
    <section class="container form-section">
      <h1>Resident Portal Login</h1>
      <form id="loginForm" novalidate>
        <label>Email
          <input name="user" type="email" placeholder="user@example.com" required />
        </label>
        <label>Password
          <input name="password" type="password" required />
        </label>
        <label class="inline"><input type="checkbox" id="remember" /> Remember me</label>
        <div class="form-actions">
          <button class="btn primary" type="submit">LOGIN</button>
          <a class="btn" href="./register">Don't have an account? REGISTER HERE</a>
        </div>
        <p class="muted"><a href="#">Forgot password</a> • <a href="./home">BACK</a></p>
        <p class="muted">Security reminder: Do not share your login credentials.</p>
      </form>
    </section>
  </main>

  <script>
    // Simplified local login for layout preview: skip backend and redirect
    const DASHBOARD_URL = 'docs';
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      // Basic client-side validation only
      const email = String(form.user.value || '').trim();
      const pw = String(form.password.value || '').trim();
      if (!email || !pw) { alert('Please enter email and password.'); return; }

      // honor optional `next` query param if present
      try {
        const params = new URLSearchParams(window.location.search);
        const nextUrl = params.get('next');
        if (nextUrl && nextUrl.endsWith('.html') && nextUrl.startsWith('./')) {
          window.location.href = nextUrl;
        } else {
          window.location.href = DASHBOARD_URL;
        }
      } catch (err) {
        window.location.href = DASHBOARD_URL;
      }
    });
  </script>
</body>
</html>
