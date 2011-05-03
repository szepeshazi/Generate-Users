<?php

	function search_generated_users($count = false, $offset = 0, $limit = 10)
	{
		global $CONFIG;
		
		if ($count) {
			$query = "SELECT count(e.guid) as total ";
		} else {
			$query = "SELECT e.* "; 
		}

		$access = get_access_sql_suffix("e");
		
		$order_by = "e.time_created desc";

		$query .= "from {$CONFIG->dbprefix}entities e join {$CONFIG->dbprefix}users_entity u on e.guid=u.guid ";
		$query .= "join {$CONFIG->dbprefix}metadata m on e.guid=m.entity_guid join {$CONFIG->dbprefix}metastrings s on m.name_id=s.id where ";
		$query .= " s.string = 'hu_skawa_genusers_generated' and ";
		$query .= " $access";
		
		$query .= " order by $order_by "; // Add order and limit
		if ($limit) $query .= " limit $offset, $limit"; // Add order and limit

		if (!$count) {
			return get_data($query, "entity_row_to_elggstar");
		} else {
			if ($count = get_data_row($query)) {
				return $count->total;
			}
		}
		return false;

	}
	
	function validate_form_input($input_variables) {
		$failed_variable=null;

		foreach($input_variables as $variable=>$properties) {
			if (isset($properties['required']) and strlen($properties['required'])>0) {
				if (strpos($properties['required'],'range(') !== false) {
// Check if an input variable is in a certain numeric range
					$tempvalue=rtrim(ltrim($properties['required'],'range('),')');
					$range_values=explode(',',$tempvalue);
					$lower=$range_values[0];
					eval("\$lower = $lower;");
					$higher=$range_values[1];
					eval("\$higher = $higher;");
					$value=$properties['value'];
					eval("\$valid_input=(\"$value\" >= \"$lower\" and \"$value\" <= \"$higher\" );");
				} else {
// Check if an input variable matches the given regular expression
					$tempvalue=rtrim(ltrim($properties['required'],'regexp('),')');
					$valid_input=preg_match($tempvalue,$properties['value'],$matches);
				}
// If an input variable failed validation, return the first failed variable from the function
				if (!$valid_input and $failed_variable==null) {  
					$failed_variable=$variable;
				}
			}
		}
		
		return $failed_variable;
	}

	function write_progress($progress) {
		global $CONFIG;
		$min_percent_for_estimate=3;

		$total_weight=0;
		$total_progress=0;
		foreach ($progress as $key => $value) {
			if ($key!='progress_total' and $value['enabled']==true) {
				$total_weight+=$value['weight'];
				$total_progress+=$value['percent']*$value['weight'];
			}
		}
		$progress['progress_total']['percent']=intval($total_progress/$total_weight);
		$elapsed=time()-$progress['progress_total']['starttime'];
		$progress['progress_total']['elapsed']=gmdate("H:i:s",$elapsed);
		if ($progress['progress_total']['percent']>=$min_percent_for_estimate) {
			$progress['progress_total']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_total']['percent'])-$elapsed);
		}	
		$output=json_encode($progress);
		$outfileName=$CONFIG->pluginspath . "hu_skawa_genusers/actions/progress.json";
		file_put_contents($outfileName,$output);
	}
	
	function create_jmeter_test($input_variables) {

		global $CONFIG;
	
		$startTimeStamp=microtime(true);

		foreach($input_variables as $variable=>$properties) {
			$value=$properties["value"];
			eval(eval("return \$variable;"). "= \"$value\";");
		}

		$generated_id = get_metastring_id('hu_skawa_genusers_generated');
		$query="SELECT MIN(entity_guid) min_guid, MAX(entity_guid) max_guid FROM {$CONFIG->dbprefix}metadata WHERE name_id=".$generated_id;
		$row=get_data_row($query);
		$first_guid=$row->min_guid;
		$last_guid=$row->max_guid;
	
		$query="SELECT username FROM {$CONFIG->dbprefix}users_entity WHERE guid=".$first_guid;
		$row=get_data_row($query);
		$first_username=$row->username;
		$user_prefix=substr($first_username,0,strlen($first_username)-1);

		$userid_array=range(1,$last_guid-$first_guid+1);
		shuffle($userid_array);

// create list of user names and passwords for test users
		$users_content="";
		$users_content.=$user_prefix.$userid_array[0].",".$default_password;
		for ($i=1; $i<$jmeter_onlineusers; $i++) {
			$users_content.="\r\n".$user_prefix.$userid_array[$i].",".$default_password;
		}
		$usersfileName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/users.csv";
		file_put_contents($usersfileName,$users_content);
		
// create list of user guid's for mail sendings		
		$mailto_content="";
		$mailto_content.=$user_prefix.$userid_array[0].",".($first_guid+$userid_array[0]-1);
		for ($i=1; $i<count($userid_array); $i++) {
			$mailto_content.="\r\n".$user_prefix.$userid_array[$i].",".($first_guid+$userid_array[$i]-1);
		}
		$mailtofileName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/mailto.csv";
		file_put_contents($mailtofileName,$mailto_content);
		
// create list of files to upload during test
		$uploadDirName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/uploads/";
		$files_content="";
		$uploadIndex=0;
		if ($dh = opendir($uploadDirName)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($uploadDirName . $file)=='file' && strripos($file, '.jpg')>0) {
					$files_content.=(strlen($files_content)==0?"":"\r\n")."\"".($uploadDirName.$file)."\"";
				}
			}
			closedir($dh);
   		}
		$filescontentfileName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/files.csv";
		file_put_contents($filescontentfileName,$files_content);


// create JMeter test plan form the test template file		
		$templatefileName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/test_template.jmx";
		$test_template = file_get_contents($templatefileName);
		
		$test_template=str_replace('###NUM_OF_THREADS###',$jmeter_onlineusers,$test_template);
		$test_template=str_replace('###RAMP_UP_TIME###',$jmeter_rampup,$test_template);

		$domain_name=$CONFIG->wwwroot;
		$domain_name=str_replace('http://','',$domain_name);
		$domain_name=rtrim($domain_name,'/');
		$test_template=str_replace('###DOMAIN_NAME###',$domain_name,$test_template);
		
		$test_template=str_replace('###TIMER_DELAY###',$jmeter_minpause*1000,$test_template);
		$test_template=str_replace('###TIMER_RANGE###',($jmeter_maxpause-$jmeter_minpause)*1000,$test_template);
		$test_template=str_replace('###TEST_RUNTIME###',$jmeter_testlength,$test_template);
		
		$test_template=str_replace('###ENABLE_DASHBOARD###',(get_input('jmeter_dashboard')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_READMESSAGES###',(get_input('jmeter_messages')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_WRITEMESSAGE###',(get_input('jmeter_sendmessage')?'true':'false'),$test_template);
		
		$test_template=str_replace('###ENABLE_VIEWFRIENDS###',(get_input('jmeter_friends')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_VIEWFRIENDSOF###',(get_input('jmeter_friendsof')?'true':'false'),$test_template);
		
		$test_template=str_replace('###ENABLE_VIEWPROFILE###',(get_input('jmeter_profile')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_UDATEPROFILE###',(get_input('jmeter_profileupdate')?'true':'false'),$test_template);
		
		$test_template=str_replace('###ENABLE_VIEWBLOG###',(get_input('jmeter_blogs')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_COMMENTBLOG###',(get_input('jmeter_blogcomment')?'true':'false'),$test_template);
		
		$test_template=str_replace('###ENABLE_VIEWFILE###',(get_input('jmeter_uploads')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_COMMENTFILE###',(get_input('jmeter_uploadcomment')?'true':'false'),$test_template);
		$test_template=str_replace('###ENABLE_UPLOADFILE###',(get_input('jmeter_newupload')?'true':'false'),$test_template);

		$outfileName=$CONFIG->pluginspath . "hu_skawa_genusers/JMeter/elggloadtest.jmx";
		file_put_contents($outfileName,$test_template);

		$endTimeStamp=microtime(true);
		system_message(sprintf(elgg_echo('messages:jmeter:success'), $endTimeStamp-$startTimeStamp));
		
	}

?>
