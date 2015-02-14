<?php
/**
 * Detect the child and/ or parent of a taxonomy being queried for.
 *
 * @package   calderawp\taxonomy_tools
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\taxonomy_tools;

/**
 * Class detect_hierarchy
 *
 * @package calderawp\taxonomy_tools
 */
class detect_hierarchy {

	/**
	 * Detect if term in current query has a parent and or child term.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Query|object $query WP_Query object to test.
	 * @param bool|string $return_both. Optional. If true, the default, then an array showing if term has parent and child is returned. If is "parent" or "child" then it will return a boolean based on if term has parent/child.
	 * @param bool $main_query_only Optional. If true, the default, will only run if this is the main query.
	 * @param bool $front_end_only Optional. If true, the default, will not run if is admin.
	 *
	 * @return array|bool|void. By default, returns array with keys of "parent" and "child" whose values are booleans reflecting if term has those. The param $return_both can cause it to return a boolean reflecting if it has a parent or child.
	 */
	static public function from_query( $query, $return_both = true, $main_query_only = true, $front_end_only = true ) {
		if ( self::can_haz( $query, $main_query_only, $front_end_only ) ) {
			if ( ! isset( $query->tax_query ) ) {
				return;
			}

			if ( get_query_var( 'category_name' ) ) {
				$term_slug = get_query_var( 'category_name' );
				$taxonomy_slug = 'category';
			}else{
				$term_slug = get_query_var( 'term' );
				$taxonomy_slug = get_query_var( 'taxonomy' );
			}

			$term = get_term_by( 'slug', $term_slug, $taxonomy_slug );

			if ( is_object( $term ) && ! is_wp_error( $term ) ) {
				$parent = get_term( $term->parent, $taxonomy_slug );

				$children = get_term_children( $term->term_id, $taxonomy_slug );

				if ( ! is_wp_error( $parent ) && $parent->term_id != "" && sizeof( $children ) > 0 ) {

					// has parent and child
					$haz = array(
						'parent' => true,
						'child'  => true
					);

				} elseif ( ( ! is_wp_error( $parent ) && $parent->term_id != "" ) && sizeof( $children ) == 0 ) {

					// has parent, no child
					$haz = array(
						'parent' => true,
						'child'  => false
					);

				} elseif ( ( is_wp_error( $parent ) || $parent->term_id == "" ) && ( sizeof( $children ) > 0 ) ) {
					// no parent, has child
					$haz = array(
						'parent' => false,
						'child'  => true
					);
				}

			}

			if ( true === $return_both || ! array_key_exists( $return_both, $haz ) ) {
				return $haz;

			}else{
				return $haz[ $return_both ];

			}

		}

	}

	/**
	 * Check that $query is a WP_Query, we have a tax query in it and that our two options match.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Query|object $query WP_Query object to test.
	 * @param bool $main_query_only Optional. If true, the default, will only run if this is the main query.
	 * @param bool $front_end_only Optional. If true, the default, will not run if is admin.
	 *
	 * @return bool
	 */
	protected static function can_haz( $query, $main_query_only = true, $front_end_only = true ) {
		if ( ! is_a( $query, 'WP_Query' ) || ! isset( $query->tax_query ) ) {
			return false;

		}

		if ( $front_end_only ) {
			if ( is_admin() ) {
				return false;

			}

		}

		if ( $main_query_only ) {
			if ( ! $query->is_main_query() ) {
				return false;

			}

		}

		return true;

	}

}
