<?php
declare(strict_types=1);

namespace App\Util;

class Brand
{
    public const SHOP_NAME = 'CharityDoing';
    public const ICON_PATH = '/assets/user/images/charitydoing-icon.svg';

    public static function getTitle(?string $title): string
    {
        return self::replaceLegacyBrand($title ?? '');
    }

    public static function apply(array &$config): void
    {
        $config['shop_name'] = self::SHOP_NAME;

        if (isset($config['title'])) {
            $config['title'] = self::getTitle((string)$config['title']);
        }
    }

    public static function replaceLegacyBrand(string $value): string
    {
        return str_replace(['异次元店铺', '异次元商城', '异次元', 'Jiminaishop'], self::SHOP_NAME, $value);
    }
}
