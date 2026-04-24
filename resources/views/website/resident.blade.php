<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Residents Record - DIGIBARANGAY</title>
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
        <a class="active" href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a href="./rest-acc"><span class="ico">🔐</span><span>Acc Resident</span></a>
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
          <strong id="topbarUserName">{{ session('admin_name', 'CHAIRMAN') }}</strong>
          <span>Barangay Admin</span>
        </div>
        <div class="top-icons">
          <div style="position:relative;display:inline-block;">
            <button id="notifBellBtn" class="bubble" type="button" title="Notifications" aria-label="Notifications" aria-expanded="false" style="cursor:pointer;position:relative;">
              🔔
              <span id="notifBadge" hidden style="position:absolute;top:-3px;right:-3px;min-width:18px;height:18px;border-radius:999px;background:#ef4444;color:#fff;font-size:.68rem;font-weight:700;line-height:18px;text-align:center;padding:0 4px;">0</span>
            </button>
            <div id="notifPanel" hidden style="position:absolute;right:0;top:120%;width:320px;max-height:360px;overflow:auto;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 14px 34px rgba(2,6,23,.16);z-index:30;">
              <div style="padding:.75rem .9rem;border-bottom:1px solid #eef2f7;font-weight:700;color:#111827;">New Requests</div>
              <div id="notifList" style="padding:.4rem;"></div>
            </div>
          </div>
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

  <div id="notifToastHost" style="position:fixed;top:16px;right:16px;z-index:120;display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>

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
                <th>PDF</th>
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
    const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
    const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
    const GLOBAL_CLEARANCE_TEMPLATE_KEY = 'digibarangay_saved_clearance_template_v1';
    const NOTIF_SEEN_KEY = 'digibarangay_seen_request_refs_v1';
    // Page init / refresh
    let residents = [];
    let knownRefSet = new Set(loadRequests().map((r) => String(r?.ref || '').trim()).filter(Boolean));

    if (!localStorage.getItem(NOTIF_SEEN_KEY)) {
      writeSeenRefs(knownRefSet);
    }

    const notifBellBtn = document.getElementById('notifBellBtn');
    const notifPanel = document.getElementById('notifPanel');

    function markAllCurrentRequestsAsSeen() {
      const requests = loadRequests();
      const seenSet = readSeenRefs();
      requests.forEach((r) => {
        const ref = String(r?.ref || '').trim();
        if (ref) seenSet.add(ref);
      });
      writeSeenRefs(seenSet);
      updateNotifications(requests);
    }

    function detectNewRequestsAndNotify() {
      const requests = loadRequests();
      const nowSet = new Set(requests.map((r) => String(r?.ref || '').trim()).filter(Boolean));
      const newItems = requests.filter((r) => {
        const ref = String(r?.ref || '').trim();
        return ref && !knownRefSet.has(ref);
      });

      if (newItems.length) {
        updateNotifications(requests, { toastNew: true, newItems });
      } else {
        updateNotifications(requests);
      }

      knownRefSet = nowSet;
    }

    if (notifBellBtn && notifPanel) {
      notifBellBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const willOpen = notifPanel.hidden;
        notifPanel.hidden = !willOpen;
        notifBellBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        if (willOpen) {
          markAllCurrentRequestsAsSeen();
        }
      });

      document.addEventListener('click', (e) => {
        if (notifPanel.hidden) return;
        if (notifPanel.contains(e.target) || notifBellBtn.contains(e.target)) return;
        notifPanel.hidden = true;
        notifBellBtn.setAttribute('aria-expanded', 'false');
      });
    }

    function refreshResidentsTable(){
      const allRequests = loadRequests();
      residents = groupResidents(allRequests);
      setStats(allRequests, residents);
      renderResidents(residents);
      updateNotifications(allRequests);
    }

    refreshResidentsTable();

    document.getElementById('q').addEventListener('input', () => renderResidents(residents));
    document.getElementById('hasPending').addEventListener('change', () => renderResidents(residents));

    // If requests are submitted from docs in another tab, refresh automatically.
    window.addEventListener('storage', (e) => {
      if (e.key === REQUESTS_KEY) {
        detectNewRequestsAndNotify();
        refreshResidentsTable();
      }
    });

    // Refresh when tab gains focus.
    window.addEventListener('pageshow', refreshResidentsTable);
    setInterval(detectNewRequestsAndNotify, 5000);

    function safeJsonParse(raw, fallback){
      try{ const v = raw ? JSON.parse(raw) : null; return v ?? fallback; } catch { return fallback; }
    }

    function loadRequests(){
      const arr = safeJsonParse(localStorage.getItem(REQUESTS_KEY), []);
      return Array.isArray(arr) ? arr : [];
    }

    function readGlobalClearanceTemplate() {
      return safeJsonParse(localStorage.getItem(GLOBAL_CLEARANCE_TEMPLATE_KEY), null);
    }

    function readSeenRefs() {
      const data = safeJsonParse(localStorage.getItem(NOTIF_SEEN_KEY), []);
      if (!Array.isArray(data)) return new Set();
      return new Set(data.map((v) => String(v || '').trim()).filter(Boolean));
    }

    function writeSeenRefs(setValue) {
      localStorage.setItem(NOTIF_SEEN_KEY, JSON.stringify(Array.from(setValue)));
    }

    function formatNotifDate(raw) {
      const s = String(raw || '').trim();
      if (!s) return 'Unknown date';
      const d = new Date(s);
      if (Number.isNaN(d.getTime())) return s;
      return d.toLocaleString();
    }

    function renderNotifBadge(count) {
      const badge = document.getElementById('notifBadge');
      if (!badge) return;
      if (!count) {
        badge.hidden = true;
        badge.textContent = '0';
        return;
      }
      badge.hidden = false;
      badge.textContent = String(Math.min(count, 99));
    }

    function renderNotifPanel(items) {
      const list = document.getElementById('notifList');
      if (!list) return;

      if (!items.length) {
        list.innerHTML = '<div style="padding:.85rem;color:#6b7280;font-size:.92rem;">No new requests.</div>';
        return;
      }

      list.innerHTML = items
        .slice()
        .sort((a, b) => String(b.ref || '').localeCompare(String(a.ref || '')))
        .slice(0, 10)
        .map((r) => {
          const name = String(r.name || 'Resident').trim() || 'Resident';
          const ref = String(r.ref || '').trim();
          const date = formatNotifDate(r.dateRequested || r.date);
          return '<div style="padding:.65rem .7rem;border-radius:10px;border:1px solid #eef2f7;background:#f8fafc;margin:.35rem;">'
            + '<div style="font-size:.86rem;font-weight:700;color:#111827;">New request from ' + name + '</div>'
            + '<div style="font-size:.8rem;color:#4b5563;margin-top:.2rem;">Ref: ' + ref + '</div>'
            + '<div style="font-size:.78rem;color:#6b7280;">' + date + '</div>'
            + '</div>';
        })
        .join('');
    }

    function showNotifToast(message) {
      const host = document.getElementById('notifToastHost');
      if (!host) return;
      const item = document.createElement('div');
      item.style.pointerEvents = 'none';
      item.style.background = '#111827';
      item.style.color = '#fff';
      item.style.padding = '.7rem .85rem';
      item.style.borderRadius = '10px';
      item.style.boxShadow = '0 10px 28px rgba(2,6,23,.25)';
      item.style.fontSize = '.9rem';
      item.style.opacity = '0';
      item.style.transform = 'translateY(-8px)';
      item.style.transition = 'all .2s ease';
      item.textContent = message;
      host.appendChild(item);
      requestAnimationFrame(() => {
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
      });
      setTimeout(() => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(-8px)';
        setTimeout(() => item.remove(), 220);
      }, 2800);
    }

    function updateNotifications(requests, options = {}) {
      const seenSet = readSeenRefs();
      const unseen = (requests || []).filter((r) => {
        const ref = String(r?.ref || '').trim();
        return ref && !seenSet.has(ref);
      });
      renderNotifBadge(unseen.length);
      renderNotifPanel(unseen);

      if (options.toastNew && options.newItems && options.newItems.length) {
        const first = options.newItems[0];
        const firstName = String(first?.name || 'resident').trim() || 'resident';
        const count = options.newItems.length;
        showNotifToast(count === 1
          ? ('New request received from ' + firstName)
          : ('You have ' + count + ' new requests.'));
      }
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
    let currentResident = null;

    function openResidentModal(resident){
      currentKey = resident.key;
      currentResident = resident;
      residentTitle.textContent = resident.name;
      residentMeta.textContent = 'Total requests: ' + resident.total + ' • Pending: ' + resident.pending;
      if(!resident.requests.length){
        residentReqRows.innerHTML = '<tr><td colspan="5" style="padding:1rem;color:#6b7280">No requests.</td></tr>';
      } else {
        residentReqRows.innerHTML = resident.requests.map(req => {
          const st = normalize(req.status) || 'pending';
          const badge = st === 'approved'
            ? '<span class="badge approved">Approved</span>'
            : (st === 'rejected' ? '<span class="badge rejected">Rejected</span>' : '<span class="badge pending">Pending</span>');
          const globalTemplate = readGlobalClearanceTemplate();
          const certTypeRaw = String((req.savedTemplate && req.savedTemplate.certificateType) || req.savedCertType || (globalTemplate && globalTemplate.certificateType) || '').trim();
          const certTypeSafe = certTypeRaw
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;');
          const certTypeNote = certTypeSafe
            ? '<div style="font-size:.72rem;color:#4b5563;margin-top:.25rem;line-height:1.2;">' + certTypeSafe + '</div>'
            : '';
          const pdfBadge = (req.pdfSaved || !!globalTemplate)
            ? ('<span class="badge approved">Saved</span>' + certTypeNote)
            : '<span class="badge pending">Not Saved</span>';
          return '<tr>'
            + '<td>' + (req.ref || '') + '</td>'
            + '<td>' + (req.purpose || '—') + '</td>'
            + '<td>' + (req.dateRequested || req.date || '—') + '</td>'
            + '<td>' + badge + '</td>'
            + '<td>' + pdfBadge + '</td>'
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
      const latestReq = currentResident && Array.isArray(currentResident.requests) && currentResident.requests.length
        ? currentResident.requests[0]
        : null;

      if (latestReq) {
        const payload = {
          name: String(currentResident.name || latestReq.name || '').trim(),
          age: latestReq.age ?? '',
          address: String(latestReq.address || '').trim(),
          purpose: String(latestReq.purposeReason || latestReq.purpose || '').trim(),
          date: String(latestReq.dateRequested || latestReq.date || '').trim(),
          ref: String(latestReq.ref || '').trim(),
        };
        try {
          sessionStorage.setItem(CERT_AUTOFILL_KEY, JSON.stringify(payload));
          if (latestReq.savedTemplate && typeof latestReq.savedTemplate === 'object') {
            sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(latestReq.savedTemplate));
          } else {
            const globalTemplate = readGlobalClearanceTemplate();
            if (globalTemplate && typeof globalTemplate === 'object') {
              sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(globalTemplate));
            } else {
              sessionStorage.removeItem(TEMPLATE_OVERRIDE_KEY);
            }
          }
        } catch (err) {
          console.error('Unable to save certificate autofill payload', err);
        }
      }

      window.location.href = '/certificate?t=' + Date.now();
    });

    document.getElementById('rows').addEventListener('click', (e) => {
      const btn = e.target.closest && e.target.closest('button[data-action]');
      if(!btn) return;
      const action = btn.getAttribute('data-action');
      if(action !== 'view') return;
      const key = btn.getAttribute('data-key');
      const res = residents.find(r => r.key === key);
      if(res) openResidentModal(res);
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
        console.error('Logout error', err);
      }

      window.location.replace('/');
    });

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>
</body>
</html>
