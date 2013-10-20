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
 * Template Overview Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Judd Lyon
 * @link		http://juddlyon.com
 */

$plugin_info = array(
	'pi_name'		=> 'Templates Overview',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Judd Lyon',
	'pi_author_url'	=> 'http://juddlyon.com',
	'pi_description'=> 'Outputs template settings in a table for quick debugging',
	'pi_usage'		=> Templates_overview::usage()
);


class Templates_overview {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();

		$this->EE->load->library('table');

		// used for table headings
		$column_labels = array('ID', 'group', 'name', 'edit date', 'PHP', 'parse stage', 'type', 'save as file', 'cache', 'HTTP auth');

		// cols for query
		$fields = array('template_id', 'template_groups.group_name', 'template_name', 'edit_date', 'allow_php', 'php_parse_location', 'template_type', 'save_template_file', 'cache', 'enable_http_auth');
	
	    	// MSM check
	    	if ($this->EE->config->item('multiple_sites_enabled'))
	    	{
			array_unshift($fields, 'sites.site_label');	
			array_unshift($column_labels, 'site');	  			  
	    	}

		$get_templates = $this->EE->db
								->select($fields)
								->from('templates')
								->join('template_groups', 'templates.group_id = template_groups.group_id')
								->join('sites', 'templates.site_id = sites.site_id')								
								->get();		

		// table library output template tweaks
		$table_template = array(
			'table_open' => '<table style="width: 66%; margin: 20px auto; font-family: sans-serif; border: 1px solid #eee; border-collapse: collapse; font-size: 12px;">',
			'heading_cell_start'  => '<th style="padding: 5px; border: 1px solid #eee;">',
			'row_start' => '<tr style="border: 1px solid #eee;">',
			'cell_start' => '<td style="padding: 5px; border: 1px solid #eee; text-align: center;">',		
			'row_alt_start' => '<tr style="border: 1px solid #eee; background-color: #eee;">',
			'cell_alt_start' => '<td style="padding: 5px; border: 1px solid #eee; font-size: 12px; text-align: center;">'
             );
		
		$this->EE->table->set_template($table_template);

		$this->EE->table->set_caption('<strong style="display: block; text-align: left; padding: 10px; font-size: 16px;">Templates Overview</strong>');

		$this->EE->table->set_heading($column_labels);								

		$results = $get_templates->result_array();

		// callback to modify query result
		function format_results(&$value, $key)
		{
			if ($key == 'edit_date') 
			{
				$value = date('Y-m-d H:i:s', $value);
			}

			switch ($value) {
				case 'n':
					$value = 'no';
					break;
				case 'y':
					$value = 'yes';
					break;
				case 'o':
					$value = 'output';
					break;
				case 'i':
					$value = 'input';
					break;
			}
		}

		array_walk_recursive($results, 'format_results');

		// build HTML table from results array
		$output = $this->EE->table->generate($results);

		// send output to the template
		$this->return_data = $output;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>
{exp:templates_overview}

Outputs:

- site name (MSM only)
- template ID
- group
- name
- edit date
- PHP
- parse stage
- type
- save as file
- cache
- HTTP auth

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.templates_overview.php */
/* Location: /system/expressionengine/third_party/templates_overview/pi.templates_overview.php */