<?php
get_template_part('admin/options/fields/select/field_select');
class Redux_Options_post_type_select extends Redux_Options_select {

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
		
        $wp_args = wp_parse_args($this->field['args'], array('public' => true));
		$this->field['options'] = get_post_types($wp_args, 'names');
    }
}
