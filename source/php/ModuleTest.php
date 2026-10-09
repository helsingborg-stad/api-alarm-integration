<?php

declare(strict_types=1);

namespace Modularity {
    /**
     * Stand-in for the module base class when testing URL resolution.
     */
    class Module
    {
    }
}

namespace ApiAlarmIntegration {
    use PHPUnit\Framework\TestCase;

    require_once __DIR__ . '/Module.php';

    /**
     * Tests selection of the places API URL.
     */
    final class ModuleTest extends TestCase
    {
        /**
         * Missing places URLs use the configured alarm API host.
         */
        public function testMissingPlacesUrlUsesFallback(): void
        {
            $fallbackUrl = 'https://example.com/api/alarms';
            $expected = 'https://example.com/json/wp/v2/place?per_page=100';

            self::assertSame($expected, Module::getPlacesUrl(null, $fallbackUrl));
            self::assertSame($expected, Module::getPlacesUrl(false, $fallbackUrl));
            self::assertSame($expected, Module::getPlacesUrl('', $fallbackUrl));
        }

        /**
         * Base URLs without an endpoint use the default places endpoint.
         */
        public function testBasePlacesUrlUsesFallback(): void
        {
            $fallbackUrl = 'https://example.com:8080/api/alarms';
            $expected = 'https://example.com:8080/json/wp/v2/place?per_page=100';

            self::assertSame($expected, Module::getPlacesUrl('https://other.example/', $fallbackUrl));
            self::assertSame($expected, Module::getPlacesUrl('https://other.example', $fallbackUrl));
        }

        /**
         * Configured places endpoints are used unchanged.
         */
        public function testConfiguredPlacesUrlIsPreserved(): void
        {
            $placesUrl = 'https://other.example/json/wp/v2/place?per_page=25';

            self::assertSame($placesUrl, Module::getPlacesUrl($placesUrl, 'https://example.com'));
        }
    }
}
