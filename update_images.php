<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;

$sourceDir = 'C:\Users\sakti\.gemini\antigravity\brain\e9824820-4dd4-46fd-9256-3989f8197b0b';
$destDir = __DIR__ . '/public/images/services';

if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

// Map slug to the file prefix
$imagesMap = [
    'laundry-kiloan' => 'laundry_kiloan',
    'cuci-kering' => 'cuci_kering',
    'cuci-setrika' => 'cuci_setrika',
    'setrika-saja' => 'setrika_saja',
    'laundry-sepatu' => 'laundry_sepatu',
    'laundry-boneka' => 'laundry_boneka',
    'laundry-karpet' => 'laundry_karpet',
    'laundry-bed-cover' => 'laundry_bed_cover',
];

$files = scandir($sourceDir);

foreach ($imagesMap as $slug => $prefix) {
    // Find the newest file matching the prefix
    $matchedFile = null;
    $latestTime = 0;
    foreach ($files as $file) {
        if (strpos($file, $prefix) === 0 && str_ends_with($file, '.png')) {
            $mtime = filemtime($sourceDir . DIRECTORY_SEPARATOR . $file);
            if ($mtime > $latestTime) {
                $latestTime = $mtime;
                $matchedFile = $file;
            }
        }
    }
    
    if ($matchedFile) {
        $sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $matchedFile;
        $destFile = $prefix . '.png';
        $destPath = $destDir . DIRECTORY_SEPARATOR . $destFile;
        
        copy($sourcePath, $destPath);
        
        $dbPath = 'images/services/' . $destFile;
        Service::where('slug', $slug)->update(['image_path' => $dbPath]);
        echo "Updated $slug with $dbPath\n";
    } else {
        echo "Could not find image for $slug\n";
    }
}

echo "All done.\n";
