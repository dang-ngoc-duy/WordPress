<?php
/**
 * Contents header section field data.
 *
 * @since 3.0.0
 */
class Fixedtoc_Field_Contents_Header_Section_Data extends Fixedtoc_Field_Section_Data {
	
	/*
	 * Create section data.
	 *
	 * @since 3.0.0
	 * @access protected
	 */
	protected function create_section_data() {
		$this->title();
		$this->font_family();
		$this->customize_font_family();
		$this->font_bold();
	}
	
	/*
	 * Title.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function title() {
		$this->section_data['contents_header_title'] = array(
			'name' 								=> 'contents_header_title',
			'label' 							=> __( 'Title', 'fixedtoc' ),
			'default' 						=> 'Contents',
			'type' 								=> 'text',
			'input_attrs'					=> array(
																'class' => 'regular-text'
															),			
			'widget_input_attrs'	=> array(
																'class' => 'widefat'
															),
			'sanitize'						=> 'sanitize_text_field',
			'des'									=> '',
			'transport'						=> 'postMessage'
		);
	}
	
	/*
	 * Font family.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function font_family() {
		$this->section_data['contents_header_font_family'] = array(
			'name' 								=> 'contents_header_font_family',
			'label' 							=> __( 'Font Family', 'fixedtoc' ),
			'default' 						=> 'inherit',
			'type' 								=> 'select',
			'widget_input_attrs'	=> array(
																'class' => 'widefat'
															),
			'choices'							=> $this->obj_field_data->get_font_family_choices(),
			'sanitize'						=> '',
			'des'									=> '',
			'transport'						=> 'postMessage'
		);
	}
	
	/*
	 * Customize font family.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function customize_font_family() {
		$this->section_data['contents_header_customize_font_family'] = array(
			'name' 								=> 'contents_header_customize_font_family',
			'label' 							=> '',
			'default' 						=> '',
			'type' 								=> 'text',
			'input_attrs'					=> array(
																'class' => 'regular-text'
															),
			'widget_input_attrs'	=> array(
																'class' => 'widefat'
															),
			'sanitize'						=> 'sanitize_text_field',
			'des'									=> '',
			'transport'						=> 'postMessage'
		);
	}
	
	/*
	 * Font bold.
	 *
	 * @since 3.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function font_bold() {
		$this->section_data['contents_header_font_bold'] = array(
			'name' 					=> 'contents_header_font_bold',
			'label' 				=> __( 'Font Bold', 'fixedtoc' ),
			'default' 			=> '1',
			'type' 					=> 'checkbox',
			'des'						=> '',
			'transport'			=> 'postMessage'
		);
	}

}