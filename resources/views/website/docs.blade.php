<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
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
      <h2>Welcome back, resident!</h2>
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
            <tr><th>Reference ID</th><th>Name</th><th>Date Requested</th><th>Valid Until</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody id="requestsBody">
            <tr><td colspan="6" class="muted">You have no requests yet.</td></tr>
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
        <form id="applyForm" class="modal-form" novalidate>
          <fieldset>
            <label>Full Name *
              <input name="fullName" type="text" required />
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
            <label>Purpose of Clearance *
              <input name="purpose" type="text" placeholder="e.g., Employment, Travel, School" required />
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
      <div class="modal-header"><h2 id="viewTitle">Request Details</h2></div>
      <div class="modal-body" id="viewBody">
        <!-- filled by JS -->
      </div>
    </div>
  </div>

  <script>
    // Layout preview: skip backend auth check and use any stored local user (if present)
    (function(){
      try{
        const user = (function(){
          try{
            const s = localStorage.getItem('digibarangay_user') || localStorage.getItem('digibarangay_registered_user');
            return s ? JSON.parse(s) : null;
          }catch(e){ return null; }
        })();
        const nameEl = document.getElementById('dashUserName');
        const emailEl = document.getElementById('dashUserEmail');
        const avatar = document.getElementById('dashAvatar');
        
        if(user){
          nameEl.textContent = user.username || user.fullname || 'Resident';
          emailEl.textContent = user.email || '';
          const initials = (user.username || user.fullname || 'R').split(' ').map(s=>s[0]).slice(0,2).join('').toUpperCase();
          avatar.textContent = initials;
        }

        // load requests from localStorage (default: none)
        const requestsJson = localStorage.getItem('digibarangay_requests');
        const requests = requestsJson ? JSON.parse(requestsJson) : [];
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
            tbody.innerHTML = '<tr><td colspan="6" class="muted">You have no requests yet.</td></tr>';
            return;
          }
          tbody.innerHTML = list.map(r=>{
            const statusLabel = r.status ? (r.status.charAt(0).toUpperCase()+r.status.slice(1)) : '';
            const statusHtml = `<span class="status-badge status-${r.status}">${statusLabel}</span>`;
            const actions = `<div class="actions-cell"><button class="action-btn" data-action="view" data-ref="${r.ref||''}" title="View">👁️</button><button class="action-btn" data-action="download" data-ref="${r.ref||''}" title="Download">⬇️</button><button class="action-btn" data-action="edit" data-ref="${r.ref||''}" title="Edit">✎</button><button class="action-btn" data-action="delete" data-ref="${r.ref||''}" title="Delete">🗑️</button></div>`;
            return `<tr data-ref="${r.ref||''}"><td>${r.ref||''}</td><td>${r.name||''}</td><td>${r.dateRequested||''}</td><td>${r.validUntil||''}</td><td>${statusHtml}</td><td>${actions}</td></tr>`;
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
          // Call backend logout endpoint
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
          // Clear local storage and redirect
          localStorage.removeItem('authToken');
          localStorage.removeItem('digibarangay_logged_in');
          window.location.href = './home.html';
        });

        // Apply modal wiring
        const applyBtn = document.querySelector('.apply-btn');
        const applyModal = document.getElementById('applyModal');
        const applyModalClose = document.getElementById('applyModalClose');
        const applyCancel = document.getElementById('applyCancel');
        const applyForm = document.getElementById('applyForm');

        function openApply(){ if(applyModal){ applyModal.hidden = false; applyModal.classList.add('open'); const f = applyModal.querySelector('input[name="fullName"]'); if(f) f.focus(); } }
        function closeApply(){ if(applyModal){ applyModal.hidden = true; applyModal.classList.remove('open'); } }

        if(applyBtn) applyBtn.addEventListener('click', (e)=>{ e.preventDefault(); openApply(); });
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
        function refreshDashboardFromRequests(requests){
          const total = requests.length;
          const pending = requests.filter(r=>r.status==='pending').length;
          const approved = requests.filter(r=>r.status==='approved').length;
          const rejected = requests.filter(r=>r.status==='rejected').length;
          document.getElementById('statTotal').textContent = total;
          document.getElementById('statPending').textContent = pending;
          document.getElementById('statApproved').textContent = approved;
          document.getElementById('statRejected').textContent = rejected;
          // re-render using current filters
          if(typeof getFilteredRequests === 'function'){
            renderRequests(getFilteredRequests());
          } else {
            renderRequests(requests);
          }
        }

        if(applyForm){
          applyForm.addEventListener('submit',(e)=>{
            e.preventDefault();
            const fd = applyForm.elements;
            if(!fd.fullName.value.trim() || !fd.address.value.trim() || !fd.purpose.value.trim()){ alert('Please complete required fields.'); return; }
            const requestsJson = localStorage.getItem('digibarangay_requests');
            const requests = requestsJson ? JSON.parse(requestsJson) : [];
            const now = new Date();
            if(editingRef){
              // update existing request
              const idx = requests.findIndex(r=>r.ref === editingRef);
              if(idx !== -1){
                requests[idx].name = fd.fullName.value.trim();
                requests[idx].dateRequested = requests[idx].dateRequested || now.toISOString().split('T')[0];
                requests[idx].validUntil = requests[idx].validUntil || new Date(now.getFullYear()+1, now.getMonth(), now.getDate()).toISOString().split('T')[0];
                requests[idx].purpose = fd.purpose.value.trim();
                requests[idx].contact = fd.contact.value.trim();
                // keep status unchanged
              }
              editingRef = null;
              try{ localStorage.setItem('digibarangay_requests', JSON.stringify(requests)); }catch(err){ console.error('save request', err); }
              closeApply();
              showToast('Application updated', {duration:2500});
              refreshDashboardFromRequests(requests);
              return;
            }
            // new request
            const ref = 'BR' + now.getTime();
            const dateRequested = now.toISOString().split('T')[0];
            const validUntil = new Date(now.getFullYear()+1, now.getMonth(), now.getDate()).toISOString().split('T')[0];
            const newReq = {
              ref: ref,
              name: fd.fullName.value.trim(),
              dateRequested: dateRequested,
              validUntil: validUntil,
              status: 'pending',
              purpose: fd.purpose.value.trim(),
              contact: fd.contact.value.trim()
            };
            requests.unshift(newReq);
            try{ localStorage.setItem('digibarangay_requests', JSON.stringify(requests)); }catch(err){ console.error('save request', err); }
            closeApply();
            // show success toast with reference
            showToast(`Application Submitted successfully! <strong>${ref}</strong>`, {duration:4000});
            // update UI immediately
            refreshDashboardFromRequests(requests);
          });
        }
        // view/edit/delete/download handlers (delegated)
        document.getElementById('requestsBody').addEventListener('click',(e)=>{
          const btn = e.target.closest && e.target.closest('button[data-action]');
          if(!btn) return;
          const action = btn.getAttribute('data-action');
          const ref = btn.getAttribute('data-ref');
          const requestsJson = localStorage.getItem('digibarangay_requests');
          const requests = requestsJson ? JSON.parse(requestsJson) : [];
          const req = requests.find(r=>r.ref === ref);
          if(action === 'view'){
            if(!req) return; const viewBody = document.getElementById('viewBody');
            viewBody.innerHTML = `<p><strong>Reference:</strong> ${req.ref}</p><p><strong>Name:</strong> ${req.name}</p><p><strong>Date Requested:</strong> ${req.dateRequested}</p><p><strong>Valid Until:</strong> ${req.validUntil}</p><p><strong>Status:</strong> ${req.status}</p><p><strong>Purpose:</strong> ${req.purpose || ''}</p><p><strong>Contact:</strong> ${req.contact || ''}</p>`;
            const vm = document.getElementById('viewModal'); if(vm){ vm.hidden = false; vm.classList.add('open'); }
          } else if(action === 'download'){
            if(!req) return; const content = `Reference: ${req.ref}\nName: ${req.name}\nDate Requested: ${req.dateRequested}\nValid Until: ${req.validUntil}\nStatus: ${req.status}\nPurpose: ${req.purpose || ''}\nContact: ${req.contact || ''}`;
            const blob = new Blob([content], {type:'text/plain'}); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = `${req.ref || 'request'}.txt`; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
            showToast('Download started', {duration:2000});
          } else if(action === 'edit'){
            if(!req) return; editingRef = req.ref; openApply();
            // prefill fields
            const fd = applyForm.elements;
            fd.fullName.value = req.name || '';
            fd.address.value = req.address || req.address || '';
            fd.age && (fd.age.value = req.age || '');
            fd.contact && (fd.contact.value = req.contact || '');
            fd.purpose && (fd.purpose.value = req.purpose || '');
          } else if(action === 'delete'){
            if(!req) return; if(!confirm('Delete this request?')) return; const newList = requests.filter(r=>r.ref !== ref);
            try{ localStorage.setItem('digibarangay_requests', JSON.stringify(newList)); }catch(err){ console.error('delete request', err);} showToast('Request deleted', {duration:2000}); refreshDashboardFromRequests(newList);
          }
        });

        // view modal close
        const viewModal = document.getElementById('viewModal');
        const viewModalClose = document.getElementById('viewModalClose');
        if(viewModalClose) viewModalClose.addEventListener('click', ()=>{ if(viewModal){ viewModal.hidden = true; viewModal.classList.remove('open'); } });
        if(viewModal) viewModal.addEventListener('click',(e)=>{ if(e.target === viewModal){ viewModal.hidden = true; viewModal.classList.remove('open'); } });
      }catch(e){ console.error(e); }
    })();
  </script>
</body>
</html>
