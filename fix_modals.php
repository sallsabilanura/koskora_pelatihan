<?php
$files = glob(__DIR__ . '/resources/views/admin/*/*.blade.php');
foreach ($files as $f) {
    $c = file_get_contents($f);
    
    // Add border to header and adjust padding
    $c = str_replace(
        'px-6 py-5 flex justify-between',
        'px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between',
        $c
    );
    
    // Adjust form padding
    $c = preg_replace(
        '/class="p-6 (space-y-\d+)/',
        'class="px-6 py-5 $1',
        $c
    );
    
    // Also cover forms that might not have space-y but have p-6
    $c = str_replace('class="p-6"', 'class="px-6 py-5"', $c);
    $c = str_replace("class='p-6'", "class='px-6 py-5'", $c);
    
    file_put_contents($f, $c);
}
echo "Done replacing across " . count($files) . " files.\n";
