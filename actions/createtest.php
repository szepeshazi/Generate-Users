<?php
/* 
 * Generates test users for your Elgg installation
 * 
 * WARNING!
 * This plugin should never, under any circumstances, be used on a live site. Please refer to the manual (docs/manual.pdf for details)
 * 
 */
	
	require_once($CONFIG->pluginspath . "hu_skawa_genusers/common/common.php");
	global $CONFIG;

// Only logged in administrators are allowed to run this script
	admin_gatekeeper();
	action_gatekeeper();

	$generated_user_count=search_generated_users(true);
	$input_variables = array (  '$num_of_users' => array ( 'form_variable' => '' , 'value' => $generated_user_count, 'required' => ''), 
								'$jmeter_onlineusers' => array ( 'form_variable' => 'jmeter_onlineusers' , 'value' => '', 'required' => 'range(1,$input_variables["\$num_of_users"]["value"])'), 
								'$default_password' => array ( 'form_variable' => 'defaultpassword' , 'value' => '', 'required' => 'regexp(/^[\w]{6,10}$/)'),
								'$jmeter_rampup' => array ( 'form_variable' => 'jmeter_rampup' , 'value' => '', 'required' => 'range(1,10000)'), 
								'$jmeter_testlength' => array ( 'form_variable' => 'jmeter_testlength' , 'value' => '', 'required' => 'range(5,50000)'), 
								'$jmeter_minpause' => array ( 'form_variable' => 'jmeter_minpause' , 'value' => '', 'required' => 'range(1,300))'),
								'$jmeter_maxpause' => array ( 'form_variable' => 'jmeter_maxpause' , 'value' => '', 'required' => 'range($input_variables["\$jmeter_minpause"]["value"],600)'));

	foreach($input_variables as $variable=>$properties) {
		if ($properties["form_variable"]!='') {
			$input_variables[$variable]['value']=get_input($properties["form_variable"]);
		}
	}

	$failed_variable=validate_form_input($input_variables);
	if ($failed_variable !== null) {
		register_error(elgg_echo('errors:'.ltrim($failed_variable,'$')));
		forward($_SERVER['HTTP_REFERER']);
	}

// Passed input validation
	create_jmeter_test($input_variables);
	forward($_SERVER['HTTP_REFERER']);

?>
