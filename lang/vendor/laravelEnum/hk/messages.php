<?php declare(strict_types=1);

use App\Enums\PackageType;

return [
    PackageType::class => [
        PackageType::Document => '文件',
        PackageType::NonDocument => '包裹',
    ]
];