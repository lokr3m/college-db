#!/bin/bash
# Database Validation Script
# This script helps validate the college database structure

echo "==================================="
echo "College Database Validation Script"
echo "==================================="
echo ""

# Check if .env file exists
if [ ! -f .env ]; then
    echo "⚠️  WARNING: .env file not found"
    echo "   Please copy .env.example to .env and configure database settings"
    exit 1
fi

echo "✅ .env file exists"

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP Version: $PHP_VERSION"

# Check if migrations directory exists
if [ -d "database/migrations" ]; then
    MIGRATION_COUNT=$(ls -1 database/migrations/*.php 2>/dev/null | wc -l)
    echo "✅ Found $MIGRATION_COUNT migration files"
else
    echo "❌ Migration directory not found"
    exit 1
fi

# Check if models exist
if [ -d "app/Models" ]; then
    MODEL_COUNT=$(ls -1 app/Models/*.php 2>/dev/null | wc -l)
    echo "✅ Found $MODEL_COUNT model files"
else
    echo "❌ Models directory not found"
    exit 1
fi

# Check if seeder exists
if [ -f "database/seeders/DatabaseSeeder.php" ]; then
    echo "✅ Database seeder exists"
else
    echo "❌ Database seeder not found"
fi

echo ""
echo "==================================="
echo "File Structure Validation Complete"
echo "==================================="
echo ""
echo "Next steps:"
echo "1. Run: composer install"
echo "2. Configure .env with your database credentials"
echo "3. Run: php artisan migrate"
echo "4. Run: php artisan db:seed"
echo ""
