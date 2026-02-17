#!/bin/bash

# Script لإصلاح مشكلة الـ API على السيرفر

echo "🔧 تنظيف جميع الـ Cache..."
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

echo "✅ تم تنظيف الـ Cache بنجاح"

echo "📋 التحقق من الـ Routes..."
php artisan route:list --path=api/login

echo "🔍 التحقق من الـ Permissions..."
chmod -R 755 storage bootstrap/cache

echo "✨ تم الانتهاء! جرب الـ API الآن"


