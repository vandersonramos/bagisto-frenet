<?php

namespace VandersonRamos\Frenet\Helper;

class Data
{
    // Default dimensions
    const DEFAULT_LENGTH = 16;
    const DEFAULT_HEIGHT = 2;
    const DEFAULT_WIDTH  = 11;

    /**
     * Clean the zip code removing ['-', '.', ' ']
     * @param string $zipCode
     * @return int
     */
    public static function cleanZipCode(string $zipCode): int
    {
        return (int) str_replace(['-', '.'], '', trim($zipCode));
    }

    /**
     * Formats title for display
     * @param string $carrierName
     * @param string $serviceName
     * @param int|null $deliveryTime
     * @return string
     */
    public static function formatTitle(string $carrierName, string $serviceName, int $deliveryTime = null): string
    {
        if (!is_null($deliveryTime)) {
            return sprintf('%s - %s - Em média %s dia(s) ', $carrierName, $serviceName, $deliveryTime);
        }

        return sprintf('%s - %s ', $carrierName, $serviceName);
    }

    /**
     * Fix the package weight, converting from grams to kilo
     * @param $weight
     * @return float
     */
    public static function fixPackageWeight($weight): float
    {
        return floatval(number_format($weight/1000, 2, '.', ''));
    }
}