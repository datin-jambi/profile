<div class="container mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold text-blue-700 mb-4">Cek Nilai Jual Kendaraan</h1>
  <form action="" method="post" class="bg-white shadow-md rounded-lg p-6 space-y-4">
    <div>
      <label class="block text-gray-700">Merek Kendaraan</label>
      <input type="text" name="merek" placeholder="Contoh: Toyota"
             class="w-full border px-4 py-2 rounded-lg focus:ring focus:ring-blue-300" required>
    </div>
    <div>
      <label class="block text-gray-700">Model / Tipe</label>
      <input type="text" name="model" placeholder="Contoh: Avanza 1.5 G"
             class="w-full border px-4 py-2 rounded-lg focus:ring focus:ring-blue-300" required>
    </div>
    <div>
      <label class="block text-gray-700">Tahun Produksi</label>
      <input type="number" name="tahun" placeholder="Contoh: 2020"
             class="w-full border px-4 py-2 rounded-lg focus:ring focus:ring-blue-300" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Cek Nilai Jual</button>
  </form>
</div>
