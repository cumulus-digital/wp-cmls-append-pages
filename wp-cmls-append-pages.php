<?php

namespace CUMULUS\Wordpress\AppendPages;

/*
 * Plugin Name: Append Pages
 * Plugin URI: https://github.com/cumulus-digital/wp-cmls-append-pages/
 * GitHub Plugin URI: https://github.com/cumulus-digital/wp-cmls-append-pages/
 * Primary Branch: main
 * Description: Append pages to the end of other pages.
 * Version: 0.0.3
 * Author: vena
 * License: UNLICENSED
 */
// Exit if accessed directly.
\defined( 'ABSPATH' ) || exit( 'No direct access allowed.' );

const TXTDOMAIN = 'wp-cmls-append-pages';

\define( 'CUMULUS\Wordpress\AppendPages\BASEPATH', \untrailingslashit( \plugin_dir_path( __FILE__ ) ) );
\define( 'CUMULUS\Wordpress\AppendPages\BASEURL', \untrailingslashit( \plugin_dir_url( __FILE__ ) ) );

// Scoped Autoloader
require_once __DIR__ . '/build/composer/vendor/scoper-autoload.php';

// Initialize
require __DIR__ . '/init/index.php';
