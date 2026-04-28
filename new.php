<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Detail Kendaraan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-4">

    <div class="max-w-3xl mx-auto">
        <!-- Announcement -->
        <div class="bg-amber-50 border-l-4 border-amber-500 text-amber-900 rounded-md p-4 mb-4 shadow-sm" role="alert" aria-live="polite">
            <p class="text-sm font-semibold">Pengumuman</p>
            <p class="text-sm mt-1">
                Informasi perhitungan pada halaman ini sudah digunakan sebagai acuan layanan, namun nilainya masih dapat menyesuaikan kebijakan dan pembaruan sistem yang berlaku. Nominal final yang sah tetap mengacu pada ketetapan resmi di kantor Samsat saat pembayaran pajak.
            </p>
        </div>

        <!-- Header & Form -->
        <div class="bg-white rounded-lg shadow p-6 mb-4">

            <div class="w-full flex justify-between mb-4">
                <button 
                    onclick="goBack()" 
                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800 mb-4 transition"
                    title="Kembali ke halaman sebelumnya"
                >
                    <svg 
                        width="20" 
                        height="20"
                        fill="currentColor" 
                        version="1.1" 
                        id="Capa_1" 
                        xmlns="http://www.w3.org/2000/svg" 
                        xmlns:xlink="http://www.w3.org/1999/xlink" 
                        viewBox="0 0 956.199 956.199" 
                        xml:space="preserve">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier"> 
                            <g> 
                                <path d="M859.649,385.6h-255.2L680.05,310c35.1-35.1,35.1-92.1,0-127.3c-35.101-35.1-92.101-35.1-127.3,0L323.449,412 c-21.6,22.6-32.199,55.3-23.6,85.9c2.3,8.299,5.8,17.5,10.5,24.799c3.9,6,8.4,11.201,13.1,16.602l234.2,234.199 c17.601,17.6,40.601,26.4,63.601,26.4s46.1-8.801,63.6-26.4c35.1-35.1,35.1-92.1,0-127.301l-80.6-80.6h255.1 c49.7,0,90-40.299,90-90C949.35,425.9,909.35,385.6,859.649,385.6z"></path> 
                                <path d="M96.85,0c-49.7,0-90,40.3-90,90v776.199c0,49.701,40.3,90,90,90s90-40.299,90-90V90C186.85,40.3,146.55,0,96.85,0z"></path> 
                            </g> 
                        </g>
                    </svg>    
                    <span class="text-sm font-medium">Kembali</span>
                </button>
                <a 
                    href="infopkb.html"
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium"
                >
                    Perhitungan lama
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Cek Detail Kendaraan</h1>
            <div class="flex gap-2">
                <input 
                    type="text" 
                    id="nopolInput" 
                    placeholder="Masukkan No. Polisi (Contoh: BH 6869 IK)" 
                    class="flex-1 px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-400 focus:outline-none text-sm"
                >
                <button 
                    onclick="cekKendaraan()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium"
                >
                    Cari
                </button>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="hidden bg-white rounded-lg shadow p-4">
            <div class="flex items-center gap-3">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                <p class="text-gray-600 text-sm">Memuat data...</p>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error" class="hidden bg-red-50 border border-red-300 rounded-lg shadow p-4">
            <p id="errorMessage" class="text-red-700 text-sm"></p>
        </div>

        <!-- Result -->
        <div id="result" class="hidden bg-white rounded-lg shadow p-5">
            <!-- No Polisi -->
            <div class="bg-blue-50 rounded p-3 mb-4">
                <p class="text-xs text-gray-600 mb-1">Nomor Polisi</p>
                <p id="noPol" class="text-xl font-bold text-blue-700"></p>
            </div>
            
            <!-- Info Kendaraan -->
            <div class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-sm mb-4">
                <div>
                    <span class="text-gray-500 text-xs">Merek</span>
                    <p id="merek" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">Model</span>
                    <p id="model" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">Jenis</span>
                    <p id="jenis" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">Tahun</span>
                    <p id="tahun" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">Warna</span>
                    <p id="warna" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">CC</span>
                    <p id="cc" class="font-medium text-gray-900"></p>
                </div>
            </div>

            <!-- Masa Berlaku -->
            <div class="border-t pt-4 mb-4">
                <p class="text-xs font-semibold text-gray-700 mb-2">MASA BERLAKU</p>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-gray-500 text-xs">PKB sampai</span>
                        <p id="tglPkb" class="font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs">STNK sampai</span>
                        <p id="tglStnk" class="font-semibold text-gray-900"></p>
                    </div>
                </div>
            </div>

            <!-- Info Lainnya -->
            <div class="border-t pt-4 space-y-2.5 text-sm">
                <div>
                    <span class="text-gray-500 text-xs">Bahan Bakar</span>
                    <p class="font-medium text-gray-900"><span id="bbmNama"></span></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">NJKB</span>
                    <p class="font-semibold text-gray-900" id="njkbNilai"></p>
                </div>
                <div>
                    <span class="text-gray-500 text-xs">Lokasi Transaksi Terakhir</span>
                    <p class="font-medium text-gray-900" id="lokasiNama"></p>
                </div>
            </div>

            <!-- Button Cek Pajak -->
            <div class="border-t pt-4 mt-4">
                <button 
                    onclick="cekPajak()" 
                    id="btnCekPajak"
                    class="w-full px-4 py-2.5 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium transition"
                >
                    Cek Pajak & Jasa Raharja
                </button>
            </div>
        </div>

        <!-- Result Pajak -->
        <div id="resultPajak" class="hidden space-y-4 mt-4">
            <!-- Info Pajak -->
            <div id="infoPajak" class="hidden bg-white rounded-lg shadow p-5">
                <!-- Loading Pajak -->
                <div id="loadingPajak" class="flex items-center gap-3 mb-4">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-green-600"></div>
                    <p class="text-gray-600 text-sm">Memuat data pajak...</p>
                </div>
                
                <div id="contentPajak" class="hidden">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pajak</h3>
                
                <!-- Total Tagihan -->
                <div class="bg-red-50 rounded p-4 mb-4">
                    <p class="text-xs text-gray-600 mb-1">Total Tagihan</p>
                    <p id="grandTotal" class="text-2xl font-bold text-red-600"></p>
                </div>

                <!-- Terakhir Bayar -->
                <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                    <div>
                        <span class="text-gray-500 text-xs">Terakhir Bayar</span>
                        <p id="terakhirBayar" class="font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs">Jarak Waktu</span>
                        <p id="jarakWaktu" class="font-semibold text-gray-900"></p>
                    </div>
                </div>

                <!-- Total PKB & Opsen -->
                <div class="border-t pt-4 mb-4">
                    <p class="text-xs font-semibold text-gray-700 mb-3">TOTAL TAGIHAN</p>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <!-- PKB Pokok -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                            <p class="text-gray-600 text-xs mb-1">PKB Pokok</p>
                            <p id="pkbPokok" class="font-bold text-blue-700 text-lg"></p>
                        </div>
                        <!-- Denda PKB -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-3 border border-red-200">
                            <p class="text-gray-600 text-xs mb-1">Denda PKB</p>
                            <p id="pkbDenda" class="font-bold text-red-700 text-lg"></p>
                        </div>
                        <!-- Opsen Pokok -->
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-3 border border-indigo-200">
                            <p class="text-gray-600 text-xs mb-1">Opsen Pokok</p>
                            <p id="opsenPokok" class="font-bold text-indigo-700 text-lg"></p>
                        </div>
                        <!-- Denda Opsen -->
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-3 border border-orange-200">
                            <p class="text-gray-600 text-xs mb-1">Denda Opsen</p>
                            <p id="opsenDenda" class="font-bold text-orange-700 text-lg"></p>
                        </div>
                    </div>
                </div>

                <!-- Rincian Per Periode -->
                <div class="border-t pt-4">
                    <p class="text-xs font-semibold text-gray-700 mb-3">RINCIAN PER PERIODE</p>
                    <div class="overflow-x-auto">
                        <table id="rincianPeriode" class="w-full text-xs">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-2 font-semibold text-gray-700">Periode</th>
                                    <th class="p-2 font-semibold text-gray-700">Telat</th>
                                    <th class="p-2 font-semibold text-gray-700 text-right">PKB Pokok</th>
                                    <th class="p-2 font-semibold text-gray-700 text-right">Denda PKB</th>
                                    <th class="p-2 font-semibold text-gray-700 text-right">Opsen</th>
                                    <th class="p-2 font-semibold text-gray-700 text-right">Denda Opsen</th>
                                    <th class="p-2 font-semibold text-gray-700 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody id="rincianPeriodeBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>

            <!-- Info PNBP -->
            <div id="infoPNBP" class="hidden bg-white rounded-lg shadow p-5">
                <!-- Loading PNBP -->
                <div id="loadingPNBP" class="flex items-center gap-3 mb-4">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                    <p class="text-gray-600 text-sm">Memuat data PNBP...</p>
                </div>
                
                <div id="contentPNBP" class="hidden">
                <h3 class="text-lg font-bold text-gray-800 mb-4">PNBP (Penerimaan Negara Bukan Pajak)</h3>
                <div class="text-sm">
                    <div id="pnbpContent">
                        <!-- Total PNBP -->
                        <div class="bg-blue-50 rounded p-4 mb-4">
                            <p class="text-xs text-gray-600 mb-1">Total PNBP</p>
                            <p id="pnbpTotal" class="text-2xl font-bold text-blue-600"></p>
                        </div>
                        
                        <!-- Rincian PNBP -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- STNK -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-3 border border-green-200">
                                <p class="text-gray-600 text-xs mb-1">STNK</p>
                                <p id="pnbpSTNK" class="font-bold text-green-700 text-lg"></p>
                            </div>
                            <!-- TNKB -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-3 border border-purple-200">
                                <p class="text-gray-600 text-xs mb-1">TNKB</p>
                                <p id="pnbpTNKB" class="font-bold text-purple-700 text-lg"></p>
                            </div>
                        </div>
                    </div>
                    <div id="pnbpEmpty" class="hidden">
                        <div class="bg-gray-50 rounded p-4 text-center">
                            <p class="text-gray-500">Data PNBP tidak tersedia</p>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Info Jasa Raharja -->
            <div id="infoJR" class="hidden bg-white rounded-lg shadow p-5">
                <!-- Loading JR -->
                <div id="loadingJR" class="flex items-center gap-3 mb-4">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-green-600"></div>
                    <p class="text-gray-600 text-sm">Memuat data Jasa Raharja...</p>
                </div>
                
                <div id="contentJR" class="hidden">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Jasa Raharja</h3>
                
                <!-- Total Tarif JR -->
                <div class="bg-green-50 rounded p-4 mb-4">
                    <p class="text-xs text-gray-600 mb-1">Total Biaya Jasa Raharja</p>
                    <p id="jrTotal" class="text-2xl font-bold text-green-600"></p>
                </div>

                <!-- Rincian Tarif -->
                <div class="text-sm">
                    <p class="text-xs font-semibold text-gray-700 mb-3">RINCIAN BIAYA</p>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <!-- Pokok JR -->
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-3 border border-teal-200">
                            <p class="text-gray-600 text-xs mb-1">Pokok JR</p>
                            <p id="jrPokok" class="font-bold text-teal-700 text-lg"></p>
                        </div>
                        <!-- Denda JR -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-3 border border-red-200">
                            <p class="text-gray-600 text-xs mb-1">Denda JR</p>
                            <p id="jrDenda" class="font-bold text-red-700 text-lg"></p>
                        </div>
                    </div>

                    <!-- Tarif Per Periode -->
                    <div class="border-t pt-4">
                        <p class="text-xs font-semibold text-gray-700 mb-3">RINCIAN PER PERIODE</p>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="p-2 font-semibold text-gray-700">Periode</th>
                                        <th class="p-2 font-semibold text-gray-700 text-right">Pokok JR</th>
                                        <th class="p-2 font-semibold text-gray-700 text-right">Denda JR</th>
                                        <th class="p-2 font-semibold text-gray-700 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="tarifPerTahunBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <!-- Total Keseluruhan -->
            <div id="infoTotal" class="hidden bg-white rounded-lg shadow p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Total Keseluruhan</h3>
                    <button 
                        onclick="openInfoModal()" 
                        class="text-blue-600 hover:text-blue-800 transition"
                        title="Informasi Perhitungan PKB"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-5 mb-4">
                    <p class="text-xs text-gray-600 mb-1">Total Yang Harus Dibayar</p>
                    <p id="totalKeseluruhan" class="text-3xl font-bold text-gray-900"></p>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center p-3 bg-red-50 rounded">
                        <span class="text-gray-700 font-medium">Total PKB (Pajak)</span>
                        <span id="totalPajak" class="font-bold text-red-600"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded">
                        <span class="text-gray-700 font-medium">Total Jasa Raharja</span>
                        <span id="totalJR" class="font-bold text-green-600"></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded">
                        <span class="text-gray-700 font-medium">PNBP</span>
                        <span id="totalPNBP" class="font-bold text-blue-600">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Info PKB -->
    <div id="infoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Informasi Perhitungan PKB</h2>
                <button onclick="closeInfoModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <div class="px-6 py-5 space-y-4">
                <!-- Kapan Opsen Berlaku -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                    <h3 class="font-bold text-gray-800 mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Opsen Berlaku Ketika
                    </h3>
                    <p class="text-gray-700">Tanggal transaksi <span class="font-bold">≥ 25 Januari 2025</span></p>
                </div>

                <!-- Perhitungan PKB -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 text-lg">Perhitungan PKB</h3>
                    
                    <div class="space-y-3">
                        <!-- Dengan Opsen -->
                        <div class="bg-white rounded p-3">
                            <p class="font-semibold text-blue-700 mb-2">✓ Dengan Opsen (≥ 25-01-2025)</p>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-mono bg-gray-50 p-2 rounded">
                                    PKB = 1% × Nilai Jual × Bobot × 90.4%
                                </p>
                            </div>
                        </div>

                        <!-- Tanpa Opsen -->
                        <div class="bg-white rounded p-3">
                            <p class="font-semibold text-gray-700 mb-2">✗ Tanpa Opsen (< 25-01-2025)</p>
                            <div class="text-sm text-gray-700 space-y-1">
                                <p class="font-mono bg-gray-50 p-2 rounded">
                                    PKB = 1.5% × Nilai Jual × Bobot × 100%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Perhitungan Opsen -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 text-lg">Perhitungan Opsen</h3>
                    <div class="bg-white rounded p-3">
                        <div class="text-sm text-gray-700">
                            <p class="font-mono bg-gray-50 p-2 rounded">
                                Opsen = 66% × PKB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Perhitungan Denda -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 text-lg">Perhitungan Denda</h3>
                    
                    <div class="space-y-3">
                        <!-- Dengan Opsen -->
                        <div class="bg-white rounded p-3">
                            <p class="font-semibold text-blue-700 mb-2">✓ Dengan Opsen</p>
                            <div class="text-sm text-gray-700 space-y-2">
                                <div>
                                    <p class="font-medium text-gray-600 mb-1">Denda PKB:</p>
                                    <p class="font-mono bg-gray-50 p-2 rounded">
                                        = (1% + Bulan Telat × 2%) × PKB
                                    </p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-600 mb-1">Denda Opsen:</p>
                                    <p class="font-mono bg-gray-50 p-2 rounded">
                                        = (1% + Bulan Telat × 1%) × Opsen
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tanpa Opsen -->
                        <div class="bg-white rounded p-3">
                            <p class="font-semibold text-gray-700 mb-2">✗ Tanpa Opsen</p>
                            <div class="text-sm text-gray-700 space-y-2">
                                <div>
                                    <p class="font-medium text-gray-600 mb-1">Denda PKB:</p>
                                    <p class="font-mono bg-gray-50 p-2 rounded">
                                        = (2% + Bulan Telat × 2%) × PKB
                                    </p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-600 mb-1">Denda Opsen:</p>
                                    <p class="font-mono bg-gray-50 p-2 rounded">
                                        = 0
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-2">Catatan Penting</h3>
                    <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                        <li>Nilai Jual = NJKB (Nilai Jual Kendaraan Bermotor)</li>
                        <li>Bobot umumnya bernilai 1 untuk kendaraan standar</li>
                        <li>Perhitungan berlaku untuk kendaraan bermotor roda 2 dan roda 4</li>
                    </ul>
                </div>
            </div>

            <div class="sticky bottom-0 bg-gray-50 border-t px-6 py-4">
                <button 
                    onclick="closeInfoModal()" 
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed top-4 right-4 z-50 transition-all duration-300">
        <div class="bg-white rounded-lg shadow-lg p-4 min-w-[300px] max-w-md border-l-4" id="toastContent">
            <div class="flex items-start gap-3">
                <div id="toastIcon" class="flex-shrink-0"></div>
                <div class="flex-1">
                    <p id="toastTitle" class="font-semibold text-sm"></p>
                    <p id="toastMessage" class="text-xs text-gray-600 mt-1"></p>
                </div>
                <button onclick="hideToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- script detail kendaraan -->
    <script>
        // API Token
        const API_TOKEN = 'd84e44fc2e1cb516f2d58580fcfc00a453402f0a13219ef82481d0ebad2944c8';
        
        // Variable untuk menyimpan nopol saat ini
        let currentNopol = '';

        // Fungsi untuk kembali ke halaman sebelumnya
        function goBack() {
            window.history.back();
        }

        async function cekKendaraan() {
            const nopol = document.getElementById('nopolInput').value.trim();
            
            if (!nopol) {
                showError('Nomor polisi tidak boleh kosong');
                return;
            }

            // Hide previous results
            document.getElementById('result').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');
            document.getElementById('loading').classList.remove('hidden');

            try {
                const response = await fetch(`https://api-doc.jambisamsat.net/api/kendaraan/detail?nopol=${encodeURIComponent(nopol)}`, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (data.status && data.data) {
                    showResult(data.data);
                } else {
                    showError(data.message || 'Data tidak ditemukan');
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                showError('Gagal mengambil data. Silakan coba lagi.');
                console.error('Error:', error);
            }
        }

        function showResult(data) {
            // Simpan nomor polisi untuk keperluan cek pajak
            currentNopol = data.no_polisi || '';
            
            // Informasi Utama
            document.getElementById('noPol').textContent = data.no_polisi || '-';
            document.getElementById('merek').textContent = data.nm_merek_kb || '-';
            document.getElementById('model').textContent = data.nm_model_kb || '-';
            document.getElementById('jenis').textContent = data.nm_jenis_kb || '-';
            document.getElementById('tahun').textContent = data.th_rakitan || '-';
            document.getElementById('warna').textContent = data.warna_kb || '-';
            document.getElementById('cc').textContent = data.jumlah_cc ? data.jumlah_cc + ' CC' : '-';

            // Masa Berlaku
            document.getElementById('tglPkb').textContent = formatDate(data.tg_akhir_pkb);
            document.getElementById('tglStnk').textContent = formatDate(data.tg_akhir_stnk);

            // BBM & NJKB & Lokasi
            document.getElementById('bbmNama').textContent = data.bbm?.nama || '-';
            document.getElementById('njkbNilai').textContent = data.njkb?.nilai_jual || '-';
            document.getElementById('lokasiNama').textContent = data.lokasi_transaksi_terakhir?.nama || '-';

            document.getElementById('result').classList.remove('hidden');
            
            // Reset hasil pajak sebelumnya
            document.getElementById('resultPajak').classList.add('hidden');
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('error').classList.remove('hidden');
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        // Allow Enter key to trigger search
        document.getElementById('nopolInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                cekKendaraan();
            }
        });

        // Toast Notification Functions
        let toastQueue = [];
        let isToastShowing = false;

        function showToast(title, message, type = 'info') {
            toastQueue.push({ title, message, type });
            if (!isToastShowing) {
                displayNextToast();
            }
        }

        function displayNextToast() {
            if (toastQueue.length === 0) {
                isToastShowing = false;
                return;
            }

            isToastShowing = true;
            const { title, message, type } = toastQueue.shift();

            const toast = document.getElementById('toast');
            const toastContent = document.getElementById('toastContent');
            const toastIcon = document.getElementById('toastIcon');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');

            // Set content
            toastTitle.textContent = title;
            toastMessage.textContent = message;

            // Set style based on type
            if (type === 'success') {
                toastContent.className = 'bg-white rounded-lg shadow-lg p-4 min-w-[300px] max-w-md border-l-4 border-green-500';
                toastIcon.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                toastTitle.className = 'font-semibold text-sm text-green-800';
            } else if (type === 'error') {
                toastContent.className = 'bg-white rounded-lg shadow-lg p-4 min-w-[300px] max-w-md border-l-4 border-red-500';
                toastIcon.innerHTML = '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                toastTitle.className = 'font-semibold text-sm text-red-800';
            } else if (type === 'warning') {
                toastContent.className = 'bg-white rounded-lg shadow-lg p-4 min-w-[300px] max-w-md border-l-4 border-yellow-500';
                toastIcon.innerHTML = '<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                toastTitle.className = 'font-semibold text-sm text-yellow-800';
            } else {
                toastContent.className = 'bg-white rounded-lg shadow-lg p-4 min-w-[300px] max-w-md border-l-4 border-blue-500';
                toastIcon.innerHTML = '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
                toastTitle.className = 'font-semibold text-sm text-blue-800';
            }

            // Show toast
            toast.classList.remove('hidden');

            // Auto hide after 3 seconds
            setTimeout(() => {
                hideToast();
                setTimeout(displayNextToast, 300); // Show next toast after 300ms
            }, 3000);
        }

        function hideToast() {
            const toast = document.getElementById('toast');
            toast.classList.add('hidden');
        }

        // Modal Functions
        function openInfoModal() {
            document.getElementById('infoModal').classList.remove('hidden');
        }

        function closeInfoModal() {
            document.getElementById('infoModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('infoModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeInfoModal();
            }
        });
    </script>

    <!-- script cek dan calculate PKB,JR, dan PNBP -->
    <script>
        async function cekPajak() {
            if (!currentNopol) {
                showToast('Perhatian', 'Silakan cari kendaraan terlebih dahulu', 'warning');
                return;
            }

            // Show result containers
            document.getElementById('resultPajak').classList.remove('hidden');
            document.getElementById('infoPajak').classList.remove('hidden');
            document.getElementById('infoPNBP').classList.remove('hidden');
            document.getElementById('infoJR').classList.remove('hidden');
            
            // Show all loading states
            document.getElementById('loadingPajak').classList.remove('hidden');
            document.getElementById('contentPajak').classList.add('hidden');
            document.getElementById('loadingPNBP').classList.remove('hidden');
            document.getElementById('contentPNBP').classList.add('hidden');
            document.getElementById('loadingJR').classList.remove('hidden');
            document.getElementById('contentJR').classList.add('hidden');
            document.getElementById('infoTotal').classList.add('hidden');

            let successCount = 0;
            let errorMessages = [];
            let totalGrandAll = 0;
            let totalPajak = '';
            let totalJR = 0;
            let totalPNBP = 'Rp 0';

            // Fetch PNBP
            try {
                const pnbpRes = await fetch(`https://api-doc.jambisamsat.net/api/kendaraan/pnbp?nopol=${encodeURIComponent(currentNopol)}`, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const pnbpData = await pnbpRes.json();

                document.getElementById('loadingPNBP').classList.add('hidden');
                document.getElementById('contentPNBP').classList.remove('hidden');

                if (pnbpData.status && pnbpData.data) {
                    totalPNBP = displayPNBP(pnbpData.data);
                    successCount++;
                    showToast('PNBP Berhasil', 'Data PNBP berhasil dimuat', 'success');
                } else {
                    totalPNBP = displayPNBP(null);
                    errorMessages.push('PNBP: ' + (pnbpData.message || 'Data tidak ditemukan'));
                    showToast('PNBP Gagal', pnbpData.message || 'Data PNBP tidak ditemukan', 'error');
                }
            } catch (error) {
                document.getElementById('loadingPNBP').classList.add('hidden');
                document.getElementById('contentPNBP').classList.remove('hidden');
                totalPNBP = displayPNBP(null);
                errorMessages.push('PNBP: Gagal terhubung ke server');
                showToast('PNBP Error', 'Gagal mengambil data PNBP', 'error');
                console.error('Error PNBP:', error);
            }

            // Fetch Pajak
            try {
                const pajakRes = await fetch(`https://api-doc.jambisamsat.net/api/pajak/detail?nopol=${encodeURIComponent(currentNopol)}`, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const pajakData = await pajakRes.json();

                document.getElementById('loadingPajak').classList.add('hidden');
                document.getElementById('contentPajak').classList.remove('hidden');

                if (pajakData.status && pajakData.data) {
                    displayPajak(pajakData.data);
                    successCount++;
                    showToast('Pajak Berhasil', 'Data pajak berhasil dimuat', 'success');
                    totalPajak = pajakData.data.tagihan.total.grand_total;
                } else {
                    errorMessages.push('Pajak: ' + (pajakData.message || 'Data tidak ditemukan'));
                    showToast('Pajak Gagal', pajakData.message || 'Data pajak tidak ditemukan', 'error');
                }
            } catch (error) {
                document.getElementById('loadingPajak').classList.add('hidden');
                document.getElementById('contentPajak').classList.remove('hidden');
                errorMessages.push('Pajak: Gagal terhubung ke server');
                showToast('Pajak Error', 'Gagal mengambil data pajak', 'error');
                console.error('Error Pajak:', error);
            }

            // Fetch Jasa Raharja
            try {
                const jrRes = await fetch(`https://api-doc.jambisamsat.net/api/jr/detail?nopol=${encodeURIComponent(currentNopol)}`, {
                    headers: {
                        'Authorization': `Bearer ${API_TOKEN}`,
                        'Content-Type': 'application/json'
                    }
                });
                const jrData = await jrRes.json();

                document.getElementById('loadingJR').classList.add('hidden');
                document.getElementById('contentJR').classList.remove('hidden');

                if (jrData.status && jrData.data) {
                    displayJR(jrData.data);
                    successCount++;
                    showToast('Jasa Raharja Berhasil', 'Data Jasa Raharja berhasil dimuat', 'success');
                    totalJR = jrData.data.total_tarif.total;
                } else {
                    errorMessages.push('JR: ' + (jrData.message || 'Data tidak ditemukan'));
                    showToast('Jasa Raharja Gagal', jrData.message || 'Data Jasa Raharja tidak ditemukan', 'error');
                }
            } catch (error) {
                document.getElementById('loadingJR').classList.add('hidden');
                document.getElementById('contentJR').classList.remove('hidden');
                errorMessages.push('JR: Gagal terhubung ke server');
                showToast('Jasa Raharja Error', 'Gagal mengambil data Jasa Raharja', 'error');
                console.error('Error JR:', error);
            }

            // Summary notification (akan masuk queue otomatis)
            if (successCount === 3) {
                showToast('Sempurna!', 'Semua data berhasil dimuat', 'success');
            } else if (successCount === 0) {
                showToast('Gagal Total', 'Semua data gagal dimuat. Silakan coba lagi.', 'error');
            } else {
                showToast('Sebagian Berhasil', `${successCount} dari 3 data berhasil dimuat`, 'warning');
            }

            // Display Total Keseluruhan
            displayTotalKeseluruhan(totalPajak, totalJR, totalPNBP);
        }

        function displayPajak(data) {
            // Total tagihan
            document.getElementById('grandTotal').textContent = data.tagihan.total.grand_total;
            
            // Info umum
            document.getElementById('terakhirBayar').textContent = formatDate(data.terakhir_bayar);
            document.getElementById('jarakWaktu').textContent = 
                `${data.jarak.tahun} tahun ${data.jarak.bulan % 12} bulan`;

            // Total PKB & Opsen
            document.getElementById('pkbPokok').textContent = data.tagihan.total.pkb.pokok;
            document.getElementById('pkbDenda').textContent = data.tagihan.total.pkb.denda;
            document.getElementById('opsenPokok').textContent = data.tagihan.total.opsen.pokok;
            document.getElementById('opsenDenda').textContent = data.tagihan.total.opsen.denda;

            // Rincian per periode
            const rincianContainer = document.getElementById('rincianPeriodeBody');
            rincianContainer.innerHTML = '';
            
            data.tagihan.rincian.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                tr.innerHTML = `
                    <td class="p-2 font-medium text-gray-900">${item.periode.periode}</td>
                    <td class="p-2 text-gray-600">${item.periode.total_bulan_telat} bln</td>
                    <td class="p-2 text-right text-gray-900">${item.pkb.pokok}</td>
                    <td class="p-2 text-right text-red-600">${item.pkb.denda}</td>
                    <td class="p-2 text-right text-gray-900">${item.is_opsen ? item.opsen.opsen : '-'}</td>
                    <td class="p-2 text-right text-red-600">${item.is_opsen ? item.opsen.denda_opsen : '-'}</td>
                    <td class="p-2 text-right font-semibold text-gray-900">${item.total}</td>
                `;
                rincianContainer.appendChild(tr);
            });
        }

        function displayPNBP(data) {
            if (data && data.pnbp) {
                // Display total
                document.getElementById('pnbpTotal').textContent = data.pnbp.total || 'Rp 0';
                
                // Display STNK
                document.getElementById('pnbpSTNK').textContent = 
                    data.pnbp.stnk?.status ? (data.pnbp.stnk.nominal || 'Rp 0') : 'Rp 0';
                
                // Display TNKB
                document.getElementById('pnbpTNKB').textContent = 
                    data.pnbp.tnkb?.status ? (data.pnbp.tnkb.nominal || 'Rp 0') : 'Rp 0';
                
                document.getElementById('pnbpContent').classList.remove('hidden');
                document.getElementById('pnbpEmpty').classList.add('hidden');
                
                return data.pnbp.total || 'Rp 0';
            } else {
                document.getElementById('pnbpContent').classList.add('hidden');
                document.getElementById('pnbpEmpty').classList.remove('hidden');
                return 'Rp 0';
            }
        }

        function displayJR(data) {
            // Total tarif
            const total = data.total_tarif.total;
            document.getElementById('jrTotal').textContent = formatRupiah(total);
            document.getElementById('jrPokok').textContent = formatRupiah(data.total_tarif.pokok_jr + data.total_tarif.kartu_jr);
            document.getElementById('jrDenda').textContent = formatRupiah(data.total_tarif.denda_jr);

            // Tarif per tahun dalam table
            const tarifContainer = document.getElementById('tarifPerTahunBody');
            tarifContainer.innerHTML = '';
            
            data.tarif_per_tahun.forEach((item, index) => {
                const tr = document.createElement('tr');
                tr.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                tr.innerHTML = `
                    <td class="p-2 font-medium text-gray-900">${item.keterangan}</td>
                    <td class="p-2 text-right text-gray-900">${formatRupiah(item.kartu_jr + item.pokok_jr)}</td>
                    <td class="p-2 text-right text-red-600">${formatRupiah(item.denda_jr)}</td>
                    <td class="p-2 text-right font-semibold text-gray-900">${formatRupiah(item.subtotal)}</td>
                `;
                tarifContainer.appendChild(tr);
            });
        }

        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        function parseRupiah(rupiahString) {
            // Parse string Rupiah to number
            if (!rupiahString || rupiahString === '-') return 0;
            return Number(rupiahString.replace(/[^0-9,-]/g, '').replace('.', '').replace(',', '.'));
        }

        function displayTotalKeseluruhan(totalPajak, totalJR, totalPNBP) {
            // Parse all values
            const pajakValue = parseRupiah(totalPajak);
            const jrValue = Number(totalJR) || 0;
            const pnbpValue = parseRupiah(totalPNBP);

            // Calculate grand total
            const grandTotal = pajakValue + jrValue + pnbpValue;

            // Display
            document.getElementById('totalKeseluruhan').textContent = formatRupiah(grandTotal);
            document.getElementById('totalPajak').textContent = totalPajak || 'Rp 0';
            document.getElementById('totalJR').textContent = formatRupiah(jrValue);
            document.getElementById('totalPNBP').textContent = totalPNBP || 'Rp 0';

            // Show the card
            document.getElementById('infoTotal').classList.remove('hidden');
        }
    </script>
</body>
</html>

