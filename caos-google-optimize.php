<?php

/**
 * Plugin Name: CAOS - Google Optimize Extension
 * Description: This extension allows you to use A/B testing using Google Optimize in CAOS.
 * Version: 1.0.0
 * Author: Daan from FFW.Press
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 * Text Domain: host-analyticsjs-local
 */

defined('ABSPATH') || exit;

define('CAOS_EXT_SETTING_OPTIMIZE_ID', 'caos_extension_optimize_id');
define('CAOS_OPT_EXT_OPTIMIZE_ID', esc_attr(get_option(CAOS_EXT_SETTING_OPTIMIZE_ID)));

/**
 * Add Google Optimize ID setting using CAOS' settings builder.
 */
function do_optimize()
{
    $builder            = new CAOS_Admin_Settings_Builder();
    $plugin_text_domain = 'host-analyticsjs-local';

    $builder->do_text(
        __('Google Optimize ID', $plugin_text_domain),
        CAOS_EXT_SETTING_OPTIMIZE_ID,
        __('e.g. GTM-123ABCD', $plugin_text_domain),
        CAOS_OPT_EXT_OPTIMIZE_ID,
        __('Use A/B testing to test different versions of your web pages to see how they perform against an objective you’ve specified. Not compatible with Stealth Mode and Minimal Analytics.', $plugin_text_domain),
    );
}

add_filter('caos_extensions_settings_content', 'do_optimize', 180);


/**
 * Add Google Optimize ID to Tracking Code.
 */
function google_optimize()
{
    $optimize_id = CAOS_OPT_EXT_OPTIMIZE_ID;

    if (!$optimize_id) {
        return;
    }

    if (CAOS_OPT_REMOTE_JS_FILE == 'gtag.js' || CAOS_OPT_REMOTE_JS_FILE == 'gtag-v4.js') {
        add_filter('caos_gtag_config', function ($config, $tracking_id) use ($optimize_id) {
            return $config + ['optimize_id' => $optimize_id];
        }, 10, 2);
    } else {
        add_filter('caos_analytics_before_send', function ($config) use ($optimize_id) {
            $option = [
                'optimize' => "ga('require', '$optimize_id');"
            ];

            return $config + $option;
        });
    }
}

add_action('caos_process_settings', 'google_optimize');
