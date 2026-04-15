  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Certificate Management - DIGIBARANGAY</title>
  <link rel="stylesheet" href="{{asset('css/styles.css')}}" />
  <style>
    .paper{
      width:794px;
      min-height:1123px;
      max-width:100%;
      margin:0 auto;
      padding:70px 72px 82px;
      background:#fff;
      border:1px solid #d7dde5;
      box-shadow:0 12px 28px rgba(2,6,23,.10);
      position:relative;
      overflow:hidden;
      font-family:"Times New Roman", Times, serif;
      color:#111827;
    }

    .paper .center{
      text-align:center;
      line-height:1.25;
    }

    .paper .cert-header-image-wrap{
      margin:2px auto 30px;
      text-align:center;
    }

    .paper .cert-header-image{
      display:block;
      width:100%;
      max-width:640px;
      margin:0 auto;
      height:auto;
      object-fit:contain;
    }

    .paper .type-only-header{
      margin:8px 0 4px;
      text-align:center;
      font-size:27px;
      font-weight:700;
      letter-spacing:.22em;
      text-transform:uppercase;
      color:#111827;
    }

    .paper .tiny{
      font-size:12px;
      color:#111827;
    }

    .paper h3{
      margin:14px 0 12px;
      font-size:30px;
      font-weight:700;
      letter-spacing:.28em;
      text-transform:uppercase;
    }

    .paper .body{
      margin-top:40px;
      font-size:17px;
      line-height:1.8;
      text-align:justify;
      text-justify:inter-word;
    }

    .paper .body > div{
      margin:0 0 14px;
    }

    .paper .sig{
      margin-top:72px;
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      gap:24px;
    }

    .paper .sig-signatory{
      display:flex;
      flex-direction:column;
      align-items:flex-end;
      text-align:right;
    }

    .paper .sig-signature{
      display:block;
      width:180px;
      max-height:72px;
      object-fit:contain;
      transform:translateY(-6px);
      margin-bottom:-4px;
    }

    .paper .sig .line{
      width:220px;
      border-top:1px solid #111827;
      margin-left:auto;
    }

    .paper .footer-note{
      position:absolute;
      left:72px;
      right:72px;
      bottom:34px;
      text-align:left;
      font-size:11px;
      color:#111827;
      letter-spacing:.02em;
    }

    .paper .email-note{
      position:absolute;
      right:72px;
      bottom:34px;
      max-width:42%;
      text-align:right;
      font-size:11px;
      color:#111827;
      line-height:1.25;
    }

    .paper .seal,
    .paper .qr{
      display:none;
    }

    .preview-card{
      padding:1rem;
      overflow:auto;
    }

    .preview-card .paper{
      margin:0 auto;
    }

    @media (max-width:1100px){
      .paper{
        width:100%;
        min-height:auto;
        padding:54px 40px 72px;
      }

      .paper h3{
        font-size:24px;
        letter-spacing:.22em;
      }

      .paper .body{
        font-size:15px;
      }

      .paper .sig{
        margin-top:56px;
      }
    }

    #templateLibraryModal .modal {
      width: min(96vw, 920px);
      max-width: 920px;
      max-height: 90vh;
      overflow: visible;
    }

    #templateLibraryModal .modal-body {
      max-height: none;
      overflow: visible;
    }

    #templateLibraryList {
      max-height: none;
      overflow: visible;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
    }

    #templateLibraryList .adm-table {
      width: 100%;
      table-layout: fixed;
    }

    #templateLibraryList .adm-table td {
      white-space: normal;
      word-break: break-word;
    }
  </style>
  </head>
  @php
    $certTemplateFiles = [];
    $certTemplateDir = public_path('file-cert');
    if (\Illuminate\Support\Facades\File::isDirectory($certTemplateDir)) {
      foreach (\Illuminate\Support\Facades\File::files($certTemplateDir) as $file) {
        $filename = $file->getFilename();
        $certTemplateFiles[] = [
          'name' => $filename,
          'url' => asset('file-cert/' . rawurlencode($filename)),
        ];
      }
    }
  @endphp
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
          <a class="active" href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
          <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        </nav>
        <div class="adm-sidebar-footer">
          <button class="adm-logout" type="button" id="adminLogout"><span class="ico">⎋</span><span>Logout</span></button>
        </div>
      </aside>

      <main class="adm-main">
        <header class="adm-topbar">
          <button class="adm-menu-toggle" id="admMenuToggle" type="button" aria-label="Toggle menu" aria-expanded="false" aria-controls="admSidebarNav">
            <span class="bars" aria-hidden="true"><span></span><span></span><span></span></span>
          </button>
          <div class="role">
            <strong id="topbarUserName">{{ session('admin_name', 'CHAIRMAN') }}</strong>
            <span>barangay Admin</span>
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
          <div class="cert-grid">
            <section class="adm-card cert-panel">
              <div class="cert-head">
                <div>
                  <h2>CERTIFICATE MANAGEMENT</h2>
                  <div class="hint"><span class="i">i</span><span>Customize your certificate. Use placement like <strong>(AGE)</strong> and <strong>(NAME)</strong></span></div>
                </div>
                <button class="add-template" type="button" id="addTemplate">+ Add Template</button>
              </div>

              <div class="field" style="margin:.85rem 0 1rem;">
                <label>Type of Certificate (from selected template)</label>
                <div class="upload-box" id="autoHdrType">BARANGAY CERTIFICATE</div>
              </div>

              <div class="cert-tabs" role="tablist" aria-label="Certificate tabs">
                <button class="cert-tab active" type="button" data-tab="body" aria-selected="true">Body</button>
                <button class="cert-tab" type="button" data-tab="signature" aria-selected="false">Signature</button>
                <button class="cert-tab" type="button" data-tab="format" aria-selected="false">Format</button>
              </div>

              <div class="tab-panels">
                <input id="barangayName" type="hidden" />
                <input id="contactNo" type="hidden" />
                <input id="barangayAddress" type="hidden" />
                <input id="certificateType" type="hidden" />

                <!-- BODY PANEL -->
                <div class="tab-panel active" data-panel="body">
                  <div class="field">
                    <label for="bodyHeading">Body Content (Heading)</label>
                    <input id="bodyHeading" type="text" placeholder="TO WHOM IT MAY CONCERN:" />
                  </div>
                  <div class="field">
                    <label for="mainBody">Main Body</label>
                    <textarea id="mainBody" placeholder="This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), ..."></textarea>
                  </div>
                  <div class="field">
                    <label for="purposeStatement">Purpose Statement</label>
                    <textarea id="purposeStatement" placeholder="This certification is issued upon the request of the above-named person for (PURPOSE)."></textarea>
                  </div>
                  <div class="field">
                    <label for="issuedLine">Issued Date Line</label>
                    <input id="issuedLine" type="text" placeholder="Issued this (DATE) at (BARANGAY)." />
                  </div>
                  <div class="muted" style="margin-top:.4rem">Use (NAME), (AGE), (ADDRESS), (PURPOSE), (DATE) placeholders</div>
                </div>

                <!-- SIGNATURE PANEL -->
                <div class="tab-panel" data-panel="signature">
                  <div class="field">
                    <label for="signName">Signatory Name</label>
                    <input id="signName" type="text" placeholder="Barangay Captain Name" />
                  </div>
                  <div class="field">
                    <label for="signTitle">Signatory Title</label>
                    <input id="signTitle" type="text" placeholder="Punong Barangay" />
                  </div>
                  <div class="field">
                    <label for="signImage">Signature Picture</label>
                    <input id="signImage" type="file" accept="image/*" />
                    <input id="signImageData" type="hidden" />
                    <div class="muted" id="signImageMeta" style="margin-top:.4rem">No signature image selected.</div>
                  </div>
                </div>

                <!-- FORMAT PANEL -->
                <div class="tab-panel" data-panel="format">
                  <div class="field">
                    <label for="borderColor">Border Color</label>
                    <input id="borderColor" type="text" placeholder="#2b77d1" />
                  </div>
                  <div class="field">
                    <label for="issueEmail">Issuance Email</label>
                    <input id="issueEmail" type="email" placeholder="barangay192@example.com" />
                  </div>
                </div>
              </div>

              <div class="cert-actions">
                <button class="btn-save" type="button" id="saveTemplate">💾 Save Template</button>
                <button class="btn-reset" type="button" id="resetTemplate">⟲ Reset to Default</button>
              </div>
            </section>

            <aside class="adm-card preview-card">
              <div class="preview-title"><span class="dot"></span><span>Live Preview</span></div>
              <div class="paper" id="paper" style="border-color: var(--certBorder, #2b77d1)">
                <div class="cert-header-image-wrap">
                  <img id="pvHeaderImage" class="cert-header-image" alt="Certificate header" src="{{ asset('img/Screenshot 2026-04-15 162812.jpg') }}" />
                </div>
                <h3 class="type-only-header" id="pvType">BARANGAY CERTIFICATE</h3>

                <div class="body" id="pvBody"></div>

                <div class="sig">
                  <div class="tiny" id="pvIssued">Issued this (DATE) at BARANGAY 192.</div>
                  <div class="sig-signatory" style="text-align:right">
                    <img id="pvSignImage" class="sig-signature" alt="Captain signature" hidden />
                    <div class="line"></div>
                    <div style="font-weight:900;font-size:.78rem;margin-top:.35rem" id="pvSignName">Barangay Captain Name</div>
                    <div class="tiny" id="pvSignTitle">Punong Barangay</div>
                  </div>
                </div>

                <div class="email-note tiny" id="pvEmail">For verification, email: (Optional)</div>
                <div class="footer-note">This is a digitally issued certificate. The issuance is effective without official seal and signature.</div>
              </div>
            </aside>
          </div>
        </section>
      </main>
    </div>

    <div id="templateLibraryModal" class="modal-overlay" hidden>
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="templateLibraryTitle">
        <button class="modal-close" id="templateLibraryClose" aria-label="Close">✕</button>
        <div class="modal-header"><h2 id="templateLibraryTitle">Certificate Template Library</h2></div>
        <div class="modal-body">
          <p class="muted" id="templateLibraryCount" style="margin-top:0;margin-bottom:.8rem"></p>
          <div id="templateLibraryList" style="border-radius:10px;">
            <!-- populated by JS -->
          </div>
        </div>
      </div>
    </div>

<div id="saveModal" class="modal-overlay" hidden>
  <div class="modal" style="max-width:350px;">
    
    <button class="modal-close" id="saveClose">✕</button>

    <div class="modal-header">
      <h2>Save Template</h2>
    </div>

    <div class="modal-body" style="text-align:center;">
      <p>Are you sure you want to save this template?</p>

      <div style="display:flex;gap:10px;margin-top:15px;">
        <button class="btn primary" id="saveOkBtn" style="flex:1;">OK</button>
        <button class="btn" id="saveCancelBtn" style="flex:1;">Cancel</button>
      </div>
    </div>

  </div>
</div>

<div id="notifToastHost" style="position:fixed;top:16px;right:16px;z-index:120;display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>

    <script>
      const TEMPLATE_KEY = 'digibarangay_certificate_template_v2';
      const LOGO_KEY = 'digibarangay_certificate_logo_dataurl_v2';
      const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
  const REQUESTS_KEY = 'digibarangay_requests';
  const NOTIF_SEEN_KEY = 'digibarangay_seen_request_refs_v1';
      const CERT_TEMPLATE_FILES = @json($certTemplateFiles);
      const AUTO_HEADER_PRESET = {
        barangayName: 'BARANGAY 192',
        barangayAddress: 'City/Municipality, Province',
        contactNo: '',
      };
      const AUTO_HEADER_ASSETS = {
        headerImage: '{{ asset('img/Screenshot 2026-04-15 162812.jpg') }}',
      };
      const CERT_AUTOFILL_DATA = (() => {
        try {
          const raw = sessionStorage.getItem(CERT_AUTOFILL_KEY);
          if (!raw) return null;
          sessionStorage.removeItem(CERT_AUTOFILL_KEY);
          const parsed = JSON.parse(raw);
          return parsed && typeof parsed === 'object' ? parsed : null;
        } catch {
          return null;
        }
      })();

      function safeJsonParse(raw, fallback) {
        try {
          const parsed = raw ? JSON.parse(raw) : null;
          return parsed ?? fallback;
        } catch {
          return fallback;
        }
      }

      function loadRequests() {
        const arr = safeJsonParse(localStorage.getItem(REQUESTS_KEY), []);
        return Array.isArray(arr) ? arr : [];
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

      const DEFAULT_TEMPLATE = {
        // header
        barangayName: AUTO_HEADER_PRESET.barangayName,
        barangayAddress: AUTO_HEADER_PRESET.barangayAddress,
        certificateType: 'BARANGAY CERTIFICATE',
        contactNo: AUTO_HEADER_PRESET.contactNo,

        // body
        bodyHeading: 'TO WHOM IT MAY CONCERN:',
        mainBody:
          'This is to certify that (NAME), (AGE) years old, _________ is a bonafide resident of this barangay with postal address at (ADDRESS) St. Bo. Obrero Tondo, Manila, who is known to me as a person of good moral character and has no derogatory record as of this date.',
        purposeStatement:
          'This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in BADAC watchlist or drug personalities and not connected nor member of any left leaning group/organizations.',
        issuedLine:
          'This certification is being issued for whatever legal purpose it may serve.\n\nDone and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this (DATE), City of Manila.',

        // signature
        signName: 'MARIA MAGDALENA H. LEGASPI',
        signTitle: 'Barangay Chairwoman',
        signImage: '',

        // format
        borderColor: '#2b77d1',
        issueEmail: '',
      };

      function loadTemplate() {
        try {
          const raw = localStorage.getItem(TEMPLATE_KEY);
          const t = raw ? JSON.parse(raw) : null;
          if (!t || typeof t !== 'object') return { ...DEFAULT_TEMPLATE };
          return { ...DEFAULT_TEMPLATE, ...t, ...AUTO_HEADER_PRESET };
        } catch {
          return { ...DEFAULT_TEMPLATE };
        }
      }

      function saveTemplate(t) {
        localStorage.setItem(TEMPLATE_KEY, JSON.stringify({ ...t, ...AUTO_HEADER_PRESET }));
      }

      function applyPlaceholders(text, template) {
        const auto = CERT_AUTOFILL_DATA || {};
        const autoName = String(auto.name || '').trim();
        const autoAge = String(auto.age ?? '').trim();
        const autoAddress = String(auto.address || '').trim();
        const autoPurpose = String(auto.purpose || '').trim();
        const autoDate = String(auto.date || '').trim();
        const sample = {
          '(NAME)': autoName || 'JUAN DELA CRUZ',
          '(AGE)': autoAge || '21',
          '(ADDRESS)': autoAddress || '17 Sampaguita Street, Barangay 192',
          '(PURPOSE)': autoPurpose || 'Employment',
          '(DATE)': autoDate || new Date().toLocaleDateString(),
          '(BARANGAY)': template.barangayName || DEFAULT_TEMPLATE.barangayName,
        };
        let out = String(text || '');
        for (const k of Object.keys(sample)) out = out.split(k).join(sample[k]);
        return out;
      }

      function escapeHtml(s) {
        return String(s)
          .replaceAll('&', '&amp;')
          .replaceAll('<', '&lt;')
          .replaceAll('>', '&gt;');
      }

      function setPaperLogo(dataUrl) {
        const paper = document.getElementById('paper');
        const existing = paper.querySelector('[data-logo]');
        if (existing) existing.remove();
        if (!dataUrl) return;

        const img = document.createElement('img');
        img.src = dataUrl;
        img.alt = 'Barangay logo';
        img.setAttribute('data-logo', '1');
        img.style.position = 'absolute';
        img.style.left = '14px';
        img.style.top = '14px';
        img.style.width = '52px';
        img.style.height = '52px';
        img.style.objectFit = 'contain';
        img.style.borderRadius = '10px';
        img.style.background = '#f3f4f6';
        img.style.padding = '6px';
        paper.appendChild(img);
      }

      function setAutoHeaderDesign() {
        const paper = document.getElementById('paper');
        paper.querySelectorAll('[data-auto-header="1"]').forEach((el) => el.remove());
        paper.querySelectorAll('[data-auto-watermark="1"]').forEach((el) => el.remove());

        const headerImg = document.getElementById('pvHeaderImage');
        if (headerImg) {
          headerImg.src = AUTO_HEADER_ASSETS.headerImage;
        }

        paper.querySelectorAll('.cert-header-image-wrap, .body, .sig, .qr, .footer-note').forEach((el) => {
          el.style.position = 'relative';
          el.style.zIndex = '2';
        });
      }

      function hydrateForm(t) {
        const withAutoHeader = { ...t, ...AUTO_HEADER_PRESET };
        // header
        document.getElementById('barangayName').value = withAutoHeader.barangayName || '';
        document.getElementById('barangayAddress').value = withAutoHeader.barangayAddress || '';
        document.getElementById('certificateType').value = withAutoHeader.certificateType || '';
        document.getElementById('contactNo').value = withAutoHeader.contactNo || '';

        const autoHdrType = document.getElementById('autoHdrType');
        if (autoHdrType) autoHdrType.textContent = withAutoHeader.certificateType || DEFAULT_TEMPLATE.certificateType;

        // body
        document.getElementById('bodyHeading').value = withAutoHeader.bodyHeading || '';
        document.getElementById('mainBody').value = withAutoHeader.mainBody || '';
        document.getElementById('purposeStatement').value = withAutoHeader.purposeStatement || '';
        document.getElementById('issuedLine').value = withAutoHeader.issuedLine || '';

        // signature
        document.getElementById('signName').value = withAutoHeader.signName || '';
        document.getElementById('signTitle').value = withAutoHeader.signTitle || '';
        document.getElementById('signImageData').value = withAutoHeader.signImage || '';
        document.getElementById('signImage').value = '';
        document.getElementById('signImageMeta').textContent = withAutoHeader.signImage
          ? 'Saved signature image ready.'
          : 'No signature image selected.';

        // format
        document.getElementById('borderColor').value = withAutoHeader.borderColor || '';
        document.getElementById('issueEmail').value = withAutoHeader.issueEmail || '';
      }

      function readForm() {
        return {
          barangayName: AUTO_HEADER_PRESET.barangayName,
          barangayAddress: AUTO_HEADER_PRESET.barangayAddress,
          certificateType: String(document.getElementById('certificateType').value || '').trim(),
          contactNo: AUTO_HEADER_PRESET.contactNo,

          bodyHeading: String(document.getElementById('bodyHeading').value || '').trim(),
          mainBody: String(document.getElementById('mainBody').value || '').trim(),
          purposeStatement: String(document.getElementById('purposeStatement').value || '').trim(),
          issuedLine: String(document.getElementById('issuedLine').value || '').trim(),

          signName: String(document.getElementById('signName').value || '').trim(),
          signTitle: String(document.getElementById('signTitle').value || '').trim(),
          signImage: String(document.getElementById('signImageData').value || '').trim(),

          borderColor: String(document.getElementById('borderColor').value || '').trim(),
          issueEmail: String(document.getElementById('issueEmail').value || '').trim(),
        };
      }

      function renderSignatureImage(dataUrl) {
        const img = document.getElementById('pvSignImage');
        const src = String(dataUrl || '').trim();
        if (src && src.startsWith('data:image/')) {
          img.src = src;
          img.hidden = false;
          return;
        }
        img.removeAttribute('src');
        img.hidden = true;
      }

      function renderPreview(t) {
        const withAutoHeader = { ...t, ...AUTO_HEADER_PRESET };
        const pvType = document.getElementById('pvType');
        if (pvType) {
          pvType.textContent = withAutoHeader.certificateType || DEFAULT_TEMPLATE.certificateType;
        }

        const heading = applyPlaceholders(t.bodyHeading || DEFAULT_TEMPLATE.bodyHeading, t);
        const mainBody = applyPlaceholders(t.mainBody || DEFAULT_TEMPLATE.mainBody, t);
        const purpose = applyPlaceholders(t.purposeStatement || DEFAULT_TEMPLATE.purposeStatement, t);

        const bodyLines = [heading, '', ...mainBody.split('\n'), '', ...purpose.split('\n')];
        const bodyHtml = bodyLines
          .map(line => '<div>' + escapeHtml(line) + '</div>')
          .join('');
        document.getElementById('pvBody').innerHTML = bodyHtml;

        document.getElementById('pvIssued').textContent = applyPlaceholders(t.issuedLine || DEFAULT_TEMPLATE.issuedLine, t);
        document.getElementById('pvSignName').textContent = t.signName || DEFAULT_TEMPLATE.signName;
        document.getElementById('pvSignTitle').textContent = t.signTitle || DEFAULT_TEMPLATE.signTitle;
        renderSignatureImage(t.signImage || '');

        // Format
        const border = t.borderColor || DEFAULT_TEMPLATE.borderColor;
        document.getElementById('paper').style.setProperty('--certBorder', border);
        document.getElementById('paper').style.borderColor = border;
        const issueEmail = String(t.issueEmail || DEFAULT_TEMPLATE.issueEmail || '').trim();
        document.getElementById('pvEmail').textContent = issueEmail
          ? ('For verification, email: ' + issueEmail)
          : 'For verification, email: (Optional)';

        // Logo
        setAutoHeaderDesign();
      }

      // Tab switching
      function setActiveTab(tabName) {
        document.querySelectorAll('.cert-tab').forEach(btn => {
          const isActive = btn.getAttribute('data-tab') === tabName;
          btn.classList.toggle('active', isActive);
          btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        document.querySelectorAll('.tab-panel').forEach(panel => {
          panel.classList.toggle('active', panel.getAttribute('data-panel') === tabName);
        });
      }
      document.querySelectorAll('.cert-tab').forEach(btn => {
        btn.addEventListener('click', () => setActiveTab(btn.getAttribute('data-tab')));
      });

      const template = loadTemplate();
      hydrateForm(template);
      renderPreview(template);

      // Live preview wiring
      const inputIds = [
        'barangayName','barangayAddress','certificateType','contactNo',
        'bodyHeading','mainBody','purposeStatement','issuedLine',
        'signName','signTitle','borderColor','issueEmail'
      ];
      inputIds.forEach(id => {
        const el = document.getElementById(id);
        const evt = (el && el.type === 'checkbox') ? 'change' : 'input';
        if (el) el.addEventListener(evt, () => renderPreview(readForm()));
      });

      const signImageInput = document.getElementById('signImage');
      const signImageData = document.getElementById('signImageData');
      const signImageMeta = document.getElementById('signImageMeta');
      if (signImageInput) {
        signImageInput.addEventListener('change', () => {
          const file = signImageInput.files && signImageInput.files[0];
          if (!file) {
            signImageData.value = '';
            signImageMeta.textContent = 'No signature image selected.';
            renderPreview(readForm());
            return;
          }
          if (!String(file.type || '').startsWith('image/')) {
            alert('Please upload an image file for signature.');
            signImageInput.value = '';
            return;
          }

          const reader = new FileReader();
          reader.onload = () => {
            const result = String(reader.result || '');
            signImageData.value = result;
            signImageMeta.textContent = 'Selected: ' + file.name;
            renderPreview(readForm());
          };
          reader.onerror = () => {
            alert('Unable to read signature image. Please try again.');
          };
          reader.readAsDataURL(file);
        });
      }

      const saveModal = document.getElementById('saveModal');
      const saveOkBtn = document.getElementById('saveOkBtn');
      const saveCancelBtn = document.getElementById('saveCancelBtn');
      const saveCloseBtn = document.getElementById('saveClose');

      function openSaveModal() {
        if (!saveModal) return;
        saveModal.hidden = false;
        saveModal.classList.add('open');
      }

      function closeSaveModal() {
        if (!saveModal) return;
        saveModal.classList.remove('open');
        saveModal.hidden = true;
      }

      document.getElementById('saveTemplate').addEventListener('click', openSaveModal);
      if (saveOkBtn) {
        saveOkBtn.addEventListener('click', () => {
          const t = readForm();
          saveTemplate(t);
          renderPreview(t);
          closeSaveModal();
        });
      }
      if (saveCancelBtn) saveCancelBtn.addEventListener('click', closeSaveModal);
      if (saveCloseBtn) saveCloseBtn.addEventListener('click', closeSaveModal);
      if (saveModal) {
        saveModal.addEventListener('click', (e) => {
          if (e.target === saveModal) closeSaveModal();
        });
      }

      document.getElementById('resetTemplate').addEventListener('click', () => {
        localStorage.removeItem(TEMPLATE_KEY);
        hydrateForm({ ...DEFAULT_TEMPLATE });
        renderPreview({ ...DEFAULT_TEMPLATE });
        setActiveTab('body');
      });

      const templateLibraryModal = document.getElementById('templateLibraryModal');
      const templateLibraryClose = document.getElementById('templateLibraryClose');
      const templateLibraryList = document.getElementById('templateLibraryList');
      const templateLibraryCount = document.getElementById('templateLibraryCount');

      function normalizeFileName(value) {
        return String(value || '').trim().toLowerCase();
      }

      function getTemplatePresetFromFileName(fileName) {
        const n = normalizeFileName(fileName);
        const base = {
          bodyHeading: 'TO WHOM IT MAY CONCERN:',
          mainBody: DEFAULT_TEMPLATE.mainBody,
          purposeStatement: DEFAULT_TEMPLATE.purposeStatement,
          issuedLine: DEFAULT_TEMPLATE.issuedLine,
          signName: 'MARIA MAGDALENA H. LEGASPI',
          signTitle: 'Barangay Chairwoman',
        };

        if (n.includes('indigency')) {
          return {
            ...base,
            certificateType: 'BARANGAY INDIGENCY',
            mainBody: 'This is to certify that (NAME), of legal age, and a resident of (ADDRESS), belongs to an indigent family in this barangay.',
            purposeStatement: 'This certification is issued upon the request of the above-named person for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) for whatever legal purpose it may serve.',
          };
        }

        if (n.includes('good moral')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE OF GOOD MORAL CHARACTER',
            mainBody: 'This is to certify that (NAME), (AGE) years old, _________ is a bonafide resident of this barangay with postal address at (ADDRESS) St. Bo. Obrero Tondo, Manila, who is known to me as a person of good moral character and has no derogatory record as of this date.',
            purposeStatement: 'This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in BADAC watchlist or drug personalities and not connected nor member of any left leaning group/organizations.',
            issuedLine: 'This certification is being issued for whatever legal purpose it may serve.\n\nDone and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this (DATE), City of Manila.',
          };
        }

        if (n.includes('oneness')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE OF ONENESS',
            mainBody: 'This is to certify that (NAME), a resident of (ADDRESS), is recognized as one and the same person for legal and official purposes.',
            purposeStatement: 'Issued upon request for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) for record and identification purposes.',
          };
        }

        if (n.includes('1st-time job seeker') || n.includes('first-time job seeker')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE FOR 1ST-TIME JOB SEEKER',
            mainBody: 'This is to certify that (NAME), a resident of (ADDRESS), qualifies as a first-time job seeker under applicable laws and barangay records.',
            purposeStatement: 'Issued to support (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) in relation to first-time job seeker application.',
          };
        }

        if (n.includes('oath of undertaking')) {
          return {
            ...base,
            certificateType: 'OATH OF UNDERTAKING',
            bodyHeading: 'TO WHOM IT MAY CONCERN:',
            mainBody: 'I, (NAME), of legal age and a resident of (ADDRESS), hereby undertake and affirm the truthfulness of all information I submitted for this request.',
            purposeStatement: 'This undertaking is executed for (PURPOSE).',
            signTitle: 'Applicant / Affiant',
            issuedLine: 'Executed this (DATE) at (BARANGAY).',
          };
        }

        return {
          ...base,
          certificateType: 'BARANGAY CERTIFICATE',
          mainBody: 'This is to certify that (NAME), (AGE) years old, _________ is a bonafide resident of this barangay with postal address at (ADDRESS) St. Bo. Obrero Tondo, Manila, who is known to me as a person of good moral character and has no derogatory record as of this date.',
          purposeStatement: 'This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in BADAC watchlist or drug personalities and not connected nor member of any left leaning group/organizations.',
          issuedLine: 'This certification is being issued for whatever legal purpose it may serve.\n\nDone and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this (DATE), City of Manila.',
        };
      }

      function applyTemplateFromFileName(fileName) {
        const preset = getTemplatePresetFromFileName(fileName);
        const merged = {
          ...readForm(),
          ...preset,
        };

        hydrateForm(merged);
        renderPreview(merged);
        saveTemplate(merged);
        setActiveTab('body');
        closeTemplateLibrary();
        alert('Template applied: ' + fileName);
      }

      function renderTemplateLibrary() {
        const files = Array.isArray(CERT_TEMPLATE_FILES) ? CERT_TEMPLATE_FILES : [];
        templateLibraryCount.textContent = files.length
          ? (files.length + ' template file(s) found in public/file-cert')
          : 'No template files found in public/file-cert.';

        if (!files.length) {
          templateLibraryList.innerHTML = '<div style="padding:1rem;color:#6b7280">No files to show.</div>';
          return;
        }

        templateLibraryList.innerHTML = [
          '<table class="adm-table" aria-label="Template files">',
          '<thead><tr><th>File Name</th><th style="width:160px">Action</th></tr></thead>',
          '<tbody>',
          ...files.map((f, i) => {
            const safeName = escapeHtml(String(f.name || ''));
            const safeIndex = String(i);
            return '<tr>'
              + '<td>' + safeName + '</td>'
              + '<td>'
              + '<div style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center">'
              + '<button type="button" class="btn-mini approve" data-use-template="1" data-template-index="' + safeIndex + '">Use Template</button>'
              + '</div>'
              + '</td>'
              + '</tr>';
          }),
          '</tbody>',
          '</table>'
        ].join('');
      }

      function openTemplateLibrary() {
        renderTemplateLibrary();
        templateLibraryModal.hidden = false;
        templateLibraryModal.classList.add('open');
      }

      function closeTemplateLibrary() {
        templateLibraryModal.classList.remove('open');
        templateLibraryModal.hidden = true;
      }

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

      document.getElementById('addTemplate').addEventListener('click', openTemplateLibrary);
      if (templateLibraryClose) templateLibraryClose.addEventListener('click', closeTemplateLibrary);
      if (templateLibraryModal) {
        templateLibraryModal.addEventListener('click', (event) => {
          if (event.target === templateLibraryModal) closeTemplateLibrary();
        });
      }

      if (templateLibraryList) {
        templateLibraryList.addEventListener('click', (event) => {
          const btn = event.target.closest && event.target.closest('button[data-use-template]');
          if (!btn) return;
          const idx = Number(btn.getAttribute('data-template-index'));
          if (Number.isNaN(idx) || !CERT_TEMPLATE_FILES[idx]) return;
          applyTemplateFromFileName(CERT_TEMPLATE_FILES[idx].name || 'Template');
        });
      }

      const notifBellBtn = document.getElementById('notifBellBtn');
      const notifPanel = document.getElementById('notifPanel');
      let knownRefSet = new Set(loadRequests().map((r) => String(r?.ref || '').trim()).filter(Boolean));

      if (!localStorage.getItem(NOTIF_SEEN_KEY)) {
        writeSeenRefs(knownRefSet);
      }
      updateNotifications(loadRequests());

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

      window.addEventListener('storage', (event) => {
        if (event.key !== REQUESTS_KEY) return;
        detectNewRequestsAndNotify();
      });

      setInterval(detectNewRequestsAndNotify, 5000);

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
