#!/usr/bin/env bash

set -e

echo "Cleaning dist..."
rm -rf dist
mkdir -p dist/tmp/culqi

echo "Copying files..."
if ! command -v rsync >/dev/null 2>&1; then
  echo "[ERROR] rsync no está instalado"
  exit 1
fi
# rsync -a --exclude-from=build/exclude.txt ./ dist/tmp/culqi/
rsync -av --exclude-from=build/exclude.txt ./ dist/tmp/culqi/

echo "Creating zip..."
cd dist/tmp
zip -r ../culqi.zip culqi > /dev/null
cd ../..

echo "Cleaning temp..."
rm -rf dist/tmp

echo "✅ Build generado en dist/culqi.zip"
