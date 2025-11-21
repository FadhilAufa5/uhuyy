# Activity Log System Guide

## Overview
Sistem Activity Log mencatat semua kegiatan yang dilakukan oleh user dan admin dalam aplikasi. Logs dapat dilihat oleh Admin dan SuperAdmin saja.

## Fitur

### 1. **Automatic Logging** 
Otomatis mencatat aktivitas:
- ✅ Login
- ✅ Logout  
- ✅ Create data (otomatis via trait)
- ✅ Update data (otomatis via trait)
- ✅ Delete data (otomatis via trait)
- ✅ Upload file (Branch documents)

### 2. **Detail Informasi yang Dicatat**
- User yang melakukan aksi
- Jenis event (created, updated, deleted, logged_in, logged_out)
- Model yang diubah
- Deskripsi aktivitas
- Properties (old & new values)
- IP Address
- User Agent (browser/device info)
- Timestamp

### 3. **Filter & Search**
- Search by description, user name, or model
- Filter by event type
- Filter by user
- Pagination (10, 25, 50, 100 per page)

## Cara Menggunakan

### 1. Mengakses Activity Logs
- Login sebagai **Admin** atau **SuperAdmin**
- Klik menu **"Activity Logs"** di sidebar
- View semua aktivitas dalam tabel

### 2. Melihat Detail Log
- Klik button **"View"** pada kolom Details
- Modal akan muncul dengan informasi lengkap:
  - User info
  - Event type
  - IP Address & timestamp
  - Properties (JSON format)
  - User Agent

### 3. Filter Logs
- **Search**: Ketik di search box untuk mencari description, user, atau model
- **Event Filter**: Pilih jenis event (created, updated, deleted, dll)
- **User Filter**: Pilih user tertentu
- **Clear Filters**: Klik "Clear All" untuk reset semua filter

## Untuk Developer

### Menambahkan Logging ke Model

Tambahkan trait `LogsActivity` ke model yang ingin di-log:

```php
<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model
{
    use LogsActivity;
    
    // ... rest of your model
}
```

**Models yang sudah menggunakan LogsActivity:**
- ✅ `Branch` - Otomatis log created, updated, deleted
- ✅ Upload branch document - Custom log saat upload file PDF

**Untuk menambahkan ke model lain:**
Cukup tambahkan `use LogsActivity;` di dalam model class.

### Custom Activity Logging

Untuk mencatat aktivitas kustom:

```php
use App\Traits\LogsActivity;

// Contoh: Log custom action
LogsActivity::logCustomActivity(
    'custom_event',
    'User melakukan aksi spesial',
    ['key' => 'value'] // Optional properties
);
```

### Contoh Penggunaan di Controller/Livewire

```php
public function approveDocument($documentId)
{
    $document = Document::find($documentId);
    $document->status = 'approved';
    $document->save();
    
    // Log custom activity
    LogsActivity::logCustomActivity(
        'document_approved',
        'Document #' . $documentId . ' telah disetujui',
        [
            'document_id' => $documentId,
            'document_name' => $document->name,
            'approved_by' => auth()->user()->name
        ]
    );
}
```

## Event Types & Badge Colors

| Event | Color | Icon | Description |
|-------|-------|------|-------------|
| `created` | Green | plus-circle | Data baru dibuat |
| `updated` | Blue | pencil | Data diupdate |
| `deleted` | Red | trash | Data dihapus |
| `logged_in` | Lime | arrow-right | User login |
| `logged_out` | Gray | arrow-left | User logout |
| `restored` | Purple | arrow-path | Data direstore |
| `uploaded` | Sky | arrow-up-tray | File diupload |
| `custom` | Zinc | information-circle | Custom event |

## Database Schema

Table: `activity_logs`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_id | bigint | Foreign key to users |
| user_name | string | Backup user name |
| event | string | Event type |
| model_type | string | Model class name |
| model_id | bigint | Model ID |
| description | string | Human readable description |
| properties | json | Old/new values |
| ip_address | string | IP address |
| user_agent | text | Browser/device info |
| created_at | timestamp | When logged |
| updated_at | timestamp | Last update |

## Permissions

**Access Control:**
- ✅ Admin: Full access
- ✅ SuperAdmin: Full access  
- ❌ User: No access
- ❌ Vendor: No access

## Best Practices

### 1. **Jangan Over-Log**
Hindari logging untuk operasi yang terlalu frequent (misal: view/read operations)

### 2. **Sensitive Data**
Jangan log data sensitif seperti:
- Password
- Credit card numbers
- Personal identification numbers

### 3. **Clean Old Logs**
Setup scheduler untuk clean logs lama (optional):

```php
// Di app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Delete logs older than 90 days
    $schedule->call(function () {
        \App\Models\ActivityLog::where('created_at', '<', now()->subDays(90))->delete();
    })->daily();
}
```

### 4. **Indexing**
Table sudah di-index untuk:
- `user_id` + `created_at`
- `model_type` + `model_id`
- `event`

## Troubleshooting

### Log tidak muncul?
1. Check apakah trait `LogsActivity` sudah ditambahkan ke model
2. Check apakah user sudah login (`Auth::check()`)
3. Check permission - hanya Admin/SuperAdmin yang bisa lihat

### Performance issue?
1. Cleanup old logs secara berkala
2. Gunakan pagination dengan per-page yang reasonable
3. Add more indexes jika needed

### Properties tidak tersimpan?
Pastikan column `properties` di database menggunakan type `json`

## UI Components

### Table Columns
- \# (Index)
- User (dengan avatar)
- Event (dengan badge warna)
- Model (nama & ID)
- Description
- IP Address
- Time (relative & absolute)
- Details (button untuk view modal)

### Modal Details
 

## Security Notes

1. **Access Control**: Hanya Admin/SuperAdmin
2. **Data Protection**: User info di-backup (`user_name`) jika user dihapus
3. **Audit Trail**: Tidak bisa diedit/dihapus melalui UI
4. **IP Tracking**: Semua akses dicatat dengan IP address

## Future Enhancements (Optional)

- Export logs to CSV/Excel
- Real-time notifications untuk critical events
- Advanced analytics dashboard
- Log retention policies UI
- Bulk delete old logs
- Filter by date range
- Chart visualization

## Support

Jika ada issues atau questions, check:
1. Documentation ini
2. Code comments di `app/Traits/LogsActivity.php`
3. Example usage di `app/Models/ActivityLog.php`
