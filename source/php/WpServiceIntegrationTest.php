<?php

declare(strict_types=1);

namespace WpService\Implementations {
    function installAssetTestFunctions(): void
    {
        function wp_cache_get(...$args): array { return []; }
        function plugins_url(...$args): string { return 'https://example.com/wp-content/plugins/alarm'; }
        function wp_register_script(...$args): bool { $GLOBALS['assetCalls'][] = ['register_script', $args]; return true; }
        function wp_enqueue_script(...$args): void { $GLOBALS['assetCalls'][] = ['enqueue_script', $args]; }
        function wp_localize_script(...$args): bool { $GLOBALS['assetCalls'][] = ['localize_script', $args]; return true; }
        function wp_register_style(...$args): bool { $GLOBALS['assetCalls'][] = ['register_style', $args]; return true; }
        function wp_enqueue_style(...$args): void { $GLOBALS['assetCalls'][] = ['enqueue_style', $args]; }
        function add_filter($hook, $callback, $priority, $acceptedArgs): true
        {
            $GLOBALS['assetCalls'][] = ['filter', [$hook, is_callable($callback), $priority, $acceptedArgs]];
            return true;
        }
    }
}

namespace ApiAlarmIntegration {
    function installTranslationTestFunction(): void
    {
        function __(string $text, string $domain): string
        {
            return $text;
        }
    }

    use PHPUnit\Framework\Attributes\RunInSeparateProcess;
    use PHPUnit\Framework\TestCase;
    use WpService\Implementations\NativeWpService;
    use WpUtilService\WpUtilService;

    final class WpServiceIntegrationTest extends TestCase
    {
        #[RunInSeparateProcess]
        public function testAssetEnqueueUsesNativeWpService(): void
        {
            \WpService\Implementations\installAssetTestFunctions();
            installTranslationTestFunction();
            $GLOBALS['assetCalls'] = [];
            $wp = new NativeWpService();
            App::$wpEnqueue = (new WpUtilService($wp))->enqueue('/var/www/wp-content/plugins/alarm');

            App::enqueueAlarmScripts();
            App::enqueueAlarmScripts();
            App::enqueueStyle();

            self::assertSame([
                ['register_script', ['js-api-alarm-integrationjs', 'https://example.com/wp-content/plugins/alarm/assets/dist/js/api-alarm-integration.js', [], false, true]],
                ['filter', ['script_loader_tag', true, 10, 2]],
                ['enqueue_script', ['js-api-alarm-integrationjs', '', [], false, []]],
                ['localize_script', ['js-api-alarm-integrationjs', 'ApiAlarmIntegrationLang', ['show_filters' => 'Show filters', 'hide_filters' => 'Hide filters']]],
                ['register_style', ['css-api-alarm-integrationcss', 'https://example.com/wp-content/plugins/alarm/assets/dist/css/api-alarm-integration.css', [], false, 'all']],
                ['enqueue_style', ['css-api-alarm-integrationcss', '', [], false, 'all']],
            ], $GLOBALS['assetCalls']);
        }
    }
}
