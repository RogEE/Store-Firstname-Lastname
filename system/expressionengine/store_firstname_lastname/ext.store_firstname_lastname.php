<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Store: Firstname Lastname Extension
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Extension
 * @author		Michael Rog
 * @link		http://rog.ee
 */

class Store_firstname_lastname_ext {
	
	public $settings 		= array();
	public $description		= 'Combines the first two Order Fields in the Store cart (order_custom1 & order_custom2) into the billing_name value.';
	public $docs_url		= 'http://rog.ee';
	public $name			= 'Store: Firstname/Lastname';
	public $settings_exist	= 'n';
	public $version			= '0.1.0';
	
	private $EE;
	
	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings = '')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}
	
	// ----------------------------------------------------------------------
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see http://codeigniter.com/user_guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	public function activate_extension()
	{
		// Setup custom settings in this array.
		$this->settings = array();
		
		$data = array(
			'class'		=> __CLASS__,
			'method'	=> 'process_billing_name',
			'hook'		=> 'store_cart_update_end',
			'settings'	=> serialize($this->settings),
			'version'	=> $this->version,
			'enabled'	=> 'y'
		);

		$this->EE->db->insert('extensions', $data);			
		
	}	

	// ----------------------------------------------------------------------
	
	/**
	 * Process firstname/lastname
	 *
	 * @param 
	 * @return 
	 */
	public function process_billing_name($cart_contents)
	{
		
		if ($this->EE->extensions->last_call)
		{
			$cart_contents = $this->EE->extensions->last_call;
		}
		
		// firstname field
		$firstname = $this->EE->input->post('order_custom1');
	
		// lastname field
		$lastname = $this->EE->input->post('order_custom2');
		
		if ($firstname !== FALSE && $lastname !== FALSE)
		{
		
			$cart_contents["billing_name"] = $firstname . " " . $lastname;
			
			if ($cart_contents["shipping_same_as_billing"])
			{
				$cart_contents["shipping_name"] = $cart_contents["billing_name"];
			}		
		
		}
		
		return $cart_contents;
		
	}

	// ----------------------------------------------------------------------

	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}

	// ----------------------------------------------------------------------

	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		else
		{
			$this->disable_extension();
			$this->activate_extension();
		}
	}	
	
	// ----------------------------------------------------------------------
}

/* End of file ext.store_firstname_lastname.php */
/* Location: /system/expressionengine/third_party/store_firstname_lastname/ext.store_firstname_lastname.php */