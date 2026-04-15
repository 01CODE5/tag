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
        <p class="muted"><a href="#">Forgot password</a></p>
        <p class="muted">Security reminder: Do not share your login credentials.</p>
      </form>
    </section>
  </main>

  <script>
    const form = document.getElementById('loginForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

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
