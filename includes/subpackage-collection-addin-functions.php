<?php 
/**
 * subpackage_collection Admin Helpers.
 *
 * @author 		Larry / TSW
 * @category 	Admin
 * @package 	subpackage_collection/includes
 * @version 	1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', 'subpackage_collection_addin_check_for_debug' );
/**
 * Supported ob_end_flush() for all levels
 *
 * This replaces the WordPress `wp_ob_end_flush_all()` function
 * with a replacement that doesn't cause PHP notices.
 */
function subpackage_collection_addin_check_for_debug(){

    $values = get_option( 'subpackage-collection-standard-fields' );
    if ( is_array( $values ) && isset( $values[ 'checkbox' ] ) ) { 
        remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
        add_action( 'shutdown', function() {
            while ( @ob_end_flush() );
            } 
        );
    }
}

if ( ! function_exists( 'subpackage_collection_is_ajax' ) ) {

	/**
	 * is_ajax - Returns true when the page is loaded via ajax.
	 *
	 * @access public
	 * @return bool
	 */
	function subpackage_collection_is_ajax() {
		if ( defined('DOING_AJAX') ) {
			return true;
		}

		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 
			strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) ? true : false;
	}
}

/**
 * Sanitize taxonomy names. Slug format (no spaces, lowercase).
 *
 * Doesn't use sanitize_title as this destroys utf chars.
 *
 * @access public
 * @param mixed $taxonomy
 * @return string
 */
function subpackage_collection_sanitize_taxonomy_name( $taxonomy ) {
	$filtered = strtolower( remove_accents( stripslashes( strip_tags( $taxonomy ) ) ) );
	$filtered = preg_replace( '/&.+?;/', '', $filtered ); // Kill entities
	$filtered = str_replace( array( '.', '\'', '"' ), '', $filtered ); // Kill quotes and full stops.
	$filtered = str_replace( array( ' ', '_' ), '-', $filtered ); // Replace spaces and underscores.

	return apply_filters( 'sanitize_taxonomy_name', $filtered, $taxonomy );
} 

/** #F2
 * 
 */
function subpackage_collection_add_suffix_to_title($title, $id = null){
	//global $post, $post_ID;
    
	if ( is_single() ){
    
		$suffix = Subpackage_Collection()->settings->get_value('text', '', 'standard-fields');   
        $title  = $title . '<span class="title-suffix-after">' . $suffix . '</span>';
		
		ob_start(); echo $title; return ob_get_clean();
	}

}
// F2
remove_filter( 'the_title', 'subpackage_collection_add_suffix_to_title', 10, 2 ); 
