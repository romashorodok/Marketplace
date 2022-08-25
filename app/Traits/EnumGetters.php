<?php

declare(strict_types=1);

namespace App\Traits;

use App\Exceptions\EnumException;
use ReflectionEnum;

trait EnumGetters
{
    public static function tryFrom(string $caseName): ?self
    {
        $rc = new ReflectionEnum(self::class);

        $caseName = ucfirst($caseName);

        return $rc->hasCase($caseName) ? $rc->getConstant($caseName) : null;
    }

    /**
     * @throws EnumException
     */
    public static function from(string $caseName): self
    {
        return self::tryFrom($caseName) ?? throw new EnumException('Enum '. $caseName . ' not found in ' . self::class);
    }

}
