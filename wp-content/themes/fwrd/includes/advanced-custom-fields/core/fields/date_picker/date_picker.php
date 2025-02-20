<?php

class acf_field_date_picker extends acf_field
{
	
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'date_picker';
		$this->label = esc_html__("Date Picker",'acf');
		$this->category = esc_html__("jQuery",'acf');
		$this->defaults = array(
			'date_format' => 'yymmdd',
			'display_format' => 'dd/mm/yy',
			'first_day' => 1, // monday
		);
		
		
		// actions
		add_action('init', array($this, 'init'));
		
		
		// do not delete!
    	parent::__construct();
	}
	
	
	/*
	*  init
	*
	*  This function is run on the 'init' action to set the field's $l10n data. Before the init action, 
	*  access to the $wp_locale variable is not possible.
	*
	*  @type	action (init)
	*  @date	3/09/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	function init()
	{
		global $wp_locale;
		
		$this->l10n = array(
			'closeText'         => esc_html__( 'Done', 'acf' ),
	        'currentText'       => esc_html__( 'Today', 'acf' ),
	        'monthNames'        => array_values( $wp_locale->month ),
	        'monthNamesShort'   => array_values( $wp_locale->month_abbrev ),
	        'monthStatus'       => esc_html__( 'Show a different month', 'acf' ),
	        'dayNames'          => array_values( $wp_locale->weekday ),
	        'dayNamesShort'     => array_values( $wp_locale->weekday_abbrev ),
	        'dayNamesMin'       => array_values( $wp_locale->weekday_initial ),
	        'isRTL'             => isset($wp_locale->is_rtl) ? $wp_locale->is_rtl : false,
		);
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/
	
	function create_field( $field )
	{
		// make sure it's not blank
		if( !$field['date_format'] )
		{
			$field['date_format'] = 'yymmdd';
		}
		if( !$field['display_format'] )
		{
			$field['display_format'] = 'dd/mm/yy';
		}
		

		// html
		echo '<div class="acf-date_picker" data-save_format="' . esc_attr($field['date_format']) . '" data-display_format="' . esc_attr($field['display_format']) . '" data-first_day="' . esc_attr($field['first_day']) . '">';
			echo '<input type="hidden" value="' . esc_attr($field['value']) . '" name="' . esc_attr($field['name']) . '" class="input-alt" />';
			echo '<input type="text" value="" class="input"  />';
		echo '</div>';
	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function create_options( $field )
	{
		// global
		global $wp_locale;
		
		
		// vars
		$key = $field['name'];
	    
	    ?>
<tr class="field_option field_option_<?php echo esc_attr($this->name); ?>">
	<td class="label">
		<label><?php esc_html_e("Save format",'acf'); ?></label>
		<p class="description"><?php esc_html_e("This format will determin the value saved to the database and returned via the API",'acf'); ?></p>
		<p><?php esc_html_e("\"yymmdd\" is the most versatile save format. Read more about",'acf'); ?> <a href="http://docs.jquery.com/UI/Datepicker/formatDate"><?php esc_html_e("jQuery date formats",'acf'); ?></a></p>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields[' .$key.'][date_format]',
			'value'	=>	$field['date_format'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo esc_attr($this->name); ?>">
	<td class="label">
		<label><?php esc_html_e("Display format",'acf'); ?></label>
		<p class="description"><?php esc_html_e("This format will be seen by the user when entering a value",'acf'); ?></p>
		<p><?php esc_html_e("\"dd/mm/yy\" or \"mm/dd/yy\" are the most used display formats. Read more about",'acf'); ?> <a href="http://docs.jquery.com/UI/Datepicker/formatDate" target="_blank"><?php esc_html_e("jQuery date formats",'acf'); ?></a></p>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields[' .$key.'][display_format]',
			'value'	=>	$field['display_format'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo esc_attr($this->name); ?>">
	<td class="label">
		<label for=""><?php esc_html_e("Week Starts On",'acf'); ?></label>
	</td>
	<td>
		<?php 
		
		$choices = array_values( $wp_locale->weekday );
		
		do_action('acf/create_field', array(
			'type'	=>	'select',
			'name'	=>	'fields['.$key.'][first_day]',
			'value'	=>	$field['first_day'],
			'choices'	=>	$choices,
		));
		
		?>
	</td>
</tr>
		<?php
		
	}
	
}

new acf_field_date_picker();

?>