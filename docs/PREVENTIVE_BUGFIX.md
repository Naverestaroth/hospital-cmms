# Dokumentasi Bugfix: Preventive (Form Tidak Bisa Submit / Tidak Masuk Database)

## Ringkasan
Pada modul **Preventive Maintenance** terjadi masalah:
- Saat tombol **Submit/Schedule Maintenance** ditekan, **data tidak masuk ke database**.
- Tidak terjadi redirect sesuai harapan menuju halaman utama (`preventives.index`).

Masalah ini disebabkan **mismatch (ketidaksesuaian) antara field yang dikirim dari form** dengan **field yang divalidasi oleh backend**.

---

## Permasalahan Utama
### Backend: `app/Http/Controllers/PreventiveController.php`
Pada method `store()` terdapat validasi wajib:
- `room` : `required`
- `asset_id` : `required|exists:assets,id`
- `schedule_date` : `required|date`
- `technician` : `required`
- `status` : `required`
- `notes` : `nullable`

### Frontend: `resources/views/preventives/create.blade.php`
Pada awalnya form **tidak mengirim** field berikut:
- Tidak ada input/field `asset_id`
- Tidak ada input/field `status`

Akibatnya, setiap submit selalu gagal di tahap validasi sehingga record **tidak pernah dibuat**.

---

## Dampak
- Tidak ada data baru pada tabel `preventives`.
- Redirect ke `preventives.index` tidak terjadi karena request berhenti di validasi.
- Secara UI, pengguna bisa merasa submit “tidak jalan”.

---

## Perbaikan yang Dilakukan (Implementasi)
Saya melakukan penyelarasan **Opsi 1 (sesuaikan form dengan backend)**.

### Perubahan file: `resources/views/preventives/create.blade.php`
1. **Menambah field `status`**
   - Ditambahkan dropdown berisi:
     - `Scheduled`
     - `Completed`
     - `Missed`
   - Default selection untuk `Scheduled`.
   - Field ini memenuhi validasi `status required`.

2. **Menambah field `asset_id`**
   - Ditambahkan dropdown `asset_id`.
   - Sumber data: `$assets` (sudah disediakan oleh `PreventiveController@create()`).
   - Field ini memenuhi validasi `asset_id required|exists:assets,id`.

3. **Mencegah kehilangan input saat validasi gagal**
   - Menambahkan penggunaan `old()` pada textarea:
     - `good_condition`
     - `problem_found`
     - `notes`
   - Ini tidak mengubah logika validasi, tapi memperbaiki UX jika user mengalami error input.

---

## Detail Implementasi Opsi 1 dan Opsi 2

## Opsi 1 (DIREKOMENDASIKAN): Sesuaikan form dengan `PreventiveController@store`
**Inti:** backend mengharuskan `asset_id` dan `status`, maka form wajib mengirim dua field itu.

### Cara kerjanya
1. Backend sudah memvalidasi `asset_id` dan `status`.
2. Form ditambah input:
   - `asset_id` (dropdown dari `$assets`)
   - `status` (select dengan nilai valid sesuai enum)
3. Saat submit:
   - request lolos validasi
   - `Preventive::create($request->all())` berjalan
   - redirect ke `preventives.index` terjadi.

### Di repo saat ini
Opsi 1 ini yang sudah diterapkan pada `resources/views/preventives/create.blade.php`.

---

## Opsi 2: Sesuaikan backend dengan form yang ada
**Inti:** jika form memang ingin menyimpan kolom seperti `asset_code`, `asset_name`, dll (tanpa `asset_id` dan tanpa `status` user memilih), maka backend harus mengikuti.

### Yang harus diubah (contoh pendekatan)
1. **Validasi**
   - Hapus/ubah aturan `asset_id` menjadi tidak required (atau dihapus jika tidak ingin dipakai).
   - `status` bisa diubah menjadi `nullable` atau diberi default di backend.

2. **Model / Migration / Persist data**
   - Pastikan kolom yang di-submit sesuai dengan `preventives` tabel dan `fillable` model.

3. **Default `status`**
   - Misal set `status` otomatis ke `'Scheduled'` jika user tidak mengirim.

### Dampak opsi 2
- Lebih “mudah” jika form memang tidak ingin memakai relasi asset.
- Tapi tetap harus memastikan konsistensi skema tabel dan model.

---

## Verifikasi yang Disarankan
1. Buka halaman `preventives/create`.
2. Isi field yang valid:
   - `room`
   - `schedule_date`
   - pilih `asset_id`
   - `technician`
   - pilih `status`
   - opsional `notes`
3. Klik **Schedule Maintenance**.
4. Harusnya:
   - Record masuk ke tabel `preventives`
   - Redirect ke `preventives/index`
   - Muncul flash message `success`.

---

## Catatan Teknis
- Checklist / field seperti `checklist[]`, `good_condition`, `problem_found`, `condition` pada form awalnya tidak divalidasi dan tidak otomatis tersimpan karena migration + `$fillable` model tidak menampung semuanya.
- Perbaikan ini fokus pada penyebab utama “tidak masuk DB” yaitu **validasi `asset_id` dan `status` yang tidak dipenuhi oleh form**.

