<?php
/**
 * Defines the `collections` shortcode
 */
if ( ! class_exists( 'Subpackage_Collection_List_Shortcode' ) ) {
    class Subpackage_Collection_List_Shortcode {
        /**
         * Registers the `collections` shortcode
         * @author TSW
         * @since 1.0.0
         */
        public static function register_shortcode() {
            add_shortcode( 'collections', array( 'Subpackage_Collection_List_Shortcode', 'callback' ) );
        }

        /**
         * The callback function for the `collections` shortcode.
         * @author TSW
         * @since 1.0.0
         * @param array $atts The shortcode attributes
         * @param string $content The inner content
         * @return string
         */
        public static function callback( $atts, $content='' ) {
            $atts = shortcode_atts( array(
                'layout'        => 'default',
                'person_layout' => 'default',
                'name_element'  => 'h3',
                'categories'    => null,
                'limit'         => null,
                'offset'        => null
            ), $atts, 'collections' );

            /**
             * Get the posts to send to the layout function
             */
            $layout = $atts['layout'];

            // Remove layout now that we have the value.
            unset( $atts['layout'] );

            $args = array(
                'post_type'      => 'collection',
                'posts_per_page' => -1,
                'orderby'        => 'menu_order',
                'order'          => 'ASC'
            );

            if ( $atts['categories'] ) {
                $args['category_name'] = $atts['categories'];
            }

            if ( $atts['limit'] ) {
                $args['posts_per_page'] = $atts['limit'];
            }

            if ( $atts['offset'] ) {
                $args['offset'] = $atts['offset'];
            }

            $collections = get_posts( $args );

            if ( $collections ) {
                return Subpackage_Collection_List_Shortcode::display_collections( 
                    $collections, $layout, $atts 
                );
            }

            return '';
        }
    }
}