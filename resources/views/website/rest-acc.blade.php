<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Acc Resident - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <style>
    .admin-dashboard {
      position: relative;
    }

    .admin-dashboard::before {
      content: '';
      position: fixed;
      left: 50%;
      top: 54%;
      width: 460px;
      height: 460px;
      transform: translate(-50%, -50%);
      background: url('{{ asset('img/Barangay Official Logo.png') }}') center/contain no-repeat;
      opacity: .07;
      filter: grayscale(100%) blur(2px);
      pointer-events: none;
      z-index: 0;
    }

    .adm-layout {
      position: relative;
      z-index: 1;
    }

    .reset-email-cell {
      text-align: left;
      font-weight: 600;
      letter-spacing: 0.1px;
      white-space: nowrap;
    }

    .reset-action-cell {
      text-align: center;
      min-width: 320px;
    }

    .reset-actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 8px;
    }

    .reset-date-cell {
      white-space: nowrap;
    }

    .reset-table th,
    .reset-table td {
      vertical-align: middle;
    }

    .reset-table th:nth-child(2),
    .reset-table td:nth-child(2) {
      text-align: left;
    }

    .reset-table th:nth-child(3),
    .reset-table td:nth-child(3) {
      text-align: center;
    }
  </style>
</head>
<body class="admin-dashboard">
  <div class="adm-layout">
    <button class="adm-sidebar-overlay" id="admSidebarOverlay" type="button" aria-label="Close menu"></button>
    <aside class="adm-sidebar">
      <div class="adm-brand">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" />
        <div>
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      <nav class="adm-nav" id="admSidebarNav" aria-label="Admin navigation">
        <a href="./dashs"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a class="active" href="./rest-acc"><span class="ico">🔐</span><span>Acc Resident</span></a>
      </nav>

      <div class="adm-sidebar-footer">
        <button class="adm-logout" type="button" id="adminLogout">
          <span class="ico">⎋</span><span>Logout</span>
        </button>
      </div>
    </aside>

    <main class="adm-main">
      <header class="adm-topbar">
        <button class="adm-menu-toggle" id="admMenuToggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="admSidebarNav">
          <span class="bars" aria-hidden="true"><span></span><span></span><span></span></span>
        </button>
        <div class="role">
          <strong>{{ session('admin_name', 'CHAIRMAN') }}</strong>
          <span>barangay admin</span>
        </div>
      </header>

      <section class="adm-content">
        <div>
          <div class="adm-title">Acc Resident Requests</div>
          <div class="adm-subtitle">Table of resident reset-password email requests</div>
        </div>

        <div class="adm-card" style="padding:1rem;overflow:auto">
          <table class="adm-table reset-table" aria-label="Resident reset requests table">
            <thead>
              <tr>
                <th>#</th>
                <th>Email</th>
                <th>Date Requested</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="resetRows"></tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <div id="mailModal" class="modal-overlay" hidden>
    <div class="modal" style="max-width:440px;">
      <button class="modal-close" id="mailCloseBtn" type="button">✕</button>
      <div class="modal-header">
        <h2>Send Mail</h2>
      </div>
      <div class="modal-body">
        <label style="display:block;margin-bottom:8px;">Email</label>
        <input id="mailToInput" type="email" readonly style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;background:#f8fafc;" />
        <label style="display:block;margin:12px 0 8px;">Message</label>
        <textarea id="mailMessageInput" rows="8" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;resize:vertical;">Hello,

Natanggap namin ang iyong request para i-reset ang iyong password.
Maaari mo nang gamitin ang bagong password sa ibaba para makapag-log in sa iyong account.

Temporary Password: 123456

Siguraduhing palitan agad ang password na ito pagkatapos mong makapasok para mapanatiling secure ang iyong account.</textarea>
        <div style="display:flex;gap:10px;margin-top:12px;">
          <button class="btn" id="mailCancelBtn" type="button" style="flex:1;">Cancel</button>
          <button class="btn primary" id="mailSendBtn" type="button" style="flex:1;">Send Mail</button>
        </div>
        <p id="mailStatus" style="display:none;margin-top:10px;"></p>
      </div>
    </div>
  </div>

  <div id="changePasswordModal" class="modal-overlay" hidden>
    <div class="modal" style="max-width:440px;">
      <button class="modal-close" id="changeCloseBtn" type="button">✕</button>
      <div class="modal-header">
        <h2>Change Password</h2>
      </div>
      <div class="modal-body">
        <label style="display:block;margin-bottom:8px;">Email</label>
        <input id="changeEmailInput" type="email" readonly style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;background:#f8fafc;" />
        <label style="display:block;margin:12px 0 8px;">New Password</label>
        <input id="changePasswordInput" type="password" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;" />
        <label style="display:block;margin:12px 0 8px;">Confirm Password</label>
        <input id="changePasswordConfirmInput" type="password" style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:6px;" />
        <div style="display:flex;gap:10px;margin-top:12px;">
          <button class="btn" id="changeCancelBtn" type="button" style="flex:1;">Cancel</button>
          <button class="btn primary" id="changeSaveBtn" type="button" style="flex:1;">Change Password</button>
        </div>
        <p id="changeStatus" style="display:none;margin-top:10px;"></p>
      </div>
    </div>
  </div>

  <script>
    const FORGOT_REQUESTS_KEY = 'digibarangay_forgot_requests_v1';

    function safeJsonParse(raw, fallback) {
      try {
        const parsed = raw ? JSON.parse(raw) : null;
        return parsed ?? fallback;
      } catch {
        return fallback;
      }
    }

    function loadResetRequests() {
      const forgot = safeJsonParse(localStorage.getItem(FORGOT_REQUESTS_KEY), []);
      if (!Array.isArray(forgot)) return [];
      return forgot
        .filter((item) => String(item?.email || '').trim() !== '')
        .map((item) => ({
          email: String(item.email || '').trim(),
          date: String(item.date || '').trim() || new Date().toLocaleString()
        }));
    }

    function openMailModal(email) {
      document.getElementById('mailToInput').value = String(email || '').trim();
      const mailStatus = document.getElementById('mailStatus');
      mailStatus.style.display = 'none';
      mailStatus.textContent = '';
      const modal = document.getElementById('mailModal');
      modal.hidden = false;
      modal.classList.add('open');
    }

    function openChangePasswordModal(email) {
      document.getElementById('changeEmailInput').value = String(email || '').trim();
      document.getElementById('changePasswordInput').value = '';
      document.getElementById('changePasswordConfirmInput').value = '';
      const changeStatus = document.getElementById('changeStatus');
      changeStatus.style.display = 'none';
      changeStatus.textContent = '';
      const modal = document.getElementById('changePasswordModal');
      modal.hidden = false;
      modal.classList.add('open');
    }

    function closeChangePasswordModal() {
      const modal = document.getElementById('changePasswordModal');
      modal.classList.remove('open');
      modal.hidden = true;
    }

    function closeMailModal() {
      const modal = document.getElementById('mailModal');
      modal.classList.remove('open');
      modal.hidden = true;
    }

    async function sendMailTo(email, messageText) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const payload = {
        recipient: email,
        subject: 'DIGIBARANGAY Password Reset Request',
        message: messageText,
        ref: 'RESET-' + Date.now(),
        name: 'Resident',
        purpose: 'Password Reset',
        reason: 'Forgot Password',
        date: new Date().toLocaleString()
      };

      const response = await fetch('/rest-acc/send-email', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data.message || 'Unable to send mail.');
      }
      return data;
    }

    function renderResetTable() {
      const rows = document.getElementById('resetRows');
      const items = loadResetRequests();

      if (!items.length) {
        rows.innerHTML = '<tr><td colspan="4" style="padding:1rem;color:#6b7280">No reset-password requests found.</td></tr>';
        return;
      }

      rows.innerHTML = items.map((item, idx) => {
        const escapedEmail = String(item.email || '').replace(/"/g, '&quot;');
        return '<tr>'
          + '<td>' + (idx + 1) + '</td>'
          + '<td class="reset-email-cell">' + (item.email || '-') + '</td>'
          + '<td class="reset-date-cell">' + (item.date || '-') + '</td>'
          + '<td class="reset-action-cell">'
          + '<div class="reset-actions">'
          + '<button class="btn-mini approve" data-action="change-password" data-email="' + escapedEmail + '">Change Password</button>'
          + '<button class="btn-mini view" data-action="open-modal" data-email="' + escapedEmail + '">Modal</button>'
          + '<button class="btn-mini email" data-action="send-mail" data-email="' + escapedEmail + '">Send Mail</button>'
          + '</div>'
          + '</td>'
          + '</tr>';
      }).join('');
    }

    document.getElementById('resetRows').addEventListener('click', async (e) => {
      const btn = e.target.closest && e.target.closest('button[data-action]');
      if (!btn) return;

      const action = btn.getAttribute('data-action');
      const email = String(btn.getAttribute('data-email') || '').trim();
      if (!email) return;

      if (action === 'open-modal') {
        openMailModal(email);
        return;
      }

      if (action === 'change-password') {
        openChangePasswordModal(email);
        return;
      }

      if (action === 'send-mail') {
        try {
          await sendMailTo(
            email,
            `Hello,

Natanggap namin ang iyong request para i-reset ang iyong password.
Maaari mo nang gamitin ang bagong password sa ibaba para makapag-log in sa iyong account.

Temporary Password: 123456

Siguraduhing palitan agad ang password na ito pagkatapos mong makapasok para mapanatiling secure ang iyong account.`
          );
          alert('Mail sent successfully.');
        } catch (err) {
          alert(err.message || 'Unable to send mail.');
        }
      }
    });

    document.getElementById('mailCloseBtn').addEventListener('click', closeMailModal);
    document.getElementById('mailCancelBtn').addEventListener('click', closeMailModal);
    document.getElementById('mailModal').addEventListener('click', (e) => {
      if (e.target.id === 'mailModal') closeMailModal();
    });

    document.getElementById('changeCloseBtn').addEventListener('click', closeChangePasswordModal);
    document.getElementById('changeCancelBtn').addEventListener('click', closeChangePasswordModal);
    document.getElementById('changePasswordModal').addEventListener('click', (e) => {
      if (e.target.id === 'changePasswordModal') closeChangePasswordModal();
    });

    document.getElementById('mailSendBtn').addEventListener('click', async () => {
      const email = String(document.getElementById('mailToInput').value || '').trim();
      const messageText = String(document.getElementById('mailMessageInput').value || '').trim();
      const mailStatus = document.getElementById('mailStatus');

      if (!email) {
        mailStatus.style.display = 'block';
        mailStatus.style.color = '#dc2626';
        mailStatus.textContent = 'Missing email.';
        return;
      }

      try {
        await sendMailTo(email, messageText || 'Hello, your reset-password request was received.');
        mailStatus.style.display = 'block';
        mailStatus.style.color = '#15803d';
        mailStatus.textContent = 'Mail sent successfully.';
      } catch (err) {
        mailStatus.style.display = 'block';
        mailStatus.style.color = '#dc2626';
        mailStatus.textContent = err.message || 'Unable to send mail.';
      }
    });

    document.getElementById('changeSaveBtn').addEventListener('click', async () => {
      const email = String(document.getElementById('changeEmailInput').value || '').trim();
      const password = String(document.getElementById('changePasswordInput').value || '').trim();
      const confirmPassword = String(document.getElementById('changePasswordConfirmInput').value || '').trim();
      const changeStatus = document.getElementById('changeStatus');

      if (!email || !password || !confirmPassword) {
        changeStatus.style.display = 'block';
        changeStatus.style.color = '#dc2626';
        changeStatus.textContent = 'Please fill in all fields.';
        return;
      }

      if (password !== confirmPassword) {
        changeStatus.style.display = 'block';
        changeStatus.style.color = '#dc2626';
        changeStatus.textContent = 'Passwords do not match.';
        return;
      }

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const response = await fetch('/rest-acc/change-password', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            email,
            password,
            password_confirmation: confirmPassword
          })
        });

        const data = await response.json().catch(() => ({}));
        if (!response.ok) {
          throw new Error(data.message || 'Unable to change password.');
        }

        changeStatus.style.display = 'block';
        changeStatus.style.color = '#15803d';
        changeStatus.textContent = data.message || 'Password changed successfully.';
      } catch (err) {
        changeStatus.style.display = 'block';
        changeStatus.style.color = '#dc2626';
        changeStatus.textContent = err.message || 'Unable to change password.';
      }
    });

    const admMenuToggle = document.getElementById('admMenuToggle');
    const admSidebarOverlay = document.getElementById('admSidebarOverlay');
    const admNav = document.querySelector('.adm-nav');

    function setMobileMenu(open) {
      document.body.classList.toggle('adm-menu-open', !!open);
      if (admMenuToggle) admMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    if (admMenuToggle) {
      admMenuToggle.addEventListener('click', () => {
        const willOpen = !document.body.classList.contains('adm-menu-open');
        setMobileMenu(willOpen);
      });
    }

    if (admSidebarOverlay) {
      admSidebarOverlay.addEventListener('click', () => setMobileMenu(false));
    }

    if (admNav) {
      admNav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
          if (window.innerWidth <= 720) setMobileMenu(false);
        });
      });
    }

    window.addEventListener('resize', () => {
      if (window.innerWidth > 720) setMobileMenu(false);
    });

    document.getElementById('adminLogout').addEventListener('click', async () => {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      try {
        await fetch('/loginadmin/logout', {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          }
        });
      } catch (err) {
        console.error('Logout error:', err);
      }
      window.location.replace('/');
    });

    renderResetTable();
    window.addEventListener('storage', (event) => {
      if (event.key === FORGOT_REQUESTS_KEY) {
        renderResetTable();
      }
    });
  </script>
</body>
</html>
