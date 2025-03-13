<?php declare(strict_types=1);

use App\Enums\PackageType;

return [
    PackageType::class => [
        PackageType::Document => '서류',
        PackageType::NonDocument => '물품',
    ]
];