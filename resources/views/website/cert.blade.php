<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Certificate Management - DIGIBARANGAY</title>
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
        <a href="./dashboard"><span class="ico">🏠</span><span>Dashboard</span></a>
        <a class="active" href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a href="./settings"><span class="ico">⚙️</span><span>Setting</span></a>
      </nav>
      <div class="adm-sidebar-footer">
        <button class="adm-logout" type="button" id="adminLogout"><span class="ico">⎋</span><span>Logout</span></button>
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
        <div class="cert-grid">
          <section class="adm-card cert-panel">
            <div class="cert-head">
              <div>
                <h2>CERTIFICATE MANAGEMENT</h2>
                <div class="hint"><span class="i">i</span><span>Customize your certificate. Use placement like <strong>(AGE)</strong> and <strong>(NAME)</strong></span></div>
              </div>
              <button class="add-template" type="button" id="addTemplate">+ Add Template</button>
            </div>

            <div class="cert-tabs" role="tablist" aria-label="Certificate tabs">
              <button class="cert-tab active" type="button" data-tab="header" aria-selected="true">Header</button>
              <button class="cert-tab" type="button" data-tab="body" aria-selected="false">Body</button>
              <button class="cert-tab" type="button" data-tab="signature" aria-selected="false">Signature</button>
              <button class="cert-tab" type="button" data-tab="format" aria-selected="false">Format</button>
            </div>

            <div class="tab-panels">
              <!-- HEADER PANEL -->
              <div class="tab-panel active" data-panel="header">
                <div class="upload-box">
                  <p class="title">Certificated Logo</p>
                  <label class="upload-cta" for="logoInput">
                    <span>⬆</span>
                    <div>
                      Click to Upload logo
                      <small id="logoName">No file chosen</small>
                    </div>
                  </label>
                  <input id="logoInput" type="file" accept="image/png,image/jpeg" hidden />
                  <div class="upload-meta">PNG, JPG up to 5MB</div>
                </div>

                <div class="field-grid" style="margin-top:1rem">
                  <div class="field">
                    <label for="barangayName">Barangay Name</label>
                    <input id="barangayName" type="text" placeholder="BARANGAY 192" />
                  </div>
                  <div class="field">
                    <label for="contactNo">Contact No (optional)</label>
                    <input id="contactNo" type="text" placeholder="0930-123-4567" />
                  </div>
                  <div class="field span-2">
                    <label for="barangayAddress">Address</label>
                    <input id="barangayAddress" type="text" placeholder="City/Municipality, Province" />
                  </div>
                  <div class="field span-2">
                    <label for="certificateType">Type of Certificate</label>
                    <input id="certificateType" type="text" placeholder="BARANGAY CLEARANCE" />
                  </div>
                </div>
              </div>

              <!-- BODY PANEL -->
              <div class="tab-panel" data-panel="body">
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
              </div>

              <!-- FORMAT PANEL -->
              <div class="tab-panel" data-panel="format">
                <div class="field">
                  <label for="borderColor">Border Color</label>
                  <input id="borderColor" type="text" placeholder="#2b77d1" />
                </div>
                <div class="field">
                  <label class="muted" style="display:flex;align-items:center;gap:.5rem">
                    <input id="showQR" type="checkbox" /> Show QR box
                  </label>
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
              <div class="center">
                <div class="seal">🛡</div>
                <div class="tiny">Republic of the Philippines</div>
                <div style="font-weight:900" id="pvBarangay">BARANGAY 192</div>
                <div class="tiny" id="pvAddress">City/Municipality, Province</div>
                <h3 id="pvType">BARANGAY CLEARANCE</h3>
                <div class="tiny" id="pvContact">Contact No: (Optional)</div>
              </div>

              <div class="body" id="pvBody"></div>

              <div class="sig">
                <div class="tiny" id="pvIssued">Issued this (DATE) at BARANGAY 192.</div>
                <div style="text-align:right">
                  <div class="line"></div>
                  <div style="font-weight:900;font-size:.78rem;margin-top:.35rem" id="pvSignName">Barangay Captain Name</div>
                  <div class="tiny" id="pvSignTitle">Punong Barangay</div>
                </div>
              </div>

              <div class="qr" id="pvQR">QR</div>
              <div class="footer-note">This is a digitally issued certificate. The issuance is effective without official seal and signature.</div>
            </div>
          </aside>
        </div>
      </section>
    </main>
  </div>

  <script>
    const TEMPLATE_KEY = 'digibarangay_certificate_template_v2';
    const LOGO_KEY = 'digibarangay_certificate_logo_dataurl_v2';

    const DEFAULT_TEMPLATE = {
      // header
      barangayName: 'BARANGAY 192',
      barangayAddress: 'City/Municipality, Province',
      certificateType: 'BARANGAY CLEARANCE',
      contactNo: '',

      // body
      bodyHeading: 'TO WHOM IT MAY CONCERN:',
      mainBody:
        'This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), is known to be of good moral character and has no derogatory records filed in this barangay.',
      purposeStatement:
        'This certification is issued upon the request of the above-named person for (PURPOSE).',
      issuedLine: 'Issued this (DATE) at (BARANGAY).',

      // signature
      signName: 'Barangay Captain Name',
      signTitle: 'Punong Barangay',

      // format
      borderColor: '#2b77d1',
      showQR: true,
    };

    function loadTemplate() {
      try {
        const raw = localStorage.getItem(TEMPLATE_KEY);
        const t = raw ? JSON.parse(raw) : null;
        if (!t || typeof t !== 'object') return { ...DEFAULT_TEMPLATE };
        return { ...DEFAULT_TEMPLATE, ...t };
      } catch {
        return { ...DEFAULT_TEMPLATE };
      }
    }

    function saveTemplate(t) {
      localStorage.setItem(TEMPLATE_KEY, JSON.stringify(t));
    }

    function applyPlaceholders(text, template) {
      const sample = {
        '(NAME)': 'JUAN DELA CRUZ',
        '(AGE)': '21',
        '(ADDRESS)': '17 Sampaguita Street, Barangay 192',
        '(PURPOSE)': 'Employment',
        '(DATE)': new Date().toLocaleDateString(),
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

    function hydrateForm(t) {
      // header
      document.getElementById('barangayName').value = t.barangayName || '';
      document.getElementById('barangayAddress').value = t.barangayAddress || '';
      document.getElementById('certificateType').value = t.certificateType || '';
      document.getElementById('contactNo').value = t.contactNo || '';

      // body
      document.getElementById('bodyHeading').value = t.bodyHeading || '';
      document.getElementById('mainBody').value = t.mainBody || '';
      document.getElementById('purposeStatement').value = t.purposeStatement || '';
      document.getElementById('issuedLine').value = t.issuedLine || '';

      // signature
      document.getElementById('signName').value = t.signName || '';
      document.getElementById('signTitle').value = t.signTitle || '';

      // format
      document.getElementById('borderColor').value = t.borderColor || '';
      document.getElementById('showQR').checked = !!t.showQR;
    }

    function readForm() {
      return {
        barangayName: String(document.getElementById('barangayName').value || '').trim(),
        barangayAddress: String(document.getElementById('barangayAddress').value || '').trim(),
        certificateType: String(document.getElementById('certificateType').value || '').trim(),
        contactNo: String(document.getElementById('contactNo').value || '').trim(),

        bodyHeading: String(document.getElementById('bodyHeading').value || '').trim(),
        mainBody: String(document.getElementById('mainBody').value || '').trim(),
        purposeStatement: String(document.getElementById('purposeStatement').value || '').trim(),
        issuedLine: String(document.getElementById('issuedLine').value || '').trim(),

        signName: String(document.getElementById('signName').value || '').trim(),
        signTitle: String(document.getElementById('signTitle').value || '').trim(),

        borderColor: String(document.getElementById('borderColor').value || '').trim(),
        showQR: !!document.getElementById('showQR').checked,
      };
    }

    function renderPreview(t) {
      document.getElementById('pvBarangay').textContent = t.barangayName || DEFAULT_TEMPLATE.barangayName;
      document.getElementById('pvAddress').textContent = t.barangayAddress || DEFAULT_TEMPLATE.barangayAddress;
      document.getElementById('pvType').textContent = t.certificateType || DEFAULT_TEMPLATE.certificateType;
      document.getElementById('pvContact').textContent = t.contactNo ? ('Contact No: ' + t.contactNo) : 'Contact No: (Optional)';

      const heading = applyPlaceholders(t.bodyHeading || DEFAULT_TEMPLATE.bodyHeading, t);
      const mainBody = applyPlaceholders(t.mainBody || DEFAULT_TEMPLATE.mainBody, t);
      const purpose = applyPlaceholders(t.purposeStatement || DEFAULT_TEMPLATE.purposeStatement, t);

      const bodyHtml = [heading, '', mainBody, '', purpose]
        .map(line => '<div>' + escapeHtml(line) + '</div>')
        .join('');
      document.getElementById('pvBody').innerHTML = bodyHtml;

      document.getElementById('pvIssued').textContent = applyPlaceholders(t.issuedLine || DEFAULT_TEMPLATE.issuedLine, t);
      document.getElementById('pvSignName').textContent = t.signName || DEFAULT_TEMPLATE.signName;
      document.getElementById('pvSignTitle').textContent = t.signTitle || DEFAULT_TEMPLATE.signTitle;

      // Format
      const border = t.borderColor || DEFAULT_TEMPLATE.borderColor;
      document.getElementById('paper').style.setProperty('--certBorder', border);
      document.getElementById('paper').style.borderColor = border;
      document.getElementById('pvQR').style.display = (t.showQR ? 'flex' : 'none');

      // Logo
      setPaperLogo(localStorage.getItem(LOGO_KEY));
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
      'signName','signTitle','borderColor','showQR'
    ];
    inputIds.forEach(id => {
      const el = document.getElementById(id);
      const evt = (el && el.type === 'checkbox') ? 'change' : 'input';
      if (el) el.addEventListener(evt, () => renderPreview(readForm()));
    });

    document.getElementById('saveTemplate').addEventListener('click', () => {
      const t = readForm();
      saveTemplate(t);
      renderPreview(t);
      alert('Template saved!');
    });

    document.getElementById('resetTemplate').addEventListener('click', () => {
      localStorage.removeItem(TEMPLATE_KEY);
      localStorage.removeItem(LOGO_KEY);
      document.getElementById('logoName').textContent = 'No file chosen';
      hydrateForm({ ...DEFAULT_TEMPLATE });
      renderPreview({ ...DEFAULT_TEMPLATE });
      setActiveTab('header');
    });

    document.getElementById('addTemplate').addEventListener('click', () => {
      alert('Add Template (next step)');
    });

    document.getElementById('logoInput').addEventListener('change', (e) => {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      document.getElementById('logoName').textContent = file.name;
      if (file.size > 5 * 1024 * 1024) {
        alert('File too big (max 5MB).');
        return;
      }
      const reader = new FileReader();
      reader.onload = () => {
        try {
          localStorage.setItem(LOGO_KEY, String(reader.result || ''));
          renderPreview(readForm());
        } catch {
          alert('Unable to save logo.');
        }
      };
      reader.readAsDataURL(file);
    });

    document.getElementById('adminLogout').addEventListener('click', () => {
      localStorage.removeItem('digibarangay_admin_logged_in');
      localStorage.removeItem('digibarangay_admin_email');
      window.location.href = './admin.html';
    });
  </script>
</body>
</html>
