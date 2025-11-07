# PDF Auto-Delete System

## Overview
Sistem otomatis menghapus file PDF dari storage setelah berhasil dikonversi menjadi image (PNG).

## How It Works

### 1. **Upload Flow**
```
User Upload PDF → Store to storage/app/public/branches/ → Dispatch ConvertPdfToJpgJob
```

### 2. **Conversion Flow**
```
ConvertPdfToJpgJob → Convert PDF to PNG images → Save images to storage
                   ↓
              Update database with image paths
                   ↓
              Delete original PDF file (Auto)
```

### 3. **Safety Features**

#### Security Checks:
- ✅ **File Existence Check**: Verifies file exists before deletion
- ✅ **Extension Check**: Only deletes `.pdf` files
- ✅ **Directory Check**: Only deletes files in `branches/` directory
- ✅ **Conversion Status**: Only deletes if conversion status = 'selesai'

#### Error Handling:
- ❌ **Conversion Failed**: PDF is NOT deleted (for debugging/retry)
- ❌ **File Not Found**: Logs warning, continues execution
- ❌ **Invalid Extension**: Skips deletion, logs warning
- ❌ **Outside Directory**: Logs security error, blocks deletion

## Implementation

### Files Modified:

#### 1. `app/Jobs/ConvertPdfToJpgJob.php`
```php
// After successful conversion
PdfCleanupService::deletePdfAfterConversion($this->branch->file_path);
```

#### 2. `app/Services/PdfCleanupService.php` (NEW)
Service class with safety checks and logging:
- `deletePdfAfterConversion()` - Delete PDF with validation
- `getFileSizeFormatted()` - Get human-readable file size

## Logging

### Success Log:
```php
[2025-11-07 10:30:45] local.INFO: ✅ PDF successfully deleted after conversion
{
    "file_path": "branches/document.pdf",
    "full_path": "/storage/app/public/branches/document.pdf",
    "deleted_at": "2025-11-07 10:30:45"
}
```

### Conversion Log:
```php
[2025-11-07 10:30:44] local.INFO: PDF conversion completed
{
    "branch_id": 5,
    "file_name": "document.pdf",
    "file_size": "2.5 MB",
    "pages_converted": 10
}
```

### Error Logs:
```php
// File not found
[2025-11-07 10:30:45] local.WARNING: PDF file not found for deletion: /path/to/file.pdf

// Not a PDF file
[2025-11-07 10:30:45] local.WARNING: File is not a PDF, skipping deletion: /path/to/file.jpg

// Security violation
[2025-11-07 10:30:45] local.ERROR: Security: Attempted to delete file outside branches directory
```

## Benefits

### 1. **Storage Optimization**
- Automatically frees up disk space
- No manual cleanup needed
- Reduces storage costs

### 2. **Clean Storage Structure**
```
storage/app/public/branches/
├── document_page_1.png  ✅ (Kept)
├── document_page_2.png  ✅ (Kept)
└── document.pdf         ❌ (Auto-deleted after conversion)
```

### 3. **Safety & Reliability**
- Multiple validation layers
- Comprehensive logging
- No accidental deletions
- Failed conversions preserve PDF

## Configuration

No configuration needed! The system works automatically.

### Optional: Disable Auto-Delete

If you want to keep PDFs after conversion, comment out this line in `ConvertPdfToJpgJob.php`:

```php
// PdfCleanupService::deletePdfAfterConversion($this->branch->file_path);
```

## Testing

### Test Successful Conversion:
1. Upload a PDF file through Branches page
2. Wait for queue job to process
3. Check logs: `storage/logs/laravel.log`
4. Verify PDF is deleted: `storage/app/public/branches/`
5. Verify images exist: `storage/app/public/branches/*.png`

### Test Failed Conversion:
1. Upload an invalid/corrupted PDF
2. Conversion will fail
3. Check logs for error message
4. Verify PDF is NOT deleted (preserved for debugging)

## Storage Savings Example

| Before Auto-Delete | After Auto-Delete | Savings |
|-------------------|-------------------|---------|
| 100 PDFs (500 MB) | 1000 Images (300 MB) | 200 MB (40%) |
| Manual cleanup needed | Automatic cleanup | 100% automated |

## Monitoring

### Check Logs:
```bash
# View recent deletions
tail -f storage/logs/laravel.log | grep "PDF successfully deleted"

# View conversion stats
tail -f storage/logs/laravel.log | grep "PDF conversion completed"
```

### Check Storage:
```bash
# List files in branches directory
ls -lh storage/app/public/branches/

# Count PDF files remaining
ls storage/app/public/branches/*.pdf | wc -l
```

## Troubleshooting

### PDF Not Deleted?

**Check conversion status:**
```php
Branch::find($id)->conversion_status; // Should be 'selesai'
```

**Check logs:**
```bash
grep "PDF successfully deleted" storage/logs/laravel.log
```

**Check file permissions:**
```bash
ls -la storage/app/public/branches/
```

### Images Not Generated?

Check Imagick installation:
```bash
php -m | grep imagick
```

Check queue is running:
```bash
php artisan queue:work
```

## Best Practices

1. ✅ **Always run queue worker** for background jobs
2. ✅ **Monitor logs** regularly for errors
3. ✅ **Backup important PDFs** before upload (if needed)
4. ✅ **Test with small PDFs** first
5. ✅ **Check storage space** periodically

## Security Notes

- Only deletes files in `branches/` directory
- Validates file extension before deletion
- Logs all deletion attempts
- Preserves PDFs if conversion fails
- No user can trigger manual deletion (system-only)

---

**Status**: ✅ Active and Working
**Last Updated**: 2025-11-07
**Version**: 1.0
