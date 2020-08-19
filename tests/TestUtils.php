<?php

namespace Tests;

use App\Constants\DataValidationErrorMessages;
use App\Constants\ResponseStatuses;
use App\Constants\Statuses;
use App\DetaljiKorisnika;
use App\Korisnik;
use App\LokacijaSkole;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class TestUtils extends TestCase
{
    public static function assertFieldIsRequired(
        $fieldName,
        $getResponseCallback
    ) {
        self::assertFieldFailedDataValidationWithMessage(
            $getResponseCallback,
            DataValidationErrorMessages::required($fieldName)
        );
    }

    public static function assertFieldIsEmail($fieldName, $getResponseCallback)
    {
        self::assertFieldFailedDataValidationWithMessage(
            $getResponseCallback,
            DataValidationErrorMessages::email($fieldName)
        );
    }

    public static function assertFieldIsUnique($fieldName, $getResponseCallback)
    {
        self::assertFieldFailedDataValidationWithMessage(
            $getResponseCallback,
            DataValidationErrorMessages::unique($fieldName)
        );
    }

    public static function assertFieldIsConfirmed(
        $fieldName,
        $getResponseCallback
    ) {
        self::assertFieldFailedDataValidationWithMessage(
            $getResponseCallback,
            DataValidationErrorMessages::confirmed($fieldName)
        );
    }

    private static function assertFieldFailedDataValidationWithMessage(
        $getResponseCallback,
        $expectedErrorMessage
    ) {
        $response = $getResponseCallback();
        $response->assertStatus(400);

        $errors = $response->decodeResponseJson()['errors'];

        self::assertContains($expectedErrorMessage, $errors);
    }
}
