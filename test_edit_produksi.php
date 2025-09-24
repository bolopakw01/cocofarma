<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing grade loading in edit-produksi context:\n\n";

try {
    $grades = \App\Models\Pengaturan::getProductGrades();
    echo "Grades loaded successfully:\n";

    if (empty($grades)) {
        echo "No grades found, using fallback:\n";
        $grades = [
            ['name' => 'Grade A', 'label' => 'Premium'],
            ['name' => 'Grade B', 'label' => 'Standard'],
            ['name' => 'Grade C', 'label' => 'Below Standard']
        ];
    }

    foreach ($grades as $index => $grade) {
        $gradeValue = chr(65 + $index); // A, B, C, etc.
        echo "Option: value='$gradeValue' text='{$grade['name']} ({$grade['label']})'\n";
    }

} catch (\Exception $e) {
    echo "Error loading grades: " . $e->getMessage() . "\n";
    echo "Using emergency fallback:\n";

    $grades = [
        ['name' => 'Grade A', 'label' => 'Premium'],
        ['name' => 'Grade B', 'label' => 'Standard'],
        ['name' => 'Grade C', 'label' => 'Below Standard']
    ];

    foreach ($grades as $index => $grade) {
        $gradeValue = chr(65 + $index);
        echo "Option: value='$gradeValue' text='{$grade['name']} ({$grade['label']})'\n";
    }
}

echo "\nTest completed.\n";