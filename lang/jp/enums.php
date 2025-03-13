<?php declare(strict_types=1);

use App\Enums\PackageType;

return [
    PackageType::class => [
        PackageType::Document => '書類',
        PackageType::NonDocument => '非書類',
    ]
];