<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title>Barangay Admin - DIGIBARANGAY</title>
 <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
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
        <a class="active" href="./dashs"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
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
          <span>barangay admin</span>
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
              <input id="q" type="text" placeholder="Search name / address / clearance / reason" />
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
                  <th>Email</th>
                  <th>Address</th>
                  <th>Age</th>
                  <th>Purpose</th>
                  <th>Reason</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>PDF</th>
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

<div id="emailModal" class="modal-overlay" hidden>
  <div class="modal" style="max-width:400px;">
    
    <button class="modal-close" id="emailClose">✕</button>

    <div class="modal-header">
      <h2>Send Email</h2>
    </div>

    <div class="modal-body">
      <input type="email" id="emailInput" placeholder="Enter email address"
        style="width:100%;padding:10px;margin-top:10px;margin-bottom:15px;border-radius:6px;border:1px solid #ccc;text-align:center;">

      <div style="display:flex;gap:10px;">
        <button class="btn primary" id="emailSendBtn" style="flex:1;">Send</button>
        <button class="btn" id="emailCancelBtn" style="flex:1;">Cancel</button>
      </div>
    </div>

  </div>
</div>

<div id="deleteModal" class="modal-overlay" hidden>
  <div class="modal" style="max-width:400px;">
    
    <div class="modal-header">
      <h2>Confirm Delete</h2>
    </div>

    <div class="modal-body" style="text-align:center;">
      <p id="deleteMessage">Are you sure you want to delete this?</p>

      <div style="display:flex;gap:10px;margin-top:15px;">
        <button class="btn primary" id="deleteOkBtn" style="flex:1;">OK</button>
        <button class="btn" id="deleteCancelBtn" style="flex:1;">Cancel</button>
      </div>
    </div>

  </div>
</div>

<div id="notifToastHost" style="position:fixed;top:16px;right:16px;z-index:120;display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>

  <script>
    const STORAGE_KEY = 'digibarangay_requests';
    const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
    const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
    const GLOBAL_CLEARANCE_TEMPLATE_KEY = 'digibarangay_saved_clearance_template_v1';
    const EMAIL_BOOK_KEY = 'digibarangay_request_email_book_v1';
    const NOTIF_SEEN_KEY = 'digibarangay_seen_request_refs_v1';

    function readGlobalClearanceTemplate() {
      return safeJsonParse(localStorage.getItem(GLOBAL_CLEARANCE_TEMPLATE_KEY), null);
    }

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

    function looksLikeEmail(value) {
      const s = String(value || '').trim();
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s);
    }

    function getRequestByRef(ref) {
      return loadRequests().find(r => String(r.ref) === String(ref)) || null;
    }

    function requestOwnerKey(req) {
      return String(req?.ownerKey || req?.owner_key || req?.userKey || '').trim().toLowerCase();
    }

    function requestNameKey(req) {
      return String(req?.name || '').trim().toLowerCase();
    }

    function readEmailBook() {
      return safeJsonParse(localStorage.getItem(EMAIL_BOOK_KEY), {});
    }

    function writeEmailBook(book) {
      localStorage.setItem(EMAIL_BOOK_KEY, JSON.stringify(book || {}));
    }

    function getEmailFromBook(req) {
      const book = readEmailBook();
      const keys = [
        'ref:' + String(req?.ref || '').trim().toLowerCase(),
        'owner:' + requestOwnerKey(req),
        'name:' + requestNameKey(req),
      ];
      for (const k of keys) {
        if (!k || k.endsWith(':')) continue;
        const value = String(book[k] || '').trim();
        if (looksLikeEmail(value)) return value;
      }
      return '';
    }

    function saveEmailToBook(req, email) {
      const normalizedEmail = String(email || '').trim();
      if (!looksLikeEmail(normalizedEmail)) return;

      const book = readEmailBook();
      const refKey = String(req?.ref || '').trim().toLowerCase();
      const ownerKey = requestOwnerKey(req);
      const nameKey = requestNameKey(req);
      if (refKey) book['ref:' + refKey] = normalizedEmail;
      if (ownerKey) book['owner:' + ownerKey] = normalizedEmail;
      if (nameKey) book['name:' + nameKey] = normalizedEmail;
      writeEmailBook(book);
    }

    function extractEmailDeep(obj) {
      if (!obj || typeof obj !== 'object') return '';
      const queue = [obj];
      const visited = new Set();
      let seen = 0;
      while (queue.length && seen < 120) {
        const current = queue.shift();
        if (!current || typeof current !== 'object') continue;
        if (visited.has(current)) continue;
        visited.add(current);
        seen += 1;
        for (const value of Object.values(current)) {
          if (typeof value === 'string') {
            const s = value.trim();
            if (looksLikeEmail(s)) return s;
          } else if (value && typeof value === 'object') {
            queue.push(value);
          }
        }
      }
      return '';
    }

    function safeJsonParse(raw, fallback) {
      try {
        const parsed = raw ? JSON.parse(raw) : null;
        return parsed ?? fallback;
      } catch {
        return fallback;
      }
    }

    function readKnownProfiles() {
      const profiles = [];

      // Scan all localStorage JSON blobs so we can match even if resident data
      // is stored under a different key name.
      Object.keys(localStorage).forEach((key) => {
        const value = safeJsonParse(localStorage.getItem(key), null);
        if (!value) return;
        if (Array.isArray(value)) {
          value.forEach((item) => { if (item && typeof item === 'object') profiles.push(item); });
          return;
        }
        if (typeof value === 'object') profiles.push(value);
      });
      return profiles;
    }

    function getRecipientEmail(req) {
      if (!req || typeof req !== 'object') return '';

      const fromBook = getEmailFromBook(req);
      if (fromBook) return fromBook;

      const candidates = [
        req.email,
        req.userEmail,
        req.residentEmail,
        req.resident_email,
        req.applicantEmail,
        req.applicant_email,
        req.contact,
        req.ownerKey,
        req.owner_key,
        req.userKey,
      ];
      for (const raw of candidates) {
        const email = String(raw || '').trim();
        if (looksLikeEmail(email)) return email;
      }

      const deepEmail = extractEmailDeep(req);
      if (deepEmail) return deepEmail;

      const reqOwner = String(req.ownerKey || req.owner_key || req.userKey || '').trim().toLowerCase();
      const reqName = String(req.name || '').trim().toLowerCase();
      const profiles = readKnownProfiles();
      for (const p of profiles) {
        const profileEmail = String(p.email || p.userEmail || '').trim();
        if (!looksLikeEmail(profileEmail)) continue;

        const profileOwner = String(p.user_key || p.userKey || p.id || p.username || p.fullname || p.name || '').trim().toLowerCase();
        const profileName = String(p.fullname || p.name || p.username || '').trim().toLowerCase();

        if ((reqOwner && profileOwner && reqOwner === profileOwner) || (reqName && profileName && reqName === profileName)) {
          return profileEmail;
        }
      }

      // Fallback: look for another request from the same person that already has an email.
      const ownerKey = requestOwnerKey(req);
      const nameKey = requestNameKey(req);
      const related = loadRequests();
      for (const item of related) {
        if (!item || typeof item !== 'object') continue;
        const sameOwner = ownerKey && requestOwnerKey(item) && ownerKey === requestOwnerKey(item);
        const sameName = nameKey && requestNameKey(item) && nameKey === requestNameKey(item);
        if (!sameOwner && !sameName) continue;

        const relatedCandidates = [
          item.email,
          item.userEmail,
          item.residentEmail,
          item.resident_email,
          item.applicantEmail,
          item.applicant_email,
          item.contact,
        ];
        for (const raw of relatedCandidates) {
          const email = String(raw || '').trim();
          if (looksLikeEmail(email)) return email;
        }

        const deepRelatedEmail = extractEmailDeep(item);
        if (deepRelatedEmail) return deepRelatedEmail;
      }
      return '';
    }

    function persistRequestEmail(ref, email) {
      const normalizedEmail = String(email || '').trim();
      if (!looksLikeEmail(normalizedEmail)) return;

      const all = loadRequests();
      const target = all.find((r) => String(r.ref) === String(ref));
      if (!target) return;

      target.email = normalizedEmail;
      if (!target.userEmail) target.userEmail = normalizedEmail;
      localStorage.setItem(STORAGE_KEY, JSON.stringify(all));
      saveEmailToBook(target, normalizedEmail);
    }

    function readSeenRefs() {
      const data = safeJsonParse(localStorage.getItem(NOTIF_SEEN_KEY), []);
      if (!Array.isArray(data)) return new Set();
      return new Set(data.map((v) => String(v || '').trim()).filter(Boolean));
    }

    function writeSeenRefs(setValue) {
      localStorage.setItem(NOTIF_SEEN_KEY, JSON.stringify(Array.from(setValue)));
    }

    function getUnseenRequests(requests, seenSet) {
      return (requests || []).filter((r) => {
        const ref = String(r?.ref || '').trim();
        return ref && !seenSet.has(ref);
      });
    }

    function formatNotifDate(raw) {
      const s = String(raw || '').trim();
      if (!s) return 'Unknown date';
      const d = new Date(s);
      if (Number.isNaN(d.getTime())) return s;
      return d.toLocaleString();
    }

    function renderNotifPanel(items) {
      const list = document.getElementById('notifList');
      if (!list) return;

      if (!items.length) {
        list.innerHTML = '<div style="padding:.85rem;color:#6b7280;font-size:.92rem;">No new requests.</div>';
        return;
      }

      const html = items
        .slice()
        .sort((a, b) => String(b.ref || '').localeCompare(String(a.ref || '')))
        .slice(0, 10)
        .map((r) => {
          const name = String(r.name || 'Resident').trim() || 'Resident';
          const date = formatNotifDate(r.dateRequested || r.date);
          const ref = String(r.ref || '').trim();
          return '<div style="padding:.65rem .7rem;border-radius:10px;border:1px solid #eef2f7;background:#f8fafc;margin:.35rem;">'
            + '<div style="font-size:.86rem;font-weight:700;color:#111827;">New request from ' + name + '</div>'
            + '<div style="font-size:.8rem;color:#4b5563;margin-top:.2rem;">Ref: ' + ref + '</div>'
            + '<div style="font-size:.78rem;color:#6b7280;">' + date + '</div>'
            + '</div>';
        })
        .join('');

      list.innerHTML = html;
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
      const unseen = getUnseenRequests(requests, seenSet);
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

    const emailModal = document.getElementById('emailModal');
    const emailInput = document.getElementById('emailInput');
    const emailSendBtn = document.getElementById('emailSendBtn');
    const emailCancelBtn = document.getElementById('emailCancelBtn');
    const emailCloseBtn = document.getElementById('emailClose');
    const deleteModal = document.getElementById('deleteModal');
    const deleteOkBtn = document.getElementById('deleteOkBtn');
    const deleteCancelBtn = document.getElementById('deleteCancelBtn');
    const notifBellBtn = document.getElementById('notifBellBtn');
    const notifPanel = document.getElementById('notifPanel');
    let selectedEmailRef = null;
    let selectedDeleteRef = null;
    let knownRefSet = new Set();

    function openEmailModal(ref) {
      selectedEmailRef = ref;
      const req = getRequestByRef(ref);
      const preset = req ? getRecipientEmail(req) : '';
      emailInput.value = preset || '';
      emailModal.hidden = false;
      emailModal.classList.add('open');
      emailInput.focus();
    }

    function closeEmailModal() {
      selectedEmailRef = null;
      emailModal.classList.remove('open');
      emailModal.hidden = true;
    }

    function openDeleteModal(ref) {
      selectedDeleteRef = ref;
      deleteModal.hidden = false;
      deleteModal.classList.add('open');
    }

    function closeDeleteModal() {
      selectedDeleteRef = null;
      deleteModal.classList.remove('open');
      deleteModal.hidden = true;
    }

    async function sendRequestByEmail(ref, overrideEmail) {
      const req = getRequestByRef(ref);
      if (!req) {
        alert('Request not found.');
        return;
      }

      const recipient = String(overrideEmail || getRecipientEmail(req) || '').trim();
      if (!recipient || !looksLikeEmail(recipient)) {
        alert('Please enter a valid email address.');
        return;
      }

      persistRequestEmail(ref, recipient);

      const payload = {
        recipient,
        subject: 'Barangay Request Update - ' + String(req.ref || '').trim(),
        message:
          'Hello ' + String(req.name || 'Resident').trim() + '\n\n'
          + 'Your barangay request details:\n'
          + 'Reference: ' + String(req.ref || '').trim() + '\n'
          + 'Status: ' + String(req.status || 'pending').trim() + '\n'
          + 'Purpose: ' + String(req.purpose || '').trim() + '\n'
          + 'Reason: ' + String(req.purposeReason || '').trim() + '\n'
          + 'Date Requested: ' + String(req.dateRequested || req.date || '').trim() + '\n\n'
          + 'Thank you.\n'
          + 'Barangay Admin',
        ref: String(req.ref || '').trim(),
        name: String(req.name || '').trim(),
        purpose: String(req.purpose || '').trim(),
        reason: String(req.purposeReason || '').trim(),
        date: String(req.dateRequested || req.date || '').trim(),
      };

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

      try {
        const response = await fetch('/dashs/send-email', {
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
          throw new Error(data.message || 'Unable to send email.');
        }

        closeEmailModal();
        alert(data.message || 'Email sent successfully.');
      } catch (error) {
        console.error('Email send error:', error);
        alert(error.message || 'Unable to send email.');
      }
    }

    if (emailCancelBtn) emailCancelBtn.addEventListener('click', closeEmailModal);
    if (emailCloseBtn) emailCloseBtn.addEventListener('click', closeEmailModal);
    if (emailModal) {
      emailModal.addEventListener('click', (event) => {
        if (event.target === emailModal) closeEmailModal();
      });
    }
    if (emailSendBtn) {
      emailSendBtn.addEventListener('click', () => {
        const email = String(emailInput.value || '').trim();
        sendRequestByEmail(selectedEmailRef, email);
      });
    }

    if (deleteCancelBtn) deleteCancelBtn.addEventListener('click', closeDeleteModal);
    if (deleteModal) {
      deleteModal.addEventListener('click', (event) => {
        if (event.target === deleteModal) closeDeleteModal();
      });
    }
    if (deleteOkBtn) {
      deleteOkBtn.addEventListener('click', () => {
        if (!selectedDeleteRef) return;

        const next = loadRequests().filter(r => String(r.ref) !== String(selectedDeleteRef));
        localStorage.setItem(STORAGE_KEY, JSON.stringify(next));

        calcStats(next);
        renderTable(next);
        updateNotifications(next);
        closeDeleteModal();
      });
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

    function renderTable(requests) {
      const q = normalizeText(document.getElementById('q').value);
      const status = normalizeText(document.getElementById('statusFilter').value);

      const filtered = requests.filter(r => {
        const name = normalizeText(r.name);
        const email = normalizeText(getRecipientEmail(r));
        const address = normalizeText(r.address);
        const age = normalizeText(r.age);
        const purpose = normalizeText(r.purpose);
        const reason = normalizeText(r.purposeReason);
        const matchQ = !q || name.includes(q) || email.includes(q) || address.includes(q) || age.includes(q) || purpose.includes(q) || reason.includes(q);
        const matchStatus = !status || normalizeText(r.status) === status;
        return matchQ && matchStatus;
      });

      const rows = document.getElementById('rows');
      if (!filtered.length) {
        rows.innerHTML = '<tr><td colspan="10" style="padding:1rem;color:#6b7280">No requests found.</td></tr>';
        return;
      }

      rows.innerHTML = filtered.map((r, idx) => {
        const ref = r.ref || String(idx + 1);
        const name = r.name || '—';
        const email = getRecipientEmail(r) || '—';
        const address = String(r.address || '—').trim() || '—';
        const age = String(r.age ?? '').trim() || '—';
        const purpose = r.purpose || '—';
        const reason = r.purposeReason || '—';
        const date = r.dateRequested || r.date || '—';
        const st = normalizeText(r.status) || 'pending';
        const globalTemplate = readGlobalClearanceTemplate();
        const pdfSaved = !!r.pdfSaved || !!globalTemplate;
        const certTypeRaw = String((r.savedTemplate && r.savedTemplate.certificateType) || r.savedCertType || (globalTemplate && globalTemplate.certificateType) || '').trim();
        const certTypeSafe = certTypeRaw
          .replaceAll('&', '&amp;')
          .replaceAll('<', '&lt;')
          .replaceAll('>', '&gt;');
        const certTypeNote = certTypeSafe
          ? '<div style="font-size:.72rem;color:#4b5563;margin-top:.25rem;line-height:1.2;">' + certTypeSafe + '</div>'
          : '';
        const pdfBadge = pdfSaved
          ? ('<span class="badge approved">Saved</span>' + certTypeNote)
          : '<span class="badge pending">Not Saved</span>';

        const actions = st === 'pending'
          ? '<button class="btn-mini view" data-action="view" data-ref="' + ref + '">View</button> '
            + '<button class="btn-mini edit" data-action="edit" data-ref="' + ref + '">Edit</button> '
            + '<button class="btn-mini approve" data-action="approve" data-ref="' + ref + '">Approve</button> '
            + '<button class="btn-mini reject" data-action="reject" data-ref="' + ref + '">Reject</button> '
            + '<button class="btn-mini delete" data-action="delete" data-ref="' + ref + '">Delete</button>'
          : '<button class="btn-mini view" data-action="view" data-ref="' + ref + '">View</button> '
            + '<button class="btn-mini edit" data-action="edit" data-ref="' + ref + '">Edit</button> '
            + '<button class="btn-mini email" data-action="send-email" data-ref="' + ref + '">Send Email</button> '
            + '<button class="btn-mini cert" data-action="cert" data-ref="' + ref + '">Cert</button> '
            + '<button class="btn-mini delete" data-action="delete" data-ref="' + ref + '">Delete</button>';

        return '<tr>'
          + '<td>' + name + '</td>'
          + '<td>' + email + '</td>'
          + '<td>' + address + '</td>'
          + '<td>' + age + '</td>'
          + '<td>' + purpose + '</td>'
          + '<td>' + reason + '</td>'
          + '<td>' + date + '</td>'
          + '<td>' + statusBadge(st) + '</td>'
          + '<td>' + pdfBadge + '</td>'
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

    function openCertificateWithRequest(ref) {
      const all = loadRequests();
      const req = all.find(r => String(r.ref) === String(ref));
      if (!req) {
        alert('Request not found.');
        return;
      }

      const payload = {
        name: String(req.name || '').trim(),
        age: String(req.age ?? '').trim(),
        address: String(req.address || '').trim(),
        purpose: String(req.purposeReason || req.purpose || '').trim(),
        date: String(req.dateRequested || req.date || '').trim(),
        ref: String(req.ref || '').trim(),
      };

      try {
        sessionStorage.setItem(CERT_AUTOFILL_KEY, JSON.stringify(payload));
        if (req && req.savedTemplate && typeof req.savedTemplate === 'object') {
          sessionStorage.setItem(TEMPLATE_OVERRIDE_KEY, JSON.stringify(req.savedTemplate));
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

      window.location.href = '/certificate?t=' + Date.now();
    }

    const requests = loadRequests();

    knownRefSet = new Set(requests.map((r) => String(r?.ref || '').trim()).filter(Boolean));
    if (!localStorage.getItem(NOTIF_SEEN_KEY)) {
      writeSeenRefs(knownRefSet);
    }

    calcStats(requests);
    renderTable(requests);
    updateNotifications(requests);

    document.getElementById('q').addEventListener('input', () => renderTable(loadRequests()));
    document.getElementById('statusFilter').addEventListener('change', () => renderTable(loadRequests()));

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

    function detectNewRequestsAndNotify() {
      const nowRequests = loadRequests();
      const nowSet = new Set(nowRequests.map((r) => String(r?.ref || '').trim()).filter(Boolean));
      const newItems = nowRequests.filter((r) => {
        const ref = String(r?.ref || '').trim();
        return ref && !knownRefSet.has(ref);
      });

      if (newItems.length) {
        updateNotifications(nowRequests, { toastNew: true, newItems });
      } else {
        updateNotifications(nowRequests);
      }

      knownRefSet = nowSet;
    }

    window.addEventListener('storage', (event) => {
      if (event.key !== STORAGE_KEY) return;
      detectNewRequestsAndNotify();
      calcStats(loadRequests());
      renderTable(loadRequests());
    });

    setInterval(detectNewRequestsAndNotify, 5000);

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
        updateNotifications(loadRequests());
        return;
      }
      if (action === 'reject') {
        const all = loadRequests();
        updateStatusByRef(all, ref, 'rejected');
        calcStats(loadRequests());
        renderTable(loadRequests());
        updateNotifications(loadRequests());
        return;
      }

      if (action === 'delete') {
        openDeleteModal(ref);
        return;
      }

      if (action === 'view' || action === 'cert' || action === 'edit') {
        openCertificateWithRequest(ref);
        return;
      }

      if (action === 'send-email') {
        openEmailModal(ref);
        return;
      }

      // Placeholder actions for now
      alert(action.toUpperCase() + ' (ref=' + ref + ')');
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

    window.addEventListener('pageshow', (event) => {
      if (event.persisted) {
        window.location.reload();
      }
    });
  </script>
</body>
</html>
