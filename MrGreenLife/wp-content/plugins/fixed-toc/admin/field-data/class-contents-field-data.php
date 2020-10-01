<?php
/**
 * Contents ection field data.
 *
 * @since 3.0.0
 */
class Fixedtoc_Field_Contents_Section_Data extends Fixedtoc_Field_Section_Data {
	
	/*
	 * Create section data.
	 *
	 * @since 3.0.0
	 * @access protected
	 */
	protected function create_section_data() {
		$this->font_size();
		$this->fixed_width();
		$this->fixed_height();
		$this->shape();
		$this->border_width();
		$this->display_in_post();
		$this->float_in_post();
		$this->width_in_post();
		$this->height_in_post();
		$this->col_exp_init();
	}
	
	/*
	 * Font size.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function font_size() {
		$this->section_data['contents_font_size'] = array(
			'name' 								=> 'contents_font_size',
			'label' 							=> __( 'Font Size', 'fixedtoc' ),
			'default' 						=> 12,
			'type' 								=> 'range',
			'input_attrs'					=> array(
																'min' => 10,
																'max' => 20
															),
			'widget_input_attrs'	=> array(
																'class' => 'widefat',
																'min' => 10,
																'max' => 20
															),
			'sanitize'						=> 'absint',
			'transport'						=> 'postMessage'
		);
	}
	
	/*
	 * Width for fixed postion.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function fixed_width() {
		$this->section_data['contents_fixed_width'] = array(
			'name' 					=> 'contents_fixed_width',
			'label' 				=> __( 'Width', 'fixedtoc' ),
			'default' 			=> 250,
			'type' 					=> 'number',
			'input_attrs'		=> array(
													'class' => 'small-text'
												),
			'sanitize'			=> 'absint',
			'des'						=> __( 'When the TOC is fixed to the post.<br>Unit: px.<br>Empty means auto calculate the width.', 'fixedtoc' ),
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Height for fixed position.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function fixed_height() {
		$this->section_data['contents_fixed_height'] = array(
			'name' 					=> 'contents_fixed_height',
			'label' 				=> __( 'Height', 'fixedtoc' ),
			'default' 			=> '',
			'type' 					=> 'number',
			'input_attrs'		=> array(
													'class' => 'small-text'
												),
			'sanitize'			=> 'absint',
			'des'						=> __( 'When the TOC is fixed to the post.<br>Unit: px.<br>Empty means auto calculate the height.', 'fixedtoc' ),
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Shape.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function shape() {
		$this->section_data['contents_shape'] = array(
			'name' 					=> 'contents_shape',
			'label' 				=> __( 'Shape', 'fixedtoc' ),
			'default' 			=> 'square',
			'type' 					=> 'select',
			'choices'				=> $this->obj_field_data->get_shape_choices(),
			'sanitize'			=> '',
			'des'						=> '',
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Border width.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function border_width() {
		$this->section_data['contents_border_width'] = array(
			'name' 					=> 'contents_border_width',
			'label' 				=> __( 'Border', 'fixedtoc' ),
			'default' 			=> 'medium',
			'type' 					=> 'select',
			'choices'				=> $this->obj_field_data->get_border_width_choices(),
			'sanitize'			=> '',
			'des'						=> '',
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Display in post
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function display_in_post() {
		$this->section_data['contents_display_in_post'] = array(
			'name' 					=> 'contents_display_in_post',
			'label' 				=> __( 'Display In Post', 'fixedtoc' ),
			'default' 			=> '1',
			'type' 					=> 'checkbox',
			'sanitize'			=> '',
			'transport'		=> 'refresh',
			'des'						=> __( 'Display at the top of the post.<br>It doesn\'t work if you have unchecked the \'Display in Widget\' option.', 'fixedtoc' ),
			'meta_des'			=> __( 'Display at the top of the post or insert the shortcode [toc] anywhere. <a href="https://codex.wordpress.org/Shortcode" target="_blank">What is shortcode?</a><br>Make sure that you have unchecked the \'Display in Widget\' option.', 'fixedtoc' )
		);
	}
	
	/*
	 * Float in post
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function float_in_post() {
		$this->section_data['contents_float_in_post'] = array(
			'name' 					=> 'contents_float_in_post',
			'label' 				=> __( 'Float In Post', 'fixedtoc' ),
			'default' 			=> 'right',
			'type' 					=> 'radio',
			'choices'				=> array(
				'left' => __( 'Float to left', 'fixedtoc' ),
				'right' => __( 'Float to right', 'fixedtoc' ),
				'none' => __( 'None', 'fixedtoc' )
			),
			'transport'		=> 'refresh'
		);
	}
	
	/*
	 * Width in post.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function width_in_post() {
		$this->section_data['contents_width_in_post'] = array(
			'name' 					=> 'contents_width_in_post',
			'label' 				=> __( 'Width In Post', 'fixedtoc' ),
			'default' 			=> 250,
			'type' 					=> 'number',
			'input_attrs'		=> array(
													'class' => 'small-text'
												),
			'sanitize'			=> 'absint',
			'des'						=> __( 'When the TOC displays in the post.<br>Unit: px.<br>Empty means auto calculate the width.', 'fixedtoc' ),
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Height in post.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function height_in_post() {
		$this->section_data['contents_height_in_post'] = array(
			'name' 					=> 'contents_height_in_post',
			'label' 				=> __( 'Height In Post', 'fixedtoc' ),
			'default' 			=> '',
			'type' 					=> 'number',
			'input_attrs'		=> array(
													'class' => 'small-text'
												),
			'sanitize'			=> 'absint',
			'des'						=> __( 'When the TOC displays in the post.<br>Unit: px.<br>Empty means auto calculate the height.', 'fixedtoc' ),
			'transport'			=> 'postMessage'
		);
	}
	
	/*
	 * Collapse/expand in initial state.
	 *
	 * @since 3.1.4
	 * @access private
	 *
	 * @return void
	 */
	private function col_exp_init() {
		$this->section_data['contents_col_exp_init'] = array(
			'name' 					=> 'contents_col_exp_init',
			'label' 				=> __( 'Collapsing For Initiation', 'fixedtoc' ),
			'default' 			=> '',
			'type' 					=> 'checkbox',
			'sanitize'			=> '',
			'des'						=> __( 'Check or uncheck for collapsing or expanding the contents after the page loaded.', 'fixedtoc' ),
			'meta_des'			=> __( 'Available when the TOC displays in the post or in the Widget.', 'fixedtoc' ),
			'transport'			=> 'refresh'
		);
	}

}