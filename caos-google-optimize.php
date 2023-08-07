<?php

/**
 * Plugin Name: CAOS Google Optimize
 * Description: This extension allows you to measure your Google Optimize A/B test results in CAOS.
 * Version: 1.0.1
 * Author: Daan from Daan.dev
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 * Text Domain: host-analyticsjs-local
 */

defined('ABSPATH') || exit;

define('CAOS_EXT_SETTING_OPTIMIZE_ID', 'caos_extension_optimize_id');
define('CAOS_OPT_EXT_OPTIMIZE_ID', esc_attr(get_option(CAOS_EXT_SETTING_OPTIMIZE_ID)));

function caos_go_register_setting($settings)
{
    return array_merge(
        [
            'CAOS_EXT_SETTING_OPTIMIZE_ID' => CAOS_EXT_SETTING_OPTIMIZE_ID
        ],
        $settings
    );
}
add_filter('caos_register_settings', 'caos_go_register_setting');

/**
 * Add Google Optimize ID setting using CAOS' settings builder.
 */
function caos_go_add_setting()
{
    $builder = new \CAOS\Admin\Settings\Builder();
    $plugin_text_domain = 'host-analyticsjs-local';

    $builder->do_text(
        __('Google Optimize Container ID', $plugin_text_domain),
        CAOS_EXT_SETTING_OPTIMIZE_ID,
        __('e.g. GTM-123ABCD', $plugin_text_domain),
        CAOS_OPT_EXT_OPTIMIZE_ID,
        __('Enter your Google Optimize Container ID (starting with <code>GTM-</code> or <code>OPT-</code>) to measure results of A/B testing. Not compatible with Stealth Mode and Minimal Analytics.', $plugin_text_domain),
    );
}

add_filter('caos_extensions_settings_content', 'caos_go_add_setting', 80);


/**
 * Add Google Optimize ID to Tracking Code.
 * 
 * @return void
 */
function caos_go_process_setting()
{
    $optimize_id = CAOS_OPT_EXT_OPTIMIZE_ID;

    if (!$optimize_id) {
        return;
    }

    add_filter(
        'caos_gtag_config', function ($config, $tracking_id) use ($optimize_id) {
            return $config + ['optimize_id' => $optimize_id];
        }, 10, 2
    );

}

add_action('caos_process_settings', 'caos_go_process_setting');
