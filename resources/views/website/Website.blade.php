<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body class="admin-page">
  <button class="admin-back" type="button" aria-label="Back" id="adminBack">←</button>

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

  <!-- Admin Login Modal (shows when clicking Barangay) -->
  <div id="adminLoginModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="adminLoginTitle">
      <button class="modal-close" id="adminLoginClose" aria-label="Close">✕</button>
      <div class="admin-login-header">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="logo" />
        <h2 id="adminLoginTitle">Admin</h2>
        <p>Login to access the admin dashboard</p>
      </div>
      <div class="admin-login-body">
        <form id="adminLoginForm" novalidate>
          <div class="admin-field">
            <label for="adminEmail">Email Address</label>
            <input id="adminEmail" name="email" type="email" placeholder="Enter your Email Address" required />
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
      const email = document.getElementById('adminEmail');
      if(email) email.focus();
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
      const email = String(document.getElementById('adminEmail').value || '').trim();
      const password = String(document.getElementById('adminPassword').value || '').trim();
      if(!email || !password){
        return;
      }
      // Basic demo gate (can be replaced with real auth later)
      localStorage.setItem('digibarangay_admin_logged_in', 'true');
      localStorage.setItem('digibarangay_admin_email', email);
      closeModal();
      window.location.href = './admin-barangay.html';
    });

    document.getElementById('adminBack').addEventListener('click', () => {
      // Since we're hiding Home for now, send back to admin itself.
      window.location.href = './dash';
    });
  </script>
</body>
</html>