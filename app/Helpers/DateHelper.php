<?php

declare(strict_types=1);

namespace MyParcelCom\Microservice\Helpers;

use Carbon\Carbon;
use DateTime;
use Exception;

class DateHelper
{
    private static $supportedFormats = [
        'Y-m-d',
        'Y/m/d',
        'd.m.Y',
        'd/m/Y',
        'd-m-Y',
        'm/d/Y',
        'd F, Y',
        'd F Y',
        'F d Y',
        'F d, Y',
    ];

    /**
     * Checks if given string is a valid date string
     *
     * @param string $string
     * @return bool
     */
    public static function isDateString(string $string): bool
    {
        foreach (self::$supportedFormats as $format) {
            if (DateTime::createFromFormat($format, $string)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Converts given date string to given format
     *
     * @param string $dateString
     * @param string $format
     * @return string
     */
    public static function convertDateStringToFormat(string $dateString, string $format = 'Y-m-d'): string
    {
        try {
            // DateTime not only throws an Exception when it cannot parse a string
            // but it also emits an E_WARNING. We suppress the warning with '@' to
            // keep it from triggering any error/warning handler.
            $dateTime = @new DateTime($dateString);

            return $dateTime->format($format);
        } catch (Exception $exception) {
            // If the string is formatted dd/mm/yyyy, replace the
            // slashes with dashes and try again
            if (preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $dateString)) {
                $dateString = str_replace('/', '-', $dateString);

                return static::convertDateStringToFormat($dateString, $format);
            }

            throw $exception;
        }
    }

    public static function convertIsoOrTimestampToCarbon($time): Carbon
    {
        if (is_numeric($time)) {
            return Carbon::createFromTimestamp($time);
        }

        return (new Carbon($time))->utc();
    }
}
