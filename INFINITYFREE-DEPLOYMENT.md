# 🚀 DEPLOYMENT COCO FARMA KE INFINITYFREE (GRATIS)

## 📋 **SYARAT & KETENTUAN**
- Akun InfinityFree gratis
- Domain gratis (.epizy.com)
- MySQL Database gratis
- PHP 8.1+ support

## 📝 **LANGKAH-LANGKAH DEPLOYMENT**

### 1. **DAFTAR INFINITYFREE**
1. Kunjungi: https://infinityfree.net/
2. Klik **Sign Up** → Buat akun gratis
3. Verifikasi email Anda

### 2. **BUAT WEBSITE BARU**
1. Login ke **Control Panel**
2. Klik **Create Account** (menu kiri)
3. Pilih domain gratis (contoh: `cocofarma.epizy.com`)
4. Tunggu 5-10 menit sampai aktif

### 3. **UPLOAD FILES**
1. Di Control Panel → **File Manager**
2. Upload file: `cocofarma-deployment-infinityfree-20250929.zip`
3. **Extract** file zip tersebut
4. **Pindahkan** semua isi ke folder `htdocs/`

### 4. **BUAT DATABASE**
1. Di Control Panel → **MySQL Databases**
2. Klik **Create Database**
3. Isi nama database (contoh: `cocofarma_db`)
4. **Catat** informasi database:
   - Database Name
   - Username
   - Password
   - Host (biasanya `sqlXXX.epizy.com`)

### 5. **KONFIGURASI APLIKASI**
1. Di File Manager → Rename `.env.infinityfree` → `.env`
2. Klik Edit pada file `.env`
3. Update konfigurasi berikut:
   ```env
   APP_URL=https://cocofarma.epizy.com
   DB_HOST=sqlXXX.epizy.com          # Ganti dengan host database Anda
   DB_DATABASE=cocofarma_db           # Ganti dengan nama database Anda
   DB_USERNAME=epiz_XXXXXX            # Ganti dengan username database Anda
   DB_PASSWORD=your_password          # Ganti dengan password database Anda
   ```

### 6. **JALANKAN DEPLOYMENT**
1. Di File Manager → Klik kanan `deploy-infinityfree.sh`
2. Pilih **Run** atau **Execute**
3. Tunggu proses selesai

### 7. **TEST APLIKASI**
1. Kunjungi: `https://cocofarma.epizy.com`
2. Login dengan akun admin default:
   - Email: admin@cocofarma.com
   - Password: password

## 🔧 **FITUR YANG SUDAH TERSEDIA**
- ✅ Dashboard dengan Goals/Targets
- ✅ Progress indicators visual
- ✅ User categories
- ✅ Alternating row colors
- ✅ Numbered ordering
- ✅ Compact layout
- ✅ Dark text untuk readability

## 🆘 **TROUBLESHOOTING**

### Error Database Connection
- Pastikan DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD benar
- Cek apakah database sudah dibuat di MySQL Databases

### Error 500 Internal Server Error
- Cek file `.env` sudah rename dan dikonfigurasi
- Pastikan permissions sudah benar (755 untuk folders)

### Migration Error
- Jalankan ulang script deployment
- Atau jalankan manual: `php artisan migrate --force`

## 📞 **DUKUNGAN**
Jika ada masalah, cek:
1. **Error logs**: `storage/logs/laravel.log`
2. **PHP version**: Pastikan 8.1+
3. **Database connection**: Test dengan phpMyAdmin

---
**🎉 SELAMAT! APLIKASI ANDA SUDAH ONLINE GRATIS**