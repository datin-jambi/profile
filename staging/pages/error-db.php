<div class="container mx-auto max-w-2xl text-center p-10">
    <h1 class="text-4xl font-bold text-red-600 mb-4">500 - Kesalahan Koneksi Database</h1>
    <p class="mb-6 text-gray-700">
        Maaf, sistem tidak dapat terhubung ke database saat ini.
    </p>

    <?php if (!empty($dbErrorMessage)): ?>
        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
            <strong>Detail Teknis:</strong><br>
            <?= htmlspecialchars($dbErrorMessage); ?>
        </div>
    <?php endif; ?>

</div>