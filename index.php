<?php
	/**
	 * Elgg automated user generation plugin
	 * 
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author András Szepesházi
	 * @copyright Skawa 2008
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	require_once($CONFIG->pluginspath . "hu_skawa_genusers/common/common.php");

	extend_view('metatags', 'jsinclude');

	set_view_location('input/button', $CONFIG->pluginspath . 'hu_skawa_genusers/views/mod/');
	set_view_location('input/radio', $CONFIG->pluginspath . 'hu_skawa_genusers/views/mod/');
	
	admin_gatekeeper();
	set_context('admin');
	
	// Set admin user for user block
	set_page_owner($_SESSION['guid']);
	
	$generated_user_count=search_generated_users(true);
	
	$title = elgg_view_title(elgg_echo('genusers'));
	if ($generated_user_count==0) {
		$body .= elgg_view('genpage');	
	} else {
		$body .= elgg_view('deletepage');
	}
	// Display main admin menu
	page_draw(elgg_echo('genusers'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));




?>