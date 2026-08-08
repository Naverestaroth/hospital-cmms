# Prompt Troubleshoot (Task Baru): Corrective Maintenance Bugfix (Form Submit Tidak Masuk DB)

## Konteks
Problem yang ingin kamu troubleshoot mirip seperti kasus **Preventive**:
- Saat tombol **Submit/Save** ditekan, **data tidak masuk ke database**
- Biasanya penyebabnya: **mismatch antara field yang dikirim form** dan **field yang divalidasi oleh backend** (mis. `required`, `exists`, atau `status` wajib)

Dokumen ini berisi “prompt/langkah troubleshoot” yang sama pola dengan task sebelumnya, tapi untuk modul **Corrective**.

---

## Langkah 0 — Identifikasi bagian yang mungkin salah
1. Buka halaman form **correctives create**:
   - `resources/views/correctives/create.blade.php`
2. Cari action submit:
   - `action="{{ route('correctives.store') }}"` (atau sejenisnya)
3. Pastikan method = `POST` dan ada `@csrf`.

---

## Langkah 1 — Cek backend validasi yang membuat submit gagal
1. Buka controller backend:
   - `app/Http/Controllers/CorrectiveController.php`
2. Fokus ke method:
   - `store(Request $request)`
3. Catat semua field yang divalidasi dan bersifat wajib:
   - contoh pola yang perlu dicurigai:
     - `required`
     - `exists:table,id`
     - `in(...)` / `enum` berbasis database
     - field yang tidak ada di form tapi divalidasi required

> Output yang harus kamu tulis di catatan:
- Daftar field required versi backend: `field1, field2, field3...`
- Daftar field yang sebenarnya ada di form: `fieldA, fieldB, fieldC...`
- Temukan selisih (mismatch).

---

## Langkah 2 — Bandingkan field form vs validasi backend
1. Di `resources/views/correctives/create.blade.php`, buat daftar semua input yang terkirim:
   - `<input name="...">`
   - `<textarea name="...">`
   - `<select name="...">`
   - radio: `name="..."`
   - checkbox array: `name="something[]"`

2. Bandingkan dengan validasi `CorrectiveController@store`.

### Temuan mismatch yang paling umum (wajib dicari)
- Form tidak mengirim `asset_id` tapi backend mewajibkan `asset_id`
- Form tidak mengirim `status` tapi backend mewajibkan `status`
- Nama field beda (contoh form pakai `technician`, backend mengharapkan `employee`)
- Form pakai `condition` sedangkan backend mengharapkan `status`
- Field array tidak disimpan karena:
  - migration tidak punya kolom
  - model `$fillable` tidak ada field tersebut

---

## Langkah 3 — Tambahkan UX error (jika belum ada)
Kalau submit gagal validasi, pastikan error tampil ke user:
- Di blade create, tambahkan blok:
  - tampilkan `$errors->any()` dan daftar `$errors->all()`
- Dengan ini kamu bisa melihat field mana yang menyebabkan submit gagal.

---

## Langkah 4 — Terapkan Fix dengan 2 opsi (gunakan pola Opsi 1 Preventive)
Setelah mismatch ditemukan, pilih pendekatan:

### Opsi 1 (DIREKOMENDASIKAN)
**Samakan form dengan backend**
- Tambahkan field yang kurang di form (mis. `asset_id`, `status`, dll)
- Gunakan `old('field')` untuk textarea/input agar UX bagus saat validasi gagal

### Opsi 2
**Samakan backend dengan form**
- Ubah validasi menjadi `nullable` / hapus `required`
- Pastikan nilai default dibuat di backend jika tidak dikirim form
- Sinkronkan juga migration dan `$fillable`

---

## Langkah 5 — Verifikasi minimal (critical-path)
Lakukan verifikasi manual (atau automated kalau ada) dengan urutan:
1. Submit form dengan data valid → harus:
   - redirect ke `correctives.index`
   - flash success tampil
   - record masuk tabel `correctives`
2. Submit form dengan data invalid yang sengaja kosongkan field required → harus:
   - tidak insert
   - halaman kembali ke form
   - tampil alert/list validasi (error jelas)

---

## Checklist Dokumentasi Bugfix (mirip PREVENTIVE_BUGFIX.md)
Untuk task baru, buat dokumen di:
- `docs/CORRECTIVE_BUGFIX.md`

Isinya minimal:
1. Ringkasan masalah
2. Penyebab (mismatch form vs validasi backend)
3. Dampak
4. Perubahan yang dilakukan (Opsi 1/Opsi 2)
5. Langkah verifikasi yang disarankan

---

## Rubrik “Selesai” untuk troubleshooting ini
Kamu bisa bilang “fixed” kalau:
- Submit corrective yang valid benar-benar insert DB
- Redirect/UX benar
- Saat validasi gagal, error tampil jelas dan tidak silent
