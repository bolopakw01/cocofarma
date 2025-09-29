# 🚀 DEPLOYMENT COCO FARMA KE 000WEBHOST (GRATIS & CEPAT)

## 📋 **KEUNGGULAN 000WEBHOST**
- ✅ **Langsung aktif** (5-10 menit)
- ✅ PHP 8.1+ & MySQL gratis
- ✅ Unlimited bandwidth
- ✅ Domain .000webhostapp.com

## 📝 **LANGKAH-LANGKAH DEPLOYMENT**

### 1. **DAFTAR 000WEBHOST**
1. Kunjungi: https://www.000webhost.com/
2. Klik **Sign Up** → Buat akun gratis
3. Verifikasi email (langsung aktif)

### 2. **BUAT WEBSITE BARU**
1. Login ke **Control Panel**
2. Klik **Create New Site**
3. Pilih domain gratis (contoh: `cocofarma.000webhostapp.com`)

### 3. **UPLOAD FILES**
1. Di Control Panel → **File Manager**
2. Upload file: `cocofarma-deployment-infinityfree-20250929.zip`
3. **Extract** file zip tersebut
4. **Pindahkan** semua isi ke folder `public_html/`

### 4. **BUAT DATABASE**
1. Di Control Panel → **Database Manager**
2. Klik **Create Database**
3. Isi detail:
   - Database Name: `cocofarma` (akan jadi `idXXXXXXX_cocofarma`)
   - Username: auto-generated
   - Password: isi password Anda
4. **Catat** informasi database

### 5. **KONFIGURASI APLIKASI**
1. Di File Manager → Rename `.env.000webhost` → `.env`
2. Klik Edit pada file `.env`
3. Update konfigurasi:
   ```env
   APP_URL=https://cocofarma.000webhostapp.com
   DB_DATABASE=idXXXXXXX_cocofarma    # Ganti dengan nama database Anda
   DB_USERNAME=idXXXXXXX_cocofarma    # Ganti dengan username database Anda
   DB_PASSWORD=your_actual_password   # Ganti dengan password database Anda
   ```

### 6. **JALANKAN DEPLOYMENT**
1. Di File Manager → Klik kanan `deploy-infinityfree.sh`
2. Pilih **Run** atau **Execute**
3. Tunggu proses selesai

### 7. **TEST APLIKASI**
1. Kunjungi: `https://cocofarma.000webhostapp.com`
2. Login admin default

## 🔧 **CATATAN PENTING**
- Database host selalu `localhost`
- Nama database akan ada prefix `idXXXXXXX_`
- Jika error, cek **Error Logs** di control panel

---
**⚡ APLIKASI ONLINE DALAM 10 MENIT!**