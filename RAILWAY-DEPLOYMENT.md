# 🚀 DEPLOYMENT COCO FARMA KE RAILWAY (GRATIS & INSTANT)

## 📋 **KEUNGGULAN RAILWAY**
- ✅ **Langsung aktif** tanpa verifikasi
- ✅ Auto-deploy dari GitHub
- ✅ Database PostgreSQL gratis ($5 credits)
- ✅ Modern cloud infrastructure
- ✅ Sleep setelah 30 menit tidak aktif (bangun otomatis)

## 📝 **LANGKAH-LANGKAH DEPLOYMENT**

### 1. **PUSH CODE KE GITHUB**
```bash
# Pastikan code sudah di-commit
git add .
git commit -m "Ready for deployment"
git push origin main
```

### 2. **DAFTAR RAILWAY**
1. Kunjungi: https://railway.app/
2. Login dengan GitHub account
3. Klik **Create Project**

### 3. **CONNECT GITHUB REPOSITORY**
1. Pilih **Deploy from GitHub repo**
2. Search dan pilih repository `cocofarma`
3. Klik **Deploy**

### 4. **SETUP DATABASE**
1. Di project dashboard → **Add Plugin**
2. Pilih **PostgreSQL**
3. Klik **Add PostgreSQL**
4. Tunggu database siap (1-2 menit)

### 5. **KONFIGURASI ENVIRONMENT**
1. Di project dashboard → **Variables**
2. Railway akan otomatis detect `.env.railway`
3. Atau tambahkan manual:
   ```
   APP_ENV=production
   APP_KEY=base64:PUrrFYSGQ1ZZQuSeeQqmn323pFJlr8zkgoipJDXvCl4=
   APP_DEBUG=false
   ```

### 6. **RUN MIGRATIONS**
1. Di project dashboard → **Deployments**
2. Klik **View Logs** pada deployment terbaru
3. Jika perlu manual migration, klik **Terminal**
4. Jalankan: `php artisan migrate --force`

### 7. **TEST APLIKASI**
1. Di project dashboard → **Settings**
2. Copy **Public URL** (contoh: `https://cocofarma-production.up.railway.app`)
3. Kunjungi URL tersebut
4. Login dengan akun admin

## 🔧 **FILE KHUSUS RAILWAY**
- `railway.json` - Konfigurasi build dan deploy
- `.env.railway` - Environment variables

## 📊 **MONITORING**
- **Logs**: View real-time logs di dashboard
- **Metrics**: CPU, Memory, Network usage
- **Database**: Akses via Railway dashboard

## 💰 **COST MANAGEMENT**
- Free tier: $5 credits/bulan
- Sleep mode: Otomatis setelah 30 menit idle
- Upgrade anytime jika perlu

## 🆘 **TROUBLESHOOTING**
- **Build Error**: Cek logs di Deployments tab
- **Migration Error**: Jalankan manual di Terminal
- **Database Error**: Pastikan PostgreSQL plugin aktif

---
**⚡ APLIKASI ONLINE DALAM 5 MENIT DARI GITHUB PUSH!**