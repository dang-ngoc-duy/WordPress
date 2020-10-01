<?php
/**
 * Control the whole frontend.
 *
 * @since 3.0.0
 */
class Fixedtoc_Frontend_Control {
	/**
	 * a data for creating TOC
	 *
	 * @since 3.0.0
	 * @access private
	 *        
	 * @var array
	 */
	private $data = array();
	
	/**
	 * The raw post content.
	 *
	 * @since 3.1.0
	 * @var string
	 */
	private $raw_post_content = '';
	
	/**
	 * Whether multi page.
	 * 
	 * @since 3.1.0
	 * @access private
	 * 
	 * @var bool
	 */
	private $multipage = false;

	/*
	 * Constructor.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'create_data' ), 1 );
	}

	/**
	 * Create data
	 * Condinute to create TOC if data not empty
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function create_data() {
		// Check if is TOC page.
		if ( ! fixedtoc_is_true( 'toc_page' ) ) {
			return;
		}
		
		global $post, $pages, $multipage;
		
		// Check if the $post is defined.
		if ( ! isset( $post ) ) {
			return;
		}
		
		// Get post content.
		setup_postdata( $post );
		if ( ! have_posts() ) {
			return;
		}
		
		$this->raw_post_content = get_the_content();
	
		$this->multipage = (bool) $multipage;
		
		// Create data
		require_once 'data/class-data.php';
		
		if ( $this->is_multipage() ) {
			$this->data = $this->create_multipage_data( $pages );
		} else {
			// Support the Beaver Builder plugin
			if ( method_exists( 'FLBuilderModel', 'is_builder_enabled')  && FLBuilderModel::is_builder_enabled() && method_exists( 'FLBuilder', 'render_content_by_id') ) {
				ob_start();
				FLBuilder::render_content_by_id( $post->ID );
				$single_content = ob_get_clean();
			} else {
				$single_content = $this->raw_post_content;
			}
			$this->data = $this->create_single_data( $single_content );
		}
		
		wp_reset_postdata();
		
		// Check if is empty data.
		if ( empty( $this->data ) ) {
			return;
		}
		
		// Check if is larger than min heading num
		if ( count( $this->data ) < fixedtoc_get_val( 'general_min_headings_num' ) ) {
			return;
		}
		
		// Continue to create TOC if data not empty.
		if ( $this->data ) {
			$GLOBALS['FTOC_HAS_DATA'] = true;
			
			// Define debug mode.
			define( 'FTOC_DEBUG', fixedtoc_get_option( 'developer_debug' ) );
			
			// Create TOC
			$this->create_toc();
		}
	}

	/**
	 * Detect if multi page.
	 * 
	 * @since 3.1.0
	 * @access private
	 * 
	 * @return bool
	 */
	private function is_multipage() {
		return (bool) $this->multipage;
	}
	
	/**
	 * Create data from single page content.
	 * 
	 * @since 3.1.0
	 * @access private
	 * 
	 * @param string $content
	 * @return array|array[]       	
	 */
	private function create_single_data( $content ) {
		$content = apply_filters( 'the_content', $content );
		$obj_data = new Fixedtoc_Data( $content );
		if ( $obj_data->has_matches() ) {
			// Load datum files here
			require_once 'data/abstract-datum.php';
			require_once 'data/class-datum-element.php';
			require_once 'data/class-datum-id.php';
			require_once 'data/class-datum-title.php';
			require_once 'data/class-datum-origin-title.php';
			require_once 'data/class-datum-parent-id.php';
			
			// Add datum object here
			$datum = array( new Fixedtoc_Datum_Origin_Title(), new Fixedtoc_Datum_Title(), new Fixedtoc_Datum_element(), new Fixedtoc_Datum_Id(), new Fixedtoc_Datum_Parent_Id() );
			$obj_data->create_data( $datum );
			
			return $obj_data->get_data();
		}
		
		return array();
	}

	/**
	 * Create a data from multipage content.
	 * 
	 * @since 3.1.0
	 * @access private
	 * 
	 * @param string $multipage_contents
	 * @return array|array[]
	 */
	private function create_multipage_data( $multipage_contents ) {
		if ( empty( $multipage_contents ) || ! is_array( $multipage_contents ) ) {
			return array();
		}
		
		$i = 1;
		$multipage_content = '';
		$multipage_filter_content = '';
		
		foreach ( $multipage_contents as $content ) {
			$content = apply_filters( 'the_content', $content );
			$multipage_content .= $content;
			$multipage_filter_content .= preg_replace( '/(\<h\d)(.*?>.+?\<\/h\d\>)/i', '${1}' . ' data-page="' . $i . '"${2}', $content );
			$i++;
		}
		
		if ( empty( $multipage_content ) || empty( $multipage_filter_content ) ) {
			return array();
		}
		
		// Combine $data;
		$data = $this->create_single_data( $multipage_content );
		if ( $data ) {
			// Create data include page data.
			$obj_data = new Fixedtoc_Data( $multipage_filter_content );
			require_once 'data/class-datum-page.php';
			$obj_datum = array( new Fixedtoc_Datum_page() );
			$obj_data->create_data( $obj_datum);
			$page_data = $obj_data->get_data();
			
			// Merger
			$j = 0;
			$new_data = array();
			foreach ( $data as $datum ) {
				$new_data[] = array_merge( $datum, $page_data[ $j ] );
				$j++;
			}
			
			return $new_data;
		}
		
		return array();
	}

	/**
	 * Add necessary hooks to create TOC.
	 *
	 * @since 3.0.0
	 * @access private
	 *        
	 * @return void
	 */
	private function create_toc() {
		require_once 'html/abstract-element.php';
		require_once 'html/class-dom.php';
		
		if ( ! fixedtoc_is_true( 'in_widget' ) ) {
			add_filter( 'the_content', array( $this, 'create_html' ), 20 );
		}
		add_filter( 'the_content', array( $this, 'filter_post_headings' ), 99999 );
		add_filter( 'the_content', array( $this, 'wrap_postcontent' ), 11 );
		add_filter( 'body_class', array( $this, 'add_body_class' ) );
		add_filter( 'post_class', array( $this, 'add_post_class' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'localize_scripts' ) );
		add_action( 'wp_footer', array( $this, 'hacks' ) );
		
		if ( fixedtoc_is_true( 'in_widget' ) ) {
			add_filter( 'fixedtoc_widget_content', array( $this, 'create_html' ) );
		}
		
		if ( fixedtoc_is_true( 'in_post' ) ) {
			$this->toc_shortcode();
		}
	}

	/**
	 * Create HTML code of TOC.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @param string $content
	 *        	The post content.
	 * @return string
	 */
	public function create_html( $content ) {
		if ( has_shortcode( $this->raw_post_content, 'toc' ) ) {
			return $content;
		}
		
		if ( fixedtoc_is_true( 'in_post' ) ) {
			require_once 'html/class-element-container-outer.php';
			$obj_dom = new Fixedtoc_Dom( new Fixedtoc_Element_Container_outer( $this->data ) );
		} else {
			require_once 'html/class-element-container.php';
			$obj_dom = new Fixedtoc_Dom( new Fixedtoc_Element_Container( $this->data ) );
		}
		
		$content = $obj_dom->get_html() . "\n" . $content;
		return $content;
	}

	/**
	 * Add extra classes to boby.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @param array $classes.        	
	 * @return array
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'has-ftoc';
		return $classes;
	}

	/**
	 * Add extra classes to post.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @param array $classes.        	
	 * @return array
	 */
	public function add_post_class( $classes ) {
		$classes[] = 'post-ftoc';
		
		if ( fixedtoc_is_true( 'in_post' ) ) {
			$classes[] = 'ftwp-in-post';
		}
		
		return $classes;
	}

	/**
	 * Add styles and Javascript.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @return void
	 */
	public function enqueue_scripts() {
		// Enqueue css
		if ( FTOC_DEBUG ) {
			wp_enqueue_style( 'fixedtoc-style', plugins_url( 'assets/css/ftoc.css', __FILE__ ), array() );
		} else {
			wp_enqueue_style( 'fixedtoc-style', plugins_url( 'assets/css/ftoc.min.css', __FILE__ ), array() );
		}
		
		// Inline css
		if ( fixedtoc_is_true( 'in_widget' ) ) {
			add_action( 'fixedtoc_before_widget', array( $this, 'add_inline_style' ) );
		} else {
			$this->add_inline_style();
		}
		
		// Custom css
		$custom_css = fixedtoc_get_val( 'general_css' );
		if ( $custom_css ) {
			wp_add_inline_style( 'fixedtoc-style', wp_strip_all_tags( $custom_css, true ) );
		}
		
		// Enqueue JS
		if ( FTOC_DEBUG ) {
			wp_enqueue_script( 'fixedtoc-js', plugins_url( 'assets/js/ftoc.js', __FILE__ ), array( 'jquery' ), false, true );
		} else {
			wp_enqueue_script( 'fixedtoc-js', plugins_url( 'assets/js/ftoc.min.js', __FILE__ ), array( 'jquery' ), false, true );
		}
	}

	/**
	 * Add inline style.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @return void
	 */
	public function add_inline_style() {
		require_once 'style/class-inline-style.php';
		$obj_style = new Fixedtoc_Inline_Style();
		$compress = FTOC_DEBUG ? false : true;
		$code = $obj_style->get_css( $compress );
		if ( empty( $code ) ) {
			return;
		}
		
		if ( fixedtoc_is_true( 'in_widget' ) ) {
			if ( $code ) {
				echo "\n<style type=\"text/css\" id=\"fixedtoc-style-inline-css\">";
				echo $code;
				echo "</style>\n";
			}
		} else {
			wp_add_inline_style( 'fixedtoc-style', $code );
		}
	}

	/**
	 * Localize scripts.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @return void
	 */
	public function localize_scripts() {
		wp_localize_script( 'fixedtoc-js', 'fixedtocOption', 
				array( 
					'showAdminbar' => is_admin_bar_showing(), 
					'inOutEffect' => fixedtoc_get_val( 'effects_in_out' ), 
					'isNestedList' => fixedtoc_is_true( 'nested_list' ), 
					'isColExpList' => fixedtoc_is_true( 'colexp_list' ), 
					'showColExpIcon' => fixedtoc_is_true( 'show_colexp_icon' ),
					'isAccordionList' => fixedtoc_is_true( 'accordion_list' ), 
					'isQuickMin' => fixedtoc_is_true( 'quick_min' ), 
					'isEscMin' => fixedtoc_is_true( 'esc_min' ), 
					'isEnterMax' => fixedtoc_is_true( 'enter_max' ), 
					'fixedMenu' => fixedtoc_get_val( 'debug_menu_selector' ), 
					'scrollOffset' => fixedtoc_get_val( 'debug_scroll_offset' ), 
					'fixedOffsetX' => fixedtoc_get_val( 'location_horizontal_offset' ), 
					'fixedOffsetY' => fixedtoc_get_val( 'location_vertical_offset' ), 
					'fixedPosition' => fixedtoc_get_val( 'location_fixed_position' ), 
					'contentsFixedHeight' => fixedtoc_get_val( 'contents_fixed_height' ), 
					'inPost' => fixedtoc_is_true( 'in_post' ), 
					'contentsFloatInPost' => fixedtoc_get_val( 'contents_float_in_post' ), 
					'contentsWidthInPost' => fixedtoc_get_val( 'contents_width_in_post' ), 
					'contentsHeightInPost' => fixedtoc_get_val( 'contents_height_in_post' ),
					'contentsColexpInit'	=> fixedtoc_get_val( 'contents_col_exp_init' ),
					'inWidget' => fixedtoc_is_true( 'in_widget' ), 
					'fixedWidget' => fixedtoc_is_true( 'fixed_widget' ), 
					'triggerBorder' => fixedtoc_get_val( 'trigger_border_width' ), 
					'contentsBorder' => fixedtoc_get_val( 'contents_border_width' ), 
					'triggerSize' => fixedtoc_get_val( 'trigger_size' ),
					'debug' => FTOC_DEBUG
				) 
		);
	}

	/**
	 * Filter headings in the post content.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @param string $content
	 *        	The post content.
	 * @return string
	 */
	public function filter_post_headings( $content ) {
		global $page;
		$search = array();
		$replace = array();
		
		foreach ( $this->data as $datum ) {
			if ( $this->is_multipage() && isset( $datum['page'] ) && $page != $datum['page'] ) {
				continue;
			}
						
			$element = $datum['element'];
			$search[] = '/' . preg_quote( $element, '/' ) . '/';
			
			// Remove class attribute
			$new_element = preg_replace( '/ class(\s*)=(\s*)(\"|\')(.*?)(\"|\')/i', '', $element );
			
			// Assign value to $class_attr
			$class_attr = 'ftwp-heading';
			if ( $new_element != $element ) {
				preg_match( '/ class(\s*)=(\s*)(\"|\')(.*?)(\"|\')/i', $element, $match_class );
				$class_attr .= ' ' . trim( $match_class[4] );
			}
			
			// Remove id attribute
			$new_element = preg_replace( '/ id(\s*)=(\s*)(\"|\')(.*?)(\"|\')/i', '', $new_element );
			// Build $replace
			$start = substr( $new_element, 0, 3 );
			$end = substr( $new_element, 3 );
			$end = str_replace( '$', '\$', $end ); // Fix the heading with the $ character.
			$replace[] = $start . ' id="' . esc_attr( $datum['id'] ) . '" class="' . esc_attr( $class_attr ) . '"' . $end;
		}
		
		$content = preg_replace( $search, $replace, $content, 1, $count );
		return $content;
	}

	/**
	 * Wrap the psot content with a div element.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @param string $content
	 *        	The post content.
	 * @return string
	 */
	public function wrap_postcontent( $content ) {
		return '<div id="ftwp-postcontent">' . $content . '</div>';
	}

	/**
	 * Hacks.
	 *
	 * @since 3.0.0
	 * @access public
	 *        
	 * @return void
	 */
	public function hacks() {
		?>
<!--[if lte IE 9]>
			<script>
				(function($) {
					$(document).ready(function() {
						$( '#ftwp-container' ).addClass( 'ftwp-ie9' );
					});
				})(jQuery);
			</script>
		<![endif]-->
<?php
	}

	/**
	 * Add shortcode feature.
	 *
	 * @since 3.1.0
	 * @access private
	 *        
	 * @return void.
	 */
	private function toc_shortcode() {
		require_once 'html/class-element-container-outer.php';
		$obj_toc = new Fixedtoc_Dom( new Fixedtoc_Element_Container_outer( $this->data ) );
		
		require_once 'features/class-shortcode.php';
		new Fixedtoc_Shortcode( $obj_toc );
	}

}