<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Starter Plugin Taxonomy Class
 *
 * Re-usable class for registering post type taxonomies.
 *
 * @package WordPress
 * @subpackage Subpackage_Collection
 * @category Plugin
 * @author Matty
 * @since 1.0.0
 */
class Subpackage_Collection_Taxonomy {
	/**
	 * The post type to register the taxonomy for.
	 * @access  private
	 * @since   1.3.0
	 * @var     string
	 */
	private $post_type;

	/**
	 * The key of the taxonomy.
	 * @access  private
	 * @since   1.3.0
	 * @var     string
	 */
	private $token;

	/**
	 * The singular name for the taxonomy.
	 * @access  private
	 * @since   1.3.0
	 * @var     string
	 */
	private $singular;

	/**
	 * The plural name for the taxonomy.
	 * @access  private
	 * @since   1.3.0
	 * @var     string
	 */
	private $plural;

	/**
	 * The arguments to use when registering the taxonomy.
	 * @access  private
	 * @since   1.3.0
	 * @var     string
	 */
	private $args;

	/**
	 * Class constructor.
	 * @access  public
	 * @since   1.3.0
	 * @param   string $post_type The post type key.
	 * @param   string $token     The taxonomy key.
	 * @param   string $singular  Singular name.
	 * @param   string $plural    Plural  name.
	 * @param   array  $args      Array of argument overrides.
	 */
	public function __construct ( $post_type = 'collection', 
								  $token = 'collection-category', 
								  $singular = '', $plural = '', 
								  $args = array() ) {

		$this->post_type = $post_type;
		$this->token     = esc_attr( $token );
		$this->singular  = esc_html( $singular );
		$this->plural    = esc_html( $plural );

		if ( '' === $this->singular ) {
			$this->singular = __( 'Category', 'subpackage-collection' );
		}
		if ( '' === $this->plural ) {
			$this->plural = __( 'Categories', 'subpacke-collection' );
		}

		$this->args = wp_parse_args( $args, $this->get_default_args() );
	}

	/**
	 * Return an array of default arguments.
	 * @access  private
	 * @since   1.3.0
	 * @return  array Default arguments.
	 */
	private function get_default_args () {
		return array(
			'labels'            => $this->get_default_labels(),
			'public'            => true,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'show_in_nav_menus' => false,
			'show_tagcloud'     => false,
		);
	}

	/**
	 * Return an array of default labels.
	 * @access  private
	 * @since   1.3.0
	 * @return  array Default labels.
	 */
	private function get_default_labels () {
		return array(
			'name'              => $this->plural,
			'singular_name'     => $this->singular,
			/* translators: taxonomy name, in plural */
			'search_items'      => sprintf( __( 'Search %s', 'subpackage-collection' ), $this->plural ),
			/* translators: taxonomy name, in plural */
			'all_items'         => sprintf( __( 'All %s', 'subpackage-collection' ), $this->plural ),
			/* translators: taxonomy name, in singular */
			'parent_item'       => sprintf( __( 'Parent %s', 'subpackage-collection' ), $this->singular ),
			/* translators: taxonomy name, in singular */
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'subpackage-collection' ), $this->singular ),
			/* translators: taxonomy name, in singular */
			'edit_item'         => sprintf( __( 'Edit %s', 'subpackage-collection' ), $this->singular ),
			/* translators: taxonomy name, in singular */
			'update_item'       => sprintf( __( 'Update %s', 'subpackage-collection' ), $this->singular ),
			/* translators: taxonomy name, in singular */
			'add_new_item'      => sprintf( __( 'Add New %s', 'subpackage-collection' ), $this->singular ),
			/* translators: taxonomy name, in singular */
			'new_item_name'     => sprintf( __( 'New %s Name', 'subpackage-collection' ), $this->singular ),
			'menu_name'         => $this->plural,
		);
	}

	/**
	 * Register the taxonomy.
	 * @access  public
	 * @since   1.3.0
	 * @return  void
	 */
	public function register () {
		register_taxonomy( esc_attr( $this->token ), 
			esc_attr( $this->post_type ), 
			(array) $this->args );
	}
}

