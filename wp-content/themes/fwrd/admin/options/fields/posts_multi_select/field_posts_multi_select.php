<?php
get_template_part('admin/options/fields/select/field_select');
class Redux_Options_posts_multi_select extends Redux_Options_select {

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
		
        $wp_args = wp_parse_args($this->field['args'], array('numberposts' => '-1'));
		$posts = get_posts($wp_args);
		foreach ($posts as $post) {
			$this->field['options'][$post->ID] = $post->post_title;
		}
		$this->field['multiselect'] = true;
    }
}
