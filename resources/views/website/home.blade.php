<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>My Website</title>
	<link rel="stylesheet" href="{{asset('css/styles.css')}}" />
</head>
<body>
	<header class="site-header">
		<div class="container header-inner">
			<div class="header-left">
				<a class="brand" href="./">
					<img src="{{ asset('img/logo_zed.png') }}" alt="DIGIBARANGAY logo" class="brand-logo" />
					<span class="brand-text">DIGIBARANGAY</span>
				</a>
			</div>
			<div class="header-right">
				<button class="btn login-btn" id="topLogin">Login</button>
			</div>
		</div>
	</header>

	<main>
		<section class="hero hero-large">
			<div class="container hero-inner">
				<h1 class="hero-title">Fast, Paperless and Secure</h1>
				<p class="hero-sub">Request your barangay clearance online in minutes</p>
				<button class="cta-btn" id="ctaRequest"><span class="cta-icon">📄</span> Request a certificate</button>
			</div>
		</section>

		<section id="features" class="features">
			<div class="container">
				<div class="cards-row">
					<div class="feature-card">
						<div class="feature-icon">📱</div>
						<h3>Mobile-First</h3>
						<p>Access from any device, optimized for smartphones.</p>
					</div>
					<div class="feature-card">
						<div class="feature-icon">🔒</div>
						<h3>Secure & verification</h3>
						<p>Digital clearances with QR code authentication.</p>
					</div>
					<div class="feature-card">
						<div class="feature-icon">🔁</div>
						<h3>Real-time status</h3>
						<p>Track your request status with instant notifications.</p>
					</div>
				</div>
			</div>
		</section>

		<section id="about" class="about">
			<div class="container">
				<h2>About</h2>
				<p>This is a starter frontend you can adapt to your project. Replace text, images, and colors to match your brand.</p>
			</div>
		</section>

		<section id="announcements" class="announcements">
			<div class="container">
				<h2>Barangay Announcement</h2>
				<div class="announcement-list">
					<div class="announcement">
						<h4>System Maintenance Notice</h4>
						<p>The system will undergo scheduled maintenance on October 28, 2025 from 2:00 AM to 4:00 AM.</p>
					</div>
					<div class="announcement">
						<h4>System Maintenance Notice</h4>
						<p>The system will undergo scheduled maintenance on October 28, 2025 from 2:00 AM to 4:00 AM.</p>
					</div>
				</div>
			</div>
		</section>
	</main>

	<footer class="site-footer">
		<div class="container">
			<p>&copy; <span id="year"></span> MySite. All rights reserved. • <a href="#" class="open-privacy">Data Privacy Notice</a></p>
		</div>
	</footer>

	<script src="./form.js" defer></script>
	<script src="./app.js" defer></script>

	<!-- Data Privacy modal -->
	<div id="privacyModal" class="modal-overlay" hidden>
		<div class="modal" role="dialog" aria-modal="true" aria-labelledby="privacyTitle">
			<button class="modal-close" id="privacyClose" aria-label="Close">✕</button>
			<div class="modal-header"><h2 id="privacyTitle">DATA PRIVACY NOTICE</h2></div>
			<div class="modal-body privacy-body">
				<p><strong>Paalaala sa Privacy ng Datos</strong></p>
				<p>Ang Barangay Administration ay nangangalaga sa inyong personal na impormasyon. Ang lahat ng datos na aming kinokolekta ay gagamitin lamang para sa mga opisyal na layunin ng barangay, gaya ng pagproseso ng Barangay Clearance, sertipikasyon, at iba pang kaukulang serbisyo.</p>
				<ul>
					<li><strong>Saklaw ng Datos na Kinokolekta</strong><br/>Pangalan, Tirahan, Kapanganakan, Valid ID at contact details, Layunin ng kahilingan (hal. trabaho, aplikasyon, negosyo)</li>
					<li><strong>Layunin ng Pagproseso</strong><br/>Para sa opisyal na dokumento at record ng barangay; Para sa tamang pag-verify ng pagkakakilanlan ng aplikante; Para sa maayos na paghahatid ng serbisyo sa komunidad.</li>
					<li><strong>Seguridad ng Datos</strong><br/>Ang lahat ng impormasyon ay itatago sa ligtas na sistema ng barangay. Tanging awtorisadong opisyal lamang ang may access sa inyong datos. Hindi ito ibabahagi sa ibang tao o organisasyon maliban kung hinihingi ng batas.</li>
					<li><strong>Panahon ng Pag-iingat ng Datos</strong><br/>Ang datos ay itatago lamang hangga't kailangan para sa layunin ng barangay administration o ayon sa itinakda ng batas.</li>
					<li><strong>Karapatan ng Mamamayan</strong><br/>May karapatan kayong humiling ng access, correction, o pagbura ng inyong personal na impormasyon. Maaari kayong makipag-ugnayan sa aming Barangay Data Protection Officer para sa anumang concern.</li>
				</ul>
				<div class="privacy-actions">
					<label class="switch"><input type="checkbox" id="agreeTermsCheckbox"/> <span>I agree to the <a href="./terms.html">Term & Condition</a></span></label>
					<label class="switch"><input type="checkbox" id="agreePrivacyCheckbox"/> <span>I agree to the <a href="#">Data Privacy Notice</a></span></label>
				</div>
				<div class="form-actions">
					<button id="privacySubmit" class="btn primary" type="button" disabled>SUBMIT</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Login Modal -->
	<div id="loginModal" class="modal-overlay" hidden>
		<div class="modal" role="dialog" aria-modal="true" aria-labelledby="loginTitle">
			<button class="modal-close" id="loginModalClose" aria-label="Close">✕</button>
			<div class="modal-header"><h2 id="loginTitle">Resident Portal</h2></div>
			<div class="modal-body">
				<p class="muted">Login to access your account</p>
				<form id="loginModalForm" class="modal-form" novalidate>
					<label>Username & Email Address
						<input name="user" type="text" placeholder="JaneD or JaneDelacruz@gmail.com" required />
					</label>
					<label>Password
						<div class="input-with-toggle">
							<input name="password" type="password" required />
							<button type="button" class="toggle-pw" aria-label="Show password">👁️</button>
						</div>
					</label>
					<label class="inline"><input type="checkbox" id="loginRemember"/> Remember me</label>
					<div class="form-actions">
						<button class="btn primary" type="submit"><span class="icon">➜</span> <span>LOGIN</span></button>
					</div>
					<p class="muted">Don't have an account? <a href="#" class="open-register">REGISTER HERE</a></p>
					<button type="button" class="btn secondary" id="loginBack">← BACK</button>
				</form>
			</div>
		</div>
	</div>

	    </div>
		</div>
		</div>

		<!-- Register Modal (small box like login) -->
		<div id="registerModal" class="modal-overlay" hidden>
			<div class="modal" role="dialog" aria-modal="true" aria-labelledby="registerTitle">
				<button class="modal-close" id="registerModalClose" aria-label="Close">✕</button>
				<div class="modal-header"><h2 id="registerTitle">Create Resident Account</h2></div>
				<div class="modal-body">
					<form id="registerModalForm" class="modal-form" novalidate>
						<fieldset>
							<legend>Account Information</legend>
							<label>Username (required)
								<input name="username" type="text" required />
							</label>
							<label>Email address (required)
								<input name="email" type="email" required />
							</label>
							<label>Password (min 6 characters)
								<input name="password" type="password" minlength="6" required />
							</label>
							<label>Confirm password
								<input name="passwordConfirm" type="password" minlength="6" required />
							</label>
						</fieldset>

						<fieldset>
							<legend>Personal Information</legend>
							<label>Full name (required)
								<input name="fullname" type="text" required />
							</label>
							<label>Contact number (optional, format: 09XX-XXXX-XXXX)
								<input name="contact" type="tel" pattern="^09\d{2}-\d{4}-\d{4}$" placeholder="09XX-XXXX-XXXX" />
							</label>
							<label>Age (required)
								<input name="age" type="number" min="1" required />
							</label>
							<label>Complete address (example: House No., Street, Barangay 192)
								<input name="address" type="text" required />
							</label>
						</fieldset>

						<div class="form-actions">
							<button type="submit" class="btn primary">Register Account</button>
							<button type="button" class="btn" id="registerBack">← BACK</button>
						</div>
					</form>
				</div>
			</div>
		</div>

 
</body>
</html>
