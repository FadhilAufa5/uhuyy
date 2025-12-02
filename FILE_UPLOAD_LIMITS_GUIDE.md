# File Upload Limits Configuration Guide

## 📊 Current Upload Limits

### ✅ CONFIGURED LIMITS (After Optimization)

| Setting | Value | Description |
|---------|-------|-------------|
| **Laravel Validation** | **20 MB** | Maximum file size in validation rules |
| **PHP upload_max_filesize** | **20 MB** | Maximum upload file size |
| **PHP post_max_size** | **25 MB** | Maximum POST data size (must be ≥ upload_max_filesize) |
| **PHP max_execution_time** | **300 seconds** | Maximum script execution time (5 minutes) |
| **PHP max_input_time** | **300 seconds** | Maximum input parsing time (5 minutes) |
| **PHP memory_limit** | **512 MB** | Maximum memory allocation |

### 🎯 Supported File Types

Currently, only **PDF files** are allowed for upload:

```php
// File: app/Livewire/Forms/BranchForm.php
'file_path' => 'required|file|mimes:pdf|max:20480', // max 20MB
```

**Supported MIME types:**
- `application/pdf` (.pdf)

## 🔧 Configuration Files

### 1. Laravel Validation Rules

**File:** `app/Livewire/Forms/BranchForm.php`

```php
public function rules(): array
{
    return [
        'file_path' => 'required|file|mimes:pdf|max:20480', // max 20MB (in KB)
    ];
}
```

**To Change:**
```php
// For 10MB
'file_path' => 'required|file|mimes:pdf|max:10240',

// For 30MB
'file_path' => 'required|file|mimes:pdf|max:30720',

// For 50MB
'file_path' => 'required|file|mimes:pdf|max:51200',
```

**Note:** Value is in **kilobytes (KB)**, so 20MB = 20480 KB

### 2. Apache/PHP Configuration (.htaccess)

**File:** `public/.htaccess`

```apache
# PHP Upload Settings
<IfModule mod_php.c>
    php_value upload_max_filesize 20M
    php_value post_max_size 25M
    php_value max_execution_time 300
    php_value max_input_time 300
    php_value memory_limit 512M
</IfModule>
```

**Important:** `post_max_size` should be **slightly larger** than `upload_max_filesize` to account for form data overhead.

### 3. PHP-FPM Configuration (.user.ini)

**File:** `public/.user.ini`

```ini
; Alternative configuration for PHP-FPM servers
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M
```

**Note:** This file is used when `.htaccess` `php_value` directives don't work (common on PHP-FPM).

### 4. Server PHP Configuration (php.ini)

If you have access to `php.ini`, you can set limits there permanently:

**Find php.ini location:**
```bash
php --ini
```

**Edit php.ini:**
```ini
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300
max_input_time = 300
memory_limit = 512M
```

**Restart server after changes:**
```bash
# Apache
sudo systemctl restart apache2

# Nginx + PHP-FPM
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

## 📈 How to Increase Upload Limits

### For 50MB Files:

#### Step 1: Update Laravel Validation
```php
// app/Livewire/Forms/BranchForm.php
'file_path' => 'required|file|mimes:pdf|max:51200', // 50MB
```

#### Step 2: Update .htaccess
```apache
php_value upload_max_filesize 50M
php_value post_max_size 55M
php_value max_execution_time 600
```

#### Step 3: Update .user.ini
```ini
upload_max_filesize = 50M
post_max_size = 55M
max_execution_time = 600
```

#### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

#### Step 5: Restart Server
```bash
# Restart web server (if applicable)
sudo systemctl restart apache2  # or nginx
```

### For 100MB Files:

```php
// Laravel
'file_path' => 'required|file|mimes:pdf|max:102400', // 100MB

// .htaccess & .user.ini
upload_max_filesize = 100M
post_max_size = 110M
max_execution_time = 900
memory_limit = 1024M
```

## ⚠️ Important Considerations

### 1. **Server Resources**

Large file uploads require adequate server resources:

| File Size | Recommended Memory | Recommended Timeout |
|-----------|-------------------|---------------------|
| 10 MB | 256 MB | 120 seconds |
| 20 MB | 512 MB | 300 seconds |
| 50 MB | 1024 MB (1 GB) | 600 seconds |
| 100 MB | 2048 MB (2 GB) | 900 seconds |

### 2. **Network Speed**

Upload time depends on network speed:

| Speed | 20MB Upload Time | 50MB Upload Time |
|-------|------------------|------------------|
| 1 Mbps | ~3 minutes | ~7 minutes |
| 5 Mbps | ~30 seconds | ~80 seconds |
| 10 Mbps | ~15 seconds | ~40 seconds |
| 50 Mbps | ~3 seconds | ~8 seconds |

### 3. **Processing Time**

PDF conversion to images takes time:

| Pages | Processing Time |
|-------|----------------|
| 1-10 pages | 5-15 seconds |
| 10-50 pages | 15-60 seconds |
| 50-100 pages | 1-3 minutes |
| 100+ pages | 3+ minutes |

**Recommendation:** Keep files under **50MB** and **100 pages** for optimal performance.

### 4. **Database Storage**

Images are stored in database as base64:

| PDF Pages | Approx. DB Size |
|-----------|----------------|
| 10 pages | ~5-10 MB |
| 50 pages | ~25-50 MB |
| 100 pages | ~50-100 MB |

**Consider:** For very large PDFs (>100 pages), database size can grow quickly.

## 🔍 Troubleshooting

### Issue: Upload fails with "413 Request Entity Too Large"

**Cause:** Web server (Nginx/Apache) limit

**Solution for Nginx:**
```nginx
# /etc/nginx/nginx.conf or site config
client_max_body_size 50M;
```

**Solution for Apache:**
```apache
# .htaccess (already configured)
# Or in Apache config
LimitRequestBody 52428800  # 50MB in bytes
```

**Restart server:**
```bash
sudo systemctl restart nginx  # or apache2
```

### Issue: Upload fails with "Maximum execution time exceeded"

**Cause:** Script timeout

**Solution:**
```php
// Increase in .htaccess or .user.ini
max_execution_time = 600  # 10 minutes
max_input_time = 600
```

**Or in code (temporary):**
```php
set_time_limit(600); // 10 minutes
```

### Issue: Upload fails with "Allowed memory size exhausted"

**Cause:** Not enough memory

**Solution:**
```php
// Increase in .htaccess or .user.ini
memory_limit = 1024M  # 1GB
```

**Or in code (temporary):**
```php
ini_set('memory_limit', '1024M');
```

### Issue: Upload succeeds but validation fails

**Cause:** Laravel validation limit is lower than PHP limit

**Solution:**
```php
// Make sure Laravel max is ≤ PHP upload_max_filesize
// app/Livewire/Forms/BranchForm.php
'file_path' => 'required|file|mimes:pdf|max:20480', // Must match or be lower
```

### Issue: Changes don't take effect

**Solution:**
1. Clear Laravel cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

2. Restart web server:
   ```bash
   sudo systemctl restart apache2  # or nginx
   sudo systemctl restart php-fpm  # if using PHP-FPM
   ```

3. Clear browser cache (Ctrl + F5)

4. Verify PHP settings:
   ```bash
   php -i | grep upload_max_filesize
   php -i | grep post_max_size
   ```

5. Check server error logs:
   ```bash
   # Laravel logs
   tail -f storage/logs/laravel.log
   
   # Apache logs
   tail -f /var/log/apache2/error.log
   
   # Nginx logs
   tail -f /var/log/nginx/error.log
   ```

## 📊 Verify Current Settings

### Check PHP Settings:

```bash
# Via command line
php -i | grep upload_max_filesize
php -i | grep post_max_size
php -i | grep memory_limit

# Via web (create info.php in public folder)
<?php phpinfo(); ?>
# Visit: http://yoursite.com/info.php
# IMPORTANT: Delete after checking!
```

### Check Laravel Validation:

```php
// Check BranchForm.php
cat app/Livewire/Forms/BranchForm.php | grep "max:"
```

### Test Upload Limit:

1. Go to upload page
2. Try uploading a file exactly at the limit (e.g., 20MB)
3. Try uploading slightly over the limit (e.g., 21MB)
4. Check Laravel logs: `storage/logs/laravel.log`

## 🎯 Recommended Settings by Use Case

### Small Documents (Default) - **Recommended**
```
✅ Max File Size: 20MB
✅ Max Execution Time: 300 seconds (5 min)
✅ Memory Limit: 512MB
```

**Good for:**
- Most PDF documents
- Reports, forms, contracts
- 10-50 pages
- Fast processing

### Medium Documents
```
⚡ Max File Size: 50MB
⚡ Max Execution Time: 600 seconds (10 min)
⚡ Memory Limit: 1GB
```

**Good for:**
- Larger PDFs
- 50-100 pages
- Scanned documents
- Some delay acceptable

### Large Documents (Not Recommended)
```
⚠️ Max File Size: 100MB
⚠️ Max Execution Time: 900 seconds (15 min)
⚠️ Memory Limit: 2GB
```

**Good for:**
- Very large PDFs
- 100+ pages
- Archive documents
- Slow processing expected

**Warning:** Can cause:
- Long processing times
- High memory usage
- Potential timeouts
- Large database storage

## 🔐 Security Considerations

### 1. **File Type Validation**

Always validate file types:
```php
'file_path' => 'required|file|mimes:pdf|max:20480',
```

**Never allow:**
- Executable files (.exe, .sh, .bat)
- Script files (.php, .js, .py)
- Archive files (.zip, .rar) without scanning

### 2. **Virus Scanning**

For production, consider adding virus scanning:
```php
// Using ClamAV or similar
use Xenolope\Quahog\Client;

$scanner = new Client('unix:///var/run/clamav/clamd.ctl');
$result = $scanner->scanFile($filePath);
```

### 3. **Storage Location**

- ✅ Store uploads in `storage/app/public` (not web-accessible by default)
- ✅ Use Laravel's Storage facade
- ❌ Don't store directly in `public/` folder

### 4. **Rate Limiting**

Implement rate limiting for uploads:
```php
// In routes or middleware
RateLimiter::for('uploads', function (Request $request) {
    return Limit::perMinute(5)->by($request->user()->id);
});
```

## 📝 Configuration Checklist

- [ ] Laravel validation rule updated (`BranchForm.php`)
- [ ] `.htaccess` configured with proper limits
- [ ] `.user.ini` created as fallback
- [ ] Server PHP configuration checked (if accessible)
- [ ] Web server (Nginx/Apache) configured
- [ ] Cache cleared (`php artisan config:clear`)
- [ ] Server restarted (if needed)
- [ ] Settings verified via `phpinfo()` or CLI
- [ ] Upload tested with file at limit
- [ ] Error logs checked for issues
- [ ] Documentation updated (this file)

## 🚀 Best Practices

1. **Set Reasonable Limits**
   - Don't set limits too high unnecessarily
   - Consider server resources and typical use cases
   - Balance between user needs and performance

2. **Monitor Usage**
   - Track upload sizes and processing times
   - Watch for failed uploads in logs
   - Monitor server resource usage

3. **User Communication**
   - Display maximum file size in UI
   - Show upload progress
   - Provide helpful error messages

4. **Optimize Processing**
   - Use queue jobs for large files (already implemented)
   - Implement chunked uploads for very large files
   - Consider compression before upload

5. **Regular Maintenance**
   - Clean up old files periodically
   - Monitor database size growth
   - Review and adjust limits as needed

## 📞 Support

If you continue to have upload issues:

1. Check all configuration files
2. Review error logs (Laravel, PHP, web server)
3. Verify PHP settings with `php -i`
4. Test with progressively larger files to find actual limit
5. Consider server resource constraints

---

**Current Upload Limit: 20MB** ✅

This should handle most PDF documents efficiently while maintaining good performance.
