<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>High Admin - Create Official</title>
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
    .card-title{font-size:18px;font-weight:700;color:#333;margin-bottom:4px}
    .card-subtitle{font-size:13px;color:#666;margin-bottom:24px}
    label{display:block;font-weight:600;margin-top:14px;margin-bottom:6px;font-size:14px;color:#333}
    input,select{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;box-sizing:border-box;font-size:14px}
    .form-row{display:flex;gap:15px;margin-top:12px}
    .form-row input{flex:1}
    .create-btn{background:#0b66c2;color:#fff;padding:12px 24px;border:none;border-radius:6px;font-weight:700;cursor:pointer;margin-top:20px;font-size:14px}
    .create-btn:hover{background:#0a5ab0}
  </style>
</head>
<body>
  @if ($errors->any())
    <div style="
        background-color: #fff5f5; 
        border-left: 5px solid #f56565; 
        color: #c53030; 
        padding: 16px; 
        margin-bottom: 24px; 
        border-radius: 4px; 
        font-family: sans-serif;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    " role="alert">
        
        <div style="font-weight: bold; margin-bottom: 8px; font-size: 16px;">
            Please correct the following errors:
        </div>

        <ul style="
            margin: 0; 
            padding-left: 20px; 
            list-style-type: disc;
            font-size: 14px;
            line-height: 1.5;
        ">
            @foreach ($errors->all() as $error)
                <li style="margin-bottom: 4px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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
        <a href="/dash" style="color:#fff"><span>🏠</span><span>Dashboard</span></a>
        <a href="/barangay" class="active" style="color:#fff"><span>👤</span><span>official Account</span></a>
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
          <div class="card-title">Create Barangay Official</div>
          <div class="card-subtitle">Add a new barangay official account</div>

          <div id="msgSuccess" style="background:#e6f9ef;color:#064e3b;padding:10px;border-radius:6px;margin-bottom:12px;display:none"></div>
          <div id="msgError" style="background:#ffecec;color:#c9302c;padding:10px;border-radius:6px;margin-bottom:12px;display:none"></div>

          <form id="createForm">
            <label>Full name</label>
            <input name="fullname" required />

            <div class="form-row">
              <input name="email" type="email" placeholder="Email" required />
              <input name="username" placeholder="Username" required />
            </div>

            <div class="form-row">
              <input name="password" type="password" minlength="6" placeholder="Password" required />
              <input name="passwordConfirm" type="password" minlength="6" placeholder="Confirm Password" required />
            </div>

            <label>Contact number</label>
            <input name="contact" placeholder="09XXXXXXXXX" />

            <label>Complete address</label>
            <input name="address" />

            <label>Role</label>
            <select name="role">
              <option value="admin" selected>Barangay Official (admin)</option>
            </select>

            <button type="submit" class="create-btn">Create Account</button>
          </form>
        </div>
      </section>
    </main>
  </div>

  <script>
    // Form submission - POST to backend
    document.getElementById('createForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const msgError = document.getElementById('msgError');
      const msgSuccess = document.getElementById('msgSuccess');
      const submitBtn = e.target.querySelector('button[type="submit"]');
      msgError.style.display = 'none';
      msgSuccess.style.display = 'none';

      const f = e.target;
      const data = {
        username: f.username.value.trim(),
        email: f.email.value.trim(),
        password: f.password.value,
        password_confirmation: f.passwordConfirm.value,
        fullname: f.fullname.value.trim(),
        contact: f.contact.value.trim() || null,
        address: f.address.value.trim() || null,
        role: f.role.value || 'admin'
      };

      if(!data.username || !data.email || !data.password){
        msgError.textContent = 'Please fill required fields.';
        msgError.style.display = 'block';
        return;
      }
      if(data.password !== data.password_confirmation){
        msgError.textContent = 'Passwords do not match.';
        msgError.style.display = 'block';
        return;
      }

      try{
        submitBtn.disabled = true;
        submitBtn.textContent = 'Saving...';

        const res = await fetch('/api/officers/register', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        });
        const body = await res.json().catch(() => ({}));
        if(res.ok){
          msgSuccess.textContent = 'Staff account created successfully!';
          msgSuccess.style.display = 'block';
          f.reset();
          setTimeout(() => {
            window.location.href = '/dash';
          }, 1500);
        } else {
          let errMsg = body.message || body.error || 'Failed to create account.';
          if (body.errors && typeof body.errors === 'object') {
            errMsg = Object.values(body.errors).flat().join(' ');
          }
          msgError.textContent = errMsg;
          msgError.style.display = 'block';
        }
      }catch(err){
        console.error('create admin err', err);
        msgError.textContent = 'Network error. Make sure backend is running.';
        msgError.style.display = 'block';
      } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Account';
      }
    });
  </script>
</body>
</html>