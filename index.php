<?php
/**
* Relative Options
*
* @package     PluginPackage
* @author      Daniel Gregory
* @copyright   2018 Relative Marketing
*
* @wordpress-plugin
* Plugin Name: Relative Options
* Plugin URI:  https://relativemarketing.co.uk/
* Description: Easily add options pages to for your plugins
* Version:     1.0.0
* Author:      Relative Marketing
* Author URI:  https://relativemarketing.co.uk
* Text Domain: relative-options
*/

namespace RelativeMarketing\Options;

defined( 'ABSPATH' ) or die();

include plugin_dir_path( __FILE__ ) . 'inc/class-page.php';
include plugin_dir_path( __FILE__ ) . 'inc/form/class-generate.php';