<?php
/**
 * Plugin Name: WP No Base Permalink
 * Plugin URI: https://wordpress.org/plugins/wp-no-base-permalink/
 * Description: Removes category base or tag base (optional) from your category or tag permalinks and removes parents categories from your category permalinks (optional). Compatible with WPML Plugin and WordPress Multisite.
 * Version: 0.3.1
 * Author: Sergio P.A. ( 23r9i0 )
 * Author URI: http://dsergio.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

add_action( 'plugins_loaded', array( 'WP_No_Base_Permalink', 'on_load' ) );
register_activation_hook( __FILE__, array( 'WP_No_Base_Permalink', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'WP_No_Base_Permalink', 'on_deactivation' ) );

if ( ! class_exists( 'WP_No_Base_Permalink') ) :
class WP_No_Base_Permalink {

	const DOMAIN = 'wpnbplang';

	private static $_options;

	public static function on_activation() {
		$default = array( 'disabled-category-base' => '1' );

		if ( $options = get_option( 'wp_no_base_permalink' ) ) {
			if ( get_option( 'wp_no_base_permalink_version' ) ) {
				$options = array_merge( $options, $default );
				delete_option( 'wp_no_base_permalink_version' );
			}

			if ( $update = self::_update_options( $options ) ) {
				update_option( 'wp_no_base_permalink', $update );
			} else {
				delete_option( 'wp_no_base_permalink' );
			}

		} else {
			update_option( 'wp_no_base_permalink', $default );
		}

		update_option( 'wp_no_base_permalink_flush', 1 );
	}

	public static function on_deactivation() {
		delete_option( 'wp_no_base_permalink' );
		delete_option( 'wp_no_base_permalink_version' );
		remove_filter( 'category_rewrite_rules', array( __CLASS__, 'category_rewrite_rules' ) );
		remove_filter( 'tag_rewrite_rules', array( __CLASS__, 'tag_rewrite_rules' ) );
		add_action( 'shutdown', array( __CLASS__, 'flush_rewrite_rules' ) );
	}

	public static function on_load() {
		add_filter( 'plugin_action_links' , array( __CLASS__, 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 4 );
		if ( current_user_can( 'manage_options' ) )
			add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		load_plugin_textdomain( self::DOMAIN, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

		if ( get_option( 'wp_no_base_permalink_flush' ) ) {
			add_action( 'shutdown', array( __CLASS__, 'flush_rewrite_rules' ) );
			delete_option( 'wp_no_base_permalink_flush' );
		} else {
			remove_action( 'shutdown', array( __CLASS__, 'flush_rewrite_rules' ) );
		}

		self::$_options = get_option( 'wp_no_base_permalink', array() );

		if (
			isset( self::$_options['disabled-category-base'] ) ||
			isset( self::$_options['remove-parents-categories'] )
		) {
			add_action( 'created_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_action( 'edited_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_action( 'delete_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_filter( 'category_rewrite_rules', array( __CLASS__, 'category_rewrite_rules' ) );
		} else {
			remove_action( 'created_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_action( 'edited_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_action( 'delete_category', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_filter( 'category_rewrite_rules', array( __CLASS__, 'category_rewrite_rules' ) );
		}


		if ( isset( self::$_options['disabled-tag-base'] ) ) {
			add_action( 'created_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_action( 'edited_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_action( 'delete_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			add_filter( 'tag_rewrite_rules', array( __CLASS__, 'tag_rewrite_rules' ) );
		} else {
			remove_action( 'created_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_action( 'edited_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_action( 'delete_post_tag', array( __CLASS__, 'flush_rewrite_rules' ), 999 );
			remove_filter( 'tag_rewrite_rules', array( __CLASS__, 'tag_rewrite_rules' ) );
		}

		if (
			isset( self::$_options['disabled-category-base'] ) ||
			isset( self::$_options['remove-parents-categories'] ) ||
			isset( self::$_options['disabled-tag-base'] )
		) {
			add_filter( 'term_link', array( __CLASS__, 'term_link' ), 10, 3 );
			add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
			add_filter( 'request', array( __CLASS__, 'request' ) );
		} else {
			remove_filter( 'term_link', array( __CLASS__, 'term_link' ) );
			remove_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
			remove_filter( 'request', array( __CLASS__, 'request' ) );
		}
	}

	public static function flush_rewrite_rules() {
		flush_rewrite_rules();
	}

	public static function plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( __FILE__ ) ) {
			$link_setting = '<a href="'. get_admin_url( null, 'options-permalink.php' ) .'">' . __( 'Settings', self::DOMAIN ) . '</a>';
			array_unshift( $links, $link_setting );
		}

		return $links;
	}

	public static function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
		if( $plugin_file === plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = sprintf(
				'<a target="_blank" href="%s">%s</a>',
				esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X7SFE48Y4FEQL' ), __( 'Donate', self::DOMAIN )
			);
		}

		return $plugin_meta;
	}

	private static function _get_terms( $taxonomy, $args = array() ) {
		// WPML is present: temporary disable terms_clauses filter to get all categories for rewrite
		if ( class_exists( 'Sitepress' ) ) {
			global $sitepress;
			remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
			$terms = get_terms( $taxonomy, array_merge( $args, array( '_icl_show_all_langs' => true ) ) );
			add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
		} else {
			$terms = get_terms( $taxonomy, $args );
		}

		return $terms;
	}

	private static function _regex_categories( $include_parents = true ) {
		$regex = array();
		$categories = self::_get_terms( 'category', array( 'hide_empty' => false ) );

		if ( ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				$category_nicename = $category->slug;
				if ( $category->parent == $category->term_id ) { // recursive recursion
					$category->parent = 0;
				} elseif ( $category->parent != 0 ) {
					$parents = get_category_parents( $category->parent, false, '/', true );
					if ( ! is_wp_error( $parents ) && $include_parents ) {
						$category_nicename = $parents . $category_nicename;
					}
				}

				$regex[] = $category_nicename;
			}

			return $regex;
		}

		return $categories;
	}

	public static function category_rewrite_rules( $rewrite ) {
		$category_rewrite = array();
		$blog_prefix      = ( is_multisite() && ! is_subdomain_install() && is_main_site() ) ? 'blog/' : '';
		$include_parents  = isset( self::$_options['remove-parents-categories'] ) ? false : true;
		$category_base    = ! isset( self::$_options['disabled-category-base'] ) ? ( $cb = get_option( 'category_base' ) ? $cb . '/' : 'category/' ) : '';
		$categories       = self::_regex_categories( $include_parents );

		if ( ! is_wp_error( $categories ) ) {
			foreach ( array_chunk( $categories, 100 ) as $regex ) {
				$category_rewrite[$blog_prefix . $category_base . '(' . implode( '|', $regex ) . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
				$category_rewrite[$blog_prefix . $category_base . '(' . implode( '|', $regex ) . ')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
				$category_rewrite[$blog_prefix . $category_base . '(' . implode( '|', $regex ) . ')/?$'] = 'index.php?category_name=$matches[1]';
			}

			if ( ! $include_parents ) {
				$remove_parents_categories_regex = array();
				$categories                      = self::_regex_categories( true );
				if ( ! is_wp_error( $categories ) ) {
					foreach ( array_chunk( $categories, 1 ) as $regex ) {
						$regex = implode( '', $regex );
						if ( false !== $p = strrpos( $regex, '/' ) ) {
							$remove_parents_categories_regex[] = substr_replace( $regex, '/(', $p, 1 ) . ')';
						}
					}
				}

				if ( count( $remove_parents_categories_regex ) ) {
					foreach ( array_chunk( $remove_parents_categories_regex, 100 ) as $regex ) {
						$category_rewrite[$blog_prefix . $category_base . '(' . implode( '|', $regex ) . ')/?$'] = 'index.php?category_redirect=$matches[2]';
					}
				}
			}

			if ( isset( self::$_options['disabled-category-base'] ) ) {
				$old_category_base = array( 'category' );
				if ( isset( self::$_options['old-category-redirect'] ) && is_array( self::$_options['old-category-redirect'] ) )
					$old_category_base = array_merge( $old_category_base, self::$_options['old-category-redirect']  );

				if ( $category_base_option = get_option( 'category_base' ) )
					$old_category_base = array_merge( $old_category_base, array( $category_base_option ) );

				$old_category_base_regex = $blog_prefix;
				$old_category_base = array_unique( array_map( 'trim', $old_category_base ) );
				if ( count( $old_category_base ) ) {
					$old_category_base_regex .= '(' . implode( '|', $old_category_base ) . ')';
					$category_rewrite[$blog_prefix . $old_category_base_regex . '/(.+?)/?$'] = 'index.php?category_redirect=$matches[2]';
				}
			}


			return $category_rewrite;
		}

		return $rewrite;
	}

	public static function tag_rewrite_rules( $rewrite ) {
		$tag_rewrite = array();
		$blog_prefix = ( is_multisite() && ! is_subdomain_install() && is_main_site() ) ? 'blog/' : '';
		$tags        = self::_get_terms( 'post_tag', array( 'hide_empty' => false ) );
		$tags_regex  = array();

		if ( ! is_wp_error( $tags ) ) {
			foreach ( $tags as $tag ) {
				$tags_regex[] = $tag->slug;
			}

			foreach ( array_chunk( $tags_regex, 100 ) as $regex ) {
				$tag_rewrite[$blog_prefix . '(' . implode( '|', $regex ) . ')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?tag=$matches[1]&feed=$matches[2]';
				$tag_rewrite[$blog_prefix . '(' . implode( '|', $regex ) . ')/page/?([0-9]{1,})/?$'] = 'index.php?tag=$matches[1]&paged=$matches[2]';
				$tag_rewrite[$blog_prefix . '(' . implode( '|', $regex ) . ')/?$'] = 'index.php?tag=$matches[1]';
			}

			$old_tag_base = array( 'tag' );
			if ( isset( self::$_options['old-tag-redirect'] ) && is_array( self::$_options['old-tag-redirect'] ) )
				$old_tag_base = array_merge( $old_tag_base, self::$_options['old-tag-redirect']  );

			if ( $tag_base_option = get_option( 'tag_base' ) )
				$old_tag_base = array_merge( $old_tag_base, array( $tag_base_option ) );

			$old_tag_base = array_unique( array_map( 'trim', $old_tag_base ) );

			$tag_rewrite[$blog_prefix . '(' . implode( '|', $old_tag_base ) .')/(.+?)/?$'] = 'index.php?tag_redirect=$matches[2]';

			return $tag_rewrite;
		}

		return $rewrite;
	}

	public static function term_link( $link, $term, $taxonomy = '' ) {
		if ( 'category' === $taxonomy ) {
			if ( isset( self::$_options['disabled-category-base'] ) ) {
				$category_base = get_option( 'category_base', '' );
				if ( '' === $category_base )
					$category_base = 'category';

				if ( '/' === substr( $category_base, 0, 1 ) )
					$category_base = substr( $category_base, 1 );

				$link = preg_replace( '/' . preg_quote( $category_base . '/', '/' ) . '/i', '', $link, 1 );
			}

			if ( 0 != $term->parent && isset( self::$_options['remove-parents-categories'] ) ) {
				$parents = get_category_parents( $term->parent, false, '/', true );
				if ( ! is_wp_error( $parents ) )
					$link = preg_replace( '/' . preg_quote( $parents, '/' ) . '/i', '', $link, 1 );
			}

		} elseif ( 'post_tag' === $taxonomy && isset( self::$_options['disabled-tag-base'] ) ) {
			$tag_base = get_option( 'tag_base', '' );
			if ( '' === $tag_base )
				$tag_base = 'tag';

			if ( '/' === substr( $tag_base, 0, 1 ) )
				$tag_base = substr( $tag_base, 1 );

			$link = preg_replace( '/' . preg_quote( $tag_base . '/', '/' ) . '/i', '', $link, 1 );
		}

		return $link;
	}

	public static function query_vars( $query_vars ) {
		if ( isset( self::$_options['disabled-category-base'] ) || isset( self::$_options['remove-parents-categories'] ) )
			$query_vars[] = 'category_redirect';

		if ( isset( self::$_options['disabled-tag-base'] ) )
			$query_vars[] = 'tag_redirect';

		return $query_vars;
	}

	public static function request( $query_vars ) {
		if ( isset( $query_vars['category_redirect'] ) ) {
			$redirect = home_url( user_trailingslashit( $query_vars['category_redirect'], 'category' ) );
			wp_redirect( $redirect, 301 );
			exit;
		}

		if ( isset( $query_vars['tag_redirect'] ) ) {
			$redirect = home_url( user_trailingslashit( $query_vars['tag_redirect'] ) );
			wp_redirect( $redirect, 301 );
			exit;
		}

		return $query_vars;
	}

	public static function admin_init() {
		add_settings_section(
			'wp_no_base_permalink-section', __( 'WP No Base Permalink', self::DOMAIN ),
			'__return_false', 'permalink'
		);

		add_settings_field(
			'disabled-category-base', __( 'Disabled Category Base',self::DOMAIN ),
			array( __CLASS__, 'disabled_category_base' ), 'permalink',
			'wp_no_base_permalink-section', array( 'label_for' => 'disabled-category-base' )
		);

		add_settings_field(
			'old-category-redirect', __( 'Categories Base',self::DOMAIN ),
			array( __CLASS__, 'old_category_redirect' ), 'permalink',
			'wp_no_base_permalink-section', array( 'label_for' => 'old-category-redirect' )
		);

		add_settings_field(
			'remove-parents-categories', __( 'Remove Parents Categories', self::DOMAIN ),
			array( __CLASS__, 'remove_parents_categories' ), 'permalink',
			'wp_no_base_permalink-section', array( 'label_for' => 'remove-parents-categories' )
		);

		add_settings_field(
			'disabled-tag-base', __( 'Disabled Tag Base',self::DOMAIN ),
			array( __CLASS__, 'disabled_tag_base' ), 'permalink',
			'wp_no_base_permalink-section', array( 'label_for' => 'disabled-tag-base' )
		);

		add_settings_field(
			'old-tag-redirect', __( 'Tags Base',self::DOMAIN ),
			array( __CLASS__, 'old_tags_redirect' ), 'permalink',
			'wp_no_base_permalink-section', array( 'label_for' => 'old-tag-redirect' )
		);

		/**
		 * Bug 9296
		 * Settings API & Permalink Settings Page
		 * http://core.trac.wordpress.org/ticket/9296
		 */
		//register_setting( 'permalink', 'wp_no_base_permalink' );

		if ( isset( $_POST['wp-no-base-permalink'] ) ) {
			check_admin_referer( 'update-permalink' );

			if ( ! count( $_POST['wp-no-base-permalink'] ) )
				return;

			if ( $update = self::_update_options( $_POST['wp-no-base-permalink'] ) ) {
				update_option( 'wp_no_base_permalink', $update );
			} else {
				delete_option( 'wp_no_base_permalink' );
			}
		}
	}

	public static function disabled_category_base() {
		?>
		<input name="wp-no-base-permalink[disabled-category-base]" id="disabled-category-base" type="checkbox" value="1"<?php checked( true, isset( self::$_options['disabled-category-base'] ) ); ?>/>
		<p class="description"><?php _e( 'Remove Category Base of the permalinks', self::DOMAIN ); ?></p>
		<?php
	}

	public static function old_category_redirect() {
		?>
		<input name="wp-no-base-permalink[old-category-redirect]" id="old-category-redirect" type="text" value="<?php self::_array_to_string( 'old-category-redirect' ); ?>" class="regular-text code" />
		<p class="description"><?php _e( 'Redirect old categories base, by default add the <code>\'category\'</code> base. For other categories base separated by <code>, </code>.', self::DOMAIN ); ?></p>
		<?php
	}

	public static function remove_parents_categories() {
		?>
		<input name="wp-no-base-permalink[remove-parents-categories]" id="remove-parents-categories" type="checkbox" value="1"<?php checked( true, isset( self::$_options['remove-parents-categories'] ) ); ?> />
		<p class="description"><?php _e( 'Remove parents categories of the permalinks leaving a cleanest permalink, in my modest opinion.', self::DOMAIN ); ?></p>
		<?php
	}

	public static function disabled_tag_base() {
		?>
		<input name="wp-no-base-permalink[disabled-tag-base]" id="disabled-tag-base" type="checkbox" value="1"<?php checked( true, isset( self::$_options['disabled-tag-base'] ) ); ?> />
		<p class="description"><?php _e( 'Remove Tag Base of the permalinks.', self::DOMAIN ); ?></p>
		<?php
	}

	public static function old_tags_redirect() {
		?>
		<input name="wp-no-base-permalink[old-tag-redirect]" id="old-tag-redirect" type="text" value="<?php self::_array_to_string( 'old-tag-redirect' ); ?>" class="regular-text code" />
		<p class="description"><?php _e( 'Redirect tag base, by default add the <code>\'tag\'</code> base. For other tag base separated by <code>, </code>.', self::DOMAIN ); ?></p>
		<?php
	}

	private static function _array_to_string( $element ) {
		$string = '';
		if ( isset( self::$_options[ $element ] ) )
			$string = implode( ', ', ( array ) self::$_options[ $element ] );

		echo esc_attr( $string );
	}

	private static function _update_options( $current ) {
		$update   = array();
		$defaults = array(
			'disabled-category-base',
			'old-category-redirect',
			'remove-parents-categories',
			'old-tag-redirect',
			'disabled-tag-base',
		);

		foreach ( $defaults as $option ) {
			if ( empty( $current[ $option ] ) )
				continue;

			switch ( $option ) {
				case 'old-category-redirect':
				case 'old-tag-redirect':
					if ( $redirect = explode( ',', $current[ $option ] ) ) {
						foreach ( $redirect as $k => &$r ) {
							$r = trim( trim( $r ), '/' );
							if ( '' === $r || ( 'old-category-redirect' === $option && 'category' === $r ) || ( 'old-tag-redirect' === $option && 'tag' === $r ) )
								unset( $redirect[ $k ] );
						}

						if ( count( $redirect ) )
							$update[ $option ] = $redirect;
					}
					break;

				case 'disabled-category-base':
				case 'remove-parents-categories':
				case 'disabled-tag-base':
					if ( is_numeric( $current[ $option ] ) && '1' === $current[ $option ] )
						$update[ $option ] = $current[ $option ];
					break;
			}
		}

		return count( $update ) ? $update : false;
	}
}
endif;
