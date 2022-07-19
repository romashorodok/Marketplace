<?php


namespace Database\Factories\Providers;

use Faker\Provider\Base;

class CategoryNameProvider extends Base
{
    protected static array $names = [
        "Phones",
        "Laptops",
        "Monitors",
        "Computers",
        "Hardware",
        "Software",
        "Routers"
    ];

    public static function categoryName(): string
    {
        return self::randomElement(self::$names);
    }
}
