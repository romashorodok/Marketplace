<?php

namespace Database\Factories\Providers;

use Faker\Provider\Base;

class ProductNameProvider extends Base
{
    protected static array $names = [
        "Case",
        "Laptop",
        "Monitor",
        "Phone",
        "Printer",
        "Processor",
        "Router",
        "Video card",
        "TV",
        "Motherboard",
        "Disk drive",
        "SSD",
    ];

    public static function productName(): string
    {
        return self::randomElement(self::$names);
    }
}
