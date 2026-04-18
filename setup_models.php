<?php
$modelsDir = __DIR__ . '/app/Models/';
$files = glob($modelsDir . '*.php');

foreach ($files as $file) {
    if (basename($file) === 'User.php') continue;
    $content = file_get_contents($file);
    if (!str_contains($content, '$guarded')) {
        $content = preg_replace('/use HasFactory;/', "use HasFactory;\n    protected \$guarded = [];\n", $content);
        file_put_contents($file, $content);
    }
}

// Add Customer relationship to Invoice
$invoice = $modelsDir . 'Invoice.php';
$content = file_get_contents($invoice);
if (!str_contains($content, 'customer()')) {
    $relation = "
    public function customer()
    {
        return \$this->belongsTo(Customer::class, 'entity_id');
    }
";
    $content = preg_replace('/}/', $relation . "\n}", $content);
    file_put_contents($invoice, $content);
}

echo "Models updated.";
