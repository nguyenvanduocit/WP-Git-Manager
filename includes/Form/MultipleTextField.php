<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 11:45 AM
 */

namespace WPPM\Form;


class MultipleTextField extends FormField{

	/**
	 * Validates a value against a field.
	 *
	 * @param mixed $value The value to check.
	 *
	 * @return mixed null if the validation failed, sanitized value otherwise.
	 */
	function validate( $value ) {
		return array_values($value);
	}
	/**
	 * Mutate the field arguments so that the value passed is rendered.
	 *
	 * @param array $args
	 * @param mixed $value
	 */
	protected function _set_value( &$args, $value ) {
		$args['value'] = maybe_serialize($value);
	}
	/**
	 * The actual rendering.
	 *
	 * @param array $args
	 */
	protected function _render( $args ) {
		$args   = wp_parse_args( $args,
			array(
				'value'    => '',
				'desc_pos' => 'after',
				'extra'    => array( 'class' => 'regular-text' )
			) );
		$values = maybe_unserialize( $args[ 'value' ] );
		$opts = '';
		foreach ( $values as $index => $group ) {
			$group_id = 'group_'.$index;
			$opts .='<div id="'.$group_id.'">';
			if(is_array($group)) {
				foreach ( $group as $key => $value ) {
					$single_input = FormField::_input_gen( array(
						'name'     => $args[ 'name' ] . '[' . $index . '][' . $key . ']',
						'type'     => 'text',
						'value'    => $value,
						'desc'     => $key,
						'desc_pos' => 'before',
					) );
					$opts .= '<br>' . str_replace( FormBuilder::TOKEN, $single_input, $args[ 'wrap_each' ] );
				}
				$opts .= '<button class="btnRemoveRule" data-id="' . $group_id . '">Remove</button></div>';
			}
		}
		$opts.='<button class="btnAddRule" data-count="'.count($values).'">Add</button>';
		return FormField::add_desc( $opts, $args[ 'desc' ], $args[ 'desc_pos' ] );
	}
}