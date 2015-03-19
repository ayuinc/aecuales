<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Freeform - Freeform State Select Fieldtype
 *
 * ExpressionEngine fieldtype interface licensed for use by EllisLab, Inc.
 *
 * @package		Solspace:Freeform
 * @author		Solspace, Inc.
 * @copyright	Copyright (c) 2008-2015, Solspace, Inc.
 * @link		http://solspace.com/docs/freeform
 * @license		http://www.solspace.com/license_agreement
 * @filesource	freeform/default_fields/freeform_ft.state_select.php
 */

class State_select_freeform_ft extends Freeform_base_ft
{
	public 	$info 	= array(
		'name' 			=> 'State Select',
		'version' 		=> FREEFORM_VERSION,
		'description' 	=> 'A dropdown selection of US states and territories.'
	);

	private $states = array();


	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access	public
	 * @return	null
	 */

	public function __construct()
	{
		parent::__construct();

		$this->info['name'] 		= lang('default_state_name');
		$this->info['description'] 	= lang('default_state_desc');

		// -------------------------------------
		//	parse states
		// -------------------------------------

		$states 	= array_map(
			'trim',
			preg_split(
				'/[\n\r]+/',
				lang('list_of_us_states'),
				-1,
				PREG_SPLIT_NO_EMPTY
			)
		);

		//need matching key => value pairs for the select values to be correct
		//for the output value we are removing the ' (AZ)' code for the value and the 'Arizona' code for the key
		foreach ($states as $key => $value)
		{
			$this->states[
				preg_replace('/[\w|\s]+\(([a-zA-Z\-_]+)\)$/', "$1", $value)
			] = preg_replace('/\s+\([a-zA-Z\-_]+\)$/', '', $value);
		}
	}
	//END __construct


	// --------------------------------------------------------------------

	/**
	 * Display Field
	 *
	 * @access	public
	 * @param	string 	saved data input
	 * @param  	array 	input params from tag
	 * @param 	array 	attribute params from tag
	 * @return	string 	display output
	 */

	public function display_field ($data = '', $params = array(), $attr = array())
	{
		return form_dropdown(
			$this->field_name,
			$this->states,
			(isset($this->states[$data]) ? array($data) : NULL),
			$this->stringify_attributes(array_merge(
				array('id' => 'freeform_' . $this->field_name),
				$attr
			))
		);
	}
	//END display_field


	// --------------------------------------------------------------------

	/**
	 * Display Field Settings
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */

	public function validate ($data)
	{
		$data = trim($data);

		$return = TRUE;

		if ($data != '' AND ! array_key_exists($data, $this->states))
		{
			$return = lang('invalid_state');
		}

		return $return;
	}
	//END validate


	// --------------------------------------------------------------------

	/**
	 * Save Field Data
	 *
	 * @access	public
	 * @param	string 	data to be inserted
	 * @param	int 	form id
	 * @return	string 	data to go into the entry_field
	 */

	public function save ($data)
	{
		$data = trim($data);
		return $this->validate($data) ? $data : '';
	}
	//END save
}
//END class State_select_ft