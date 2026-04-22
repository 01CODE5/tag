<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DIGIBARANGAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        
        <div class="bg-gradient-to-b from-[#4facfe] to-[#72c667] p-10 text-center text-white">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="w-20 h-20 object-contain" />
            </div>
            <h1 class="text-4xl font-bold tracking-tight">Admin</h1>
            <p class="text-sm opacity-90 mt-2">Login to access the admin dashboard</p>
        </div>

        <div class="p-8">
            <form id="adminLoginForm" action="{{ route('loginadmin.submit') }}" method="POST" class="space-y-6">
              @csrf
                <div>
                    <label for="login" class="block text-gray-800 font-bold mb-2">Email or Username</label>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        placeholder="Enter your Email or Username" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <div>
                    <label for="password" class="block text-gray-800 font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your Password" 
                        class="w-full px-4 py-3 bg-[#f1f4f9] border-none rounded-xl focus:ring-2 focus:ring-blue-400 outline-none transition-all placeholder:text-gray-400 text-gray-700"
                        required
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#4facfe] hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition-colors duration-300 shadow-md"
                >
                    Login
                </button>
            </form>

        </div>
    </div>

<div id="errorModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-sm rounded-xl shadow-xl p-6 text-center">
    <h2 class="text-lg font-bold mb-2">Login Error</h2>
    <p id="errorMessage" class="text-gray-600 mb-5">
      Access denied. Admin accounts only.
    </p>
    <button id="errorOkBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-full font-semibold">
      OK
    </button>
  </div>
</div>

<script>
  const errorModal = document.getElementById('errorModal');
  const errorMessage = document.getElementById('errorMessage');
  const errorOkBtn = document.getElementById('errorOkBtn');

  function showError(message) {
    errorMessage.textContent = message;
    errorModal.classList.remove('hidden');
  }

  function closeError() {
    errorModal.classList.add('hidden');
  }

  errorOkBtn.addEventListener('click', closeError);

  // click outside = close
  errorModal.addEventListener('click', (e) => {
    if (e.target === errorModal) closeError();
  });

  const adminLoginForm = document.getElementById('adminLoginForm');
  adminLoginForm.addEventListener('submit', async function (e) {
    e.preventDefault();
    const login = String(adminLoginForm.login.value || '').trim();
    const password = String(adminLoginForm.password.value || '').trim();
    const csrf = adminLoginForm.querySelector('input[name="_token"]')?.value || '';

    if (!login || !password) {
      showError('Please enter username/email and password.');
      return;
    }

    try {
      const res = await fetch(adminLoginForm.action, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ login, email: login, password })
      });

      const body = await res.json().catch(() => ({}));
      if (!res.ok) {
        showError(body.message || 'Login failed.');
        return;
      }

      window.location.replace('/dashboard');
    } catch (err) {
      showError('Network error. Please try again.');
    }
  });
</script>

</body>
</html>