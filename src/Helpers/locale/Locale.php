<?php namespace Gvera\Helpers\locale;

use Gvera\Cache\Cache;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Locale
 * @package Gvera\Helpers\locale
 * Locale mechanism, depends on locale_setup.php where the current locale is defined.
 * Reads the yml file for key/value and stores it into the cache.
 */
class Locale
{
    const LOCALE_CACHE_KEY = "gv_locale";
    public static $locales;
    public static $currentLocale;
    public static $localesDirectory;

    public static function setLocalesDirectory(string $directory)
    {
        self::$localesDirectory = $directory;
    }

    public static function setCurrentLocale($locale)
    {
        self::$currentLocale = $locale;
    }

    /**
     * @param string|null $key
     * @param array|null $additionalParams
     * @return mixed|string
     * @throws \Exception
     */
    public static function getLocale(string $key = null, array $additionalParams = null)
    {

        self::$locales = self::getLocalesFromCache();

        if (null === $key) {
            return self::$locales;
        }

        $value = self::$locales[$key] ?? $key;

        return sprintf($value, $additionalParams);
    }

    public static function getLocaleCacheKey()
    {
        return self::$currentLocale . '_' . self::LOCALE_CACHE_KEY;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private static function getLocalesFromCache()
    {
        if (Cache::getCache()->exists(self::getLocaleCacheKey())) {
            return Cache::getCache()->load(self::getLocaleCacheKey());
        }

        $directory = self::$localesDirectory ?? __DIR__ . '/../../../resources/locale/';

        $locales = Yaml::parse(
            file_get_contents($directory . self::$currentLocale .'/messages.yml')
        );
        Cache::getCache()->save(self::getLocaleCacheKey(), $locales);

        return $locales;
    }
}
