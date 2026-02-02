<?php
include_once("../../whistle/lib.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whistle Blower</title>
    <link rel="shortcut icon" href="../../whistle/admin/img/favsample.png">
    <script src="../../../staging/tailwind/tailwind.js"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-indigo-200 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white/90 shadow-md sticky top-0 z-20">
        <div class="container mx-auto flex flex-col sm:flex-row items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <img src="../../whistle/images/logo.png" alt="Logo" class="h-10 w-auto" />
            </div>
            <nav class="mt-2 sm:mt-0">
                <ul class="flex gap-4 text-base font-semibold">
                    <li><a href="../../whistle/beranda.php" class="hover:text-blue-600 transition">Beranda</a></li>
                    <li><a href="../../whistle/pelaporan.php?id=0" class="hover:text-blue-600 transition">Pelaporan</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Banner/Slider -->
    <section class="relative w-full bg-white/80 py-4 shadow-inner">
        <div class="container mx-auto flex items-center justify-center gap-4">
            <button id="sliderPrev" class="p-2 rounded-full hover:bg-blue-100 transition"><img src="../../whistle/images/arrow_left.gif" alt="Kiri" class="h-6 w-6" /></button>
            <div class="relative w-full h-48 sm:h-72 flex items-center justify-center overflow-hidden">
                <div id="sliderImages" class="absolute inset-0 w-full h-full flex transition-transform duration-700">
                    <img src="../../whistle/images/2.jpg" alt="Banner 1" class="h-48 sm:h-72 w-full object-cover rounded-none shadow flex-shrink-0" />
                    <img src="../../whistle/images/gambar2.jpg" alt="Banner 2" class="h-48 sm:h-72 w-full object-cover rounded-none shadow flex-shrink-0" />
                    <img src="../../whistle/images/gambar3.jpg" alt="Banner 3" class="h-48 sm:h-72 w-full object-cover rounded-none shadow flex-shrink-0" />
                </div>
                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1 z-10">
                    <span class="slider-dot w-2 h-2 rounded-full bg-blue-300 cursor-pointer"></span>
                    <span class="slider-dot w-2 h-2 rounded-full bg-blue-300 cursor-pointer"></span>
                    <span class="slider-dot w-2 h-2 rounded-full bg-blue-300 cursor-pointer"></span>
                </div>
            </div>
            <button id="sliderNext" class="p-2 rounded-full hover:bg-blue-100 transition"><img src="../../whistle/images/arrow_right.gif" alt="Kanan" class="h-6 w-6" /></button>
        </div>
    </section>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="container mx-auto px-4 py-6">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM tbl_beranda");
            while ($row = mysqli_fetch_assoc($query)) {
                $replace1 = str_replace("&lt;", "<", $row['isi_beranda']);
                $replace2 = str_replace("&gt;", ">", $replace1);
                echo '<div class="mb-8 bg-white/90 rounded-lg shadow p-6">'
                    . '<h2 class="text-xl font-bold text-blue-700 mb-2">' . htmlspecialchars($row['nama_beranda']) . '</h2>'
                    . '<div class="prose max-w-none">' . $replace2 . '</div>'
                . '</div>';
            }
            ?>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-gradient-to-r from-blue-900/90 to-indigo-900/90 text-white mt-auto">
        <div class="container mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <a href="../../whistle/beranda.php" class="hover:underline font-semibold tracking-wide">BERANDA</a>
                <span class="hidden md:inline-block">|</span>
                <a href="../../whistle/pelaporan.php" class="hover:underline font-semibold tracking-wide">PELAPORAN</a>
            </div>
            <div class="flex flex-col items-center md:items-end gap-1">
                <a href="http://bankjambi.co.id" target="_blank" rel="noopener"><img src="../../whistle/images/logo.png" alt="Logo Bank Jambi" class="h-8 sm:h-10 w-auto mb-1" /></a>
                <span class="text-xs text-gray-200">&copy; <?php echo date('Y'); ?> Dipenda Prov. Jambi - SAMSAT Jambi</span>
            </div>
        </div>
    </footer>
<script>
// Simple Carousel/Slider
const images = document.querySelectorAll('#sliderImages img');
const sliderImages = document.getElementById('sliderImages');
const dots = document.querySelectorAll('.slider-dot');
let current = 0;
let interval = null;

function showSlide(idx) {
    current = idx;
    sliderImages.style.transform = `translateX(-${idx * 100}%)`;
    dots.forEach((dot, i) => {
        dot.classList.toggle('bg-blue-600', i === idx);
        dot.classList.toggle('bg-blue-300', i !== idx);
    });
}

function nextSlide() {
    showSlide((current + 1) % images.length);
}
function prevSlide() {
    showSlide((current - 1 + images.length) % images.length);
}

document.getElementById('sliderNext').onclick = nextSlide;
document.getElementById('sliderPrev').onclick = prevSlide;
dots.forEach((dot, i) => dot.onclick = () => showSlide(i));

function startAutoSlide() {
    interval = setInterval(nextSlide, 3500);
}
function stopAutoSlide() {
    clearInterval(interval);
}
sliderImages.parentElement.addEventListener('mouseenter', stopAutoSlide);
sliderImages.parentElement.addEventListener('mouseleave', startAutoSlide);
showSlide(0);
startAutoSlide();
</script>
</body>
</html>