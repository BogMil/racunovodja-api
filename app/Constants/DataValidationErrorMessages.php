<?php

namespace App\Constants;

use Illuminate\Support\Facades\Lang;

class DataValidationErrorMessages
{
    public static function required($attributeName)
    {
        return self::singleAttributeErrorMessage(
            $attributeName,
            'validation.required'
        );
    }

    public static function unique($attributeName)
    {
        return self::singleAttributeErrorMessage(
            $attributeName,
            'validation.unique'
        );
    }

    public static function confirmed($attributeName)
    {
        return self::singleAttributeErrorMessage(
            $attributeName,
            'validation.confirmed'
        );
    }

    public static function email($attributeName)
    {
        return self::singleAttributeErrorMessage(
            $attributeName,
            'validation.email'
        );
    }

    private static function singleAttributeErrorMessage(
        $attributeName,
        $errorKey
    ) {
        $prettyAttrName = self::withoutDashChars($attributeName);
        return str_replace(":attribute", $prettyAttrName, Lang::get($errorKey));
    }

    private static function withoutDashChars($fieldName)
    {
        if (strpos($fieldName, '_') !== false) {
            return str_replace('_', ' ', $fieldName);
        }
        return $fieldName;
    }

    public const neovlasceniPristup = "Nemate prava da pristupate tuđim podacima";
}
