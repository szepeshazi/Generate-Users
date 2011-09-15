<?php
	/**
	 * Elgg automated user generation plugin
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author András Szepesházi
	 * @copyright Skawa 2008
	 */

	function hu_skawa_genusers_init() {

		// Load system configuration
		global $CONFIG;
		elgg_register_page_handler('genusers','hu_skawa_genusers_page_handler');
	}
	
	function hu_skawa_genusers_pagesetup() {
		if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
			global $CONFIG;
			$menu_item = new ElggMenuItem('genusers', elgg_echo('genusers:short'), $CONFIG->wwwroot . 'pg/genusers/');
			elgg_register_menu_item('page', $menu_item);
		}
	}
	
	function hu_skawa_genusers_page_handler($page) {
		global $CONFIG;
		include($CONFIG->pluginspath . "hu_skawa_genusers/index.php"); 
	}
	
	global $CONFIG;
	
	elgg_register_event_handler('init','system','hu_skawa_genusers_init');
	elgg_register_event_handler('pagesetup','system','hu_skawa_genusers_pagesetup');
	
	$access = 'admin';
	elgg_register_action("hu_skawa_genusers/generate", $CONFIG->pluginspath . "hu_skawa_genusers/actions/generate.php", $access);
	elgg_register_action("hu_skawa_genusers/delete", $CONFIG->pluginspath . "hu_skawa_genusers/actions/delete.php", $access);
	elgg_register_action("hu_skawa_genusers/createtest", $CONFIG->pluginspath . "hu_skawa_genusers/actions/createtest.php", $access);
	
?>