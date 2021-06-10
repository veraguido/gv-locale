<?php

namespace Tests;

use Gvera\Cache\Cache;
use Gvera\Helpers\config\Config;
use Gvera\Helpers\locale\Locale;
use PHPUnit\Framework\TestCase;

class TestLocale extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function testLocale()
    {
        $config = new Config();
        $config->overrideKey('cache_type', 'files');
        $config->overrideKey('files_cache_path', __DIR__.'/../var/cache/files/');
        Cache::setConfig($config);

        Locale::setCurrentLocale('en');
        Locale::setLocalesDirectory(__DIR__.'/../resources/locale/');
        $this->assertTrue(Locale::getLocale('Hello world') === 'Hi planet!');
        $this->assertTrue(Locale::getLocale('asd') === 'Meh');

        Cache::getCache()->delete(Locale::getLocaleCacheKey());

        Locale::setCurrentLocale('es');
        $this->assertTrue(Locale::getLocale('Hello world') === 'Hola Mundo!');
        $this->assertTrue(Locale::getLocale('asd') === 'qwe');
        $this->assertIsArray(Locale::getLocale());

        Cache::getCache()->delete(Locale::getLocaleCacheKey());

    }
}