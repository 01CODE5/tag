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
      padding:40px 60px 80px;
      background:#fff;
      border:1px solid #d7dde5;
      box-shadow:0 12px 28px rgba(2,6,23,.10);
      position:relative;
      overflow:hidden;
      font-family:"Times New Roman", Times, serif;
      color:#111827;
    }

    .paper::before{
      content:'';
      position:absolute;
      left:50%;
      top:54%;
      width:430px;
      height:430px;
      transform:translate(-50%, -50%);
      background-image:url('{{ asset('img/Barangay Official Logo.png') }}');
      background-repeat:no-repeat;
      background-position:center;
      background-size:contain;
      opacity:.12;
      filter:grayscale(100%) blur(1.8px);
      pointer-events:none;
      z-index:1;
    }

    .paper .center{
      text-align:center;
      line-height:1.25;
    }

    .paper .cert-header{
      text-align:center;
      margin-bottom:20px;
      position:relative;
    }

    .paper .cert-header-seals{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:10px;
    }

    .paper .cert-header-seal{
      width:60px;
      height:60px;
      border-radius:50%;
      border:2px solid #111827;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:10px;
      font-weight:bold;
      text-align:center;
      background:#fff;
    }

    .paper .cert-header-text{
      font-size:12px;
      line-height:1.3;
      margin:5px 0;
    }

    .paper .cert-header-divider{
      border-top:2px solid #111827;
      margin:10px 0;
    }

    .paper .cert-header-image-wrap{
      margin:2px auto 30px;
      text-align:center;
    }

    .paper .cert-header-image{
      display:block;
      width:100%;
      max-width:750px;
      margin:0 auto;
      height:170%;
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
      margin-bottom:64px;
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
      bottom:18px;
      text-align:left;
      font-size:11px;
      line-height:1.35;
      color:#111827;
      letter-spacing:.02em;
    }

    .paper .email-note{
      position:absolute;
      right:72px;
      bottom:44px;
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
        margin-bottom:36px;
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

    .preview-head {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:.75rem;
      margin-bottom:.6rem;
    }

    .preview-view-btn {
      border:1px solid #2b77d1;
      background:#fff;
      color:#2b77d1;
      border-radius:8px;
      padding:.42rem .72rem;
      font-size:.82rem;
      font-weight:700;
      cursor:pointer;
      white-space:nowrap;
    }

    .preview-view-btn:hover {
      background:#eef5ff;
    }

    #pdfPreviewModal .modal {
      width:min(99vw, 1380px);
      max-width:1380px;
      max-height:95vh;
      overflow:hidden;
      padding:0;
      display:flex;
      flex-direction:column;
    }

    #pdfPreviewModal .modal-header {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:.75rem;
      padding:.9rem 1rem;
      border-bottom:1px solid #e5e7eb;
      background:#fff;
    }

    #pdfPreviewModal .modal-body {
      padding:.75rem;
      background:#f3f4f6;
      overflow:auto;
    }

    #pdfPreviewMount {
      min-height:0;
      display:flex;
      align-items:flex-start;
      justify-content:center;
      padding:.5rem 1rem .75rem;
    }

    #pdfPreviewMount .paper {
      width:794px;
      max-width:794px;
      min-width:794px;
      margin:0;
      zoom:1.04;
      transform-origin:top center;
      box-shadow:0 10px 24px rgba(2,6,23,.15);
    }

    @media (max-width:1200px){
      #pdfPreviewMount .paper {
        zoom:1.05;
      }
    }

    @media (max-width:860px){
      #pdfPreviewMount .paper {
        zoom:1;
        min-width:unset;
        width:100%;
        max-width:100%;
      }
    }
  </style>
  </head>
  @php
    // PDF content with HTML markup for formatting (simulate PDF layout)
    $pdfTemplateData = [
      ["name" => "BRGY CERTIFICATE of ONENESS.pdf", "content" => '<div style="text-align:center;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">CERTIFICATION</div><div style="margin-top:2.5em;text-align:-webkit-left;font-size:1.1rem;font-weight:bold;">TO WHOM IT MAY CONCERN:</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">This is to certify that <u>______________________________</u> and <u>______________________________</u>, both of which are <u>______</u> years old and both with postal address of <u>______________________________</u> St. Bo. Obrero Tondo, Manila, is the <b>same person</b> and resident of Barangay 192.<br><br>This certification was issued upon the request of the above-mentioned name for any legal purposes that may serve him/her best.<br><br>Issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this <u>_____</u> day of <u>_____________</u>, <u>______</u> City of Manila.</div><div style="margin-top:3.5em;text-align:right;font-weight:bold;font-size:1.1rem;"><div style="display:inline-block;text-align:center;"><div><br><span style="font-weight:normal;"></span></div></div></div><div style="position:absolute;bottom:15px;left:15px;font-size:10px;"></div>'],
      ["name" => "BRGY CERTIFICATE with Good Moral.pdf", "content" => '<div style="text-align:center;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">CERTIFICATION</div><div style="margin-top:2.5em;text-align:-webkit-left;font-size:1.1rem;font-weight:bold;">TO WHOM IT MAY CONCERN:</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">This is to certify that <u>______________________________</u>, <u>____</u> years old, <u>_________</u> is a bonafide resident of this barangay with postal address at <u>______________________________</u> St. Bo. Obrero Tondo, Manila, who is known to me as a person of <b>good moral character</b> and has <b>no derogatory record</b> as of this date.<br><br>This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in <b>BADAC</b> watchlist or drug personalities and not connected nor member of any left leaning group/organizations.<br><br>This certification is being issued for whatever legal purpose it may serve.<br><br>Done and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this <u>____</u> day of <u>_____________</u>, <u>______</u>, City of Manila.</div><div style="margin-top:3.5em;text-align:right;font-weight:bold;font-size:1.1rem;"><div style="display:inline-block;text-align:center;"><div><br><span style="font-weight:normal;"></span></div></div></div><div style="position:absolute;bottom:15px;left:15px;font-size:10px;"></div>' ],
      ["name" => "BRGY CERTIFICATE.pdf", "content" => '<div style="text-align:center;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">CERTIFICATION</div><div style="margin-top:2.5em;text-align:-webkit-left;font-size:1.1rem;font-weight:bold;">TO WHOM IT MAY CONCERN:</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">This is to certify that <u>______________________________</u>, <u>______</u> years old, <u>_________</u>, a resident of Barangay 192 with postal address at <u>______________________________</u> St. Bo. Obrero Tondo Manila.<br><br>This certification was issued upon the request of the above-mentioned name for any legal purposes that may serve him/her best.<br><br><b>AS PER REQUIREMENT IN SUPPORT OF HIS/HER DOCUMENT:</b><br><span style="display:inline-block;width:49%">&#x2610; Employment/Work Purposes</span><span style="display:inline-block;width:49%">&#x2610; Medical Purpose</span><br><span style="display:inline-block;width:49%">&#x2610; School Requirement/Purpose</span><span style="display:inline-block;width:49%">&#x2610; Vending Permit</span><br><span style="display:inline-block;width:49%">&#x2610; Hospital Purposes</span><span style="display:inline-block;width:49%">&#x2610; Bank Transaction</span><br><span style="display:inline-block;width:49%">&#x2610; SSS/GSIS Requirement</span><span style="display:inline-block;width:49%">&#x2610; Transfer of Resident</span><br><span style="display:inline-block;width:49%">&#x2610; Senior ID and Booklet</span><span style="display:inline-block;width:49%">&#x2610; Others: ____________</span><br><br>IN WITNESS WHERE OF I have hereunto set my hand and affixed the official seal of this office.<br>Issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this <u>___</u>th day of <u>_____________</u>, City of Manila.</div><div style="margin-top:3.5em;text-align:right;font-weight:bold;font-size:1.1rem;"><div style="display:inline-block;text-align:center;"><div><br><span style="font-weight:normal;"></span></div></div></div><div style="position:absolute;bottom:15px;left:15px;font-size:10px;"></div>' ],
      ["name" => "BRGY INDIGENCY.pdf", "content" => '<div style="text-align:center;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">CERTIFICATION</div><div style="text-align:-webkit-left;font-size:1.2rem;font-weight:bold;">INDIGENT</div><div style="margin-top:2.5em;text-align:center;font-size:1.1rem;font-weight:bold;">TO WHOM IT MAY CONCERN:</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">This is to certify that <u>______________________________</u>, <u>______</u> years old, , is a resident of Barangay 192, with postal address at Street Bo. Obrero Tondo Manila.<br><br>This further certifies that the subject person concerned is known to us that belong to the <b>INDIGENT FAMILY</b> of this Barangay. Their family have no sufficient income and barely enough to meet day to day needs.<br><br>This certification was issued upon the request of the above-mentioned person.<br><br><b>IN WITNESS WHERE OF</b> I have hereunto set my hand and affixed the official seal of this office.<br>Issued in the Office of the Barangay Chairman, Barangay 192 Zone 17 District II this <u>___</u>th day of <u>_____________</u>, City of Manila.</div><div style="margin-top:3.5em;text-align:right;font-weight:bold;font-size:1.1rem;"><div style="display:inline-block;text-align:center;"><div><br><span style="font-weight:normal;"></span></div></div></div><div style="position:absolute;bottom:15px;left:15px;font-size:10px;"></div>' ],
      ["name" => "CERTIFICATE FOR 1ST-TIME JOB SEEKER.pdf", "content" => '<div style="text-align:center;font-size:1.1rem;font-weight:bold;">Barangay Certificate Number 2026-01.</div><div style="text-align:-webkit-left;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">CERTIFICATION</div><div style="text-align:center;font-size:1.1rem;font-weight:bold;">(FIRST TIME JOB SEEKERS ASSISTANCE ACT – RA 11261)</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">This is to certify that Mr./Ms. <u>______________________________</u>, <u>______</u> years old, a resident of <u>______________________________</u> St., Bo. Obrero, Tondo Manila for <u>______</u> years, is a qualified availee of <b>RA 11261 for the First Time Job Seeker Act of 2019</b>.<br><br>I further certify that the holder/bearer was informed of his/her rights, including the duties and responsibilities accorded by <b>RA 11261</b> through the <b>OATH OF UNDERTAKING</b> he/she has signed and executed in the presence of our Barangay Officials.<br><br>Signed this <u>___</u> day of <u>_____________</u>, in Barangay 192 Zone 17 District 2 Tondo, City of Manila.<br><br>This certification is valid until <u>_____________</u>, one (1) year from the date of issuance.<br><br></b><br><div style="display:inline-block;text-align:center;margin-top:1em;"><i style="/*width:180px;*//*max-height:72px;*/display:block;/*margin:0 auto 0.2em auto;*/ text-align:end"><div><br><span style="font-weight:normal;"></span></div></div>Witnessed by:<br><div style="display:inline-block;text-align:end;margin-top:1em;">_________________________<br>(NAME)<br>(Position)</div><div style="position:absolute;bottom:15px;left:15px;font-size:10px;"></div>' ],
      ["name" => "OATH OF UNDERTAKING - NEW.pdf", "content" => '<div style="text-align:center;font-size:2rem;font-weight:bold;text-decoration:underline;letter-spacing:0.2em;">OATH OF UNDERTAKING</div><div style="margin-top:2.5em;text-align:justify;font-size:1.1rem;">I, <u>______________________________</u>, <u>______</u> years old, is a resident of this barangay with postal address at <u>______________________________</u> Street Bo. Obrero Tondo, Manila for <u>______</u> years, availing the benefits of <b>Republic Act 11261</b>, otherwise known as the <b>First Time Jobseekers Act of 2019</b>, do hereby declare, agree and undertake to abide and be bound by the following:<br><ol style="margin-left:2em;margin-top:1em;"> <li>That this is the first time that I will actively look for a job, and therefore requesting that a Barangay Certification be issued in my favor to avail the benefits of the law;</li> <li>That I am aware that the benefit and privilege/s under the said law shall be valid only for one (1) year from the date of the Barangay Certification issued;</li> <li>That I can avail the benefits of the law only once;</li> <li>That I understand that my personal information shall be included in the Roster/List of First Time Jobseekers and will not be used for any unlawful purpose;</li> <li>That I will inform and/or report to the Barangay personally, through text or other means, or through my family/relatives once I get employed;</li> <li>That I am not a beneficiary of the JobStart Program under R.A. No. 10869 and other laws that give similar exemptions for the documents or transactions exempted R.A. No. 11261;</li> <li>That if issued the requested Certification, I will not use the same in any fraud, neither falsify nor help and/or assist in the fabrication of the said certification.</li> <li>That this undertaking is made solely for the purpose of obtaining a Barangay Certification consistent with the objective of R.A. 11261 and not for any other purposes.</li> <li>THAT I CONSENT TO THE USE OF MY PERSONAL INFORMATION PURSUANT TO THE Data Piracy Act and other applicable laws, rules and regulations.</li></ol><div style="margin-top:2em;"><div style="text-align:left;">Signed this <u>______</u> day of <u>_____________</u> at the Barangay Hall, Tondo, Manila.</div><div style="margin-top:1em;text-align:right;"><u>_____________________________</u><br>First Time Jobseeker</div>' ]
    ];
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
          <a href="./rest-acc"><span class="ico">🔐</span><span>Acc Resident</span></a>
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
                    <input id="bodyHeading" type="text" placeholder="Body Heading" />
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
              <div class="preview-head">
                <div class="preview-title"><span class="dot"></span><span>Live Preview</span></div>
                <button class="preview-view-btn" type="button" id="viewPdfBtn">👁 View PDF Format</button>
              </div>
              <div class="paper" id="paper" style="border-color: var(--certBorder, #2b77d1)">
                <div class="cert-header-image-wrap">
                  <img id="pvHeaderImage" class="cert-header-image" alt="Certificate header" src="{{ asset('img/Screenshot 2026-04-15 162812.jpg') }}" />
                </div>
                
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
                <div class="email-note" id="pvEmailNote"></div>
                
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

<div id="pdfPreviewModal" class="modal-overlay" hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="pdfPreviewTitle">
    <div class="modal-header">
      <h2 id="pdfPreviewTitle" style="margin:0">PDF Format Preview</h2>
      <div style="display:flex;gap:.5rem;align-items:center;">
        <button class="btn primary" type="button" id="pdfPrintBtn">Save PDF</button>
        <button class="btn" type="button" id="pdfPreviewCloseBtn">Close</button>
      </div>
    </div>
    <div class="modal-body">
      <div id="pdfPreviewMount"></div>
    </div>
  </div>
</div>

<div id="statusModal" class="modal-overlay" hidden>
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle" style="max-width:420px;">
    <button class="modal-close" id="statusModalClose" aria-label="Close">✕</button>
    <div class="modal-header">
      <h2 id="statusModalTitle">Notice</h2>
    </div>
    <div class="modal-body" style="text-align:center;">
      <p id="statusModalMessage" style="margin:0;line-height:1.5;"></p>
      <div style="display:flex;justify-content:center;margin-top:16px;">
        <button class="btn primary" type="button" id="statusModalOkBtn" style="min-width:120px;">OK</button>
      </div>
    </div>
  </div>
</div>

<div id="notifToastHost" style="position:fixed;top:16px;right:16px;z-index:120;display:flex;flex-direction:column;gap:8px;pointer-events:none;"></div>

    <script>
      const TEMPLATE_KEY = 'digibarangay_certificate_template_v2';
      const TEMPLATE_OVERRIDE_KEY = 'digibarangay_cert_template_override_v1';
      const LOGO_KEY = 'digibarangay_certificate_logo_dataurl_v2';
      const CERT_AUTOFILL_KEY = 'digibarangay_cert_autofill';
  const REQUESTS_KEY = 'digibarangay_requests';
  const NOTIF_SEEN_KEY = 'digibarangay_seen_request_refs_v1';
      // PDF template data with HTML markup for formatting
      const CERT_TEMPLATE_FILES = @json($pdfTemplateData);
      const URL_QUERY = new URLSearchParams(window.location.search);
      const IS_DOCS_VIEW = URL_QUERY.get('mode') === 'docs';
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

      const CERT_TEMPLATE_OVERRIDE_DATA = (() => {
        try {
          const raw = sessionStorage.getItem(TEMPLATE_OVERRIDE_KEY);
          if (!raw) return null;
          sessionStorage.removeItem(TEMPLATE_OVERRIDE_KEY);
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
        bodyHeading: 'Body Heading:',
        mainBody:
          'This is to certify that (NAME), (AGE) years old, _________ is a bonafide resident of this barangay with postal address at (ADDRESS) St. Bo. Obrero Tondo, Manila, who is known to me as a person of good moral character and has no derogatory record as of this date.',
        purposeStatement:
          'This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in BADAC watchlist or drug personalities and not connected nor member of any left leaning group/organizations.',
        issuedLine:
          'This certification is being issued for whatever legal purpose it may serve.\n\nDone and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this (DATE), City of Manila. NOT VALID WITHOUT BARANGAY SEAL ',

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
          const base = (!t || typeof t !== 'object')
            ? { ...DEFAULT_TEMPLATE }
            : { ...DEFAULT_TEMPLATE, ...t };
          if (CERT_TEMPLATE_OVERRIDE_DATA && typeof CERT_TEMPLATE_OVERRIDE_DATA === 'object') {
            return { ...base, ...CERT_TEMPLATE_OVERRIDE_DATA, ...AUTO_HEADER_PRESET };
          }
          return { ...base, ...AUTO_HEADER_PRESET };
        } catch {
          const fallback = { ...DEFAULT_TEMPLATE };
          if (CERT_TEMPLATE_OVERRIDE_DATA && typeof CERT_TEMPLATE_OVERRIDE_DATA === 'object') {
            return { ...fallback, ...CERT_TEMPLATE_OVERRIDE_DATA, ...AUTO_HEADER_PRESET };
          }
          return fallback;
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
        
        // Replace underscore patterns in <u> tags for PDF templates (same logic as replacePlaceholders)
        // Replace name fields - look for patterns that indicate name
        out = out.replace(/This is to certify that <u>______________________________<\/u>/gi, `This is to certify that <u>${autoName}</u>`);
        out = out.replace(/I, <u>______________________________<\/u>/gi, `I, <u>${autoName}</u>`);
        out = out.replace(/Mr\.\/Ms\. <u>______________________________<\/u>/gi, `Mr./Ms. <u>${autoName}</u>`);
        
        // Replace age fields
        out = out.replace(/<u>______<\/u>/g, `<u>${autoAge}</u>`);
        out = out.replace(/<u>____<\/u>/g, `<u>${autoAge}</u>`);
        
        // Replace gender/purpose field (medium underscores)
        out = out.replace(/<u>_________<\/u>/g, `<u>${autoPurpose}</u>`);
        
        // Replace address fields - look for patterns that indicate address
        out = out.replace(/resident of[^<]*<u>______________________________<\/u>/gi, (match) => {
          return match.replace(/<u>______________________________<\/u>/, `<u>${autoAddress}</u>`);
        });
        out = out.replace(/address at <u>______________________________<\/u>/gi, `address at <u>${autoAddress}</u>`);
        out = out.replace(/postal address at <u>______________________________<\/u>/gi, `postal address at <u>${autoAddress}</u>`);
        
        // Replace any remaining long underscores with name (fallback)
        out = out.replace(/<u>______________________________<\/u>/g, `<u>${autoName}</u>`);
        
        // Replace date fields (various lengths)
        out = out.replace(/<u>_____<\/u>/g, `<u>${autoDate}</u>`);
        out = out.replace(/<u>___<\/u>/g, `<u>${autoDate}</u>`);
        
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
        
        // Apply auto-fill data to form fields if available
        const auto = CERT_AUTOFILL_DATA || {};
        const autoName = String(auto.name || '').trim();
        const autoAge = String(auto.age ?? '').trim();
        const autoAddress = String(auto.address || '').trim();
        const autoPurpose = String(auto.purpose || '').trim();
        const autoDate = String(auto.date || '').trim();
        
        // Helper function to replace placeholders in text
        function replacePlaceholders(text) {
          if (!text) return '';
          let result = text
            .replace(/\(NAME\)/g, autoName)
            .replace(/\(AGE\)/g, autoAge)
            .replace(/\(ADDRESS\)/g, autoAddress)
            .replace(/\(PURPOSE\)/g, autoPurpose)
            .replace(/\(DATE\)/g, autoDate);
          
          // Replace underscore patterns in <u> tags for PDF templates
          // Process in order to handle multiple fields correctly
          
          // Replace name fields - look for patterns that indicate name
          result = result.replace(/This is to certify that <u>______________________________<\/u>/gi, `This is to certify that <u>${autoName}</u>`);
          result = result.replace(/I, <u>______________________________<\/u>/gi, `I, <u>${autoName}</u>`);
          result = result.replace(/Mr\.\/Ms\. <u>______________________________<\/u>/gi, `Mr./Ms. <u>${autoName}</u>`);
          
          // Replace age fields
          result = result.replace(/<u>______<\/u>/g, `<u>${autoAge}</u>`);
          result = result.replace(/<u>____<\/u>/g, `<u>${autoAge}</u>`);
          
          // Replace gender/purpose field (medium underscores)
          result = result.replace(/<u>_________<\/u>/g, `<u>${autoPurpose}</u>`);
          
          // Replace address fields - look for patterns that indicate address
          result = result.replace(/resident of[^<]*<u>______________________________<\/u>/gi, (match) => {
            return match.replace(/<u>______________________________<\/u>/, `<u>${autoAddress}</u>`);
          });
          result = result.replace(/address at <u>______________________________<\/u>/gi, `address at <u>${autoAddress}</u>`);
          result = result.replace(/postal address at <u>______________________________<\/u>/gi, `postal address at <u>${autoAddress}</u>`);
          
          // Replace any remaining long underscores with name (fallback)
          result = result.replace(/<u>______________________________<\/u>/g, `<u>${autoName}</u>`);
          
          // Replace date fields (various lengths)
          result = result.replace(/<u>_____<\/u>/g, `<u>${autoDate}</u>`);
          result = result.replace(/<u>___<\/u>/g, `<u>${autoDate}</u>`);
          
          return result;
        }
        
        // header
        document.getElementById('barangayName').value = withAutoHeader.barangayName || '';
        document.getElementById('barangayAddress').value = withAutoHeader.barangayAddress || '';
        document.getElementById('certificateType').value = withAutoHeader.certificateType || '';
        document.getElementById('contactNo').value = withAutoHeader.contactNo || '';

        
        // body - apply auto-fill to form fields
        document.getElementById('bodyHeading').value = replacePlaceholders(withAutoHeader.bodyHeading || '');
        document.getElementById('mainBody').value = replacePlaceholders(withAutoHeader.mainBody || '');
        document.getElementById('purposeStatement').value = replacePlaceholders(withAutoHeader.purposeStatement || '');
        document.getElementById('issuedLine').value = replacePlaceholders(withAutoHeader.issuedLine || '');

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

        const heading = applyPlaceholders(t.bodyHeading || DEFAULT_TEMPLATE.bodyHeading, t);
        const mainBody = applyPlaceholders(t.mainBody || DEFAULT_TEMPLATE.mainBody, t);
        const purpose = applyPlaceholders(t.purposeStatement || DEFAULT_TEMPLATE.purposeStatement, t);

        // Check if mainBody contains HTML tags (indicating it's from template library)
        const hasHtmlTags = /<[^>]+>/.test(mainBody);
        
        let bodyHtml;
        if (hasHtmlTags) {
          // If content has HTML tags, use it directly (template library content)
          bodyHtml = mainBody;
        } else {
          // If content is plain text, format it with divs and escape HTML
          const bodyLines = [heading, '', ...mainBody.split('\n'), '', ...purpose.split('\n')];
          bodyHtml = bodyLines
            .map(line => '<div>' + escapeHtml(line) + '</div>')
            .join('');
        }
        document.getElementById('pvBody').innerHTML = bodyHtml;

        document.getElementById('pvIssued').textContent = applyPlaceholders(t.issuedLine || DEFAULT_TEMPLATE.issuedLine, t);
        document.getElementById('pvSignName').textContent = t.signName || DEFAULT_TEMPLATE.signName;
        document.getElementById('pvSignTitle').textContent = t.signTitle || DEFAULT_TEMPLATE.signTitle;
        renderSignatureImage(t.signImage || '');

        // Format
        const border = t.borderColor || DEFAULT_TEMPLATE.borderColor;
        const paperEl = document.getElementById('paper');
        paperEl.style.setProperty('--certBorder', border);
        if (IS_DOCS_VIEW) {
          // Docs PDF view should not show the visible vertical edge line.
          paperEl.style.border = '0';
        } else {
          paperEl.style.border = '1px solid ' + border;
        }
        const issueEmail = String(t.issueEmail || DEFAULT_TEMPLATE.issueEmail || '').trim();
        const pvEmailEl = document.getElementById('pvEmailNote') || document.getElementById('pvEmail');
        if (pvEmailEl) {
          pvEmailEl.textContent = issueEmail
            ? ('For verification, email: ' + issueEmail)
            : 'For verification, email: (Optional)';
        }

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
          bodyHeading: 'Body Heading:',
          mainBody: DEFAULT_TEMPLATE.mainBody,
          purposeStatement: DEFAULT_TEMPLATE.purposeStatement,
          issuedLine: DEFAULT_TEMPLATE.issuedLine,
          signName: 'MARIA MAGDALENA H. LEGASPI',
          signTitle: 'Barangay Chairwoman',
        };

        // 1. OATH OF UNDERTAKING
        if (n.includes('oath of undertaking')) {
          return {
            ...base,
            certificateType: 'OATH OF UNDERTAKING',
            bodyHeading: 'Body Heading:',
            mainBody: 'I, (NAME), of legal age and a resident of (ADDRESS), hereby undertake and affirm the truthfulness of all information I submitted for this request.',
            purposeStatement: 'This undertaking is executed for (PURPOSE).',
            signTitle: 'Applicant / Affiant',
            issuedLine: 'Executed this (DATE) at (BARANGAY).',
          };
        }

        // 2. CERTIFICATE FOR 1ST-TIME JOB SEEKER
        if (n.includes('1st-time job seeker') || n.includes('first-time job seeker') || n.includes('job seeker')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE FOR 1ST-TIME JOB SEEKER',
            mainBody: 'This is to certify that (NAME), of legal age, a resident of (ADDRESS), is a FIRST-TIME JOB SEEKER as defined under Republic Act No. 11261 (First Time Job Seekers Act of 2019).',
            purposeStatement: 'This certification is issued upon the request of the above-named person for the purpose of seeking employment.',
            issuedLine: 'Issued this (DATE) at (BARANGAY) in accordance with RA 11261.',
          };
        }

        // 3. BRGY INDIGENCY
        if (n.includes('indigency')) {
          return {
            ...base,
            certificateType: 'BARANGAY INDIGENCY',
            mainBody: 'This is to certify that (NAME), of legal age, and a resident of (ADDRESS), belongs to an indigent family in this barangay.',
            purposeStatement: 'This certification is issued upon the request of the above-named person for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) for whatever legal purpose it may serve.',
          };
        }

        // 4. BRGY CERTIFICATE (basic)
        if (n.includes('brgy certificate') && !n.includes('good moral') && !n.includes('oneness')) {
          return {
            ...base,
            certificateType: 'BARANGAY CERTIFICATE',
            mainBody: 'This is to certify that (NAME), (AGE) years old, a resident of (ADDRESS), is a bonafide resident of this barangay.',
            purposeStatement: 'This certification is issued upon the request of the above-named person for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) for whatever legal purpose it may serve.',
          };
        }

        // 5. BRGY CERTIFICATE with Good Moral
        if (n.includes('good moral')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE OF GOOD MORAL CHARACTER',
            mainBody: 'This is to certify that (NAME), (AGE) years old, _________ is a bonafide resident of this barangay with postal address at (ADDRESS) St. Bo. Obrero Tondo, Manila, who is known to me as a person of good moral character and has no derogatory record as of this date.',
            purposeStatement: 'This further certifies that the subject person mentioned above and her immediate family members/relatives are not involved in any illegal activities, not listed in BADAC watchlist or drug personalities and not connected nor member of any left leaning group/organizations.',
            issuedLine: 'This certification is being issued for whatever legal purpose it may serve.\n\nDone and issued at the office of the Barangay Chairman, Barangay 192 Zone-17 Dist. II this (DATE), City of Manila.',
          };
        }

        // 6. BRGY CERTIFICATE of ONENESS
        if (n.includes('oneness')) {
          return {
            ...base,
            certificateType: 'CERTIFICATE OF ONENESS',
            mainBody: 'This is to certify that (NAME), a resident of (ADDRESS), is recognized as one and the same person for legal and official purposes.',
            purposeStatement: 'Issued upon request for (PURPOSE).',
            issuedLine: 'Issued this (DATE) at (BARANGAY) for record and identification purposes.',
          };
        }

        // Default fallback
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
        
        // Map file names to display names
        const displayNames = {
          'OATH OF UNDERTAKING - NEW.docx': 'OATH OF UNDERTAKING',
          'CERTIFICATE FOR 1ST-TIME JOB SEEKER.docx': 'CERTIFICATE FOR 1ST-TIME JOB SEEKER',
          'BRGY INDIGENCY.docx': 'BRGY INDIGENCY',
          'BRGY CERTIFICATE.docx': 'BRGY CERTIFICATE',
          'BRGY CERTIFICATE with Good Moral.docx': 'BRGY CERTIFICATE with Good Moral',
          'BRGY CERTIFICATE of ONENESS.docx': 'BRGY CERTIFICATE of ONENESS'
        };
        
        templateLibraryCount.textContent = files.length
          ? (files.length + ' template file(s) found in public/file-cert')
          : 'No template files found in public/file-cert.';

        if (!files.length) {
          templateLibraryList.innerHTML = '<div style="padding:1rem;color:#6b7280">No files to show.</div>';
          return;
        }

        templateLibraryList.innerHTML = [
          '<table class="adm-table" aria-label="Template files">',
          '<thead><tr><th>Certificate Type</th><th style="width:160px">Action</th></tr></thead>',
          '<tbody>',
          ...files.map((f, i) => {
            const safeName = escapeHtml(String(f.name || ''));
            const safeIndex = String(i);
            // Use formatted display name or fallback to file name
            const displayName = displayNames[safeName] || safeName.replace(/\.docx$/i, '').replace(/-/g, ' ').replace(/_/g, ' ');
            return '<tr>'
              + '<td><strong>' + displayName + '</strong></td>'
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
          // Load the PDF HTML content into the mainBody field and preview
          const file = CERT_TEMPLATE_FILES[idx];
          const templateData = {
            barangayName: AUTO_HEADER_PRESET.barangayName,
            barangayAddress: AUTO_HEADER_PRESET.barangayAddress,
            certificateType: file.name.replace(/\.pdf$/i, '').replace(/-/g, ' ').replace(/_/g, ' '),
            contactNo: AUTO_HEADER_PRESET.contactNo,
            bodyHeading: '',
            mainBody: file.content || '',
            purposeStatement: '',
            issuedLine: '',
            signName: '',
            signTitle: '',
            signImage: '',
            borderColor: '#2b77d1',
            issueEmail: '',
          };
          
          // Apply auto-fill data to the template content
          hydrateForm(templateData);
          
          // Render preview with auto-fill applied
          renderPreview(templateData);
          setActiveTab('body');
          closeTemplateLibrary();
          alert('Template applied: ' + file.name);
        });
      }

      const pdfPreviewModal = document.getElementById('pdfPreviewModal');
      const pdfPreviewMount = document.getElementById('pdfPreviewMount');
      const pdfPreviewCloseBtn = document.getElementById('pdfPreviewCloseBtn');
      const pdfPrintBtn = document.getElementById('pdfPrintBtn');
      const viewPdfBtn = document.getElementById('viewPdfBtn');
      const statusModal = document.getElementById('statusModal');
      const statusModalTitle = document.getElementById('statusModalTitle');
      const statusModalMessage = document.getElementById('statusModalMessage');
      const statusModalClose = document.getElementById('statusModalClose');
      const statusModalOkBtn = document.getElementById('statusModalOkBtn');

      function openStatusModal(title, message) {
        if (!statusModal || !statusModalTitle || !statusModalMessage) return;
        statusModalTitle.textContent = String(title || 'Notice');
        statusModalMessage.textContent = String(message || '');
        statusModal.hidden = false;
        statusModal.classList.add('open');
      }

      function closeStatusModal() {
        if (!statusModal) return;
        statusModal.classList.remove('open');
        statusModal.hidden = true;
      }

      if (statusModalClose) statusModalClose.addEventListener('click', closeStatusModal);
      if (statusModalOkBtn) statusModalOkBtn.addEventListener('click', closeStatusModal);
      if (statusModal) {
        statusModal.addEventListener('click', (e) => {
          if (e.target === statusModal) closeStatusModal();
        });
      }

      function closePdfPreview() {
        if (!pdfPreviewModal) return;
        pdfPreviewModal.classList.remove('open');
        pdfPreviewModal.hidden = true;
      }

      function openPdfPreview() {
        const sourcePaper = document.getElementById('paper');
        if (!sourcePaper || !pdfPreviewModal || !pdfPreviewMount) return;

        // Mirror latest current form values before creating PDF-like preview.
        renderPreview(readForm());

        const mirror = sourcePaper.cloneNode(true);
        mirror.id = 'paperMirror';
        pdfPreviewMount.innerHTML = '';
        pdfPreviewMount.appendChild(mirror);

        pdfPreviewModal.hidden = false;
        pdfPreviewModal.classList.add('open');
      }

      function updateRequestPdfSavedState(ref, fileName, savedTemplate, savedPaperHtml) {
        const targetRef = String(ref || '').trim();
        if (!targetRef) return;

        const allRequests = loadRequests();
        const idx = allRequests.findIndex((item) => String(item?.ref || '').trim() === targetRef);
        if (idx === -1) return;

        allRequests[idx].pdfSaved = true;
        allRequests[idx].pdfSavedAt = new Date().toISOString();
        allRequests[idx].savedCertUpdatedAt = allRequests[idx].pdfSavedAt;
        if (fileName) allRequests[idx].pdfFileName = fileName;
        if (savedTemplate && typeof savedTemplate === 'object') {
          allRequests[idx].savedTemplate = savedTemplate;
          const certType = String(savedTemplate.certificateType || '').trim();
          if (certType) {
            allRequests[idx].savedCertType = certType;
          }
        }
        if (typeof savedPaperHtml === 'string' && savedPaperHtml.trim()) {
          allRequests[idx].savedPaperHtml = savedPaperHtml;
        }
        localStorage.setItem(REQUESTS_KEY, JSON.stringify(allRequests));
      }

      if (viewPdfBtn) viewPdfBtn.addEventListener('click', openPdfPreview);
      if (pdfPreviewCloseBtn) pdfPreviewCloseBtn.addEventListener('click', closePdfPreview);
      if (pdfPreviewModal) {
        pdfPreviewModal.addEventListener('click', (e) => {
          if (e.target === pdfPreviewModal) closePdfPreview();
        });
      }

      if (pdfPrintBtn) {
        pdfPrintBtn.addEventListener('click', async () => {
          const source = document.getElementById('paper');
          if (!source) return;

          pdfPrintBtn.disabled = true;
          try {
            renderPreview(readForm());
            const refFromAutofill = String(CERT_AUTOFILL_DATA?.ref || '').trim();
            const snapshotTemplate = readForm();
            saveTemplate(snapshotTemplate);

            if (!refFromAutofill) {
              openStatusModal(
                'Saved Template',
                'Certificate format has been saved as template. To link it to Dashs/Resident/Docs request list, open a resident request first then click Save PDF again.'
              );
              return;
            }

            const snapshotHtml = source.outerHTML;
            const fileName = refFromAutofill + '.pdf';
            updateRequestPdfSavedState(refFromAutofill, fileName, snapshotTemplate, snapshotHtml);
            openStatusModal(
              'Saved',
              'PDF format has been linked to this request. Dashs, Resident, and Docs can now view the saved certificate format.'
            );
          } catch (error) {
            console.error('Save format error:', error);
            openStatusModal('Save Failed', error?.message || 'Unable to save PDF format right now.');
          } finally {
            pdfPrintBtn.disabled = false;
          }
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
