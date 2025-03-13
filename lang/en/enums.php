<?php declare(strict_types=1);

use App\Enums\PackageType;

return [
    PackageType::class => [
        PackageType::Document => 'Document',
        PackageType::NonDocument => 'Non Document',
    ]
];
