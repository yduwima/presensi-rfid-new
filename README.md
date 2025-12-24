# Presensi RFID - Aplikasi Absensi Siswa dan Guru Berbasis RFID

Aplikasi absensi siswa dan guru menggunakan kartu RFID dengan framework CodeIgniter 3 dan Tailwind CSS.

## Fitur Utama

### Halaman Admin
- Dashboard dengan statistik siswa, guru, dan absensi hari ini
- Pengaturan Sekolah (nama, alamat, kepala sekolah, logo, dll)
- Pengaturan Hari Kerja dan Hari Libur
- Data Master (Tahun Ajaran, Semester, Kelas, Siswa, Guru)
- Fitur Naik Kelas
- Import/Export Excel untuk data siswa dan guru
- Mata Pelajaran dan Jadwal Pelajaran
- Pengaturan Notifikasi WhatsApp
- Laporan Absensi (PDF/Excel dengan kop sekolah)
- Rekap Jurnal Guru

### Halaman Guru
- Dashboard dengan jadwal dan jurnal hari ini
- Isi Jurnal dengan input absensi siswa (H/S/I/A)
- Jadwal Mengajar
- Laporan Kinerja
- Rekap Jurnal
- Profile

### Halaman Guru Wali Kelas
- Semua menu Guru
- Input Sakit/Izin siswa

### Halaman Guru Piket
- Semua menu Guru
- Input Izin Siswa KBM (masuk/keluar tengah KBM)
- Rekap Piket

### Halaman BK
- Dashboard BK
- Monitoring siswa (alpha 3x, terlambat 5x dalam sebulan)
- Cetak Surat Panggilan
- Profile

### Halaman Absensi RFID
- Interface tanpa login untuk scan kartu RFID
- Tampilan real-time data absensi
- Notifikasi WhatsApp dengan queue system

## Teknologi

- **Framework**: CodeIgniter 3.1.13
- **CSS Framework**: Tailwind CSS
- **Database**: MySQL/MariaDB
- **Libraries**:
  - PHPSpreadsheet (Import/Export Excel)
  - TCPDF/DOMPDF (Generate PDF)
  - WhatsApp API Integration (Fonnte)

## Instalasi

### Persyaratan Sistem
- PHP >= 7.2
- MySQL >= 5.7 atau MariaDB
- Apache/Nginx web server
- Composer (optional, untuk dependencies)

### Langkah Instalasi

1. Clone repository ini:
```bash
git clone https://github.com/yduwima/presensi-rfid-new.git
cd presensi-rfid-new
```

2. Import database:
```bash
mysql -u root -p
CREATE DATABASE presensi_rfid;
exit;
mysql -u root -p presensi_rfid < database/presensi_rfid.sql
```

3. Konfigurasi database di `application/config/database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',
'password' => 'your_password',
'database' => 'presensi_rfid',
```

4. Konfigurasi base URL di `application/config/config.php`:
```php
$config['base_url'] = 'http://localhost/presensi-rfid-new/';
```

5. Buat folder uploads dan set permission:
```bash
chmod 777 assets/uploads
```

6. Akses aplikasi melalui browser:
```
http://localhost/presensi-rfid-new/
```

## Login Default

- **Username**: admin
- **Password**: admin

> **Penting**: Segera ganti password default setelah login pertama kali!

## Struktur Folder

```
application/
├── config/          # Konfigurasi aplikasi
├── controllers/     # Controller untuk routing
│   ├── admin/      # Controller admin
│   ├── guru/       # Controller guru
│   ├── bk/         # Controller BK
│   ├── Auth.php    # Controller authentication
│   └── Absensi.php # Controller absensi RFID
├── models/         # Model untuk database
├── views/          # View templates
│   ├── templates/  # Template layout
│   ├── admin/     # Views admin
│   ├── guru/      # Views guru
│   ├── bk/        # Views BK
│   └── absensi/   # Views absensi RFID
├── libraries/      # Custom libraries
│   ├── Whatsapp.php      # WhatsApp integration
│   ├── Excel_import.php  # Excel import
│   └── Pdf_generator.php # PDF generator
└── helpers/        # Custom helpers

assets/
├── css/            # CSS files (Tailwind)
├── js/             # JavaScript files
├── img/            # Images
└── uploads/        # Upload directory

database/
└── presensi_rfid.sql  # Database schema
```

## Fitur Database

### Tabel Utama
- `users` - Data pengguna sistem
- `settings` - Pengaturan sekolah
- `tahun_ajaran`, `semester`, `kelas` - Data akademik
- `siswa`, `guru` - Data master
- `absensi_harian` - Absensi RFID harian
- `mata_pelajaran`, `jadwal_pelajaran` - Data pembelajaran
- `jurnal`, `absensi_mapel` - Jurnal dan absensi per mata pelajaran
- `wa_settings`, `wa_templates`, `wa_queue` - WhatsApp notification
- `izin_siswa` - Data izin siswa
- `monitoring_bk`, `surat_bk` - Data BK

## Integrasi RFID

Aplikasi ini menggunakan RFID reader untuk mencatat absensi. Pastikan RFID reader Anda mendukung format data yang sesuai.

## Notifikasi WhatsApp

Sistem menggunakan queue untuk mengirim notifikasi WhatsApp agar tidak menghambat proses tapping RFID. Konfigurasi API WhatsApp dapat dilakukan melalui menu Admin > Pengaturan WA Notif.

## Cron Job

Untuk notifikasi otomatis siswa yang belum absen sampai jam 09:00, setup cron job:
```bash
0 9 * * * curl http://localhost/presensi-rfid-new/cron/send_reminder
```

## Support

Untuk bantuan dan pertanyaan, silakan buat issue di repository ini.

## License

MIT License

## Author

Developed for educational purposes.

