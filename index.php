<?php
/*
 * Plugin Name:       🍁 AM Scheduler
 * Description:       Scheduler 🗓️ with supporting reccuring events and tasks for events
 * Plugin URI:        https://github.com/ifnullez/am-scheduler
 * Version:           0.0.5
 * Requires at least: 6.2
 * Requires PHP:      8.2
 * Author:            Yevhen Zakarliuka
 * Author URI:        https://github.com/ifnullez
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ams
 * Domain Path:       /languages
 */

require_once "vendor/autoload.php";

use MHS\Init;

// Define base constants
if (!defined("MHS_PLUGIN_VERSION")) {
    define(
        "MHS_PLUGIN_VERSION",
        get_file_data(__FILE__, ["Version" => "Version"])["Version"]
    );
}
if (!defined("MHS_PLUGIN_DIR")) {
    define("MHS_PLUGIN_DIR", __DIR__);
}
// core files ( inc folder )
if (!defined("MHS_PLUGIN_INC_DIR")) {
    define("MHS_PLUGIN_INC_DIR", dirname(__FILE__) . "/inc");
}
// plugin views
if (!defined("MHS_PLUGIN_VIEWS_PATH")) {
    define("MHS_PLUGIN_VIEWS_PATH", dirname(__FILE__) . "/views");
}
// plugin assest url & path
if (!defined("MHS_PLUGIN_ASSETS_PATH")) {
    define("MHS_PLUGIN_ASSETS_PATH", dirname(__FILE__) . "assets");
}
if (!defined("MHS_PLUGIN_ASSETS_URL")) {
    define("MHS_PLUGIN_ASSETS_URL", plugin_dir_url(__FILE__) . "assets");
}
// plugin url & path
if (!defined("MHS_PLUGIN_URL")) {
    define("MHS_PLUGIN_URL", plugin_dir_url(__FILE__));
}
if (!defined("MHS_PLUGIN_FILE")) {
    define("MHS_PLUGIN_FILE", __FILE__);
}
// active theme constants
if (!defined("MHS_ACTIVE_THEME_DIR")) {
    define("MHS_ACTIVE_THEME_DIR", get_stylesheet_directory());
}
if (!defined("MHS_ACTIVE_THEME_URL")) {
    define("MHS_ACTIVE_THEME_URL", get_stylesheet_directory_uri());
}

Init::getInstance();
