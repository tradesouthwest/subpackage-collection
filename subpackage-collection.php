<?php
/**
 * Plugin Name:  Subpackage Collection
 * Plugin URI:   http://domain.com/starter-plugin/
 * Description:  Pluggin allows for a basic method of organizing and displaying software package.
 * Version:      1.0.2
 * Author:       Tradesouthwest
 * Author URI:   https://tradesouthwest.com/
 * Requires PHP: 7.4
 * Requires CP:  2.0
 * 
 * Text Domain: subpackage-collection
 * Domain Path: /languages/
 *
 * @package    ClassicPress
 * @subpackage Subpackage Plugin
 * @category   Plugin
 * @author     Larry Judd
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once 'classes/class-subpackage-collection.php';
require_once 'includes/subpackage-collection-addin-functions.php';
    
/**
 * Returns the main instance of subpackage-collection to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Subpackage_Collection
 */
function subpackage_collection() {
	return Subpackage_Collection::instance();
}
add_action( 'plugins_loaded', 'subpackage_collection' );
