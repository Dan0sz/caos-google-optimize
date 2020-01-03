<?php
/**
 * @formatter:off
 * Plugin Name: Google Optimize for CAOS
 * Plugin URI: https://daan.dev/google-optimize-caos/
 * Description: Add Google Optimize for CAOS in gtag.js
 * Version: 1.0.0
 * Author: Daan van den Bergh
 * Author URI: https://daan.dev
 * License: GPL2v2 or later
 * @formatter:on
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Replace 'YOUR-OPTIMIZE-CONTAINER-ID' with, well, your optimize container id.
 */
function caos_google_optimize()
{
    $optimizeId = 'YOUR-OPTIMIZE-CONTAINER-ID';

    if (CAOS_OPT_REMOTE_JS_FILE == 'gtag.js') {
        add_filter(
            'caos_gtag_config', function ($config, $trackingId) use ($optimizeId) {
            return $config + array('optimize_id' => $optimizeId);
        }, 10, 2
        );
    }
}

add_action('caos_process_settings', 'caos_google_optimize');
