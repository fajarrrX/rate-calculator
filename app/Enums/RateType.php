<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RateType extends Enum
{
    const Original = 1;
    const Personal = 2;
    const Business = 3;
}
