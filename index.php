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

use AM\Scheduler\Base\Configs\Config;
use AM\Scheduler\Init;

// load configs
Config::getInstance(__FILE__, __DIR__);

Init::getInstance();
