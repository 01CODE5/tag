<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
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
  </style>
</head>
<body>
  <div class="layout">
    <aside class="sidebar">
      <div class="brand">
        <img src="./logo_zed.png" alt="DIGIBARANGAY" />
        <div class="brand-text">
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      <nav class="nav">
        <a href="/dash" class="active" style="color:#fff"><span>🏠</span><span>Dashboard</span></a>
        <a href="/barangay" style="color:#fff"><span>👤</span><span>official Account</span></a>
      </nav>

      <button class="logout-btn">
        <span>⎋</span><span>Logout</span>
      </button>
    </aside>

    <main class="main">
      <header class="topbar">
        <div class="topbar-title">
          <strong>CHAIRMAN</strong>
          <span>Barangay Administrator</span>
        </div>
        <div class="top-icons">
          <span>👤</span>
          <span>🔔</span>
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

  <script>
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
            <td><span style="color:#666">Saved</span></td>
          </tr>
        `).join('');
        document.querySelector('#staffTable tbody').innerHTML = rows;
      } catch (err) {
        console.error('Load staff error', err);
        document.querySelector('#staffTable tbody').innerHTML = '<tr><td colspan="5" style="text-align:center;color:#c9302c">Network error.</td></tr>';
      }
    }

    // Load on page load
    loadStaff();
  </script>
</body>
</html>
