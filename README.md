# Sistem Absensi Menggunakan Wajah
<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/a76715c3-728e-46b3-bd03-c57295987134" />
<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/b9da2e1e-8c6a-41a3-a5f2-7770d8daef38" />

## 1. Gambaran Umum Proyek

Proyek ini merupakan **Sistem Absensi Digital berbasis web** yang dibangun menggunakan **Laravel** sebagai backend utama. Sistem ini dirancang untuk mendukung proses absensi siswa PKL secara modern dengan memanfaatkan **pengenalan wajah (Face Attendance)** sebagai metode utama pencatatan kehadiran.

Aplikasi terdiri dari dua fitur utama:

1. **Dashboard (Halaman Utama)** untuk menampilkan ringkasan data kehadiran.
2. **Halaman Absensi Wajah** yang digunakan untuk melakukan absensi melalui kamera.

---

## 2. Tujuan Sistem

* Mengotomatisasi proses absensi siswa PKL.
* Mengurangi kecurangan dalam absensi manual.
* Menyediakan data kehadiran yang akurat dan mudah dipantau.
* Mengintegrasikan teknologi pengenalan wajah ke dalam sistem absensi berbasis web.

---

## 3. Teknologi yang Digunakan

* **Backend** : Laravel
* **Frontend** : Blade Template, HTML, CSS, JavaScript
* **Database** : MySQL
* **Face Recognition** : Kamera Web + API Service Python (Face Detection & Recognition)
* **Web Server** : Nginx

---

## 4. Struktur Fitur Utama

### 4.1 Dashboard

Dashboard berfungsi sebagai pusat informasi kehadiran. Pada halaman ini ditampilkan ringkasan data absensi.

#### Informasi yang Ditampilkan:

* **Jumlah Siswa PKL**
  Menampilkan total siswa yang terdaftar dalam sistem.

* **Hadir**
  Jumlah siswa yang hadir pada hari berjalan.

* **Terlambat**
  Jumlah siswa yang melakukan absensi melewati batas waktu yang ditentukan.

* **Tidak Hadir**
  Jumlah siswa yang tidak melakukan absensi.

#### Data Tambahan:

* **Data Keterlambatan Bulanan**
  Menampilkan daftar siswa yang terlambat pada bulan tertentu (misalnya November).

* **Jumlah Hadir per Siswa**
  Daftar siswa beserta total kehadiran mereka dalam satu bulan.

* **Jumlah Terlambat per Siswa**
  Menampilkan akumulasi keterlambatan setiap siswa.

Dashboard ini memudahkan admin atau pembimbing PKL untuk melakukan monitoring kehadiran secara cepat dan efisien.

---

### 4.2 Halaman Absensi Dengan Wajah

Halaman ini digunakan ketika pengguna menekan tombol **"Absen"** pada dashboard.

#### Fungsi Utama:

* Mengaktifkan kamera pengguna.
* Mendeteksi wajah secara otomatis.
* Mengambil foto wajah untuk proses verifikasi.
* Mengirim data absensi ke server setelah wajah terverifikasi.

#### Alur Penggunaan:

1. Pengguna membuka halaman **Absensi Dengan Wajah**.
2. Pengguna menekan tombol **"Mulai Kamera"**.
3. Sistem mendeteksi jumlah wajah yang tertangkap kamera.
4. Jika wajah terdeteksi, sistem akan:

   * Mengaktifkan countdown otomatis (±5 detik), atau
   * Mengizinkan pengguna menekan tombol **"Ambil Foto"** secara manual.
5. Foto wajah dikirim ke backend untuk diproses.
6. Sistem mencatat status absensi (Hadir / Terlambat).

#### Panduan Penggunaan (Ditampilkan di Halaman):

* Pastikan wajah terlihat jelas di kamera.
* Gunakan pencahayaan yang cukup.
* Jaga jarak wajah dengan kamera sekitar **50–100 cm**.
* Foto akan diambil otomatis setelah wajah terdeteksi.
* Data absensi dikirim otomatis setelah foto berhasil diambil.

---

## 5. Alur Sistem Absensi

1. Pengguna login ke sistem.
2. Pengguna membuka **Dashboard**.
3. Pengguna menekan tombol **Absen**.
4. Sistem mengarahkan ke halaman **Absensi Dengan Wajah**.
5. Kamera diaktifkan dan wajah dideteksi.
6. Foto wajah diproses oleh sistem.
7. Data kehadiran disimpan ke database.
8. Dashboard diperbarui sesuai hasil absensi.

---

## 6. Keamanan dan Validasi

* Absensi hanya dapat dilakukan jika wajah terdeteksi.
* Data absensi dikaitkan dengan akun pengguna.
* Waktu absensi digunakan untuk menentukan status **Hadir** atau **Terlambat**.

---

**Dikembangkan sebagai proyek sistem informasi absensi berbasis web menggunakan Laravel.**
