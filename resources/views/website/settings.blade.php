<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Settings - DIGIBARANGAY</title>
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
        <a href="./certificate"><span class="ico">📄</span><span>Certificate Template</span></a>
        <a href="./resident"><span class="ico">👥</span><span>Residents record</span></a>
        <a class="active" href="./settings"><span class="ico">⚙️</span><span>Setting</span></a>
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
        <div class="adm-title">Settings</div>
        <div class="adm-subtitle">Update barangay profile, officials, contact info, and theme</div>

        <div class="settings-grid">
          <div class="adm-card settings-section">
            <h3>Barangay Profile</h3>
            <p class="muted">Basic information displayed on certificates and pages.</p>

            <div class="field">
              <label for="barangayName">Barangay Name</label>
              <input id="barangayName" type="text" placeholder="BARANGAY 192" />
            </div>
            <div class="field">
              <label for="barangayAddress">Address</label>
              <input id="barangayAddress" type="text" placeholder="City/Municipality, Province" />
            </div>
            <div class="field">
              <label for="barangayContact">Contact Number</label>
              <input id="barangayContact" type="text" placeholder="09XX-XXXX-XXXX" />
            </div>
          </div>

          <div class="adm-card settings-section">
            <h3>Officials</h3>
            <p class="muted">Names used for signatures and display.</p>

            <div class="field">
              <label for="captainName">Barangay Captain Name</label>
              <input id="captainName" type="text" placeholder="Juan Dela Cruz" />
            </div>
            <div class="field">
              <label for="secretaryName">Barangay Secretary Name</label>
              <input id="secretaryName" type="text" placeholder="Maria Santos" />
            </div>
            <div class="field">
              <label for="treasurerName">Barangay Treasurer Name</label>
              <input id="treasurerName" type="text" placeholder="Pedro Reyes" />
            </div>
          </div>

          <div class="adm-card settings-section">
            <h3>Theme</h3>
            <p class="muted">Optional colors for admin UI (demo).</p>

            <div class="field">
              <label for="sidebarColor">Sidebar Color</label>
              <input id="sidebarColor" type="text" placeholder="#0b5267" />
            </div>
            <div class="field">
              <label for="topbarColor">Topbar Color</label>
              <input id="topbarColor" type="text" placeholder="#6f8791" />
            </div>
            <div class="muted">(Next step) apply these colors dynamically across pages.</div>
          </div>

          <div class="adm-card settings-section">
            <h3>Data & Maintenance</h3>
            <p class="muted">Quick actions (localStorage-based demo).</p>

            <button class="btn" type="button" id="clearRequests">Clear all requests</button>
            <div class="muted" style="margin-top:.5rem">Warning: this removes `digibarangay_requests` in this browser.</div>
          </div>
        </div>

        <div class="settings-actions">
          <button class="btn-secondary" type="button" id="resetSettings">Reset</button>
          <button class="btn-save" type="button" id="saveSettings">Save Settings</button>
        </div>
      </section>
    </main>
  </div>

  <script>
    const SETTINGS_KEY = 'digibarangay_admin_settings_v1';

    const DEFAULTS = {
      barangayName: 'BARANGAY 192',
      barangayAddress: 'City/Municipality, Province',
      barangayContact: '',
      captainName: 'Barangay Captain Name',
      secretaryName: '',
      treasurerName: '',
      sidebarColor: '#0b5267',
      topbarColor: '#6f8791',
    };

    function loadSettings(){
      try{
        const raw = localStorage.getItem(SETTINGS_KEY);
        const s = raw ? JSON.parse(raw) : null;
        if(!s || typeof s !== 'object') return { ...DEFAULTS };
        return { ...DEFAULTS, ...s };
      }catch{ return { ...DEFAULTS }; }
    }

    function hydrate(s){
      for(const k of Object.keys(DEFAULTS)){
        const el = document.getElementById(k);
        if(el) el.value = s[k] ?? '';
      }
    }

    function readForm(){
      const out = {};
      for(const k of Object.keys(DEFAULTS)){
        const el = document.getElementById(k);
        out[k] = el ? String(el.value || '').trim() : '';
      }
      return out;
    }

    hydrate(loadSettings());

    document.getElementById('saveSettings').addEventListener('click', () => {
      const s = readForm();
      localStorage.setItem(SETTINGS_KEY, JSON.stringify(s));
      alert('Settings saved!');
    });

    document.getElementById('resetSettings').addEventListener('click', () => {
      localStorage.removeItem(SETTINGS_KEY);
      hydrate({ ...DEFAULTS });
    });

    document.getElementById('clearRequests').addEventListener('click', () => {
      if(!confirm('Clear ALL requests?')) return;
      localStorage.removeItem('digibarangay_requests');
      alert('Requests cleared.');
    });

    document.getElementById('adminLogout').addEventListener('click', () => {
      localStorage.removeItem('digibarangay_admin_logged_in');
      localStorage.removeItem('digibarangay_admin_email');
      window.location.href = './admin.html';
    });
  </script>
</body>
</html>
