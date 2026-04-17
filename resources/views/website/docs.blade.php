<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Resident Dashboard - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
  <style>
    /* Toast styles */
    #toastContainer .toast{min-width:300px;background:#f0fdf4;border:1px solid #bbf7d0;padding:.8rem 1rem;border-radius:8px;box-shadow:0 8px 30px rgba(2,6,23,0.12);margin-bottom:.6rem;opacity:0;transform:translateY(-8px);transition:all .3s ease;pointer-events:auto}
    #toastContainer .toast.success{background:linear-gradient(180deg,#ecfdf5,#bbf7d0);border-color:#86efac}
    #toastContainer .toast .toast-body{display:flex;align-items:center;gap:.6rem}
    #toastContainer .toast .toast-icon{width:36px;height:36px;border-radius:8px;background:#10b981;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700}
    #toastContainer .toast .toast-text{font-weight:600;color:#064e3b}
    .dashboard-top{background:linear-gradient(90deg,#0b66c3 0%,#0a5fb8 100%);color:#fff;padding:.9rem 0;box-shadow:0 2px 8px rgba(2,6,23,0.08)}
    .dashboard-top .container{display:flex;align-items:center;justify-content:space-between}
    .user-info{display:flex;align-items:center;gap:.6rem}
    .user-avatar{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.12);display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;border:1px solid rgba(255,255,255,0.12)}
    .dashboard-top img{border-radius:50%;background:#fff;padding:4px}
    .logout-icon{width:38px;height:38px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);color:#fff;cursor:pointer}
    .logout-icon:hover{background:rgba(255,255,255,0.18)}
    .logout-icon svg{width:18px;height:18px;stroke:#fff}
    .stats-row{display:flex;gap:1rem;margin:1rem 0}
    .stat-box{flex:1;background:#fff;border-radius:10px;padding:1.2rem;border:1px solid #eef4fb;text-align:center;box-shadow:0 4px 18px rgba(2,6,23,0.04)}
    .stat-box div:first-child{font-size:1.5rem;font-weight:800;color:#0b66c3}
    .apply-btn{background:#ffd400;color:#111;padding:.6rem .9rem;border-radius:8px;font-weight:700;box-shadow:0 6px 12px rgba(255,212,77,0.18);border:1px solid rgba(0,0,0,0.06)}
    /* controls */
    .controls{display:flex;gap:.75rem;align-items:center;margin:1rem 0}
    .search-box{flex:1;display:flex;align-items:center;background:#fff;border:1px solid #eef2f7;padding:.4rem .6rem;border-radius:8px}
    .search-box input{border:0;outline:none;padding:.5rem .5rem;width:100%;font-size:.95rem}
    .filter-select{min-width:160px;padding:.5rem;border-radius:8px;border:1px solid #eef2f7;background:#fff}
    /* actions */
    .action-btn{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1px solid #eef2f7;background:#fff;color:#374151;margin-left:.3rem}
    .action-btn:hover{background:#f8fafc}
    .actions-cell{display:flex;gap:.4rem;align-items:center}
    /* subtle card around table */
    .table-card{background:#fff;border-radius:10px;padding:12px;border:1px solid #eef4fb}
    .requests-table{width:100%;border-collapse:collapse;margin-top:1rem;background:#fff;border-radius:8px;overflow:hidden}
    .requests-table thead th{background:#fbfdff;padding:10px 12px;border-bottom:1px solid #eef4fb;text-align:left;font-weight:700}
    .requests-table tbody td{padding:.75rem 12px;border-bottom:1px solid #f4f6f9}
    .requests-table tbody tr:last-child td{border-bottom:0}
    .status-badge{display:inline-block;padding:.25rem .6rem;border-radius:999px;font-weight:700;font-size:.85rem}
    .status-pending{background:#fff7ed;color:#92400e;border:1px solid #fde3bf}
    .status-approved{background:#ecfdf5;color:#065f46;border:1px solid #bbf7d0}
    .status-rejected{background:#fff1f2;color:#831843;border:1px solid #ffd6e0}
    .modal-form select{width:100%;box-sizing:border-box;padding:.8rem 1rem;border-radius:10px;border:1px solid #e6e9ef;background:#f8fafc}

    body{
      position:relative;
    }

    body::before{
      content:'';
      position:fixed;
      left:50%;
      top:54%;
      width:460px;
      height:460px;
      transform:translate(-50%, -50%);
      background:url('{{ asset('img/Barangay Official Logo.png') }}') center/contain no-repeat;
      opacity:.07;
      filter:grayscale(100%) blur(2px);
      pointer-events:none;
      z-index:0;
    }

    body > *{
      position:relative;
      z-index:1;
    }
  </style>
</head>
<body>
  <header class="dashboard-top">
    <div class="container">
      <div style="display:flex;align-items:center;gap:1rem">
        <img src="{{ asset('img/logo_zed.png') }}" alt="logo" style="height:44px" />
        <div>
          <div style="font-weight:700">Resident Dashboard</div>
          <div style="font-size:.9rem;opacity:.9">Apply for barangay clearance and track your requests</div>
        </div>
      </div>
      <div class="user-info">
        <div style="text-align:right">
          <div id="dashUserName">User Name</div>
          <div id="dashUserEmail" style="font-size:.9rem;opacity:.85"></div>
        </div>
        <div class="user-avatar" id="dashAvatar">JD</div>
        <button id="logoutBtn" class="logout-icon" type="button" aria-label="Logout" title="Logout">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="M16 17l5-5-5-5" />
            <path d="M21 12H9" />
          </svg>
        </button>
      </div>
    </div>
  </header>
  <!-- Toast notifications container -->
  <div id="toastContainer" style="position:fixed;top:16px;right:16px;z-index:120;pointer-events:none"></div>

  <main class="container" style="padding:1.25rem 0">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem">
      <h2 id="welcomeResidentText">Welcome back, resident!</h2>
      <button class="apply-btn">Apply for New Clearance</button>
    </div>

    <div class="announcement-list" style="margin-top:1rem">
      <div style="background:#eef7ff;padding:1rem;border-radius:6px">How to Apply / Paano Mag-Apply: 1) Click "Apply for Clearance" to submit a new request. 2) Fill out the required information accurately. 3) Upload a valid ID if needed. 4) Track your request status.</div>
    </div>

    <div class="stats-row">
      <div class="stat-box"> <div style="font-size:1.25rem;font-weight:700" id="statTotal">0</div><div style="color:var(--muted)">Total Requests</div></div>
      <div class="stat-box"> <div style="font-size:1.25rem;font-weight:700" id="statPending">0</div><div style="color:var(--muted)">Pending</div></div>
      <div class="stat-box"> <div style="font-size:1.25rem;font-weight:700" id="statApproved">0</div><div style="color:var(--muted)">Approved</div></div>
      <div class="stat-box"> <div style="font-size:1.25rem;font-weight:700" id="statRejected">0</div><div style="color:var(--muted)">Rejected</div></div>
    </div>

    <section style="margin-top:1rem">
      <h3>My Clearance Requests</h3>
      <div class="controls">
        <div class="search-box"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg><input id="searchInput" placeholder="Search by Reference ID or Name" /></div>
        <select id="statusFilter" class="filter-select"><option value="">All Status</option><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select>
        <div style="margin-left:auto"></div>
      </div>
      <div class="table-card">
        <table class="requests-table">
          <thead>
            <tr><th>Reference ID</th><th>Name</th><th>Date Requested</th><th>Valid Until</th><th>Status</th><th>PDF</th><th>Actions</th></tr>
          </thead>
          <tbody id="requestsBody">
            <tr><td colspan="7" class="muted">You have no requests yet.</td></tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Apply for Clearance Modal -->
  <div id="applyModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="applyTitle">
      <button class="modal-close" id="applyModalClose" aria-label="Close">✕</button>
      <div class="modal-header"><h2 id="applyTitle">Apply for Barangay Clearance</h2></div>
      <div class="modal-body">
        <form id="applyForm" class="modal-form">
          <fieldset>
            <label>Full Name *
              <input name="fullName" type="text" required />
            </label>
            <label>Email Address *
              <input name="email" type="email" placeholder="resident@example.com" required />
            </label>
            <label>Complete Address *
              <input name="address" type="text" required />
            </label>
            <label>Age *
              <input name="age" type="number" min="0" required />
            </label>
            <label>Contact Number *
              <input name="contact" type="tel" placeholder="09XXXXXXXXX" required />
            </label>
            <label>Purpose *
              <select name="purpose" required>
                <option value="" selected disabled>Select clearance type</option>
                <option value="Barangay Clearance">Barangay Clearance</option>
                <option value="Barangay Indigency">Barangay Indigency</option>
                <option value="Certificate of Good Moral Character">Certificate of Good Moral Character</option>
                <option value="Certificate of Oneness">Certificate of Oneness</option>
                <option value="Certificate for 1st-Time Job Seeker">Certificate for 1st-Time Job Seeker</option>
                <option value="Oath of Undertaking">Oath of Undertaking</option>
              </select>
            </label>
            <label>Bakit mo kailangan ang clearance? *
              <input name="purposeReason" type="text" placeholder="Hal. Requirement sa trabaho o school" required />
            </label>
            <label>Upload Valid ID (optional)
              <input name="idfile" type="file" accept="image/*,.pdf" />
            </label>
          </fieldset>
          <div class="form-actions">
            <button type="button" class="btn" id="applyCancel">CANCEL</button>
            <button type="submit" class="btn primary">SUBMIT</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Request Modal -->
  <div id="viewModal" class="modal-overlay" hidden>
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="viewTitle">
      <button class="modal-close" id="viewModalClose" aria-label="Close">✕</button>
      <div class="modal-header"><h2 id="viewTitle">Certificate PDF Preview</h2></div>
      <div class="modal-body" id="viewBody" style="padding:0;display:flex;flex-direction:column;gap:1rem;">
        <div style="display:flex;justify-content:flex-end;gap:.5rem;padding:1rem 1rem 0;flex-wrap:wrap;">
          <button type="button" class="btn primary" id="viewDownloadBtn">Download PDF</button>
        </div>
        <iframe id="viewFrame" title="Certificate preview" style="width:100%;height:78vh;border:0;background:#fff;border-radius:0 0 16px 16px;"></iframe>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
  <script>
    const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
    const TEMPLATE_KEY = 'digibarangay_certificate_template_v2';
    const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
    const GLOBAL_CLEARANCE_TEMPLATE_KEY = 'digibarangay_saved_clearance_template_v1';
    const DEFAULT_TEMPLATE = {
      certificateType: 'BARANGAY CLEARANCE',
      bodyHeading: 'TO WHOM IT MAY CONCERN:',
      mainBody: 'This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), is known to be of good moral character and has no derogatory records filed in this barangay.',
      purposeStatement: 'This certification is issued upon the request of the above-named person for (PURPOSE).',
      issuedLine: 'Issued this (DATE) at BARANGAY 192.',
      signName: 'Barangay Captain Name',
      signTitle: 'Punong Barangay',
      barangayName: 'BARANGAY 192',
      barangayAddress: 'City/Municipality, Province'
    };
    let currentPreviewRequest = null;
    let currentPreviewPdfUrl = '';
    let currentPreviewPdfBlob = null;

    function readGlobalClearanceTemplate() {
      try {
        const raw = localStorage.getItem(GLOBAL_CLEARANCE_TEMPLATE_KEY);
        const parsed = raw ? JSON.parse(raw) : null;
        return parsed && typeof parsed === 'object' ? parsed : null;
      } catch {
        return null;
      }
    }

    // Layout preview: skip backend auth check and use any stored local user (if present)
    (function(){
      try{
        function clearResidentAuthState() {
          localStorage.removeItem('authToken');
          localStorage.removeItem('digibarangay_logged_in');
          localStorage.removeItem('digibarangay_user');
          localStorage.removeItem('digibarangay_registered_user');
          sessionStorage.removeItem('digibarangay_logged_in');
          sessionStorage.removeItem('digibarangay_user');
        }

        function hasResidentSession() {
          return localStorage.getItem('digibarangay_logged_in') === '1';
        }

        if (!hasResidentSession()) {
          window.location.replace('/login');
          return;
        }

        window.addEventListener('pageshow', function () {
          if (!hasResidentSession()) {
            window.location.replace('/login');
          }
        });

        const user = (function(){
          try{
            const s = localStorage.getItem('digibarangay_user') || localStorage.getItem('digibarangay_registered_user');
            if(!s) return null;
            try { return JSON.parse(s); } catch (_e) {
              // Backward compatibility if old code stored plain text instead of JSON
              return { fullname: String(s), username: String(s), email: '' };
            }
          }catch(e){ return null; }
        })();

        function userIdentityKey(u){
          if(!u || typeof u !== 'object') return '';
          return String(
            u.user_key
            || u.id
            || u.email
            || u.username
            || u.fullname
            || u.name
            || ''
          ).trim().toLowerCase();
        }

        const currentUserKey = userIdentityKey(user);

        function readAllRequests(){
          try{
            const raw = localStorage.getItem('digibarangay_requests');
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
          }catch(_e){
            return [];
          }
        }

        function requestBelongsToCurrentUser(req){
          if(!req || !currentUserKey) return false;
          const owner = String(req.ownerKey || req.owner_key || req.userKey || '').trim().toLowerCase();
          if(owner) return owner === currentUserKey;
          // Backward compatibility for old records without owner key.
          const reqName = String(req.name || '').trim().toLowerCase();
          const currentName = String(user?.fullname || user?.name || user?.username || '').trim().toLowerCase();
          return reqName !== '' && currentName !== '' && reqName === currentName;
        }

        function readMyRequests(){
          return readAllRequests().filter(requestBelongsToCurrentUser);
        }

        function writeAllRequests(next){
          try { localStorage.setItem('digibarangay_requests', JSON.stringify(next)); }
          catch(err){ console.error('save request', err); }
        }

        function normalizeRequestOwner(req){
          if(!req || typeof req !== 'object') return req;
          if(!req.ownerKey && !req.owner_key && !req.userKey && requestBelongsToCurrentUser(req)){
            req.ownerKey = currentUserKey;
          }
          return req;
        }
        const nameEl = document.getElementById('dashUserName');
        const emailEl = document.getElementById('dashUserEmail');
        const avatar = document.getElementById('dashAvatar');
        const welcomeEl = document.getElementById('welcomeResidentText');
        
        if(user){
          const displayName = user.fullname || user.name || user.username || 'Resident';
          nameEl.textContent = displayName;
          emailEl.textContent = user.email || '';
          if (welcomeEl) welcomeEl.textContent = `Welcome back, ${displayName}!`;
          const initials = displayName.split(' ').map(s=>s[0]).slice(0,2).join('').toUpperCase();
          avatar.textContent = initials;
        }

        let requests = readMyRequests().map(normalizeRequestOwner);
        if(requests.some(r => r.ownerKey === currentUserKey)){
          const allForMigration = readAllRequests().map(normalizeRequestOwner);
          writeAllRequests(allForMigration);
        }

        const total = requests.length;
        const pending = requests.filter(r=>r.status==='pending').length;
        const approved = requests.filter(r=>r.status==='approved').length;
        const rejected = requests.filter(r=>r.status==='rejected').length;
        document.getElementById('statTotal').textContent = total;
        document.getElementById('statPending').textContent = pending;
        document.getElementById('statApproved').textContent = approved;
        document.getElementById('statRejected').textContent = rejected;

        // populate requests table if any
        const tbody = document.getElementById('requestsBody');
        function renderRequests(list){
          if(!list || !list.length){
            tbody.innerHTML = '<tr><td colspan="7" class="muted">You have no requests yet.</td></tr>';
            return;
          }
          tbody.innerHTML = list.map(r=>{
            const statusLabel = r.status ? (r.status.charAt(0).toUpperCase()+r.status.slice(1)) : '';
            const statusHtml = `<span class="status-badge status-${r.status}">${statusLabel}</span>`;
            const globalTemplate = readGlobalClearanceTemplate();
            const certTypeRaw = String((r.savedTemplate && r.savedTemplate.certificateType) || r.savedCertType || (globalTemplate && globalTemplate.certificateType) || '').trim();
            const certTypeSafe = certTypeRaw
              .replaceAll('&', '&amp;')
              .replaceAll('<', '&lt;')
              .replaceAll('>', '&gt;');
            const certTypeNote = certTypeSafe
              ? '<div style="font-size:.72rem;color:#4b5563;margin-top:.25rem;line-height:1.2;">' + certTypeSafe + '</div>'
              : '';
            const pdfHtml = r.pdfSaved
              || !!globalTemplate
              ? ('<span class="status-badge status-approved">Saved</span>' + certTypeNote)
              : '<span class="status-badge status-pending">Not Saved</span>';
            const actions = `<div class="actions-cell"><button class="action-btn" data-action="view" data-ref="${r.ref||''}" title="View">👁️</button><button class="action-btn" data-action="download" data-ref="${r.ref||''}" title="Download">⬇️</button><button class="action-btn" data-action="delete" data-ref="${r.ref||''}" title="Delete">🗑️</button></div>`;
            return `<tr data-ref="${r.ref||''}"><td>${r.ref||''}</td><td>${r.name||''}</td><td>${r.dateRequested||''}</td><td>${r.validUntil||''}</td><td>${statusHtml}</td><td>${pdfHtml}</td><td>${actions}</td></tr>`;
          }).join('');
        }
        renderRequests(requests);
        // search and filter controls
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        function getFilteredRequests(){
          const q = (searchInput && searchInput.value || '').trim().toLowerCase();
          const status = (statusFilter && statusFilter.value) || '';
          return requests.filter(r=>{
            if(status && r.status !== status) return false;
            if(!q) return true;
            return (r.ref || '').toLowerCase().includes(q) || (r.name || '').toLowerCase().includes(q);
          });
        }
        if(searchInput) searchInput.addEventListener('input', ()=>{ renderRequests(getFilteredRequests()); });
        if(statusFilter) statusFilter.addEventListener('change', ()=>{ renderRequests(getFilteredRequests()); });

        document.getElementById('logoutBtn').addEventListener('click', async ()=>{
          // Try backend logout endpoint when token exists.
          const token = localStorage.getItem('authToken');
          try {
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
          // Always clear local auth state and force login page.
          clearResidentAuthState();
          window.location.replace('/login');
        });

        // Apply modal wiring
        const applyBtn = document.querySelector('.apply-btn');
        const applyModal = document.getElementById('applyModal');
        const applyModalClose = document.getElementById('applyModalClose');
        const applyCancel = document.getElementById('applyCancel');
        const applyForm = document.getElementById('applyForm');

        function openApply(){ if(applyModal){ applyModal.hidden = false; applyModal.classList.add('open'); const f = applyModal.querySelector('input[name="fullName"]'); if(f) f.focus(); } }
        function closeApply(){ if(applyModal){ applyModal.hidden = true; applyModal.classList.remove('open'); } }

        if(applyBtn) applyBtn.addEventListener('click', (e)=>{
          e.preventDefault();
          editingRef = null;
          if (applyForm) {
            applyForm.reset();
            const fd = applyForm.elements;
            if (fd.fullName) fd.fullName.value = String(user?.fullname || user?.name || user?.username || '').trim();
            if (fd.email) fd.email.value = String(user?.email || '').trim();
          }
          openApply();
        });
        if(applyModalClose) applyModalClose.addEventListener('click', closeApply);
        if(applyCancel) applyCancel.addEventListener('click', closeApply);
        if(applyModal) applyModal.addEventListener('click',(e)=>{ if(e.target===applyModal) closeApply(); });

        // state for edit mode
        let editingRef = null;

        // helper: create toast notification
        function showToast(message, options){
          const container = document.getElementById('toastContainer');
          if(!container) return;
          const toast = document.createElement('div');
          toast.className = 'toast success';
          toast.style.pointerEvents = 'auto';
          toast.innerHTML = `<div class="toast-body"><div class="toast-icon">✔</div><div class="toast-text">${message}</div></div>`;
          container.appendChild(toast);
          // animate in
          requestAnimationFrame(()=>{ toast.style.transform = 'translateY(0)'; toast.style.opacity = '1'; });
          // auto remove
          setTimeout(()=>{ toast.style.opacity = '0'; toast.style.transform = 'translateY(-10px)'; setTimeout(()=>toast.remove(),400); }, options && options.duration || 3500);
        }

        // dynamic update of counts and table without reload
        function refreshDashboardFromRequests(allRequests){
          const mine = (allRequests || []).filter(requestBelongsToCurrentUser);
          const total = mine.length;
          const pending = mine.filter(r=>r.status==='pending').length;
          const approved = mine.filter(r=>r.status==='approved').length;
          const rejected = mine.filter(r=>r.status==='rejected').length;
          document.getElementById('statTotal').textContent = total;
          document.getElementById('statPending').textContent = pending;
          document.getElementById('statApproved').textContent = approved;
          document.getElementById('statRejected').textContent = rejected;
          requests = mine;
          // re-render using current filters
          if(typeof getFilteredRequests === 'function'){
            renderRequests(getFilteredRequests());
          } else {
            renderRequests(mine);
          }
        }

        function buildCertificatePdfPayload(req){
          return {
            ref: String(req?.ref || '').trim(),
            name: String(req?.name || '').trim(),
            age: req?.age ?? '',
            address: String(req?.address || '').trim(),
            purpose: String(req?.purposeReason || req?.purpose || '').trim(),
            date: String(req?.dateRequested || req?.date || '').trim(),
          };
        }

        function revokeCurrentPreviewPdfUrl(){
          if(!currentPreviewPdfUrl) return;
          URL.revokeObjectURL(currentPreviewPdfUrl);
          currentPreviewPdfUrl = '';
        }

        async function requestCertificatePdfBlob(req){
          if (!window.html2canvas || !window.jspdf || !window.jspdf.jsPDF) {
            throw new Error('PDF libraries are not available. Please check your internet connection and refresh the page.');
          }

          const payload = buildCertificatePdfPayload(req);
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

          const sourceFrame = document.createElement('iframe');
          sourceFrame.setAttribute('aria-hidden', 'true');
          sourceFrame.style.position = 'fixed';
          sourceFrame.style.left = '0';
          sourceFrame.style.top = '0';
          sourceFrame.style.opacity = '0';
          sourceFrame.style.pointerEvents = 'none';
          sourceFrame.style.zIndex = '-1';
          sourceFrame.style.width = '794px';
          sourceFrame.style.height = '1123px';
          sourceFrame.style.border = '0';
          sourceFrame.src = '/certificate?mode=docs&t=' + Date.now();
          document.body.appendChild(sourceFrame);

          try {
            await new Promise((resolve, reject) => {
              const failTimer = setTimeout(() => reject(new Error('Timed out loading certificate preview.')), 20000);
              sourceFrame.onload = () => {
                clearTimeout(failTimer);
                resolve(null);
              };
              sourceFrame.onerror = () => {
                clearTimeout(failTimer);
                reject(new Error('Unable to load certificate preview.'));
              };
            });

            const sourceDoc = sourceFrame.contentDocument;
            const paper = sourceDoc && sourceDoc.getElementById('paper');
            if (!sourceDoc || !paper) {
              throw new Error('Certificate template content not found.');
            }

            if (sourceDoc.fonts && sourceDoc.fonts.ready) {
              try { await sourceDoc.fonts.ready; } catch (_) {}
            }

            const imageEls = Array.from(sourceDoc.images || []);
            await Promise.all(imageEls.map((img) => {
              if (img.complete) return Promise.resolve();
              return new Promise((resolve) => {
                img.addEventListener('load', resolve, { once: true });
                img.addEventListener('error', resolve, { once: true });
              });
            }));

            await new Promise((resolve) => setTimeout(resolve, 220));

            const rect = paper.getBoundingClientRect();
            const captureWidth = Math.max(
              1,
              Math.ceil(rect.width),
              Math.ceil(paper.scrollWidth || 0),
              Math.ceil(paper.offsetWidth || 0)
            );
            const captureHeight = Math.max(
              1,
              Math.ceil(rect.height),
              Math.ceil(paper.scrollHeight || 0),
              Math.ceil(paper.offsetHeight || 0)
            );

            const canvas = await window.html2canvas(paper, {
              scale: 2.2,
              useCORS: true,
              backgroundColor: '#ffffff',
              width: captureWidth,
              height: captureHeight,
              windowWidth: Math.max(sourceDoc.documentElement.scrollWidth, captureWidth),
              windowHeight: Math.max(sourceDoc.documentElement.scrollHeight, captureHeight),
              scrollX: 0,
              scrollY: 0,
            });

            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({ orientation: 'portrait', unit: 'pt', format: 'a4' });
            pdf.setDisplayMode('fullpage', 'single', 'UseNone');
            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const imageData = canvas.toDataURL('image/png');
            pdf.addImage(imageData, 'PNG', 0, 0, pageWidth, pageHeight, undefined, 'FAST');

            return { blob: pdf.output('blob'), payload };
          } finally {
            sourceFrame.remove();
          }
        }

        async function openCertificateWithRequest(req){
          if(!req) return;
          currentPreviewRequest = req;

          const viewModal = document.getElementById('viewModal');
          const viewFrame = document.getElementById('viewFrame');
          const viewDownloadBtn = document.getElementById('viewDownloadBtn');

          if (viewModal) {
            viewModal.hidden = false;
            viewModal.classList.add('open');
          }

          if (viewFrame) {
            viewFrame.src = 'about:blank';
          }

          if (viewDownloadBtn) {
            viewDownloadBtn.disabled = true;
            viewDownloadBtn.onclick = function () {
              if (currentPreviewRequest) {
                downloadCertificatePdf(currentPreviewRequest);
              }
            };
          }

          try {
            const { blob } = await requestCertificatePdfBlob(req);
            currentPreviewPdfBlob = blob;
            revokeCurrentPreviewPdfUrl();
            currentPreviewPdfUrl = URL.createObjectURL(blob);
            if (viewFrame) {
              viewFrame.src = currentPreviewPdfUrl;
            }
          } catch (error) {
            console.error('PDF preview error:', error);
            alert('Unable to load PDF preview right now.');
          } finally {
            if (viewDownloadBtn) {
              viewDownloadBtn.disabled = false;
            }
          }
        }

        async function downloadCertificatePdf(req){
          if(!req) return;

          try {
            const payload = buildCertificatePdfPayload(req);
            const blob = currentPreviewPdfBlob || (await requestCertificatePdfBlob(req)).blob;
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = (payload.ref || 'certificate') + '.pdf';
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
          } catch (error) {
            console.error('PDF download error:', error);
            alert('Unable to download PDF right now.');
          }
        }

        if(applyForm){
          applyForm.addEventListener('submit',(e)=>{
            e.preventDefault();
            if (!applyForm.checkValidity()) {
              applyForm.reportValidity();
              return;
            }
            const fd = applyForm.elements;
            const fullName = String(fd.fullName.value || '').trim();
            const email = String(fd.email?.value || '').trim().toLowerCase();
            const address = String(fd.address.value || '').trim();
            const age = String(fd.age?.value || '').trim();
            const contact = String(fd.contact?.value || '').trim();
            const purpose = String(fd.purpose.value || '').trim();
            const purposeReason = String(fd.purposeReason?.value || '').trim();

            if(!fullName || !email || !address || !age || !contact || !purpose || !purposeReason){ alert('Please complete required fields.'); return; }
            if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ alert('Please enter a valid email address.'); return; }

            const allRequests = readAllRequests();
            const now = new Date();
            if(editingRef){
              // update existing request
              const idx = allRequests.findIndex(r=>r.ref === editingRef && requestBelongsToCurrentUser(r));
              if(idx !== -1){
                allRequests[idx].name = fullName;
                allRequests[idx].email = email;
                allRequests[idx].address = address;
                allRequests[idx].age = age;
                allRequests[idx].dateRequested = allRequests[idx].dateRequested || now.toISOString().split('T')[0];
                allRequests[idx].validUntil = allRequests[idx].validUntil || new Date(now.getFullYear()+1, now.getMonth(), now.getDate()).toISOString().split('T')[0];
                allRequests[idx].purpose = purpose;
                allRequests[idx].purposeReason = purposeReason;
                allRequests[idx].contact = contact;
                allRequests[idx].ownerKey = currentUserKey;
                // keep status unchanged
              }
              editingRef = null;
              writeAllRequests(allRequests);
              closeApply();
              showToast('Application updated', {duration:2500});
              refreshDashboardFromRequests(allRequests);
              return;
            }
            // new request
            const ref = 'BR' + now.getTime();
            const dateRequested = now.toISOString().split('T')[0];
            const validUntil = new Date(now.getFullYear()+1, now.getMonth(), now.getDate()).toISOString().split('T')[0];
            const newReq = {
              ref: ref,
              name: fullName,
              email: email,
              address: address,
              age: age,
              dateRequested: dateRequested,
              validUntil: validUntil,
              status: 'pending',
              purpose: purpose,
              purposeReason: purposeReason,
              contact: contact,
              ownerKey: currentUserKey
            };
            allRequests.unshift(newReq);
            writeAllRequests(allRequests);
            closeApply();
            // show success toast with reference
            showToast(`Application Submitted successfully! <strong>${ref}</strong>`, {duration:4000});
            // update UI immediately
            refreshDashboardFromRequests(allRequests);
          });
        }
        // view/delete/download handlers (delegated)
        document.getElementById('requestsBody').addEventListener('click',(e)=>{
          const btn = e.target.closest && e.target.closest('button[data-action]');
          if(!btn) return;
          const action = btn.getAttribute('data-action');
          const ref = btn.getAttribute('data-ref');
          const allRequests = readAllRequests();
          const req = allRequests.find(r=>r.ref === ref && requestBelongsToCurrentUser(r));
          if(action === 'view'){
            if(!req) return;
            openCertificateWithRequest(req);
          } else if(action === 'download'){
            if(!req) return;
            downloadCertificatePdf(req);
          } else if(action === 'delete'){
            if(!req) return; if(!confirm('Delete this request?')) return; const newList = allRequests.filter(r=>!(r.ref === ref && requestBelongsToCurrentUser(r)));
            writeAllRequests(newList); showToast('Request deleted', {duration:2000}); refreshDashboardFromRequests(newList);
          }
        });

        // view modal close
        const viewModal = document.getElementById('viewModal');
        const viewModalClose = document.getElementById('viewModalClose');
        const viewFrame = document.getElementById('viewFrame');

        function closeViewModal(){
          if(viewModal){
            viewModal.hidden = true;
            viewModal.classList.remove('open');
          }
          if(viewFrame){
            viewFrame.src = 'about:blank';
          }
          currentPreviewPdfBlob = null;
          revokeCurrentPreviewPdfUrl();
        }

        if(viewModalClose) viewModalClose.addEventListener('click', closeViewModal);
        if(viewModal) viewModal.addEventListener('click',(e)=>{ if(e.target === viewModal){ closeViewModal(); } });
      }catch(e){ console.error(e); }
    })();
  </script>
</body>
</html>
