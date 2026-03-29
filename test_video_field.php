<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$field = new App\Models\CategoryField(['type' => 'video']);
var_dump($field->isVideoType());
var_dump($field->getTypeIconAttribute());
var_dump($field->getTypeLabelAttribute());