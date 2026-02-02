<div class="container mx-auto px-4 py-10">
  <h1 class="text-2xl font-bold text-blue-700 mb-4">Cek Informasi PKB</h1>
  <form action="" method="post" class="bg-white shadow-md rounded-lg p-6 space-y-4">
    <div>
      <label class="block text-gray-700">Nomor Polisi</label>
      <input type="text" name="nopol" placeholder="BH1234CD"
             class="w-full border px-4 py-2 rounded-lg focus:ring focus:ring-blue-300" required>
    </div>
    <div>
      <label class="block text-gray-700">Nomor Rangka</label>
      <input type="text" name="no_rangka" placeholder="Masukkan 5 digit terakhir nomor rangka"
             class="w-full border px-4 py-2 rounded-lg focus:ring focus:ring-blue-300" required>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Cek PKB</button>
  </form>
</div>
