# Database Optimization Guide

## Recommended Database Indexes

Untuk meningkatkan performa query, tambahkan index berikut pada tabel database:

### Users Table
```sql
-- Index untuk search
ALTER TABLE users ADD INDEX idx_users_search (name, username, email);
ALTER TABLE users ADD INDEX idx_users_active (is_active);
ALTER TABLE users ADD INDEX idx_users_updated (updated_at);
```

### Vendors Table
```sql
-- Index untuk search
ALTER TABLE vendors ADD INDEX idx_vendors_search (name, alias, email);
ALTER TABLE vendors ADD INDEX idx_vendors_updated (updated_at);
ALTER TABLE vendors ADD INDEX idx_vendors_user (user_id);
```

### Assets Table
```sql
-- Index untuk search
ALTER TABLE assets ADD INDEX idx_assets_search (brand, model, asset_type);
ALTER TABLE assets ADD INDEX idx_assets_assignee (assigned_to);
ALTER TABLE assets ADD INDEX idx_assets_updated (updated_at);
ALTER TABLE assets ADD INDEX idx_assets_status (status);
```

### Branches Table
```sql
ALTER TABLE branches ADD INDEX idx_branches_user (user_id);
ALTER TABLE branches ADD INDEX idx_branches_updated (updated_at);
```

### Vendor Banks Table
```sql
ALTER TABLE vendor_banks ADD INDEX idx_vendor_banks_vendor (vendor_id);
ALTER TABLE vendor_banks ADD INDEX idx_vendor_banks_bank (bank_id);
```

### Persons In Charge Table
```sql
ALTER TABLE persons_in_charges ADD INDEX idx_pics_vendor (vendor_id);
```

## Query Optimization Tips

1. **Eager Loading**: Sudah diimplementasikan di BaseTableComponent
   - Users: eager load `roles` dan `permissions`
   - Vendors: eager load `user`, `pics`, dan `banks`
   - Assets: eager load `assignee`

2. **Pagination**: Gunakan `perPage` yang wajar (10-50 records)

3. **Caching**: 
   - Role list di-cache selama 10 menit
   - Pertimbangkan cache untuk data yang jarang berubah

4. **Select Specific Columns**: Hindari `SELECT *`, pilih kolom yang diperlukan saja

## Performance Monitoring

Gunakan Laravel Debugbar untuk monitoring:
```bash
composer require barryvdh/laravel-debugbar --dev
```

Query logging untuk development:
```php
// Di AppServiceProvider
DB::listen(function($query) {
    if ($query->time > 100) { // Query lebih dari 100ms
        Log::warning('Slow query detected', [
            'sql' => $query->sql,
            'time' => $query->time
        ]);
    }
});
```
