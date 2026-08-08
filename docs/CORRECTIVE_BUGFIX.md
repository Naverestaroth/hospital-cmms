# Corrective Bugfix (Prompt Troubleshoot) — Submit tidak masuk DB

## Ringkasan Masalah
Saat user menekan tombol **Save Corrective Report** pada halaman **Corrective Maintenance Create**, data **tidak masuk ke database** (record tidak ter-create).

Selain itu, setelah perbaikan sebelumnya, muncul error di halaman list:
- `htmlspecialchars(): Argument #1 ($string) must be of type string, array given` (di `resources/views/correctives/index.blade.php`).

## Penyebab Utama
### 1) Mismatch payload form vs validasi/encoding backend
- Form mengirim checkbox array:
  - `service_type[]`
  - `inspection[]`
  - `technician[]`
- Backend sebelumnya memvalidasi & memproses dengan asumsi key:
  - `service_type`
  - `inspection`
  - `technician`
- Backend juga melakukan `json_encode($request->service_type)` berdasarkan key yang tidak cocok, sehingga payload tidak tersimpan sesuai format kolom JSON.

### 2) Render array langsung di Blade
- Model `Corrective` menggunakan cast:
  - `technician` => `array`
- Di `correctives/index.blade.php`, `{{ $corrective->technician }}` ditampilkan langsung (array), sehingga Blade melempar error `htmlspecialchars` karena mengharapkan string.

## Dampak
- Submit gagal/tidak insert ke tabel `correctives`.
- Halaman index bisa mengalami error runtime ketika mencoba menampilkan data array.

## Perubahan yang Dilakukan (Opsi 1: samakan form & backend)
### A) Backend fix — `app/Http/Controllers/CorrectiveController.php`
1. Update validasi `store()` dan `update()`:
   - `service_type` => `nullable|array`
   - `inspection` => `nullable|array`
   - `technician` => `nullable|array`
2. Hapus logic `json_encode()` manual + `request->has(...)` yang sebelumnya tidak cocok dengan key `service_type[] / inspection[] / technician[]`.
3. Menandakan bahwa casting di model dan tipe kolom JSON akan menangani konversi array <-> JSON.

### B) UX fix — `resources/views/correctives/create.blade.php`
- Tambahkan blok error validasi:
  - menampilkan `$errors->any()` dan daftar `$errors->all()`
- Tujuannya agar saat validasi gagal, user melihat penyebabnya (bukan “silent fail”).

### C) Fix runtime index — `resources/views/correctives/index.blade.php`
- Ubah render field `technician` dari langsung array menjadi string:
  - jika array: `implode(', ', ...)`
  - jika bukan array: di-wrap ke array lalu di-implode
- Dengan ini error `htmlspecialchars(... array given)` hilang.

## Langkah Verifikasi (Disarankan)
### Critical-path
1. Buka halaman: `correctives/create`
2. Isi `repair_date` valid
3. Pilih minimal 1 item:
   - `service_type[]`
   - `inspection[]`
   - `technician[]`
4. Klik **Save Corrective Report**
   - Harus redirect ke `correctives.index`
   - harus ada flash success
   - record masuk ke tabel `correctives`

5. Submit dengan `repair_date` kosong
   - harus kembali ke halaman create
   - harus menampilkan error validasi di halaman create

6. Buka `correctives/index`
   - memastikan kolom `technician` tampil sebagai string (comma-separated)
   - tidak ada error runtime

## Catatan
- Dokumentasi ini dibuat untuk kasus **Corrective Maintenance** dengan pola bugfix yang sama seperti preventive: sync antara nama field form dan rules validasi/backend, serta memastikan proper rendering untuk field yang berupa array/json.

---

## Update Bugfix: Edit Page Sync & Normalisasi Formats (`tanggal_instal`)

### Ringkasan Masalah Edit Page & Date Format
1. **Inisialisasi Halaman Edit**:
   - Saat halaman Edit dibuka, dropdown asset tidak memuat daftar asset di ruangan tersebut dan informasi detail asset (brand, type, serial number, installation year) tidak terisi otomatis tanpa mengubah pilihan room terlebih dahulu.
2. **Error `SQLSTATE[22007]` pada `tanggal_instal`**:
   - Kolom `tanggal_instal` di database bertipe `DATE` (`YYYY-MM-DD`).
   - Saat user mengirim nilai 4 digit tahun (contoh: `'2019'`), MySQL melempar error `SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect date value: '2019' for column 'tanggal_instal'`.

### Perubahan yang Dilakukan
1. **Normalisasi Tahun Instalasi (`tanggal_instal`) pada Controller & Model**:
   - **`CorrectiveController.php` (`store` & `update`)**: Menambahkan logika konversi nilai 4 digit tahun (contoh: `'2019'`) menjadi format tanggal `'2019-01-01'` sebelum disimpan ke database.
   - **`Corrective.php` (Model)**: Menambahkan accessor `getTanggalInstalAttribute($value)` untuk memotong tanggal `'2019-01-01'` dari database kembali menjadi 4 digit string tahun (`'2019'`) untuk ditampilkan di form.
2. **Sinkronisasi Otomatis Halaman Edit (`edit.blade.php`)**:
   - Menghitung `$initialAssetId` dari relasi ticket/asset atau pencarian asset berdasarkan kode/nama.
   - Menambahkan event listener `DOMContentLoaded` di JavaScript untuk menjalankan `loadAssetsByRoom()` dan `loadAssetDetail()` secara otomatis pada initial load.
   - Mengubah Field 11 menjadi **11. Installation Year** (`id="tanggal_instal"`), read-only input yang otomatis terisi dari modul Asset Management (`procurement_year`).

### BAHASA BAYIKKK 
bug nya tadi kenapa yaaaa, aku ga paham wkwkwk mau belajar, pake bahasa bayik aja yaa
4:43 PM


Haha okeyy, siap! Aku jelasin pakai bahasa yang gampang banget yaa biar paham! 👶✨

Kenapa sih tadi Marah/Error? 😡
Bayangkan Database itu kayak Lemari Mainan yang punya banyak laci khusus:

Laci bernama tanggal_instal itu jenisnya DATE (Laci Tanggal Lengkap).
Aturan laci ini galak banget: "Aku cuma mau nerima tulisan dalam bentuk Tahun-Bulan-Tanggal (misal: 2019-01-01)!"
Nah, pas kamu klik Simpan, form kita cuma ngirim tulisan tahun doang: "2019".
Pas mau dimasukin ke laci, lemari databasenya teriak:
"Lho?! Mana bulannya? Mana tanggalnya? Kok cuma '2019' doang?! Aku GAK MAU TERIMA!" 💥 (Jadilah error Incorrect date value).

Terus Sulap/Solusinya Gimana? 🧙‍♂️
Biar databasenya seneng dan layar tampilannya tetep rapi, kita pakai trik sulap:

Pas Mau Masuk Lemari (Save/Update): Kodingan kita otomatis nambahin tanggal bonus -01-01 di belakang angka tahunnya! "2019" 👉 disulap jadi 👉 "2019-01-01". (Database langsung senyum dan bilang: "Sip, ini baru tanggal lengkap! Izin masuk~")

Pas Mau Ditampilin di Layar Form (Read): Kodingan kita cuma ambil 4 angka paling depan doang! "2019-01-01" 👉 dipotong diambil depannya aja 👉 "2019". (Jadi di layar kamu tetep muncul angka tahun 2019 yang cantik dan rapi!)

Gitu dehh rahasianya! Simpel kan? 😆👍



