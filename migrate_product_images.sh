#!/bin/bash

# Navigate to the project root directory
cd "$(dirname "$0")"

# Run the migration command
php artisan products:migrate-images

echo "Image migration completed!" 