<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>High Admin Dashboard</title>
  <link rel="stylesheet" href="./styles.css" />
  <style>
    body{margin:0;background:#f5f5f5}
    .layout{display:flex;min-height:100vh}
    .sidebar{width:300px;background:#0f5668;color:#fff;padding:20px;box-sizing:border-box;display:flex;flex-direction:column}
    .brand{display:flex;gap:12px;align-items:flex-start;margin-bottom:30px}
    .brand img{height:50px;width:50px}
    .brand-text strong{display:block;font-size:15px;line-height:1.2}
    .brand-text small{display:block;font-size:12px;opacity:0.8}
    .nav{flex:1}
    .nav a{display:flex;gap:12px;align-items:center;padding:12px;color:#fff;text-decoration:none;border-radius:6px;margin-bottom:8px;font-size:14px}
    .nav a.active{background:rgba(0,0,0,0.3)}
    .logout-btn{display:flex;gap:12px;align-items:center;padding:12px;color:#fff;text-decoration:none;border:none;background:none;cursor:pointer;border-top:1px solid rgba(255,255,255,0.2);font-size:14px;width:100%}
    .main{flex:1;display:flex;flex-direction:column}
    .topbar{background:#7fa8b4;color:#fff;padding:15px 30px;display:flex;justify-content:space-between;align-items:center}
    .topbar-title strong{display:block;font-size:15px;font-weight:700}
    .topbar-title span{display:block;font-size:12px;opacity:0.9}
    .top-icons span{margin-left:15px;font-size:18px;cursor:pointer}
    .content{flex:1;padding:40px;overflow-y:auto}
    .card{background:#fff;padding:30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
    table{width:100%;border-collapse:collapse}
    thead tr{background:#0b66c2;color:#fff}
    th{padding:14px;text-align:left;font-weight:600;font-size:14px}
    td{padding:14px;border-bottom:1px solid #eee;font-size:14px}
    .btn{padding:6px 12px;border-radius:4px;border:none;cursor:pointer;font-weight:600;font-size:12px;margin-right:4px}
    .btn-view{background:#6c757d;color:#fff}
    .btn-edit{background:#ffc107;color:#333}
    .btn-approve{background:#28a745;color:#fff}
    .btn-reject{background:#dc3545;color:#fff}
    .btn-change-pw{background:#0b66c2;color:#fff;margin-right:6px}
    .btn-delete{background:#dc3545;color:#fff}
    .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.45);display:none;align-items:center;justify-content:center;z-index:1000}
    .modal-overlay.show{display:flex}
    .modal-box{background:#fff;width:min(92vw,420px);border-radius:10px;box-shadow:0 12px 28px rgba(0,0,0,0.25);padding:20px 18px;text-align:center}
    .modal-box h3{margin:0 0 10px;color:#0f5668}
    .modal-box p{margin:0 0 16px;color:#374151}
    .modal-ok{background:#0b66c2;color:#fff;border:none;border-radius:6px;padding:9px 18px;font-weight:600;cursor:pointer}
    .modal-input{width:100%;box-sizing:border-box;padding:10px;margin-bottom:15px;border-radius:6px;border:1px solid #ccc;display:block}
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY" />
        <div class="brand-text">
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      <nav class="nav">
        <a href="/dash" class="active" style="color:#fff"><span>🏠</span><span>Dashboard</span></a>
        <a href="/barangay" style="color:#fff"><span>👤</span><span>official Account</span></a>
      </nav>

      <button class="logout-btn" id="logoutBtn">
        <span>⎋</span><span>Logout</span>
      </button>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="topbar-title">
          <strong>CHAIRMAN</strong>
          <span>Barangay Administrator</span>
        </div>
      </header>

      <section class="content">
        <div class="card">
          <table id="staffTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="5" style="text-align:center;color:#666">Loading staff accounts...</td></tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <div id="deleteSuccessModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="deleteSuccessTitle">
      <h3 id="deleteSuccessTitle">Account Deleted</h3>
      <p id="deleteSuccessMessage">Account has been deleted successfully.</p>
      <button type="button" class="modal-ok" id="deleteSuccessOk">OK</button>
    </div>
  </div>

  <div id="confirmDeleteModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="confirmDeleteTitle">
      <h3 id="confirmDeleteTitle">Confirm Delete</h3>
      <p id="confirmDeleteMessage">Do you want to delete this account?</p>
      <button class="btn btn-delete" id="confirmYes">Yes</button>
      <button class="btn btn-view" id="confirmNo">No</button>
    </div>
  </div>

  <div id="changePasswordModal" class="modal-overlay" aria-hidden="true">
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="changePasswordTitle">
      <h3 id="changePasswordTitle">Change Password</h3>
      <p id="changePasswordEmail">Enter new password</p>
      <input
        type="password"
        id="newPasswordInput"
        class="modal-input"
        placeholder="Enter new password"
      />
      <button class="btn btn-approve" id="savePasswordBtn">Save</button>
      <button class="btn btn-view" id="cancelPasswordBtn">Cancel</button>
    </div>
  </div>

  <script>
    let selectedDeleteId = null;
    let selectedDeleteName = null;
    let selectedUserId = null;
    let selectedUserEmail = null;

    function showSuccessModal(title, message) {
      const modal = document.getElementById('deleteSuccessModal');
      const titleEl = document.getElementById('deleteSuccessTitle');
      const msg = document.getElementById('deleteSuccessMessage');
      titleEl.textContent = title || 'Success';
      msg.textContent = message || 'Operation completed successfully.';
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideDeleteSuccessModal() {
      const modal = document.getElementById('deleteSuccessModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    document.getElementById('deleteSuccessOk').addEventListener('click', hideDeleteSuccessModal);
    document.getElementById('deleteSuccessModal').addEventListener('click', (e) => {
      if (e.target.id === 'deleteSuccessModal') hideDeleteSuccessModal();
    });

    function showConfirmDeleteModal(id, name) {
      selectedDeleteId = id;
      selectedDeleteName = name;

      document.getElementById('confirmDeleteMessage').textContent = `Do you want to delete ${name}?`;

      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideConfirmDeleteModal() {
      const modal = document.getElementById('confirmDeleteModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    function showChangePasswordModal(id, email) {
      selectedUserId = id;
      selectedUserEmail = email;

      document.getElementById('changePasswordEmail').textContent = `Change password for ${email}`;
      document.getElementById('newPasswordInput').value = '';

      const modal = document.getElementById('changePasswordModal');
      modal.classList.add('show');
      modal.setAttribute('aria-hidden', 'false');
    }

    function hideChangePasswordModal() {
      const modal = document.getElementById('changePasswordModal');
      modal.classList.remove('show');
      modal.setAttribute('aria-hidden', 'true');
    }

    // Load staff accounts from backend
    async function loadStaff() {
      try {
        const res = await fetch('/api/officers', {
          headers: { 'Accept': 'application/json' }
        });
        if (!res.ok) {
          document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#c9302c">Failed to load staff accounts.</td></tr>';
          return;
        }
        const body = await res.json();
        const staff = body.data || [];
        if (staff.length === 0) {
          document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#666">No staff accounts yet.</td></tr>';
          return;
        }
        const rows = staff.map(s => `
          <tr>
            <td>${s.fullname || '-'}</td>
            <td>${s.username || '-'}</td>
            <td>${s.email || '-'}</td>
            <td>${s.contact || '-'}</td>
            <td>
              <button class="btn btn-change-pw" data-id="${s.id}" data-email="${s.email}">Change Password</button>
              <button class="btn btn-delete" data-id="${s.id}" data-name="${s.fullname}">Delete</button>
            </td>
          </tr>
        `).join('');
        document.querySelector('#staffTable tbody').innerHTML = rows;
        
        // Attach event handlers to delete buttons
        document.querySelectorAll('.btn-delete').forEach(btn => {
          btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            showConfirmDeleteModal(id, name);
          });
        });
        
        // Attach event handlers to change password buttons
        document.querySelectorAll('.btn-change-pw').forEach(btn => {
          btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const email = this.getAttribute('data-email');
            showChangePasswordModal(id, email);
          });
        });
      } catch (err) {
        console.error('Load staff error', err);
        document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#c9302c">Network error.</td></tr>';
      }
    }

    // Load on page load
    loadStaff();

    // Logout handler
    document.getElementById('logoutBtn').addEventListener('click', async () => {
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
        console.error('Logout error', err);
      }

      window.location.replace('/loginadmin');
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        window.location.reload();
      }
    });

    document.getElementById('confirmNo').addEventListener('click', hideConfirmDeleteModal);
    document.getElementById('confirmDeleteModal').addEventListener('click', (e) => {
      if (e.target.id === 'confirmDeleteModal') hideConfirmDeleteModal();
    });

    document.getElementById('confirmYes').addEventListener('click', async () => {
      console.log('Delete button clicked, selectedDeleteId:', selectedDeleteId);
      if (!selectedDeleteId) {
        console.log('No selectedDeleteId, returning');
        return;
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      console.log('CSRF Token:', csrfToken);

      try {
        console.log('Making DELETE request to:', `/api/officers/${selectedDeleteId}`);
        const res = await fetch(`/api/officers/${selectedDeleteId}`, {
          method: 'DELETE',
          headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, credentials: 'same-origin'
        });

        console.log('Response status:', res.status);
        console.log('Response ok:', res.ok);

        if (res.ok) {
          console.log('Delete successful, hiding modal and reloading staff');
          hideConfirmDeleteModal();
          showSuccessModal('Account Deleted', `Account for ${selectedDeleteName} has been removed.`);
          loadStaff();
        } else {
          console.log('Delete failed with status:', res.status);
          const errorText = await res.text();
          console.log('Error response:', errorText);
        }
      } catch (err) {
        console.error('Delete error:', err);
      }
    });

    document.getElementById('cancelPasswordBtn').addEventListener('click', hideChangePasswordModal);
    document.getElementById('changePasswordModal').addEventListener('click', (e) => {
      if (e.target.id === 'changePasswordModal') hideChangePasswordModal();
    });

    document.getElementById('savePasswordBtn').addEventListener('click', async () => {
      const newPassword = document.getElementById('newPasswordInput').value;

      if (!newPassword.trim()) {
        alert('Please enter a password');
        return;
      }

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        const res = await fetch(`/api/officers/${selectedUserId}/password`, {
          method: 'PUT',
          headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, credentials: 'same-origin',
          body: JSON.stringify({ password: newPassword })
        });

        if (res.ok) {
          hideChangePasswordModal();
          showSuccessModal('Password Updated', `Password for ${selectedUserEmail} has been changed successfully.`);
          loadStaff();
        }
      } catch (err) {
        console.error('Password update error', err);
      }
    });

  </script>
</body>
</html>


