<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Subpackage_Collection_Public Class
 *
 * @class Subpackage_Collection_Public
 * @version	1.0.0
 * @since 1.0.0
 * @package	Subpackage_Collection
 * @author Jeffikus
 */
final class Subpackage_Collection_Public {
	/**
	 * Subpackage_Collection_Public The single instance of Subpackage_Collection_Public.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $instance = null;
    
    /**
	 * The string containing the dynamically generated hook token.
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	//private $hook;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct () {
		// Register the scripts within WordPress.
	    add_action( 'wp_enqueue_scripts', 
                        array( $this, 'enqueue_public_scripts'));
		add_shortcode( 'collections', 
			array( $this, 'subpackage_collection_shortcode_render' ));
	}

	/**
	 * Main Subpackage_Collection_Public Instance
	 *
	 * Ensures only one instance of Subpackage_Collection_Public is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Main Subpackage_Collection_Public instance
	 */
	public static function instance () {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register the settings within the Settings API.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function enqueue_public_scripts(){
		$purl = plugins_url( '', __DIR__ );
		$pver = Subpackage_Collection()->version;
	    /*
         * Enqueue styles */
        wp_enqueue_style( 'subpackage-collection-public', 
                        $purl . '/relatives/subpackage-collection-public.css', 
                        array(), 
						$pver, 
						false 
                        );
    } 
	     
	/**
	 * Registers a custom shortcode called 'collection'
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes user entered.
	 * @return string The shortcode output.
	 */
	public function subpackage_collection_shortcode_render( $atts ) : string {
		$atts = shortcode_atts([ 
		], $atts);

		$args = array(
			'post_type' => 'collection',
			'posts_per_page' => -1 // Display all posts
		);

		$posts = new WP_Query($args);
ob_start();
		if ($posts->have_posts()) {
			while ($posts->have_posts()) {
				$posts->the_post();

				// Get featured image
				$featured_image_id = get_post_thumbnail_id();
				$featured_image_url = wp_get_attachment_url($featured_image_id);

				// Get categories
				$categories = get_the_category();

				// Get tags
				$tags = get_the_tags();

				// Output the post details
				?>
				<article class="custom-post-type-wrapper">
				<div class="custom-post-type-item">
					<div class="left-thing-content">
						<header>
								<h2><?php the_title(); ?></h2>
							<div class="tsw-excerpt">

							<p><?php echo wp_trim_words(get_the_excerpt(), 36); ?></p>
								<p><a class="td-link-btn" href="<?php the_permalink(); ?>" 
								title="<?php the_title_attribute(); ?>">
								<?php esc_html_e( 'Get ', 'tinydancer' ); ?> 
								<?php the_title(); ?><?php esc_html_e( ' Theme Here.', 'tinydancer' ); ?></a></p>
							</div>
						</header>
					</div>

						<div class="right-thing-content">
							<?php if ($featured_image_url) : ?>
								<figure>
									<img src="<?php echo $featured_image_url; ?>" alt="<?php the_title(); ?>">
								</figure>
							<?php endif; ?>
						</div>
				</div>
				<article>
				<?php
			}
			wp_reset_postdata();
		}
		return ob_get_clean();
	}
	
}