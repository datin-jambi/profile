<nav class="bg-white shadow-md fixed w-full z-50" x-data="{ open: false }">
  <div class="container mx-auto px-4 flex justify-between items-center h-16">
    <!-- Logo -->
    <a href="<?php echo BASE_URL; ?>" class="text-xl font-bold text-blue-700">Samsat Jambi</a>

    <!-- Desktop Menu -->
    <div class="hidden md:flex space-x-6 items-center">
      <a href="<?php echo BASE_URL; ?>" class="hover:text-blue-600">Beranda</a>

      <!-- Dropdown: Layanan Online -->
      <div class="relative" x-data="{ layanan: false }"
        @mouseenter="layanan = true"
        @mouseleave="layanan = false">
        <button class="hover:text-blue-600 flex items-center">
          Layanan Online
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div x-show="layanan" x-transition class="absolute mt-2 w-56 bg-white shadow-lg rounded-lg border">
          <a href="<?php echo BASE_URL; ?>?route=layanan/cek-data" class="block px-4 py-2 hover:bg-gray-100">Cek Data Kendaraan</a>
          <a href="<?php echo BASE_URL; ?>?route=layanan/cek-pkb" class="block px-4 py-2 hover:bg-gray-100">Cek Informasi PKB</a>
          <a href="<?php echo BASE_URL; ?>?route=layanan/cek-nilai-jual" class="block px-4 py-2 hover:bg-gray-100">Cek Nilai Jual Kendaraan</a>
          <a href="<?php echo BASE_URL; ?>?route=layanan/cek-progresif" class="block px-4 py-2 hover:bg-gray-100">Cek Pajak Progresif</a>
        </div>
      </div>

      <!-- Dropdown: Informasi Publik -->
      <div class="relative" x-data="{ info: false }"
        @mouseenter="info = true"
        @mouseleave="info = false">
        <button class="hover:text-blue-600 flex items-center">
          Informasi Publik
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div x-show="info" x-transition class="absolute mt-2 w-56 bg-white shadow-lg rounded-lg border">
          <a href="<?php echo BASE_URL; ?>?route=standar-pelayanan" class="block px-4 py-2 hover:bg-gray-100">Standar Pelayanan</a>
          <a href="<?php echo BASE_URL; ?>?route=alur-proses" class="block px-4 py-2 hover:bg-gray-100">Alur Proses</a>
          <a href="<?php echo BASE_URL; ?>?route=faq" class="block px-4 py-2 hover:bg-gray-100">FAQ</a>
        </div>
      </div>

      <!-- Dropdown: Download & Link -->
      <div class="relative" x-data="{ download: false }"
        @mouseover="download = true"
        @mouseleave="download = false">
        <button class="hover:text-blue-600 flex items-center">
          Download & Link
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div x-show="download" x-transition class="absolute mt-2 w-64 bg-white shadow-lg rounded-lg border">
          <a href="<?php echo BASE_URL; ?>?route=download-app" class="block px-4 py-2 hover:bg-gray-100">Download Aplikasi Smart Samsat</a>
          <a href="<?php echo BASE_URL; ?>?route=blowingsystem" class="block px-4 py-2 hover:bg-gray-100">Website Blowingsystem</a>
          <a href="<?php echo BASE_URL; ?>?route=brosur" class="block px-4 py-2 hover:bg-gray-100">Brosur / Formulir</a>
        </div>
      </div>

      <a href="<?php echo BASE_URL; ?>?route=berita" class="hover:text-blue-600">Berita & Pengumuman</a>
    </div>

    <!-- Mobile Hamburger -->
    <button @click="open = !open" class="md:hidden text-gray-700 focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M4 6h16M4 12h16M4 18h16" />
        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- Mobile Menu Accordion -->
  <div x-show="open" class="md:hidden bg-white border-t shadow-lg" x-data="{ layanan: false, info: false, download: false }">
    <a href="<?php echo BASE_URL; ?>" class="block px-4 py-2 hover:bg-gray-100">Beranda</a>

    <!-- Accordion: Layanan Online -->
    <button @click="layanan = !layanan" class="w-full flex justify-between items-center px-4 py-2 hover:bg-gray-100">
      <span>Layanan Online</span>
      <svg :class="{ 'rotate-180': layanan }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    <div x-show="layanan" x-transition class="pl-6">
      <a href="<?php echo BASE_URL; ?>?route=layanan/cek-data" class="block py-2 hover:bg-gray-100">Cek Data Kendaraan</a>
      <a href="<?php echo BASE_URL; ?>?route=layanan/cek-pkb" class="block py-2 hover:bg-gray-100">Cek Informasi PKB</a>
      <a href="<?php echo BASE_URL; ?>?route=layanan/cek-nilai-jual" class="block py-2 hover:bg-gray-100">Cek Nilai Jual Kendaraan</a>
      <a href="<?php echo BASE_URL; ?>?route=layanan/cek-progresif" class="block py-2 hover:bg-gray-100">Cek Pajak Progresif</a>
    </div>

    <!-- Accordion: Informasi Publik -->
    <button @click="info = !info" class="w-full flex justify-between items-center px-4 py-2 hover:bg-gray-100">
      <span>Informasi Publik</span>
      <svg :class="{ 'rotate-180': info }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    <div x-show="info" x-transition class="pl-6">
      <a href="<?php echo BASE_URL; ?>?route=standar-pelayanan" class="block py-2 hover:bg-gray-100">Standar Pelayanan</a>
      <a href="<?php echo BASE_URL; ?>?route=alur-proses" class="block py-2 hover:bg-gray-100">Alur Proses</a>
      <a href="<?php echo BASE_URL; ?>?route=faq" class="block py-2 hover:bg-gray-100">FAQ</a>
    </div>

    <!-- Accordion: Download & Link -->
    <button @click="download = !download" class="w-full flex justify-between items-center px-4 py-2 hover:bg-gray-100">
      <span>Download & Link</span>
      <svg :class="{ 'rotate-180': download }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    <div x-show="download" x-transition class="pl-6">
      <a href="<?php echo BASE_URL; ?>?route=download-app" class="block py-2 hover:bg-gray-100">Download Aplikasi</a>
      <a href="<?php echo BASE_URL; ?>?route=blowingsystem" class="block py-2 hover:bg-gray-100">Website Blowingsystem</a>
      <a href="<?php echo BASE_URL; ?>?route=brosur" class="block py-2 hover:bg-gray-100">Brosur / Formulir</a>
    </div>

    <!-- Berita -->
    <a href="<?php echo BASE_URL; ?>?route=berita" class="block px-4 py-2 hover:bg-gray-100">Berita & Pengumuman</a>
  </div>
</nav>