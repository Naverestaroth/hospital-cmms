# Dokumentasi Bugfix: Excel Import (Asset Inventory)

## Ringkasan

Selama implementasi fitur **Excel Import Asset Inventory**, terdapat beberapa permasalahan pada alur upload, preview, dan proses import ke database.

Dokumentasi ini mencatat seluruh proses debugging, penyebab utama (root cause), serta solusi yang diterapkan agar proses import berjalan dengan benar.

---

# Workflow Import

Alur import yang digunakan:

```text
Upload Excel
        ↓
Generate Preview
        ↓
Review Data
        ↓
Confirm Import
        ↓
Insert ke Database
```

Preview digunakan untuk memverifikasi data sebelum benar-benar disimpan ke database.

---

# Bug 1 — Preview Menampilkan 0 Data

## Gejala

Setelah upload file Excel:

- Preview berhasil terbuka.
- Tidak ada data yang ditampilkan.
- Debug menunjukkan:

```text
previewRows_count = 0
imported = 0
failed = 0
skipped = 0
duplicates = 0
```

## Penyebab

Importer (`AssetsExcelImport`) tidak dijalankan saat proses preview sehingga collection tidak pernah menghasilkan `previewRows`.

Akibatnya halaman preview menerima array kosong.

## Solusi

Menjalankan importer pada mode **dry run** saat preview.

```php
$import = new AssetsExcelImport(dryRun: true, codeSeed: $codeSeed);

Excel::import($import, $file);
```

Preview sekarang menampilkan seluruh data tanpa menyimpan ke database.

---

# Bug 2 — Tombol Import Hilang

## Gejala

Preview berhasil muncul.

Namun setelah tabel selesai ditampilkan tidak ada tombol:

- Import
- Cancel

User tidak dapat melanjutkan proses import.

## Penyebab

Saat proses refactor oleh AI Assistant, bagian bawah file `import-preview.blade.php` terhapus sehingga form konfirmasi ikut hilang.

Workflow berhenti pada tahap Preview.

## Solusi

Mengembalikan:

- Tombol Cancel
- Tombol Confirm Import

beserta form POST menuju:

```php
confirmImport()
```

Workflow kembali menjadi:

```text
Upload
↓

Preview

↓

Confirm Import

↓

Database
```

---

# Bug 3 — File Temporary Tidak Ditemukan

## Gejala

Saat menekan tombol Import muncul error:

```text
File ... does not exist.
```

padahal Preview berhasil.

## Penyebab

File upload disimpan menggunakan Laravel Storage Disk:

```php
$file->store('assets_import_tmp', 'local');
```

Namun saat import kembali file dibuka menggunakan:

```php
storage_path('app/' . $tmpPath)
```

Project menggunakan konfigurasi:

```php
local

↓

storage/app/private
```

sedangkan kode membuka:

```text
storage/app/assets_import_tmp
```

Akibatnya path file tidak sesuai dengan lokasi sebenarnya.

## Solusi

Menggunakan Storage Facade agar mengikuti konfigurasi filesystem Laravel.

Sebelum:

```php
Excel::import($import, storage_path('app/' . $tmpPath));
```

Sesudah:

```php
Excel::import(
    $import,
    Storage::disk('local')->path($tmpPath)
);
```

Setelah perubahan ini proses import berhasil membaca file temporary.

---

# Bug 4 — Validasi Import Terlalu Ketat

## Gejala

Preview berhasil.

Namun banyak data berstatus:

- Failed
- Skipped

padahal data tersebut masih layak diimport.

Contoh:

- Procurement Year tidak sesuai format.
- Brand kosong.
- Type kosong.
- Room kosong.

## Penyebab

Importer dirancang sebagai validator yang terlalu ketat.

Padahal tujuan fitur ini adalah migrasi data inventaris lama ke sistem CMMS.

## Solusi

Importer diubah menjadi **fault-tolerant migration tool**.

Prinsip yang digunakan:

- Import sebanyak mungkin data.
- Field yang tidak valid disimpan sebagai `NULL`.
- Jangan menggagalkan seluruh row hanya karena satu field tidak sesuai.
- Duplicate Serial Number tetap dilewati.

---

# Bug 5 — Duplicate Asset

## Gejala

Beberapa asset tidak diimport.

## Penyebab

Serial Number sudah ada di database.

Importer memang melakukan pengecekan duplicate.

```php
Asset::where('serial_number', $serialNumber)->exists();
```

## Solusi

Duplicate tidak dianggap error.

Importer hanya:

- menambah counter Duplicate
- melewati row tersebut
- melanjutkan proses import berikutnya.

---

# Hasil Akhir

Setelah seluruh perbaikan dilakukan:

✅ Upload Excel berhasil.

✅ Preview berhasil menampilkan seluruh data.

✅ Confirm Import berjalan normal.

✅ Asset berhasil masuk ke database.

✅ Duplicate tetap terdeteksi.

✅ Workflow import selesai tanpa error.

---

# Lessons Learned

1. Preview dan Confirm Import merupakan dua request yang berbeda.

2. Gunakan mode **dry run** pada Preview agar database tidak berubah.

3. Jangan membangun path file secara manual menggunakan `storage_path()`.

4. Selalu gunakan:

```php
Storage::disk()->path()
```

agar mengikuti konfigurasi Filesystem Laravel.

5. Migration Tool berbeda dengan Validator.

Importer sebaiknya bersifat toleran terhadap data inventaris lama sehingga proses migrasi tidak terhambat hanya karena beberapa field tidak sempurna.

6. Saat melakukan refactor menggunakan AI Assistant, selalu pastikan workflow utama (Upload → Preview → Import) tidak berubah. AI dapat memperbaiki bug tetapi tanpa sengaja menghapus bagian penting seperti tombol aksi atau form konfirmasi.

---