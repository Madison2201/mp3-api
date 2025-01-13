<?php

namespace api\enums;

enum Language: string
{
    case EN = 'en';
    case RU = 'ru';
    case KZ = 'kz';

    public static function getList(): array
    {
        return array_map(
            fn(self $language) => $language->value,
            self::cases()
        );
    }
}
