<?php

use App\Enums\RoleEnum;

if (!function_exists('isRole')) {
    /**
     * Check if the Authenticated user has a specific role
     *
     * @param string|RoleEnum $role
     * @return bool
     */
    function isRole(string|RoleEnum $role): bool
    {
        if ($role instanceof RoleEnum) {
            $role = $role->value;
        }

        return Auth::user()->hasRole($role);
    }
}

if (!function_exists('getMime')) {
    /**
     * Get mime type from file extension
     *
     * @param string $file_extension
     *
     * @return string
     */
    function getMime(string $file_extension): string
    {
        return \GuzzleHttp\Psr7\MimeType::fromFilename(".$file_extension") ?? '';
    }
}

if (!function_exists('getMimes')) {
    /**
     * Get mime types from file extensions
     *
     * @param string $file_extensions
     *
     * @return string
     */
    function getMimes(string $file_extensions): string
    {
        $mimes = explode('|', str_replace(',', '|', $file_extensions));
        $mimeArray = array_map('getMime', $mimes);
        return implode(',', $mimeArray);
    }
}

if (!function_exists('toRupiah')) {
    /**
     * Convert number to Rupiah format
     *
     * @param integer|float|null $number
     * @param boolean $usePrefix
     * @param boolean $useDecimals
     * @return string
     */
    function toRupiah(int|float|null $number, bool $usePrefix = true, bool $useDecimals = false): string
    {
        if (is_null($number) || $number === 0) {
            return $useDecimals ? 'Rp 0,00' : 'Rp 0';
        }

        $formattedNumber = number_format($number, $useDecimals ? 2 : 0, ',', '.');
        return $usePrefix ? 'Rp ' . $formattedNumber : $formattedNumber;
    }
}

if (!function_exists('bytesToMB')) {
    /**
     * Convert bytes to megabytes.
     *
     * @param int|float $bytes The number of bytes to convert.
     * @param int $precision The number of decimal places to round to.
     * @return float The number of megabytes.
     */
    function bytesToMB($bytes, int $precision = 4): float
    {
        return round($bytes / 1048576, $precision);
    }
}

if (!function_exists('mbToBytes')) {
    /**
     * Convert megabytes to bytes.
     *
     * @param int|float $mb The number of megabytes to convert.
     * @return float The number of bytes.
     */
    function mbToBytes($mb): float
    {
        return $mb * 1048576;
    }
}

// check user permission
if (!function_exists('userCan')) {
    /**
     * Check user permission
     *
     * @param string $permission
     * @return bool
     */
    function userCan(string $permission): bool
    {
        return Auth::user()->can($permission);
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format date to specific format
     *
     * @param DateTimeInterface|string|int|float|null $date
     * @param string $format
     * @return string
     */
    function dateFormat(DateTimeInterface|string|int|float|null $date, string $format = 'd M Y'): string
    {
        // Carbon::setLocale('id');
        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('makeInitial')) {
    /**
     * Make initial from string
     *
     * @param string $string
     * @param int $max_count
     * @return string
     */
    function makeInitial(string $string, int $max_count = 3): string
    {
        $words = explode(' ', $string);
        $initial = '';
        $count = 0;
        foreach ($words as $word) {
            $initial .= $word[0];
            $count++;
            if ($count == $max_count) {
                break;
            }
        }
        return strtoupper($initial);
    }
}

if (!function_exists('aiClient')) {
    /**
     * Get OpenAI Client
     *
     * @return OpenAI\Client
     */
    function aiClient(): OpenAI\Client
    {
        return OpenAI::client(getenv("OPENAI_API_KEY"));
    }
}

if (!function_exists('metaClient')) {
    /**
     * Get MetaGraphApi Client
     *
     * @return App\Class\MetaGraphApi
     */
    function metaClient(string $access_token = ''): App\Class\MetaGraphApi
    {
        return new App\Class\MetaGraphApi(
            config('services.facebook.client_id'),
            config('services.facebook.client_secret'),
            $access_token
        );
    }
}

if(!function_exists('instagramClient')) {
    /**
     * Get InstagramApi Client
     *
     * @return App\Class\InstagramApi
     */
    function instagramClient(string $access_token = ''): App\Class\InstagramApi
    {
        return new App\Class\InstagramApi(
            config('services.instagram.client_id'),
            config('services.instagram.client_secret'),
            $access_token
        );
    }
}

if(!function_exists('messengerClient'))
{
    /**
     * Get MessengerApi Client
     *
     * @return App\Class\MessengerApi
     */
    function messengerClient(string $access_token = ''): App\Class\MessengerApi
    {
        return new App\Class\MessengerApi(
            config('services.facebook.client_id'),
            config('services.facebook.client_secret'),
            config('services.facebook.verify_token'),
            $access_token
        );
    }
}

if (!function_exists('getDefaultTheme')) {
    function getDefaultTheme()
    {
        return env('DEFAULT_THEME', 'light'); // 'light' adalah default jika tidak ada di .env
    }
}