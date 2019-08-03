<?php

require_once(get_template_directory() . '/admin/options/fields/select/field_select.php');

class Redux_Options_widget_area_select extends Redux_Options_select {

	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Redux_Options 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent) {
		$this->field = $field;
		$this->value = $value;
		$this->args = $parent->args;

		global $wp_registered_sidebars;

		$this->field['options'][""] = esc_html__('- None -', 'fwrd');
		foreach ( $wp_registered_sidebars as $registered_sidebar ) {
			$this->field['options'][ $registered_sidebar['id'] ] = $registered_sidebar['name'];
		}
	}
}