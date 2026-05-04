<?php

declare(strict_types=1);

namespace ApiAlarmIntegration\Helper;

use PHPUnit\Framework\TestCase;

if (!function_exists(__NAMESPACE__ . '\\apply_filters')) {
    /**
     * Returns the provided value in tests without requiring WordPress.
     *
     * @param string $hookName Filter name.
     * @param string $value Value to return.
     *
     * @return string
     */
    function apply_filters(string $hookName, string $value): string
    {
        return $value;
    }
}

/**
 * Tests manifest resolution for cache-busted assets.
 */
class CacheBustTest extends TestCase
{
    private static string $pluginPath;

    /**
     * Sets up shared constants for the test suite.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        self::$pluginPath = sys_get_temp_dir() . '/api-alarm-integration-cachebust-test/';

        if (!defined('APIALARMINTEGRATION_PATH')) {
            define('APIALARMINTEGRATION_PATH', self::$pluginPath);
        }

        if (!defined('WP_DEBUG')) {
            define('WP_DEBUG', false);
        }
    }

    /**
     * Removes temporary manifest fixtures before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->removeDirectory(self::$pluginPath);
        mkdir(self::$pluginPath, 0777, true);
    }

    /**
     * Cleans up temporary manifest fixtures after the suite.
     *
     * @return void
     */
    public static function tearDownAfterClass(): void
    {
        self::removeDirectory(self::$pluginPath);
    }

    /**
     * Verifies the Vite manifest path is used when present.
     *
     * @return void
     */
    public function testNameReturnsAssetFromAssetsDistManifest(): void
    {
        $this->writeManifest('assets/dist/manifest.json', [
            'js/api-alarm-index.js' => 'js/api-alarm-index.hash.js',
        ]);

        $result = CacheBust::name('js/api-alarm-index.js');

        $this->assertSame('js/api-alarm-index.hash.js', $result);
    }

    /**
     * Verifies the legacy manifest path still works as a fallback.
     *
     * @return void
     */
    public function testNameFallsBackToLegacyDistManifest(): void
    {
        $this->writeManifest('dist/manifest.json', [
            'js/api-alarm-index.js' => 'js/api-alarm-index.legacy.js',
        ]);

        $result = CacheBust::name('js/api-alarm-index.js');

        $this->assertSame('js/api-alarm-index.legacy.js', $result);
    }

    /**
     * Writes a manifest fixture to the temporary plugin directory.
     *
     * @param string $relativePath Relative manifest path.
     * @param array<string, string> $manifest Manifest contents.
     *
     * @return void
     */
    private function writeManifest(string $relativePath, array $manifest): void
    {
        $manifestPath = self::$pluginPath . $relativePath;
        $manifestDirectory = dirname($manifestPath);

        if (!is_dir($manifestDirectory)) {
            mkdir($manifestDirectory, 0777, true);
        }

        file_put_contents($manifestPath, json_encode($manifest, JSON_THROW_ON_ERROR));
    }

    /**
     * Removes a directory recursively when it exists.
     *
     * @param string $directory Directory path.
     *
     * @return void
     */
    private static function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if (in_array($item, ['.', '..'], true)) {
                continue;
            }

            $path = $directory . '/' . $item;

            if (is_dir($path)) {
                self::removeDirectory($path);
                continue;
            }

            unlink($path);
        }

        rmdir($directory);
    }
}
