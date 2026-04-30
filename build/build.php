<?php

$root = dirname(__DIR__);
$dist = "$root/dist";
$temp = "$dist/tmp/culqi";
$version = getVersion($root);
$zipOut = "$dist/culqi-{$version}.zip";
$excludes = array_filter(
    array_map('trim', file("$root/build/exclude.txt")),
    fn($l) => $l !== '' && !str_starts_with($l, '#')
);

echo "Cleaning dist...\n";
deleteDir($dist);
mkdir($temp, 0755, true);

echo "Copying files...\n";
copyDir($root, $temp, $excludes);

echo "Creating zip...\n";
$zip = new ZipArchive();
$zip->open($zipOut, ZipArchive::CREATE | ZipArchive::OVERWRITE);
addDirToZip($zip, $temp, 'culqi');
$zip->close();

deleteDir("$dist/tmp");

echo "✅ Build generado en dist/culqi-{$version}.zip\n";

function isExcluded(string $rel, array $excludes): bool
{
    foreach ($excludes as $ex) {
        $ex = rtrim($ex, '/');
        if ($rel === $ex || str_starts_with($rel, "$ex/")) {
            return true;
        }
    }
    return false;
}

function copyDir(string $src, string $dst, array $excludes): void
{
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        $rel = ltrim(str_replace($src, '', $item->getPathname()), DIRECTORY_SEPARATOR);
        $rel = str_replace(DIRECTORY_SEPARATOR, '/', $rel);

        if (isExcluded($rel, $excludes))
            continue;

        $target = $dst . DIRECTORY_SEPARATOR . $rel;
        if ($item->isDir()) {
            mkdir($target, 0755, true);
        } else {
            copy($item->getPathname(), $target);
        }
    }
}

function addDirToZip(ZipArchive $zip, string $dir, string $prefix): void
{
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($items as $item) {
        if ($item->isFile()) {
            $rel = ltrim(str_replace($dir, '', $item->getPathname()), DIRECTORY_SEPARATOR);
            $zip->addFile($item->getPathname(), $prefix . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $rel));
        }
    }
}

function deleteDir(string $path): void
{
    if (!is_dir($path))
        return;
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }
    rmdir($path);
}

function getVersion(string $root): string
{
    $header = file_get_contents($root . '/index.php');
    if (preg_match('/Version:\s*(\d+\.\d+\.\d+)/', $header, $matches)) {
        return $matches[1];
    }
    return '0.0.0';
}
