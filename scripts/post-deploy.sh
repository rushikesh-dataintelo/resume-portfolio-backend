#!/usr/bin/env bash
set -e

# Clear Laravel caches after deploy to ensure config changes take effect
php artisan config:clear || true
php artisan route:clear || true
php artisan cache:clear || true
