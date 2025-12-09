<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INFORMASI j-SAMSAT</title>
    <meta name="description" content="Portal Informasi SAMSAT Provinsi Jambi">
    <script src="./staging/tailwind/tailwind.js"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Fullscreen layout untuk laptop/desktop */
        @media (min-width: 1024px) {
            html, body {
                height: 100vh;
                overflow: hidden;
            }
            .content-wrapper {
                height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .main-content {
                flex: 1;
                display: flex;
                align-items: center;
                overflow: hidden;
            }
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden">
    <!-- Background Image with Overlay -->
    <div class="fixed inset-0 z-0">
        <div class="w-full h-full bg-cover bg-center bg-no-repeat" 
             style="background-image: url('https://jambisamsat.net/assets/images/samsatjambi.jpg');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-indigo-900/80"></div>
    </div>
    
    <!-- Content Wrapper -->
    <div class="relative z-10 content-wrapper" style="position: relative; z-index: 10;">
    
    <!-- Header -->
    <header class="bg-white/95 backdrop-blur-md shadow-lg">
        <div class="container mx-auto px-4 py-4 lg:py-3">
            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-bold text-blue-600 mb-1">J-SAMSAT</h1>
                <p class="text-base lg:text-lg text-gray-600">INFORMASI SAMSAT JAMBI</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container mx-auto px-4 py-4 lg:py-0 w-full">
            <div class="max-w-6xl mx-auto">
            
            <!-- Title Section -->
            <div class="text-center mb-4 lg:mb-6 fade-in-up">
                <h2 class="text-2xl lg:text-3xl font-bold text-orange-400 mb-2 drop-shadow-lg">INFORMASI</h2>
                <p class="text-white text-base lg:text-lg drop-shadow">Pilih layanan yang Anda butuhkan</p>
            </div>

            <!-- Button Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5">
                
                <!-- Data Kendaraan -->
                <button onclick="location.href='infokb.html'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-blue-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-blue-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">DATA KENDARAAN</h3>
                    </div>
                </button>

                <!-- Pajak Kendaraan -->
                <button onclick="location.href='infopkb.html'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-green-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-green-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">PAJAK KENDARAAN</h3>
                    </div>
                </button>

                <!-- Nilai Jual -->
                <button onclick="location.href='nilaijual/form_nb.php'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-purple-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-purple-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">NILAI JUAL</h3>
                    </div>
                </button>

                <!-- Smart Samsat -->
                <a href="https://drive.google.com/file/d/1OQPvTG-7-DNY43c0Ef56Tj5084YyneMP/view?usp=sharing" target="_blank"
                    class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-indigo-500 block">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-indigo-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">SMART SAMSAT</h3>
                    </div>
                </a>

                <!-- Progresif -->
                <!-- <button onclick="location.href='infoprogresif.html'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-yellow-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-yellow-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">PROGRESIF</h3>
                    </div>
                </button> -->

                <!-- Whistleblowing -->
                <button onclick="location.href='whistle/index.php'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-red-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-red-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">WHISTLEBLOWING SYSTEM</h3>
                    </div>
                </button>

                <!-- Standar Pelayanan -->
                <button onclick="location.href='fotoslide.php?id=1'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-teal-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-teal-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">STANDAR PELAYANAN</h3>
                    </div>
                </button>

                <!-- Pelayanan Publik -->
                <button onclick="location.href='tentang.html'" 
                        class="bg-white/95 backdrop-blur-md hover:bg-white text-gray-800 font-semibold py-4 lg:py-5 px-4 lg:px-5 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 border-l-4 border-pink-500">
                    <div class="text-left">
                        <svg class="w-6 h-6 lg:w-7 lg:h-7 text-pink-500 mb-1 lg:mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="text-base lg:text-lg font-bold">PELAYANAN PUBLIK</h3>
                    </div>
                </button>

            </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/95 backdrop-blur-md shadow-lg">
        <div class="container mx-auto px-4 py-3 lg:py-4 text-center text-gray-600">
            <p class="text-sm lg:text-base">&copy; 2024 Dipenda Prov. Jambi - SAMSAT Jambi</p>
        </div>
    </footer>

    </div>
    <!-- End Content Wrapper -->

    <!-- WhatsApp Floating Button -->
    <a href="https://api.whatsapp.com/send?phone=628117404761" 
       target="_blank" 
       rel="nofollow noopener"
       style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;"
       class="bg-green-500 hover:bg-green-600 text-white rounded-full shadow-lg hover:shadow-xl transform hover:scale-110 transition-all duration-300 group">
        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
        <span class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-800 text-white text-sm py-2 px-3 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            Chat dengan Admin
        </span>
    </a>

    <!-- AI Chat SDK -->
    <script src="https://app.ebesha.net/live-chat/assets/sdk/sdk.js"></script>
    <script>
        MyChatSDK.init({
            base_path: "https://app.ebesha.net/live-chat/auth",
            auth: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzZWNyZXQiOiJlYmVzaGFfb21uaWNoYW5uZWxfbGl2ZWNoYXQifQ.CKSA5a4HVdcrwnss6OtAY__WpyUa_Nyl1OzofnG6X_4!1_9",
            chat_icon: "https://static.wixstatic.com/media/b1fac1_f6a4a583819445a99a183327431e89cb~mv2.png",
            chat_icon_color: "rgb(242, 140, 21)",
            chat_icon_width: "56px",
            chat_icon_height: "56px",
            icon_box_shadow: "0 4px 8px rgb(255 131 36)",
            icon_bottom: "92px",
            icon_right: "20px",
            z_index: 10000
        });
    </script>

</body>
</html>