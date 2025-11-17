# Testing Activity Log System

## 🧪 Cara Test Activity Log

### 1. **Test Login Activity**

**Steps:**
1. Logout jika sudah login
2. Login menggunakan SuperAdmin account
3. Masuk ke menu **Activity Logs**
4. Lihat log terbaru - harus ada log dengan event `logged_in`

**Expected Result:**
```
User: [Your Name]
Event: logged_in (badge hijau/lime)
Description: [Your Name] logged in to the system
IP Address: [Your IP]
Time: Just now
```

---

### 2. **Test Logout Activity**

**Steps:**
1. Dari dashboard, klik logout
2. Login kembali sebagai SuperAdmin
3. Masuk ke menu **Activity Logs**
4. Lihat log - harus ada log dengan event `logged_out`

**Expected Result:**
```
User: [Your Name]
Event: logged_out (badge abu-abu/gray)
Description: [Your Name] logged out from the system
IP Address: [Your IP]
Time: Few seconds ago
```

---

### 3. **Test Upload Branch Document**

**Steps:**
1. Login sebagai SuperAdmin atau user yang punya akses
2. Masuk ke menu **Branches**
3. Klik button **"Upload File"**
4. Upload sebuah PDF file
5. Setelah berhasil, masuk ke **Activity Logs**
6. Lihat log terbaru

**Expected Result:**
```
User: [Your Name]
Event: uploaded (badge biru/sky)
Model: Branch #[ID]
Description: Uploaded branch document: [filename.pdf]
Properties: {
  "branch_id": 123,
  "file_name": "filename.pdf",
  "file_path": "branches/filename.pdf"
}
```

---

### 4. **Test Delete Branch**

**Steps:**
1. Masuk ke menu **Branches**
2. Hapus salah satu branch document
3. Confirm delete
4. Masuk ke **Activity Logs**
5. Lihat log terbaru

**Expected Result:**
```
User: [Your Name]
Event: deleted (badge merah)
Model: Branch #[ID]
Description: Deleted Branch
Properties: {
  "deleted": {
    "id": 123,
    "user_id": 1,
    "file_path": "branches/file.pdf",
    ...
  }
}
```

---

### 5. **Test Create User**

**Steps:**
1. Masuk ke menu **Users**
2. Klik **"Add New User"**
3. Isi form dan create user baru
4. Masuk ke **Activity Logs**
5. Lihat log terbaru

**Expected Result:**
```
User: [Your Name]
Event: created (badge hijau)
Model: User #[ID]
Description: Created User
Properties: {
  "created": {
    "id": 456,
    "name": "New User",
    "email": "user@example.com",
    ...
  }
}
```

---

### 6. **Test Update User**

**Steps:**
1. Masuk ke menu **Users**
2. Edit salah satu user
3. Ubah data (misal: name)
4. Save changes
5. Masuk ke **Activity Logs**
6. Klik **"View"** pada log untuk lihat detail

**Expected Result:**
```
User: [Your Name]
Event: updated (badge biru)
Model: User #[ID]
Description: Updated User
Properties: {
  "old": {
    "name": "Old Name",
    ...
  },
  "new": {
    "name": "New Name",
    ...
  }
}
```

---

### 7. **Test Filter by Event**

**Steps:**
1. Masuk ke **Activity Logs**
2. Klik dropdown **"All Events"**
3. Pilih `logged_in`
4. Harus muncul hanya log login saja

**Expected Result:**
- Table hanya menampilkan logs dengan event `logged_in`
- Filter badge muncul: "Filters active"

---

### 8. **Test Filter by User**

**Steps:**
1. Masuk ke **Activity Logs**
2. Klik dropdown **"All Users"**
3. Pilih user tertentu
4. Harus muncul hanya aktivitas user tersebut

**Expected Result:**
- Table hanya menampilkan logs dari user yang dipilih
- Filter badge muncul: "Filters active"

---

### 9. **Test Search**

**Steps:**
1. Masuk ke **Activity Logs**
2. Ketik di search box: `"uploaded"` atau `"deleted"`
3. Table harus filter otomatis

**Expected Result:**
- Table menampilkan logs yang match dengan keyword
- Real-time filtering (dengan debounce)

---

### 10. **Test View Details Modal**

**Steps:**
1. Masuk ke **Activity Logs**
2. Klik button **"View"** pada salah satu log
3. Modal harus muncul dengan detail lengkap

**Expected Result:**
Modal menampilkan:
- User name & email
- Event type dengan badge
- IP Address
- Exact timestamp
- Properties dalam JSON format (dengan syntax highlighting)
- User Agent string

---

## ✅ Checklist Testing

Setelah test semua fitur, pastikan:

- [ ] Login activity tercatat
- [ ] Logout activity tercatat
- [ ] Upload branch document tercatat dengan properties lengkap
- [ ] Delete branch tercatat
- [ ] Create user tercatat
- [ ] Update user tercatat dengan old & new values
- [ ] Filter by event berfungsi
- [ ] Filter by user berfungsi
- [ ] Search berfungsi
- [ ] View details modal berfungsi
- [ ] Pagination berfungsi (10, 25, 50, 100 per page)
- [ ] Clear filters berfungsi
- [ ] Dark mode support berfungsi
- [ ] Table responsive di mobile
- [ ] Badge colors sesuai untuk setiap event
- [ ] IP address tercatat
- [ ] User agent tercatat

---

## 🐛 Troubleshooting

### Log tidak muncul?

**Check:**
1. Apakah user yang login adalah SuperAdmin?
2. Refresh halaman Activity Logs
3. Check database table `activity_logs` langsung:
   ```sql
   SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10;
   ```

### Branch delete/upload tidak tercatat?

**Check:**
1. Apakah model Branch sudah menggunakan trait `LogsActivity`?
   ```php
   // Di app/Models/Branch.php
   use App\Traits\LogsActivity;
   
   class Branch extends Model {
       use LogsActivity;
   }
   ```
2. Apakah user sudah login saat melakukan aksi?

### Properties tidak muncul di modal?

**Check:**
1. Apakah column `properties` di database menggunakan type `json`?
2. Check di browser console untuk errors

---

## 📊 Sample Data untuk Testing

Buat aktivitas berikut untuk test lengkap:

1. **Login** (2-3x dengan user berbeda)
2. **Upload** branch documents (3-5 files)
3. **Update** branch status
4. **Delete** salah satu branch
5. **Create** 2-3 users baru
6. **Update** user data
7. **Logout** dan login kembali

Setelah itu, check Activity Logs harus penuh dengan berbagai event types dengan badge warna yang berbeda-beda!

---

## 🎯 Expected Performance

- **Load time**: < 2 seconds
- **Search response**: < 500ms (dengan debounce)
- **Filter response**: Instant
- **Modal open**: < 200ms
- **Pagination**: < 1 second

---

## 📝 Notes

- Activity Logs **hanya untuk SuperAdmin**
- User biasa tidak bisa akses halaman ini
- Vendor tidak bisa akses halaman ini
- Logs tidak bisa diedit/dihapus melalui UI (by design)
- Semua aktivitas permanent tersimpan di database
