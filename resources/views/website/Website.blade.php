<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Officer - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body class="admin-page">
  <main class="admin-landing">
    <div class="admin-card">
      <div class="admin-logo-wrap">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="admin-logo" />
      </div>
      <p class="admin-title">Hi, Ka DIGIBARANGAY!</p>
      <p class="admin-sub"><span class="down">↓</span>Tap you destination.</p>

      <div class="admin-actions">
        <button class="admin-action-btn residents" type="button" id="goResidents">Residents</button>
        <button class="admin-action-btn barangay" type="button" id="goBarangay">Barangay</button>
      </div>

      <div class="admin-footer">
        Sa paggamit ng serbisyong ito, kinikilala at sinasang-ayonan mo ang mga
        <a href="./terms.html">Terms &amp; Conditions</a> at <a href="./terms.html">Data Privacy Policy</a> ng
        DIGIBARANGAY Smart Clearance System.
      </div>
    </div>
  </main>

  <!-- Officer Login Modal (shows when clicking Barangay) -->
  <div id="adminLoginModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="adminLoginTitle">
      <button class="modal-close" id="adminLoginClose" aria-label="Close">✕</button>
      <div class="admin-login-header">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="logo" />
        <h2 id="adminLoginTitle">Officer</h2>
        <p>Login to access the officer dashboard</p>
      </div>
      <div class="admin-login-body">
        <form action="{{ route('loginadmin.submit') }}" method="POST" id="adminLoginForm" novalidate>
          @csrf
          <div class="admin-field">
            <label for="adminLogin">Email or Username</label>
            <input id="adminLogin" name="login" type="text" placeholder="Enter your Email or Username" required />
          </div>
          <div class="admin-field">
            <label for="adminPassword">Password</label>
            <input id="adminPassword" name="password" type="password" placeholder="Enter your Password" required />
          </div>
          <button class="admin-login-btn" type="submit">Login</button>
          <div class="admin-login-note">For security purpose, please do not share you login credentials</div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById('adminLoginModal');

    function openModal(){
      modal.hidden = false;
      modal.classList.add('open');
      const login = document.getElementById('adminLogin');
      if(login) login.focus();
    }
    function closeModal(){
      modal.classList.remove('open');
      modal.hidden = true;
    }

    document.getElementById('goResidents').addEventListener('click', () => {
      // Residents -> go to the resident home page
      window.location.href = './home';
    });

    // Barangay -> show Admin Login modal (like screenshot)
    document.getElementById('goBarangay').addEventListener('click', () => {
      openModal();
    });

    document.getElementById('adminLoginClose').addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
      if(e.target === modal) closeModal();
    });

    document.getElementById('adminLoginForm').addEventListener('submit', (e) => {
      e.preventDefault();
      const form = e.currentTarget;
      const login = String(document.getElementById('adminLogin').value || '').trim();
      const password = String(document.getElementById('adminPassword').value || '').trim();
      const csrf = form.querySelector('input[name="_token"]')?.value || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      if(!login || !password){
        alert('Please enter username/email and password.');
        return;
      }

      fetch(form.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ login, email: login, password })
      })
      .then(async (res) => {
        const body = await res.json().catch(() => ({}));
        if (!res.ok) {
          throw new Error(body.message || 'Login failed.');
        }
        closeModal();
        window.location.replace('/dashs');
      })
      .catch((err) => {
        alert(err.message || 'Login failed.');
      });
    });

  </script>
</body>
</html>