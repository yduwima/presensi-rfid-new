# Installation Guide - Presensi RFID

## Panduan Instalasi Lengkap

### 1. Persiapan Server

Pastikan server Anda memiliki:
- PHP 7.2 atau lebih tinggi
- MySQL 5.7 atau MariaDB
- Apache dengan mod_rewrite enabled
- Extension PHP: mysqli, curl, gd, mbstring

### 2. Clone Repository

```bash
git clone https://github.com/yduwima/presensi-rfid-new.git
cd presensi-rfid-new
```

### 3. Database Setup

#### A. Buat Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE presensi_rfid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### B. Import Database Schema

```bash
mysql -u root -p presensi_rfid < database/presensi_rfid.sql
```

### 4. Konfigurasi Aplikasi

#### A. Database Configuration

Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',          // Sesuaikan dengan username MySQL Anda
    'password' => 'your_password', // Sesuaikan dengan password MySQL Anda
    'database' => 'presensi_rfid',
    // ... (lainnya sudah dikonfigurasi)
);
```

#### B. Base URL Configuration

Edit file `application/config/config.php`:

```php
// Untuk local development
$config['base_url'] = 'http://localhost/presensi-rfid-new/';

// Untuk production (ganti dengan domain Anda)
$config['base_url'] = 'https://yourdomain.com/';
```

#### C. Session Configuration (Optional)

Untuk keamanan lebih baik, edit `application/config/config.php`:

```php
$config['encryption_key'] = 'your-32-character-random-string-here';
```

Generate encryption key dengan:
```bash
php -r "echo bin2hex(random_bytes(16));"
```

### 5. Folder Permissions

Set permission untuk folder uploads:

```bash
chmod 755 assets/uploads
```

Atau jika masih error:
```bash
chmod 777 assets/uploads
```

### 6. Apache Configuration

#### A. Enable mod_rewrite

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### B. .htaccess

File `.htaccess` sudah disediakan di root folder. Pastikan AllowOverride diaktifkan di konfigurasi Apache.

Edit `/etc/apache2/sites-available/000-default.conf`:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Restart Apache:
```bash
sudo systemctl restart apache2
```

### 7. Akses Aplikasi

Buka browser dan akses:
```
http://localhost/presensi-rfid-new/
```

Atau sesuai dengan base URL yang Anda konfigurasi.

### 8. Login Default

```
Username: admin
Password: admin
```

**PENTING:** Segera ganti password default setelah login pertama kali!

## Konfigurasi WhatsApp Notification

### 1. Daftar Akun Fonnte (atau provider WA API lainnya)

- Kunjungi https://fonnte.com
- Daftar dan dapatkan API Key
- Hubungkan nomor WhatsApp Anda

### 2. Konfigurasi di Aplikasi

- Login sebagai admin
- Buka menu "Pengaturan WA Notif"
- Masukkan:
  - URL API: `https://api.fonnte.com/send`
  - API Key: (dari Fonnte dashboard)
  - Sender: Nomor WhatsApp yang terhubung
- Klik "Simpan"

### 3. Setup Cron Job untuk WhatsApp Queue

Tambahkan cron job untuk memproses antrian WhatsApp:

```bash
crontab -e
```

Tambahkan baris berikut:
```
*/5 * * * * curl http://localhost/presensi-rfid-new/cron/process_wa_queue
```

Untuk notifikasi reminder jam 09:00:
```
0 9 * * * curl http://localhost/presensi-rfid-new/cron/send_reminder
```

## Testing RFID Integration

### 1. Testing Tanpa Hardware RFID

Gunakan halaman absensi manual:
```
http://localhost/presensi-rfid-new/absensi
```

Masukkan RFID UID secara manual untuk testing.

### 2. Integrasi dengan Hardware RFID

Untuk mengintegrasikan dengan RFID reader fisik, Anda perlu:

1. RFID Reader yang support USB/Serial
2. Software untuk membaca RFID dan mengirim ke aplikasi
3. Konfigurasi reader untuk auto-submit ke input field

Contoh konfigurasi RFID reader:
- Set output mode: Keyboard emulation
- Set terminator: Enter key
- Format: UID only

## Troubleshooting

### 1. Error 404 - Page Not Found

- Pastikan mod_rewrite sudah enabled
- Cek file .htaccess ada di root folder
- Cek AllowOverride All di konfigurasi Apache

### 2. Database Connection Error

- Pastikan MySQL sudah running
- Cek username, password di config/database.php
- Pastikan database sudah dibuat dan diimport

### 3. Session Error

- Set encryption_key di config/config.php
- Pastikan folder session bisa ditulis (default: system/ci_sessions)

### 4. Upload Error

- Cek permission folder assets/uploads (chmod 755 atau 777)
- Cek PHP upload_max_filesize dan post_max_size di php.ini

### 5. WhatsApp Tidak Terkirim

- Cek API Key dan URL sudah benar
- Pastikan nomor WA sudah terhubung di Fonnte
- Cek log di tabel wa_queue untuk error

## Development vs Production

### Development

```php
// application/config/config.php
define('ENVIRONMENT', 'development');
```

### Production

```php
// application/config/config.php  
define('ENVIRONMENT', 'production');

// Juga edit index.php
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
```

## Security Checklist

- [ ] Ganti password default admin
- [ ] Set ENVIRONMENT ke 'production'
- [ ] Set encryption_key yang kuat
- [ ] Disable error display di production
- [ ] Backup database secara berkala
- [ ] Update PHP dan CodeIgniter ke versi terbaru
- [ ] Gunakan HTTPS di production

## Support

Jika mengalami kesulitan:
1. Baca dokumentasi di README.md
2. Cek file log di application/logs/
3. Buat issue di GitHub repository

## Next Steps

Setelah instalasi berhasil:
1. Tambahkan data tahun ajaran
2. Tambahkan data semester
3. Tambahkan data kelas
4. Import data siswa dan guru
5. Setup jadwal pelajaran
6. Konfigurasi RFID untuk siswa dan guru
7. Test absensi RFID
8. Konfigurasi notifikasi WhatsApp

Selamat menggunakan aplikasi Presensi RFID!
