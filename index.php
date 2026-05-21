<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>j-SAMSAT Jambi — Sistem Administrasi Manunggal Satu Atap</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { jakarta: ['Plus Jakarta Sans','sans-serif'] },
          colors: {
            jambi: { 50:'#FFF8EC',100:'#FFECC8',200:'#FFD57A',300:'#FFC240',400:'#FFAA00',600:'#D4780A',700:'#A85C06',800:'#7A3F00' },
            gov:   { 50:'#EEF4FF',100:'#C7D9F7',200:'#90B9F0',400:'#4A7FD4',600:'#1A4F9E',700:'#133D7A',800:'#0D2D61',900:'#061730' }
          }
        }
      }
    }
  </script>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    html{scroll-behavior:smooth}
    body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAFD;color:#0D2D61}

    .nav-link{position:relative;color:rgba(255,255,255,0.72);font-size:.875rem;font-weight:500;transition:color .2s;text-decoration:none}
    .nav-link:hover{color:#fff}
    .nav-link::after{content:'';position:absolute;bottom:-4px;left:0;width:0;height:2px;background:#FFAA00;border-radius:2px;transition:width .25s}
    .nav-link:hover::after{width:100%}

    .hero-bg{
      background-color:#0D2D61;
      background-image:
        radial-gradient(ellipse 80% 60% at 100% 0%,rgba(74,127,212,.35) 0%,transparent 60%),
        radial-gradient(ellipse 50% 60% at 0% 100%,rgba(255,170,0,.18) 0%,transparent 55%);
      position:relative;overflow:hidden;
    }
    .hero-pattern{
      position:absolute;inset:0;
      background-image:radial-gradient(circle,rgba(255,255,255,.04) 1px,transparent 1px);
      background-size:28px 28px;pointer-events:none;
    }
    .hero-ring{
      position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,.06);pointer-events:none;
    }
    @keyframes pulse-ring{0%,100%{transform:scale(1);opacity:.35}50%{transform:scale(1.06);opacity:.1}}

    .stat-card{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:16px;padding:1.25rem 1.5rem}

    .service-card{
      background:#fff;border:1px solid #E8EEF8;border-radius:20px;
      padding:1.5rem;display:flex;flex-direction:column;gap:1rem;
      transition:transform .22s,box-shadow .22s,border-color .22s;
      text-decoration:none;color:inherit;
    }
    .service-card:hover{transform:translateY(-5px);box-shadow:0 16px 40px rgba(13,45,97,.12);border-color:#C7D9F7}
    .service-card-dark{background:#0D2D61;border-color:transparent}
    .service-card-dark:hover{border-color:rgba(255,170,0,.4);box-shadow:0 16px 40px rgba(13,45,97,.3)}

    .section-eyebrow{
      display:inline-flex;align-items:center;gap:8px;
      background:#EEF4FF;color:#1A4F9E;
      font-size:.75rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
      padding:6px 14px;border-radius:999px;
    }
    .section-eyebrow-amber{background:#FFF8EC;color:#D4780A}

    @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
    .afu{animation:fadeUp .55s ease both}
    .d1{animation-delay:.1s}.d2{animation-delay:.2s}.d3{animation-delay:.3s}
    .d4{animation-delay:.4s}.d5{animation-delay:.5s}.d6{animation-delay:.6s}

    .float-btn{
      width:52px;height:52px;border-radius:50%;display:flex;align-items:center;justify-content:center;
      box-shadow:0 4px 16px rgba(0,0,0,.2);transition:transform .2s,box-shadow .2s;position:relative;
    }
    .float-btn:hover{transform:scale(1.1);box-shadow:0 8px 28px rgba(0,0,0,.25)}
    .float-tip{
      position:absolute;right:calc(100% + 10px);
      background:#fff;color:#374151;font-size:.72rem;font-weight:500;
      padding:5px 12px;border-radius:8px;white-space:nowrap;
      border:1px solid #E5E7EB;box-shadow:0 2px 8px rgba(0,0,0,.08);
      opacity:0;pointer-events:none;transition:opacity .2s;
    }
    .float-btn:hover .float-tip{opacity:1}

    .reveal{opacity:0;transform:translateY(20px);transition:opacity .55s ease,transform .55s ease}
    .reveal.visible{opacity:1;transform:translateY(0)}
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="hero-bg sticky top-0 z-50 border-b border-white/10">
  <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
    <div class="flex items-center gap-3">
      <div class="w-8 h-8 rounded-lg bg-jambi-400 flex items-center justify-center flex-shrink-0">
        <svg viewBox="0 0 20 20" class="w-5 h-5" fill="none">
          <rect x="2" y="8" width="16" height="9" rx="2" fill="#0D2D61"/>
          <rect x="5" y="5" width="10" height="5" rx="1.5" fill="#0D2D61" opacity=".6"/>
          <circle cx="6" cy="15" r="1.5" fill="#FFAA00"/>
          <circle cx="14" cy="15" r="1.5" fill="#FFAA00"/>
        </svg>
      </div>
      <span class="text-white font-bold text-base tracking-tight">j-SAMSAT <span class="text-jambi-300">Jambi</span></span>
    </div>
    <div class="hidden md:flex items-center gap-7">
      <a href="#profil" class="nav-link">Profil</a>
      <a href="#layanan" class="nav-link">Layanan</a>
      <a href="#informasi" class="nav-link">Informasi</a>
      <a href="#kontak" class="nav-link">Kontak</a>
    </div>
    <a href="https://api.whatsapp.com/send?phone=628117404761" target="_blank"
       class="bg-jambi-400 hover:bg-jambi-300 text-gov-900 text-xs font-bold px-4 py-2 rounded-full transition-colors">
      Hubungi Kami
    </a>
  </div>
</nav>


<!-- HERO -->
<section class="hero-bg text-white relative" style="min-height:92vh;display:flex;align-items:center">
  <div class="hero-pattern"></div>
  <div class="hero-ring" style="width:600px;height:600px;top:-200px;right:-200px;animation:pulse-ring 6s ease-in-out infinite"></div>
  <div class="hero-ring" style="width:400px;height:400px;bottom:-150px;left:-100px;animation:pulse-ring 8s ease-in-out infinite .5s"></div>

  <div class="max-w-6xl mx-auto px-5 py-20 relative z-10 w-full">
    <div class="grid md:grid-cols-2 gap-12 items-center">

      <div class="flex flex-col gap-6">
        <div class="afu d1">
          <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white/80 text-xs font-semibold px-4 py-1.5 rounded-full">
            <span class="w-2 h-2 rounded-full bg-jambi-400 animate-pulse"></span>
            Layanan Resmi Pemerintah Provinsi Jambi
          </span>
        </div>

        <h1 class="afu d2 text-4xl md:text-5xl font-extrabold leading-[1.1] tracking-tight">
          Samsat<br/>
          <span class="text-jambi-300">Satu Atap,</span><br/>
          Satu Tujuan
        </h1>

        <p class="afu d3 text-white/65 text-base leading-relaxed max-w-md">
          Sistem Administrasi Manunggal Satu Atap Provinsi Jambi hadir untuk mempermudah
          pembayaran pajak kendaraan bermotor, pengesahan STNK, dan administrasi kendaraan
          secara terpadu — <strong class="text-white/90">cepat, mudah, dan terpercaya.</strong>
        </p>

        <div class="afu d4 flex flex-wrap gap-3">
          <a href="#layanan"
             class="bg-jambi-400 hover:bg-jambi-300 text-gov-900 font-bold px-6 py-3 rounded-xl text-sm transition-all hover:-translate-y-0.5 hover:shadow-lg">
            Lihat Layanan
          </a>
          <a href="#profil"
             class="bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all">
            Tentang Kami
          </a>
        </div>

        <div class="afu d5 flex flex-wrap gap-2 pt-1">
          <span class="bg-white/8 border border-white/15 text-white/65 text-xs px-3 py-1 rounded-full">PKB &amp; BBNKB</span>
          <span class="bg-white/8 border border-white/15 text-white/65 text-xs px-3 py-1 rounded-full">Pengesahan STNK</span>
          <span class="bg-white/8 border border-white/15 text-white/65 text-xs px-3 py-1 rounded-full">SWDKLLJ</span>
          <span class="bg-white/8 border border-white/15 text-white/65 text-xs px-3 py-1 rounded-full">e-Samsat</span>
        </div>
      </div>

      <div class="afu d4 grid grid-cols-2 gap-4">
        <div class="stat-card col-span-2">
          <p class="text-white/50 text-xs font-semibold uppercase tracking-wider mb-2">Tentang j-SAMSAT</p>
          <p class="text-white/75 text-sm leading-relaxed">
            Portal resmi SAMSAT Provinsi Jambi yang dikelola oleh
            <strong class="text-white">Dinas Pendapatan Daerah (Dipenda)</strong> Provinsi Jambi —
            menyediakan akses informasi dan layanan pajak kendaraan secara terpadu dalam satu sistem digital.
          </p>
        </div>
        <div class="stat-card">
          <p class="text-3xl font-extrabold text-jambi-300">11</p>
          <p class="text-white/50 text-xs mt-1 leading-snug">Kabupaten/Kota di Provinsi Jambi</p>
        </div>
        <div class="stat-card">
          <p class="text-3xl font-extrabold text-jambi-300">24/7</p>
          <p class="text-white/50 text-xs mt-1 leading-snug">Akses informasi secara online</p>
        </div>
        <div class="stat-card col-span-2 flex items-center gap-4">
          <div class="w-10 h-10 rounded-xl bg-jambi-400/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-jambi-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <p class="text-white font-bold text-sm">Jam Operasional</p>
            <p class="text-white/50 text-xs mt-0.5">Sen – Jum 08.00–16.00 · Sabtu 08.00–12.00 WIB</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="absolute bottom-0 left-0 right-0">
    <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display:block;width:100%;height:60px">
      <path d="M0 60 L0 30 Q360 0 720 30 Q1080 60 1440 30 L1440 60 Z" fill="#F8FAFD"/>
    </svg>
  </div>
</section>


<!-- PROFIL / ABOUT -->
<section id="profil" class="max-w-6xl mx-auto px-5 py-20">
  <div class="grid md:grid-cols-2 gap-14 items-center">

    <div class="reveal order-2 md:order-1">
      <div class="relative">
        <div class="bg-gov-800 rounded-3xl p-7 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-gov-600/30 -translate-y-16 translate-x-16"></div>
          <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full bg-jambi-400/20 translate-y-10 -translate-x-8"></div>
          <div class="relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-jambi-400 flex items-center justify-center mb-5">
              <svg class="w-6 h-6 text-gov-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4zm6 0h1v11h-1zm5 0h1v11h-1zm5 0h1v11h-1z"/>
              </svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Dipenda Provinsi Jambi</h3>
            <p class="text-white/60 text-sm leading-relaxed">
              Dinas Pendapatan Daerah Provinsi Jambi bertugas mengelola dan mengoptimalkan
              penerimaan pendapatan asli daerah melalui sistem administrasi yang modern,
              transparan, dan akuntabel demi mendukung pembangunan daerah.
            </p>
            <div class="mt-5 pt-5 border-t border-white/10 grid grid-cols-3 gap-4 text-center">
              <div>
                <p class="text-2xl font-extrabold text-jambi-300">11</p>
                <p class="text-white/45 text-xs mt-0.5">Kab/Kota</p>
              </div>
              <div>
                <p class="text-2xl font-extrabold text-jambi-300">3</p>
                <p class="text-white/45 text-xs mt-0.5">Instansi Terpadu</p>
              </div>
              <div>
                <p class="text-2xl font-extrabold text-jambi-300">100%</p>
                <p class="text-white/45 text-xs mt-0.5">Berbasis Digital</p>
              </div>
            </div>
          </div>
        </div>
        <div class="absolute -bottom-4 -right-4 bg-jambi-400 text-gov-900 px-5 py-3 rounded-2xl shadow-xl">
          <p class="font-extrabold text-xs">Melayani Sejak</p>
          <p class="text-2xl font-black leading-none">2010</p>
        </div>
      </div>
    </div>

    <div class="reveal order-1 md:order-2 flex flex-col gap-5">
      <span class="section-eyebrow self-start">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.396 0 2.7.39 3.8 1.065A7.97 7.97 0 0112.2 14a7.968 7.968 0 013.3.804V4.804A7.968 7.968 0 0012.2 4c-1.255 0-2.443.29-3.5.804V4.804z"/></svg>
        Profil Lembaga
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold leading-tight text-gov-800">
        Melayani Wajib Pajak<br/>
        <span class="text-gov-400">dengan Tulus &amp; Profesional</span>
      </h2>
      <p class="text-slate-500 text-sm leading-relaxed">
        SAMSAT Jambi adalah unit pelayanan terpadu yang melibatkan tiga instansi —
        <strong class="text-gov-700">Kepolisian Negara RI</strong>,
        <strong class="text-gov-700">Dinas Pendapatan Daerah</strong>, dan
        <strong class="text-gov-700">PT. Jasa Raharja</strong> — dalam satu atap, untuk
        memberikan kemudahan registrasi kendaraan dan pembayaran pajak bagi masyarakat Jambi.
      </p>

      <div class="flex flex-col gap-3">
        <div class="bg-gov-50 border border-gov-100 rounded-xl p-4 flex gap-3">
          <div class="w-8 h-8 rounded-lg bg-gov-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </div>
          <div>
            <p class="font-bold text-gov-800 text-sm">Visi</p>
            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Terwujudnya administrasi kendaraan bermotor yang tertib, transparan, dan akuntabel untuk mendukung pembangunan Provinsi Jambi.</p>
          </div>
        </div>
        <div class="bg-jambi-50 border border-jambi-100 rounded-xl p-4 flex gap-3">
          <div class="w-8 h-8 rounded-lg bg-jambi-400 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-gov-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
          </div>
          <div>
            <p class="font-bold text-jambi-800 text-sm">Misi</p>
            <p class="text-slate-500 text-xs mt-0.5 leading-relaxed">Memberikan pelayanan prima yang cepat, mudah, dan terpercaya kepada seluruh wajib pajak kendaraan bermotor di Provinsi Jambi.</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>


<!-- LAYANAN -->
<section id="layanan" class="bg-white py-20">
  <div class="max-w-6xl mx-auto px-5">

    <div class="text-center mb-12 reveal">
      <span class="section-eyebrow section-eyebrow-amber">
        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
        Menu Layanan
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold text-gov-800 mt-3">Informasi &amp; Layanan Digital</h2>
      <p class="text-slate-400 text-sm mt-2 max-w-md mx-auto leading-relaxed">Akses berbagai layanan administrasi kendaraan bermotor secara daring, kapan saja dan di mana saja.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 reveal">

      <a href="infokb.html" class="service-card">
        <div class="w-12 h-12 rounded-2xl bg-gov-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-gov-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gov-800 text-base">Data Kendaraan</p>
          <p class="text-slate-400 text-xs mt-1 leading-relaxed">Cek informasi detail kendaraan bermotor berdasarkan nomor polisi, nomor rangka, atau nomor mesin.</p>
        </div>
        <div class="flex items-center gap-2 text-gov-400 text-xs font-semibold mt-auto">
          Akses Layanan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>

      <a href="infopkb.html" class="service-card">
        <div class="w-12 h-12 rounded-2xl bg-jambi-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-jambi-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gov-800 text-base">Pajak Kendaraan (PKB)</p>
          <p class="text-slate-400 text-xs mt-1 leading-relaxed">Cek nominal Pajak Kendaraan Bermotor dan tanggal jatuh tempo pembayaran kendaraan Anda.</p>
        </div>
        <div class="flex items-center gap-2 text-jambi-600 text-xs font-semibold mt-auto">
          Akses Layanan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>

      <a href="nilaijual/form_nb.php" class="service-card">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gov-800 text-base">Nilai Jual Kendaraan</p>
          <p class="text-slate-400 text-xs mt-1 leading-relaxed">Cek Nilai Jual Kendaraan Bermotor (NJKB) yang menjadi dasar penghitungan besaran pajak tahunan.</p>
        </div>
        <div class="flex items-center gap-2 text-emerald-600 text-xs font-semibold mt-auto">
          Akses Layanan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>

      <a href="infoprogresif.html" class="service-card">
        <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gov-800 text-base">Pajak Progresif</p>
          <p class="text-slate-400 text-xs mt-1 leading-relaxed">Cek tarif pajak progresif untuk kepemilikan lebih dari satu kendaraan bermotor atas nama yang sama.</p>
        </div>
        <div class="flex items-center gap-2 text-purple-500 text-xs font-semibold mt-auto">
          Akses Layanan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>

      <a href="https://drive.google.com/file/d/1OQPvTG-7-DNY43c0Ef56Tj5084YyneMP/view?usp=sharing"
         target="_blank" class="service-card service-card-dark relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background:radial-gradient(ellipse 70% 70% at 80% 0%,#4A7FD4,transparent)"></div>
        <div class="relative z-10 w-12 h-12 rounded-2xl bg-white/12 flex items-center justify-center">
          <svg class="w-6 h-6 text-jambi-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="relative z-10">
          <span class="bg-jambi-400/20 text-jambi-200 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">Aplikasi Mobile</span>
          <p class="font-bold text-white text-base mt-1.5">Smart Samsat</p>
          <p class="text-white/50 text-xs mt-1 leading-relaxed">Unduh aplikasi Smart Samsat untuk kemudahan layanan pembayaran pajak dari genggaman Anda.</p>
        </div>
        <div class="relative z-10 flex items-center gap-2 text-jambi-300 text-xs font-semibold mt-auto">
          Download Sekarang ↗
        </div>
      </a>

      <a href="whistle/index.php" class="service-card">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-gov-800 text-base">Whistleblowing System</p>
          <p class="text-slate-400 text-xs mt-1 leading-relaxed">Saluran pengaduan rahasia untuk melaporkan dugaan pelanggaran dan penyimpangan dalam layanan.</p>
        </div>
        <div class="flex items-center gap-2 text-rose-500 text-xs font-semibold mt-auto">
          Buat Laporan <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </div>
      </a>

    </div>
  </div>
</section>


<!-- INFORMASI PUBLIK -->
<section id="informasi" class="max-w-6xl mx-auto px-5 py-20">
  <div class="grid md:grid-cols-2 gap-10 items-start">

    <div class="reveal flex flex-col gap-5">
      <span class="section-eyebrow self-start">Informasi Publik</span>
      <h2 class="text-2xl md:text-3xl font-extrabold text-gov-800 leading-tight">Transparansi<br/>Layanan Kami</h2>
      <p class="text-slate-400 text-sm leading-relaxed">
        Kami berkomitmen pada pelayanan yang transparan, akuntabel, dan mudah diakses oleh seluruh masyarakat Provinsi Jambi.
      </p>

      <a href="fotoslide.php?id=1"
         class="group bg-white border border-slate-100 hover:border-gov-100 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md no-underline">
        <div class="w-11 h-11 rounded-xl bg-gov-50 group-hover:bg-gov-100 flex items-center justify-center flex-shrink-0 transition-colors">
          <svg class="w-5 h-5 text-gov-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <div class="flex-1">
          <p class="font-bold text-gov-800 text-sm">Standar Pelayanan</p>
          <p class="text-xs text-slate-400 mt-0.5">Prosedur, persyaratan, waktu, dan biaya layanan SAMSAT</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-gov-400 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>

      <a href="tentang.html"
         class="group bg-white border border-slate-100 hover:border-jambi-200 rounded-2xl px-5 py-4 flex items-center gap-4 shadow-sm transition-all hover:-translate-y-1 hover:shadow-md no-underline">
        <div class="w-11 h-11 rounded-xl bg-jambi-50 group-hover:bg-jambi-100 flex items-center justify-center flex-shrink-0 transition-colors">
          <svg class="w-5 h-5 text-jambi-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H5m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
        <div class="flex-1">
          <p class="font-bold text-gov-800 text-sm">Pelayanan Publik</p>
          <p class="text-xs text-slate-400 mt-0.5">Profil, fasilitas, dan informasi kantor SAMSAT Jambi</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 group-hover:text-jambi-400 transition-colors flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="reveal flex flex-col gap-5">
      <div class="bg-gov-800 rounded-3xl p-6 text-white">
        <p class="text-white/50 text-xs font-semibold uppercase tracking-wider mb-4">Jam Operasional</p>
        <div class="flex flex-col divide-y divide-white/10">
          <div class="flex justify-between py-3 text-sm"><span class="text-white/65">Senin – Kamis</span><span class="font-bold">08.00 – 16.00 WIB</span></div>
          <div class="flex justify-between py-3 text-sm"><span class="text-white/65">Jumat</span><span class="font-bold">08.00 – 16.30 WIB</span></div>
          <div class="flex justify-between py-3 text-sm"><span class="text-white/65">Sabtu</span><span class="font-bold">08.00 – 12.00 WIB</span></div>
          <div class="flex justify-between py-3 text-sm"><span class="text-white/65">Minggu &amp; Libur Nasional</span><span class="text-rose-300 font-bold">Tutup</span></div>
        </div>
      </div>

      <div class="bg-jambi-50 border border-jambi-100 rounded-2xl p-5">
        <p class="text-jambi-800 font-bold text-sm mb-3 flex items-center gap-2">
          <svg class="w-4 h-4 text-jambi-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Syarat Pembayaran PKB
        </p>
        <ul class="flex flex-col gap-2">
          <li class="flex items-start gap-2 text-xs text-slate-500">
            <svg class="w-4 h-4 text-jambi-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            STNK asli dan fotokopi
          </li>
          <li class="flex items-start gap-2 text-xs text-slate-500">
            <svg class="w-4 h-4 text-jambi-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            KTP asli pemilik kendaraan (sesuai STNK)
          </li>
          <li class="flex items-start gap-2 text-xs text-slate-500">
            <svg class="w-4 h-4 text-jambi-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            BPKB asli (untuk perpanjangan 5 tahun / ganti plat)
          </li>
          <li class="flex items-start gap-2 text-xs text-slate-500">
            <svg class="w-4 h-4 text-jambi-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Bukti hasil cek fisik kendaraan (perpanjangan 5 tahun)
          </li>
        </ul>
      </div>
    </div>

  </div>
</section>


<!-- KONTAK -->
<section id="kontak" class="bg-gov-800 text-white py-20">
  <div class="max-w-6xl mx-auto px-5">

    <div class="text-center mb-12 reveal">
      <span class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white/70 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest mb-4">
        Hubungi Kami
      </span>
      <h2 class="text-3xl md:text-4xl font-extrabold">Ada Pertanyaan?<br/><span class="text-jambi-300">Kami Siap Membantu.</span></h2>
      <p class="text-white/50 text-sm mt-3 max-w-sm mx-auto">Jangan ragu untuk menghubungi kami melalui saluran komunikasi yang tersedia.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-5 reveal">
      <div class="bg-white/8 border border-white/12 rounded-2xl p-6 flex flex-col gap-4">
        <div class="w-11 h-11 rounded-xl bg-white/12 flex items-center justify-center">
          <svg class="w-5 h-5 text-jambi-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-white text-sm">Alamat Kantor</p>
          <p class="text-white/55 text-xs mt-1.5 leading-relaxed">Jl. Ahmad Yani No. 1<br/>Telanaipura, Kota Jambi 36122<br/>Provinsi Jambi</p>
        </div>
      </div>

      <a href="https://api.whatsapp.com/send?phone=628117404761" target="_blank"
         class="bg-[#25D366]/15 border border-[#25D366]/30 rounded-2xl p-6 flex flex-col gap-4 hover:bg-[#25D366]/25 transition-colors no-underline group">
        <div class="w-11 h-11 rounded-xl bg-[#25D366]/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-white text-sm">WhatsApp Admin</p>
          <p class="text-white/55 text-xs mt-1">+62 811-7404-761</p>
          <p class="text-[#25D366] text-xs mt-2.5 font-semibold group-hover:underline">Chat Sekarang →</p>
        </div>
      </a>

      <a href="https://www.instagram.com/samsat.kota.jambi/" target="_blank"
         class="bg-white/8 border border-white/12 rounded-2xl p-6 flex flex-col gap-4 hover:bg-white/14 transition-colors no-underline group">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-pink-500/30 to-yellow-500/30 flex items-center justify-center">
          <svg class="w-5 h-5 text-pink-300" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
          </svg>
        </div>
        <div>
          <p class="font-bold text-white text-sm">Instagram</p>
          <p class="text-white/55 text-xs mt-1">@samsat.kota.jambi</p>
          <p class="text-pink-300 text-xs mt-2.5 font-semibold group-hover:underline">Follow Kami →</p>
        </div>
      </a>
    </div>

  </div>
</section>


<!-- FOOTER -->
<footer class="bg-gov-900 text-white/40 text-center text-xs py-7 px-4">
  <div class="flex items-center justify-center gap-2 mb-2">
    <div class="w-5 h-5 rounded bg-jambi-400 flex items-center justify-center flex-shrink-0">
      <svg viewBox="0 0 12 12" class="w-3 h-3" fill="#0D2D61">
        <rect x="1" y="4" width="10" height="6" rx="1"/>
        <rect x="3" y="2" width="6" height="3" rx="1" opacity=".6"/>
      </svg>
    </div>
    <span class="text-white/70 font-bold text-sm">j-SAMSAT Jambi</span>
  </div>
  <p>Dinas Pendapatan Daerah Provinsi Jambi · Jl. Ahmad Yani No. 1, Telanaipura, Kota Jambi</p>
  <p class="mt-1.5">© 2024 SAMSAT Provinsi Jambi. Hak Cipta Dilindungi.</p>
</footer>


<!-- FLOATING BUTTONS -->
<div class="fixed bottom-6 right-5 z-50 flex flex-col items-end gap-3">
  <a href="https://www.instagram.com/samsat.kota.jambi/" target="_blank" rel="noopener noreferrer"
     class="float-btn bg-gradient-to-br from-pink-500 via-red-500 to-yellow-400">
    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
    </svg>
    <span class="float-tip">Follow Instagram</span>
  </a>
  <a href="https://api.whatsapp.com/send?phone=628117404761" target="_blank" rel="noopener noreferrer"
     class="float-btn bg-[#25D366]">
    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    <span class="float-tip">Chat Admin WhatsApp</span>
  </a>
</div>

<script>
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); obs.unobserve(e.target); }});
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
</script>

</body>
</html>