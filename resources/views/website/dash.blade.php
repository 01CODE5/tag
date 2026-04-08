<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Barangay Admin - DIGIBARANGAY</title>
 <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body class="admin-dashboard">
  <!-- auth gate removed to allow layout preview without login -->

  <div class="adm-layout">
    <aside class="adm-sidebar">
      <div class="adm-brand">
        <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" />
        <div>
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      <nav class="adm-nav" aria-label="Admin navigation">
        <a class="active" href="./dashboard"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a href="./settings><span class="ico">⚙️</span><span>Setting</span></a>
      </nav>

      <div class="adm-sidebar-footer">
        <button class="adm-logout" type="button" id="adminLogout">
          <span class="ico">⎋</span><span>Logout</span>
        </button>
      </div>
    </aside>

    <main class="adm-main">
      <header class="adm-topbar">
        <div class="role">
          <strong>CHAIRMAN</strong>
          <span>Barangay Administrator</span>
        </div>
        <div class="top-icons">
          <span class="bubble" title="Profile">👤</span>
          <span class="bubble" title="Notifications">🔔</span>
        </div>
      </header>

      <section class="adm-content">
        <div>
          <div class="adm-title">Requests Management</div>
          <div class="adm-subtitle">Manage resident clearance applications</div>
        </div>

        <div class="adm-stats">
          <div class="stat total">
            <div class="meta"><div class="label">Total</div><div class="value" id="statTotal">0</div></div>
            <div class="dot">#</div>
          </div>
          <div class="stat pending">
            <div class="meta"><div class="label">Pending</div><div class="value" id="statPending">0</div></div>
            <div class="dot">!</div>
          </div>
          <div class="stat approved">
            <div class="meta"><div class="label">Approved</div><div class="value" id="statApproved">0</div></div>
            <div class="dot">✓</div>
          </div>
          <div class="stat rejected">
            <div class="meta"><div class="label">Rejected</div><div class="value" id="statRejected">0</div></div>
            <div class="dot">✕</div>
          </div>
        </div>

        <div class="adm-card" style="padding:1rem">
          <div class="adm-toolbar">
            <div class="search" aria-label="Search">
              <span style="opacity:.7">🔎</span>
              <input id="q" type="text" placeholder="Search name / purpose" />
            </div>
            <select id="statusFilter" aria-label="Status filter">
              <option value="">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>

          <div class="adm-table-wrap">
            <table class="adm-table" aria-label="Requests table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Purpose</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="rows"></tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>

  <script>
    const STORAGE_KEY = 'digibarangay_requests';

    function loadRequests() {
      try {
        const raw = localStorage.getItem(STORAGE_KEY);
        const arr = raw ? JSON.parse(raw) : [];
        return Array.isArray(arr) ? arr : [];
      } catch {
        return [];
      }
    }

    function statusBadge(status) {
      const s = String(status || '').toLowerCase();
      if (s === 'approved') return '<span class="badge approved">Approved</span>';
      if (s === 'rejected') return '<span class="badge rejected">Rejected</span>';
      return '<span class="badge pending">Pending</span>';
    }

    function normalizeText(v) {
      return String(v || '').trim().toLowerCase();
    }

    function calcStats(requests) {
      const total = requests.length;
      const pending = requests.filter(r => normalizeText(r.status) === 'pending').length;
      const approved = requests.filter(r => normalizeText(r.status) === 'approved').length;
      const rejected = requests.filter(r => normalizeText(r.status) === 'rejected').length;
      document.getElementById('statTotal').textContent = String(total);
      document.getElementById('statPending').textContent = String(pending);
      document.getElementById('statApproved').textContent = String(approved);
      document.getElementById('statRejected').textContent = String(rejected);
    }

    function renderTable(requests) {
      const q = normalizeText(document.getElementById('q').value);
      const status = normalizeText(document.getElementById('statusFilter').value);

      const filtered = requests.filter(r => {
        const name = normalizeText(r.name);
        const purpose = normalizeText(r.purpose);
        const matchQ = !q || name.includes(q) || purpose.includes(q);
        const matchStatus = !status || normalizeText(r.status) === status;
        return matchQ && matchStatus;
      });

      const rows = document.getElementById('rows');
      if (!filtered.length) {
        rows.innerHTML = '<tr><td colspan="5" style="padding:1rem;color:#6b7280">No requests found.</td></tr>';
        return;
      }

      rows.innerHTML = filtered.map((r, idx) => {
        const ref = r.ref || String(idx + 1);
        const name = r.name || '—';
        const purpose = r.purpose || '—';
        const date = r.dateRequested || r.date || '—';
        const st = normalizeText(r.status) || 'pending';

        const actions = st === 'pending'
          ? '<button class="btn-mini view" data-action="view" data-ref="' + ref + '">View</button> '
            + '<button class="btn-mini edit" data-action="edit" data-ref="' + ref + '">Edit</button> '
            + '<button class="btn-mini approve" data-action="approve" data-ref="' + ref + '">Approve</button> '
            + '<button class="btn-mini reject" data-action="reject" data-ref="' + ref + '">Reject</button>'
          : '<button class="btn-mini view" data-action="view" data-ref="' + ref + '">View</button> '
            + '<button class="btn-mini edit" data-action="edit" data-ref="' + ref + '">Edit</button> '
            + '<button class="btn-mini qr" data-action="qr" data-ref="' + ref + '">QR</button> '
            + '<button class="btn-mini cert" data-action="cert" data-ref="' + ref + '">Cert</button>';

        return '<tr>'
          + '<td>' + name + '</td>'
          + '<td>' + purpose + '</td>'
          + '<td>' + date + '</td>'
          + '<td>' + statusBadge(st) + '</td>'
          + '<td><div class="actions">' + actions + '</div></td>'
          + '</tr>';
      }).join('');
    }

    function updateStatusByRef(requests, ref, nextStatus) {
      const idx = requests.findIndex(r => String(r.ref) === String(ref));
      if (idx === -1) return false;
      requests[idx].status = nextStatus;
      localStorage.setItem(STORAGE_KEY, JSON.stringify(requests));
      return true;
    }

    const requests = loadRequests();
    calcStats(requests);
    renderTable(requests);

    document.getElementById('q').addEventListener('input', () => renderTable(loadRequests()));
    document.getElementById('statusFilter').addEventListener('change', () => renderTable(loadRequests()));

    document.getElementById('rows').addEventListener('click', (e) => {
      const btn = e.target.closest && e.target.closest('button[data-action]');
      if (!btn) return;
      const action = btn.getAttribute('data-action');
      const ref = btn.getAttribute('data-ref');

      if (action === 'approve') {
        const all = loadRequests();
        updateStatusByRef(all, ref, 'approved');
        calcStats(loadRequests());
        renderTable(loadRequests());
        return;
      }
      if (action === 'reject') {
        const all = loadRequests();
        updateStatusByRef(all, ref, 'rejected');
        calcStats(loadRequests());
        renderTable(loadRequests());
        return;
      }

      // Placeholder actions for now
      alert(action.toUpperCase() + ' (ref=' + ref + ')');
    });

    document.getElementById('adminLogout').addEventListener('click', async () => {
      const token = localStorage.getItem('authToken');
      
      try {
        // Call backend logout endpoint
        await fetch('http://localhost:8000/api/auth/logout', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
          }
        });
      } catch (err) {
        console.error('Logout error:', err);
      }
      
      // Clear all auth data
      localStorage.removeItem('authToken');
      localStorage.removeItem('digibarangay_admin_logged_in');
      localStorage.removeItem('digibarangay_admin_email');
      window.location.href = './admin.html';
    });
  </script>
</body>
</html>
