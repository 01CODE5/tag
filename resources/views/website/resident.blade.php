<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Residents Record - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>
<body class="admin-dashboard">
  <!-- auth gate removed to allow layout preview without login -->

  <div class="adm-layout">
    <aside class="adm-sidebar">
      <div class="adm-brand">
        <img {{ asset('img/logo_zed.png') }} alt="DIGIBARANGAY logo" />
        <div>
          <strong>DIGIBARANGAY</strong>
          <small>Smart Clearance System</small>
        </div>
      </div>

      <nav class="adm-nav" aria-label="Admin navigation">
        <a href="./dashboard"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a class="active" href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a href="./settings"><span class="ico">⚙️</span><span>Setting</span></a>
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
        <div class="adm-title">Residents record</div>
        <div class="adm-subtitle">List of residents who submitted clearance requests</div>

        <div class="adm-stats">
          <div class="stat total">
            <div class="meta"><div class="label">Total Residents</div><div class="value" id="statResidents">0</div></div>
            <div class="dot">#</div>
          </div>
          <div class="stat pending">
            <div class="meta"><div class="label">Residents w/ Pending</div><div class="value" id="statResidentsPending">0</div></div>
            <div class="dot">!</div>
          </div>
          <div class="stat approved">
            <div class="meta"><div class="label">Total Approved Requests</div><div class="value" id="statApproved">0</div></div>
            <div class="dot">✓</div>
          </div>
          <div class="stat rejected">
            <div class="meta"><div class="label">Total Rejected Requests</div><div class="value" id="statRejected">0</div></div>
            <div class="dot">✕</div>
          </div>
        </div>

        <div class="adm-card" style="padding:1rem">
          <div class="adm-toolbar">
            <div class="search" aria-label="Search">
              <span style="opacity:.7">🔎</span>
              <input id="q" type="text" placeholder="Search resident name" />
            </div>
            <select id="hasPending" aria-label="Filter">
              <option value="">All</option>
              <option value="pending">With Pending</option>
              <option value="nopending">No Pending</option>
            </select>
          </div>

          <div class="adm-table-wrap">
            <table class="adm-table" aria-label="Residents table">
              <thead>
                <tr>
                  <th>Resident</th>
                  <th>Total Requests</th>
                  <th>Pending</th>
                  <th>Last Request</th>
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

  <!-- Resident details modal -->
  <div id="residentModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="residentTitle">
      <button class="modal-close" id="residentClose" aria-label="Close">✕</button>
      <div class="modal-header"><h2 id="residentTitle">Resident</h2></div>
      <div class="modal-body">
        <div id="residentMeta" class="muted" style="margin-bottom:.75rem"></div>
        <div class="adm-table-wrap">
          <table class="adm-table" aria-label="Resident requests">
            <thead>
              <tr>
                <th>Ref</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="residentReqRows"></tbody>
          </table>
        </div>
        <div class="form-actions" style="margin-top:1rem;justify-content:flex-end">
          <button class="btn" type="button" id="goDashboard">Open Dashboard</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const REQUESTS_KEY = 'digibarangay_requests';

    function safeJsonParse(raw, fallback){
      try{ const v = raw ? JSON.parse(raw) : null; return v ?? fallback; } catch { return fallback; }
    }

    function loadRequests(){
      const arr = safeJsonParse(localStorage.getItem(REQUESTS_KEY), []);
      return Array.isArray(arr) ? arr : [];
    }

    function normalize(s){ return String(s || '').trim().toLowerCase(); }

    function groupResidents(requests){
      const map = new Map();
      for(const r of requests){
        const name = String(r.name || '').trim();
        if(!name) continue;
        const key = normalize(name);
        const entry = map.get(key) || { name, requests: [] };
        entry.requests.push(r);
        map.set(key, entry);
      }
      // compute stats per resident
      const residents = Array.from(map.values()).map(x => {
        const reqs = x.requests.slice().sort((a,b) => String(b.dateRequested||'').localeCompare(String(a.dateRequested||'')));
        const pending = reqs.filter(r => normalize(r.status) === 'pending').length;
        const last = reqs[0] ? (reqs[0].dateRequested || reqs[0].date || '') : '';
        return { name: x.name, key: normalize(x.name), total: reqs.length, pending, last, requests: reqs };
      });
      // sort by last date desc then name
      residents.sort((a,b) => {
        const d = String(b.last||'').localeCompare(String(a.last||''));
        if(d !== 0) return d;
        return a.name.localeCompare(b.name);
      });
      return residents;
    }

    function setStats(requests, residents){
      const approved = requests.filter(r => normalize(r.status) === 'approved').length;
      const rejected = requests.filter(r => normalize(r.status) === 'rejected').length;
      const residentsPending = residents.filter(r => r.pending > 0).length;

      document.getElementById('statResidents').textContent = String(residents.length);
      document.getElementById('statResidentsPending').textContent = String(residentsPending);
      document.getElementById('statApproved').textContent = String(approved);
      document.getElementById('statRejected').textContent = String(rejected);
    }

    function renderResidents(residents){
      const q = normalize(document.getElementById('q').value);
      const filter = normalize(document.getElementById('hasPending').value);

      const filtered = residents.filter(r => {
        const matchQ = !q || normalize(r.name).includes(q);
        const matchPending = !filter || (filter === 'pending' ? r.pending > 0 : r.pending === 0);
        return matchQ && matchPending;
      });

      const rows = document.getElementById('rows');
      if(!filtered.length){
        rows.innerHTML = '<tr><td colspan="5" style="padding:1rem;color:#6b7280">No residents found.</td></tr>';
        return;
      }

      rows.innerHTML = filtered.map(r => {
        const pendingBadge = r.pending > 0 ? '<span class="badge pending">' + r.pending + ' Pending</span>' : '<span class="badge approved">0 Pending</span>';
        return '<tr>'
          + '<td>' + r.name + '</td>'
          + '<td>' + r.total + '</td>'
          + '<td>' + pendingBadge + '</td>'
          + '<td>' + (r.last || '—') + '</td>'
          + '<td><div class="actions">'
          + '<button class="btn-mini view" data-action="view" data-key="' + r.key + '">View Requests</button>'
          + '</div></td>'
          + '</tr>';
      }).join('');
    }

    // Modal handling
    const residentModal = document.getElementById('residentModal');
    const residentClose = document.getElementById('residentClose');
    const residentTitle = document.getElementById('residentTitle');
    const residentMeta = document.getElementById('residentMeta');
    const residentReqRows = document.getElementById('residentReqRows');
    let currentKey = '';

    function openResidentModal(resident){
      currentKey = resident.key;
      residentTitle.textContent = resident.name;
      residentMeta.textContent = 'Total requests: ' + resident.total + ' • Pending: ' + resident.pending;
      if(!resident.requests.length){
        residentReqRows.innerHTML = '<tr><td colspan="4" style="padding:1rem;color:#6b7280">No requests.</td></tr>';
      } else {
        residentReqRows.innerHTML = resident.requests.map(req => {
          const st = normalize(req.status) || 'pending';
          const badge = st === 'approved'
            ? '<span class="badge approved">Approved</span>'
            : (st === 'rejected' ? '<span class="badge rejected">Rejected</span>' : '<span class="badge pending">Pending</span>');
          return '<tr>'
            + '<td>' + (req.ref || '') + '</td>'
            + '<td>' + (req.purpose || '—') + '</td>'
            + '<td>' + (req.dateRequested || req.date || '—') + '</td>'
            + '<td>' + badge + '</td>'
            + '</tr>';
        }).join('');
      }
      residentModal.hidden = false;
      residentModal.classList.add('open');
    }

    function closeResidentModal(){
      residentModal.hidden = true;
      residentModal.classList.remove('open');
    }

    residentClose.addEventListener('click', closeResidentModal);
    residentModal.addEventListener('click', (e) => { if(e.target === residentModal) closeResidentModal(); });

    document.getElementById('goDashboard').addEventListener('click', () => {
      // Future: pass filter in query params; for now just navigate.
      window.location.href = './admin-barangay.html';
    });

    // Page init
    const allRequests = loadRequests();
    const residents = groupResidents(allRequests);
    setStats(allRequests, residents);
    renderResidents(residents);

    document.getElementById('q').addEventListener('input', () => renderResidents(residents));
    document.getElementById('hasPending').addEventListener('change', () => renderResidents(residents));

    document.getElementById('rows').addEventListener('click', (e) => {
      const btn = e.target.closest && e.target.closest('button[data-action]');
      if(!btn) return;
      const action = btn.getAttribute('data-action');
      if(action !== 'view') return;
      const key = btn.getAttribute('data-key');
      const res = residents.find(r => r.key === key);
      if(res) openResidentModal(res);
    });

    document.getElementById('adminLogout').addEventListener('click', () => {
      localStorage.removeItem('digibarangay_admin_logged_in');
      localStorage.removeItem('digibarangay_admin_email');
      window.location.href = './admin.html';
    });
  </script>
</body>
</html>
