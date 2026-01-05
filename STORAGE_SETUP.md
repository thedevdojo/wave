# Storage Setup Guide

## Issue: Blog Images Not Loading / Livewire Upload Errors

If you're experiencing issues with:
- Blog post images not displaying
- "Unable to create a directory at livewire-tmp" errors
- File upload failures in Filament admin

### Solution

Run the storage setup command:

```bash
php artisan storage:setup
```

This command will:
1. Create all required storage directories
2. Set proper permissions (0755)
3. Create the `storage/app/public` symlink to `public/storage`
4. Create Livewire temporary upload directory

### Manual Setup (if command fails)

```bash
# Create directories
mkdir -p storage/app/public/posts
mkdir -p storage/app/public/videos
mkdir -p storage/app/public/attachments
mkdir -p storage/app/public/livewire-tmp

# Set permissions
chmod -R 755 storage/app/public

# Create symlink
php artisan storage:link
```

### Production Deployment

Add to your deployment script:

```bash
php artisan storage:setup
```

Or ensure these directories exist before deploying.

### Video Support

Blog posts now support video uploads (mp4, webm, ogg) up to 100MB.

In RichEditor content, videos from file attachments will work automatically.
For header videos, use the "Video" field in the post form.
