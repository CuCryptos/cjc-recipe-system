<?php
/**
 * CJC Recipe Custom Post Type
 *
 * @package CJC_Recipe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registers the cjc_recipe custom post type.
 */
class CJC_Recipe_Post_Type {

    /**
     * Post type name.
     *
     * @var string
     */
    public static $post_type = 'cjc_recipe';

    /**
     * Initialize the post type.
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_type' ) );
    }

    /**
     * Register the custom post type.
     */
    public static function register_post_type() {
        $labels = array(
            'name'                  => _x( 'Recipes', 'Post type general name', 'cjc-recipe-system' ),
            'singular_name'         => _x( 'Recipe', 'Post type singular name', 'cjc-recipe-system' ),
            'menu_name'             => _x( 'CJC Recipes', 'Admin Menu text', 'cjc-recipe-system' ),
            'name_admin_bar'        => _x( 'Recipe', 'Add New on Toolbar', 'cjc-recipe-system' ),
            'add_new'               => __( 'Add New', 'cjc-recipe-system' ),
            'add_new_item'          => __( 'Add New Recipe', 'cjc-recipe-system' ),
            'new_item'              => __( 'New Recipe', 'cjc-recipe-system' ),
            'edit_item'             => __( 'Edit Recipe', 'cjc-recipe-system' ),
            'view_item'             => __( 'View Recipe', 'cjc-recipe-system' ),
            'all_items'             => __( 'All Recipes', 'cjc-recipe-system' ),
            'search_items'          => __( 'Search Recipes', 'cjc-recipe-system' ),
            'parent_item_colon'     => __( 'Parent Recipes:', 'cjc-recipe-system' ),
            'not_found'             => __( 'No recipes found.', 'cjc-recipe-system' ),
            'not_found_in_trash'    => __( 'No recipes found in Trash.', 'cjc-recipe-system' ),
            'featured_image'        => _x( 'Recipe Image', 'Overrides the "Featured Image" phrase', 'cjc-recipe-system' ),
            'set_featured_image'    => _x( 'Set recipe image', 'Overrides the "Set featured image" phrase', 'cjc-recipe-system' ),
            'remove_featured_image' => _x( 'Remove recipe image', 'Overrides the "Remove featured image" phrase', 'cjc-recipe-system' ),
            'use_featured_image'    => _x( 'Use as recipe image', 'Overrides the "Use as featured image" phrase', 'cjc-recipe-system' ),
            'archives'              => _x( 'Recipe archives', 'The post type archive label', 'cjc-recipe-system' ),
            'insert_into_item'      => _x( 'Insert into recipe', 'Overrides the "Insert into post" phrase', 'cjc-recipe-system' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this recipe', 'Overrides the "Uploaded to this post" phrase', 'cjc-recipe-system' ),
            'filter_items_list'     => _x( 'Filter recipes list', 'Screen reader text', 'cjc-recipe-system' ),
            'items_list_navigation' => _x( 'Recipes list navigation', 'Screen reader text', 'cjc-recipe-system' ),
            'items_list'            => _x( 'Recipes list', 'Screen reader text', 'cjc-recipe-system' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => false,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-food',
            'supports'           => array( 'title', 'thumbnail', 'custom-fields' ),
            'show_in_rest'       => true,
            'rest_base'          => 'cjc-recipes',
        );

        register_post_type( self::$post_type, $args );
    }

    /**
     * Get a recipe by ID.
     *
     * @param int $id Recipe post ID.
     * @return WP_Post|false Recipe post object or false if not found.
     */
    public static function get_by_id( $id ) {
        if ( empty( $id ) ) {
            return false;
        }
        $post = get_post( $id );
        if ( $post && self::$post_type === $post->post_type ) {
            return $post;
        }
        return false;
    }

    /**
     * Get all recipe data for a given recipe ID.
     *
     * @param int $id Recipe post ID.
     * @return array|false Recipe data array or false if not found.
     */
    public static function get_recipe_data( $id ) {
        $post = self::get_by_id( $id );
        if ( ! $post ) {
            return false;
        }

        $meta = CJC_Recipe_Meta::get_all_meta( $id );
        $image_id = get_post_thumbnail_id( $id );
        $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';

        return array(
            'id'           => $id,
            'title'        => $post->post_title,
            'image_id'     => $image_id,
            'image_url'    => $image_url,
            'description'  => $meta['description'] ?? '',
            'prep_time'    => $meta['prep_time'] ?? '',
            'cook_time'    => $meta['cook_time'] ?? '',
            'total_time'   => $meta['total_time'] ?? '',
            'yield'        => $meta['yield'] ?? '',
            'yield_number' => $meta['yield_number'] ?? 0,
            'category'     => $meta['category'] ?? '',
            'cuisine'      => $meta['cuisine'] ?? '',
            'method'       => $meta['method'] ?? '',
            'diet'         => $meta['diet'] ?? '',
            'keywords'     => $meta['keywords'] ?? '',
            'ingredients'  => $meta['ingredients'] ?? array(),
            'instructions' => $meta['instructions'] ?? array(),
            'notes'        => $meta['notes'] ?? '',
            'video_url'    => $meta['video_url'] ?? '',
            'nutrition'    => array(
                'serving_size'    => $meta['serving_size'] ?? '',
                'calories'        => $meta['calories'] ?? '',
                'sugar'           => $meta['sugar'] ?? '',
                'sodium'          => $meta['sodium'] ?? '',
                'fat'             => $meta['fat'] ?? '',
                'saturated_fat'   => $meta['saturated_fat'] ?? '',
                'unsaturated_fat' => $meta['unsaturated_fat'] ?? '',
                'trans_fat'       => $meta['trans_fat'] ?? '',
                'carbohydrates'   => $meta['carbohydrates'] ?? '',
                'fiber'           => $meta['fiber'] ?? '',
                'protein'         => $meta['protein'] ?? '',
                'cholesterol'     => $meta['cholesterol'] ?? '',
            ),
            'rating'       => array(
                'average' => $meta['average_rating'] ?? 0,
                'count'   => $meta['total_reviews'] ?? 0,
            ),
        );
    }
}
