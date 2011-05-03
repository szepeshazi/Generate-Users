<?php

/* 
 * Generates test users for your Elgg installation
 * 
 * WARNING!
 * This plugin should never, under any circumstances, be used on a live site. Please refer to the manual (docs/manual.pdf for details)
 * 
 */
	
	global $CONFIG;

	require_once($CONFIG->pluginspath . "hu_skawa_genusers/common/common.php");

// Only logged in administrators are allowed to run this script
	admin_gatekeeper();
	action_gatekeeper();

// Input variable and validation definitions for user parameters
	$input_variables = array (  '$num_of_users' => array ( 'form_variable' => 'numofusers' , 'value' => '', 'required' => 'range(1,1000000)'), 
								'$num_of_friends' => array ( 'form_variable' => 'numoffriends' , 'value' => '', 'required' => 'range(0,$input_variables["\$num_of_users"]["value"]-1)'), 
								'$user_prefix' => array ( 'form_variable' => 'userprefix' , 'value' => '', 'required' => 'regexp(/^[a-zA-Z][\w]{3,7}$/)'), 
								'$default_password' => array ( 'form_variable' => 'defaultpassword' , 'value' => '', 'required' => 'regexp(/^[\w]{6,10}$/)'),
								'$num_of_emails' => array ( 'form_variable' => 'numofemails' , 'value' => '', 'required' => 'range(0,1000)'), 
								'$num_of_blogs' => array ( 'form_variable' => 'numofblogs' , 'value' => '', 'required' => 'range(0,100)'), 
								'$num_of_blogcomments' => array ( 'form_variable' => 'numofblogcomments' , 'value' => '', 'required' => 'range(0,100)'), 
								'$num_of_uploads' => array ( 'form_variable' => 'numofuploads' , 'value' => '', 'required' => 'range(0,100)'), 
								'$num_of_uploadcomments' => array ( 'form_variable' => 'numofuploadcomments' , 'value' => '', 'required' => 'range(0,100)'));

// Input variable and validation definitions for jmeter test parameters - parse only if jmeter test generation was requested
	if (get_input('dojmeter'))	{
		$input_variables = array_merge($input_variables, array (  	'$jmeter_onlineusers' => array ( 'form_variable' => 'jmeter_onlineusers' , 'value' => '', 'required' => 'range(1,$input_variables["\$num_of_users"]["value"])'), 
																	'$jmeter_rampup' => array ( 'form_variable' => 'jmeter_rampup' , 'value' => '', 'required' => 'range(1,10000)'), 
																	'$jmeter_testlength' => array ( 'form_variable' => 'jmeter_testlength' , 'value' => '', 'required' => 'range(5,50000)'), 
																	'$jmeter_minpause' => array ( 'form_variable' => 'jmeter_minpause' , 'value' => '', 'required' => 'range(1,300))'),
																	'$jmeter_maxpause' => array ( 'form_variable' => 'jmeter_maxpause' , 'value' => '', 'required' => 'range($input_variables["\$jmeter_minpause"]["value"],600)')));
	}							

	foreach($input_variables as $variable=>$properties) {
		$input_variables[$variable]['value']=get_input($properties["form_variable"]);
	}

	$failed_variable=validate_form_input($input_variables);

	foreach($input_variables as $variable=>$properties) {
		$value=$properties["value"];
		eval(eval("return \$variable;"). "= \"$value\";");
	}
	

// Input validation passed, start the generation process

// Progress array for GUI feedback during generation process
	$min_percent_for_estimate=2;
	$progress = array ( 
					'progress_users' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_friends' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 3, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_icons' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 700, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_messages' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_blogs' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_blogcomments' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 2, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_uploads' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 1000, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_uploadcomments' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 2, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'progress_total' => array ( 'enabled' => true, 'percent' => 0, 'starttime' => 0, 'elapsed' => '00:00:00', 'estimated' => '&nbsp;' ),
					'status' => array ( 'enabled' => false, 'currentstatus' => 'ok', 'message' => '')
					
				);

	if ($failed_variable !== null) {
		$progress['status']['currentstatus']='failed';
		$progress['status']['message']=elgg_echo('errors:'.ltrim($failed_variable,'$'));
	}

	$progress['progress_total']['starttime']=microtime(true);

	if ($num_of_friends==0) $progress['progress_friends']['enabled']=false;
	else $progress['progress_friends']['weight']=intval($progress['progress_friends']['weight']*$num_of_friends);
	if (!get_input('createicons')) $progress['progress_icons']['enabled']=false;
	if ($num_of_emails==0) $progress['progress_messages']['enabled']=false;
	else $progress['progress_messages']['weight']=intval($progress['progress_messages']['weight']*$num_of_emails);
	if ($num_of_blogs==0) $progress['progress_blogs']['enabled']=false;
	else $progress['progress_blogs']['weight']=intval($progress['progress_blogs']['weight']*$num_of_blogs);
	if ($num_of_blogcomments==0) $progress['progress_blogcomments']['enabled']=false;
	else $progress['progress_blogcomments']['weight']=intval($progress['progress_blogcomments']['weight']*$num_of_blogcomments);
	if ($num_of_uploads==0) $progress['progress_uploads']['enabled']=false;
	else $progress['progress_uploads']['weight']=intval($progress['progress_uploads']['weight']*$num_of_uploads);
	if ($num_of_uploadcomments==0) $progress['progress_uploadcomments']['enabled']=false;
	else $progress['progress_uploadcomments']['weight']=intval($progress['progress_uploadcomments']['weight']*$num_of_uploadcomments);
	
	write_progress($progress);

	if ($failed_variable !== null) die;
	
// Save all records that will be inserted in the metastrings table - when deleting previously generated users, these records will be cleaned up	
	$metastrings_cleanup=array();

// Start logging	
	$logfile_path=$CONFIG->pluginspath . "hu_skawa_genusers/log/log.txt";
	file_put_contents($logfile_path,date(DATE_RFC822).' User generation started.'."\r\n");
	$startTimeStamp=microtime(true);

// Create database backup if exec() is enabled and a valid path to mysqldump is provided by the user
	$disabled_functions=ini_get("disabled_functions");
	if (strpos($disabled_functions,"exec")!==false) {
		file_put_contents($logfile_path,date(DATE_RFC822).' Unable to create database backup via mysqldump. exec() function is disabled'."\r\n",FILE_APPEND);
	} else {
		$mysqldump_path = get_input('path_to_mysqldump','');
		$abs_path = '';
		if (file_exists($mysqldump_path.'mysqldump')) {
			$abs_path = $mysqldump_path.'mysqldump';
		} else if (file_exists($mysqldump_path.'mysqldump.exe')) {
			$abs_path = $mysqldump_path.'mysqldump.exe';
		} else {
			$abs_path = 'mysqldump';
		}
		file_put_contents($logfile_path,date(DATE_RFC822).' Creating database backup via mysqldump.'."\r\n",FILE_APPEND);
		$sqlfile=$CONFIG->pluginspath . "hu_skawa_genusers/log/elggdbdump.sql";
		$command = '"' .$abs_path. '" --opt --host='.$CONFIG->dbhost.' --user='.$CONFIG->dbuser.' --password='.$CONFIG->dbpass.' '.$CONFIG->dbname.' > ' . $sqlfile;
		file_put_contents($logfile_path,date(DATE_RFC822).' Executing command: '.$command."\r\n",FILE_APPEND);
		if (strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN') {
			$command = '"' . $command . '"';
		}
		exec($command);
		file_put_contents($logfile_path,date(DATE_RFC822).' Database backup successfully created.'."\r\n",FILE_APPEND);
	}

// Read sensible user names from resource files
	$fileName=$CONFIG->pluginspath . "hu_skawa_genusers/resources/lastnames.txt";
	$allFamilyNames = file_get_contents($fileName);
	$familyNames=explode("\n",$allFamilyNames);
	$fileName=$CONFIG->pluginspath . "hu_skawa_genusers/resources/firstnames.txt";
	$allFirstNames = file_get_contents($fileName);
	$firstNames=explode("\n",$allFirstNames);
	$numOfFirstNames=count($firstNames);
	$numOfFamilyNames=count($familyNames);
	
// Check the last record among entities, users will be generated with ids starting from that id
	$lastEntityRow=get_data_row("SELECT MAX(guid) guid from {$CONFIG->dbprefix}entities");
	$lastEntityId=$lastEntityRow->guid;

// Define how many records should be buffered for one 'INSERT' statement -> TODO: automatically find value for the ideal buffer size (based on Mysql max_allowed_packet)
	$insertBufferSize=1000;
	
// Check for existing required metastrings for user registration, create them if necessary
	if (!$email_id = get_metastring_id('email')) $email_id=add_metastring('email');
	if (!$value_1_id = get_metastring_id('1')) $value_1_id=add_metastring('1');
	if (!$notification_id = get_metastring_id('notification:method:email')) $notification_id=add_metastring('notification:method:email');
	if (!$validated_id = get_metastring_id('validated')) $validated_id=add_metastring('validated');
	if (!$validated_method_id = get_metastring_id('validated_method')) $validated_method_id=add_metastring('validated_method');
	if (!$generated_id = get_metastring_id('hu_skawa_genusers_generated')) { 
		$generated_id=add_metastring('hu_skawa_genusers_generated');
		$metastrings_cleanup[]=$generated_id;
	}

// New elggusers_entity fields
	$salt=generate_random_cleartext_password();
	$password=md5($default_password . $salt);
	$prev_last_action=0;
	$last_login=0;
	$prev_last_login=0;

// New elggentities fields
	$type = 'user';
	$subtype = 0;
	$owner_guid = $_SESSION['user']->getGUID(); // owner is current user, i.e. the administrator
	$site_guid = 1;  //TODO: fix this for multiple sites in an Elgg database
	$containter_guid = $owner_guid;
	$access_id = ACCESS_PUBLIC; // everything we create is public
	$enabled = 'yes';

	$createEntityStatement = '';
	$createUserStatement = '';
	$createMetadataStatement = '';
	$bufferCounter = 0;

// Generate users with bulk database inserts
	$progress['progress_users']['starttime'] = microtime(true);
	for ($i = 1; $i <= $num_of_users; $i++){
		$user_name = trim($firstNames[mt_rand(0,$numOfFirstNames-1)])." ".trim($familyNames[mt_rand(0,$numOfFamilyNames-1)]);  // each user will have a random first name and family name selected from the resource files
		$email_address = $user_prefix.$i."@hu-skawa-genusers.com"; //TODO: maybe change the hardcoded e-mail address to a form parameter
		$action_time = time();
		$createEntityStatement.= '(' . ($lastEntityId+$i) . ',"' . $type. '",' . $subtype. ',' . $owner_guid. ',' . $site_guid. ',' . $containter_guid. ',' . $access_id. ',' . $action_time. ',' . $action_time. ',' . $action_time. ',"' . $enabled . '"),';
		$createUserStatement.= '(' . ($lastEntityId+$i) . ',"' . $user_name . '","' . ($user_prefix.$i) . '","' . $password. '","' . $salt . '","' . $email_address. '","","","no","no",' . $action_time. ',' . $prev_last_action. ',' . $last_login. ',' . $prev_last_login . '),';
		$createMetadataStatement.= '(' . ($lastEntityId+$i) . ',' . $notification_id. ',' . $value_1_id. ',"text",' . $owner_guid. ',' . $access_id. ',' . $action_time. ',"yes"),';
		$createMetadataStatement.= '(' . ($lastEntityId+$i) . ',' . $validated_id. ',' . $value_1_id. ',"integer",' . $owner_guid. ',' . $access_id. ',' . $action_time. ',"yes"),';
		$createMetadataStatement.= '(' . ($lastEntityId+$i) . ',' . $validated_method_id. ',' . $email_id. ',"text",' . $owner_guid. ',' . $access_id. ',' . $action_time. ',"yes"),';
		$createMetadataStatement.= '(' . ($lastEntityId+$i) . ',' . $generated_id. ',' . $value_1_id. ',"integer",' . $owner_guid. ',' . $access_id. ',' . $action_time. ',"yes"),';
		if (($bufferCounter++ == $insertBufferSize) or ($i == $num_of_users)) {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}users_entity VALUES ".substr($createUserStatement,0,strlen($createUserStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
			$createEntityStatement = '';
			$createUserStatement = '';
			$createMetadataStatement = '';
			$bufferCounter = 0;
			$progress['progress_users']['percent'] = intval(100*$i/$num_of_users);
			$elapsed = microtime(true)-$progress['progress_users']['starttime'];
			$progress['progress_users']['elapsed'] = gmdate("H:i:s",$elapsed);
			if ($progress['progress_users']['percent'] >= $min_percent_for_estimate) {
				$progress['progress_users']['estimated'] = gmdate("H:i:s",((100*$elapsed)/$progress['progress_users']['percent'])-$elapsed);
			}
			write_progress($progress);
		}
	}

// Create friend relationships between previously generated users
	$relationship = 'friend';
	$createFriendStatement = '';
	$bufferCounter = 0;
	$friend_guids = range($lastEntityId+1,$lastEntityId+$num_of_users);
	$friend_counter = 0;
	if ($num_of_friends > 0) {
		$progress['progress_friends']['starttime'] = microtime(true);
		for ($i = 1; $i <= $num_of_users; $i++) {
			$guid_one = $lastEntityId+$i;
			shuffle($friend_guids);  // TODO: make this more efficient
			$friend_max = mt_rand(0,min($num_of_friends*2,$num_of_users-1));
			for ($j = 0; $j < $friend_max; $j++) {
				if ($guid_one != $friend_guids[$j]) { //don't make friends with himself
					$guid_two = $friend_guids[$j];
					$createFriendStatement.= '(' . $guid_one. ',"' . $relationship. '",' . $guid_two . '),';
					$friend_counter++;
					if ($bufferCounter++ == $insertBufferSize) {
						$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entity_relationships (guid_one, relationship, guid_two) VALUES ".substr($createFriendStatement,0,strlen($createFriendStatement)-1));
						$createFriendStatement = '';
						$bufferCounter = 0;
						$progress_percent = intval(100*$friend_counter/($num_of_users*$num_of_friends));
						if ($progress_percent > 100) $progress_percent = 100;
						$progress['progress_friends']['percent'] = $progress_percent;
						$elapsed = microtime(true)-$progress['progress_friends']['starttime'];
						$progress['progress_friends']['elapsed'] = gmdate("H:i:s",$elapsed);
						if ($progress['progress_friends']['percent'] >= $min_percent_for_estimate) {
							$progress['progress_friends']['estimated'] = gmdate("H:i:s",((100*$elapsed)/$progress['progress_friends']['percent'])-$elapsed);
						}
						write_progress($progress);
					}
				}
			}
		}
// Flush any pending friend relationships from insert buffer 		
		if ($bufferCounter > 0) {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entity_relationships (guid_one, relationship, guid_two) VALUES ".substr($createFriendStatement,0,strlen($createFriendStatement)-1));
		}
		$progress['progress_friends']['percent'] = 100;
		$elapsed = microtime(true)-$progress['progress_friends']['starttime'];
		$progress['progress_friends']['elapsed'] = gmdate("H:i:s",$elapsed);
		$progress['progress_friends']['estimated'] = "00:00:00";
		write_progress($progress);
		
	}
	

// Create icons for users
// TODO: make icon generation faster. This should be done with simple file copies insted of write operations through Elgg's FileStore
	if (get_input('createicons')) {
		$iconDirName=$CONFIG->pluginspath . "hu_skawa_genusers/resources/profileicons/";
		$iconFiles=array();
		$iconImages=array();
		$iconIndex=0;

// Grab all image files from the icon resource directory, these will be randomly assigned to the previously generated users
		if ($dh = opendir($iconDirName)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($iconDirName . $file)=='file' && strripos($file, '.jpg')>0) {
					$iconFiles[$iconIndex]=$file;
					$iconImages[$iconIndex++]=array();
				}
			}
			closedir($dh);
   		}

// Start icon generation
		$progress['progress_icons']['starttime']=microtime(true);
		for ($i=1; $i<=$num_of_users; $i++) {
			$fileIndex=mt_rand(0,count($iconFiles));
			if ($fileIndex<count($iconFiles)) { // if the random number exceeds the number of available icons, there will be no custom icon for this user
				$filePath=$CONFIG->pluginspath . "hu_skawa_genusers/resources/profileicons/" . $iconFiles[$fileIndex];

				if (!isset($iconImages[$fileIndex]['topbar']))
					$iconImages[$fileIndex]['topbar']=get_resized_image_from_existing_file($filePath,16,16, true);
					
				if (!isset($iconImages[$fileIndex]['tiny']))
					$iconImages[$fileIndex]['tiny'] = get_resized_image_from_existing_file($filePath,25,25, true);

				if (!isset($iconImages[$fileIndex]['small']))
					$iconImages[$fileIndex]['small'] = get_resized_image_from_existing_file($filePath,40,40, true);
					
				if (!isset($iconImages[$fileIndex]['medium']))
					$iconImages[$fileIndex]['medium'] = get_resized_image_from_existing_file($filePath,100,100, true);

				if (!isset($iconImages[$fileIndex]['large']))
					$iconImages[$fileIndex]['large'] = get_resized_image_from_existing_file($filePath,200,200);

				if (!isset($iconImages[$fileIndex]['master']))
					$iconImages[$fileIndex]['master'] = get_resized_image_from_existing_file($filePath,550,550);

				$filehandler = new ElggFile();
				$filehandler->owner_guid = $lastEntityId+$i;
				$filehandler->setFilename("profile/" . $user_prefix.$i . "large.jpg");
				$filehandler->open("write");
				$filehandler->write($iconImages[$fileIndex]['large']);
				$filehandler->close();
				$filehandler->setFilename("profile/" . $user_prefix.$i . "medium.jpg");
				$filehandler->open("write");
				$filehandler->write($iconImages[$fileIndex]['medium']);
				$filehandler->close();
				$filehandler->setFilename("profile/" . $user_prefix.$i . "small.jpg");
				$filehandler->open("write");
				$filehandler->write($iconImages[$fileIndex]['small']);
				$filehandler->close();
				$filehandler->setFilename("profile/" . $user_prefix.$i . "tiny.jpg");
				$filehandler->open("write");
				$filehandler->write($iconImages[$fileIndex]['tiny']);
				$filehandler->close();
				$filehandler->setFilename("profile/" . $user_prefix.$i . "topbar.jpg");
				$filehandler->open("write");
				$filehandler->write($iconImages[$fileIndex]['topbar']);
				$filehandler->close();
				$filehandler->setFilename("profile/" . $user_prefix.$i . "master.jpg");
				$filehandler->open("write");
                $filehandler->write($iconImages[$fileIndex]['master']);
				$filehandler->close();

				$progress['progress_icons']['percent']=intval(100*$i/$num_of_users);
				$elapsed=microtime(true)-$progress['progress_icons']['starttime'];
				$progress['progress_icons']['elapsed']=gmdate("H:i:s",$elapsed);
				if ($progress['progress_icons']['percent']>=$min_percent_for_estimate) {
					$progress['progress_icons']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_icons']['percent'])-$elapsed);
				}
				write_progress($progress);
				
			}
		}
		
	}		

	$progress['progress_icons']['percent']=100;
	$elapsed=microtime(true)-$progress['progress_icons']['starttime'];
	$progress['progress_icons']['elapsed']=gmdate("H:i:s",$elapsed);
	$progress['progress_icons']['estimated']="00:00:00";
	write_progress($progress);


// Create messages
	$fileName=$CONFIG->pluginspath . "hu_skawa_genusers/resources/mailtext.txt";
	$mailTemplate = file_get_contents($fileName);
	$mailText = str_replace('.','',$mailTemplate);
	$mailText = str_replace(',','',$mailText);
	$mailText = str_replace(';','',$mailText);
	$mailWords = explode(' ',$mailText);

	if ($num_of_emails>0) {

// Create messages with bulk database inserts
		$message_subtype=add_subtype('object','messages');

		if (!$to_id = get_metastring_id('toId')) $to_id=add_metastring('toId');
		if (!$from_id = get_metastring_id('fromId')) $from_id=add_metastring('fromId');
		if (!$msg_id = get_metastring_id('msg')) $msg_id=add_metastring('msg');
		if (!$readYet_id = get_metastring_id('readYet')) $readYet_id=add_metastring('readYet');
		if (!$hiddenFrom_id = get_metastring_id('hiddenFrom')) $hiddenFrom_id=add_metastring('hiddenFrom');
		if (!$hiddenTo_id = get_metastring_id('hiddenTo')) $hiddenTo_id=add_metastring('hiddenTo');
		if (!$zero_id = get_metastring_id('0')) $zero_id=add_metastring('0');
		if (!$one_id = get_metastring_id('1')) $one_id=add_metastring('1');
		
		$lastMetastringRow=get_data_row("SELECT MAX(id) id from {$CONFIG->dbprefix}metastrings");
		$lastMetastringId=$lastMetastringRow->id;

// Creating all user guids in the metastrings table - those values will be used as the "to" field for mails
// This is rather tricky, as the metastrings table should contain only unique values, so we need to check if any user guids are already present in the table, and only insert the missing guids
		$getUsersQuery="SELECT * FROM {$CONFIG->dbprefix}metastrings WHERE string regexp '^[[:digit:]]+$' AND cast(string as unsigned)>=".($lastEntityId+1)." AND cast(string as unsigned)<=".($lastEntityId+$num_of_users);
		$existingUserIds=get_data($getUsersQuery);
		$userStringIds=array();
		if ($existingUserIds!==false) {
			for ($i=0; $i<count($existingUserIds); $i++) {
				$userStringIds[$existingUserIds[$i]->string]=$existingUserIds[$i]->id;
			}
		}
		$bufferCounter=0;
		$newUserCounter=0;
		$metastringStatement='';
		for ($i=1; $i<=$num_of_users; $i++){
			if (!isset($userStringIds[$lastMetastringId+$i])) { // add only previuosly undefined user guids to the metastring table
				$userStringIds[$lastEntityId+$i]=$lastMetastringId+$newUserCounter+1;
				$metastringStatement.='(' . ($lastMetastringId+$newUserCounter+1) . ',"' . ($lastEntityId+$i) . '"),';
				$metastrings_cleanup[]=$lastMetastringId+$newUserCounter+1;
				$newUserCounter++;
				if ($bufferCounter++ == $insertBufferSize) {
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($metastringStatement,0,strlen($metastringStatement)-1));
					$bufferCounter=0;
					$metastringStatement='';
				}
			}
		}			
		if ($bufferCounter>0) {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($metastringStatement,0,strlen($metastringStatement)-1));
			$bufferCounter=0;
			$metastringStatement='';
		}

		$createEntityStatement='';
		$createEntityObjectStatement='';
		$createMetadataStatement='';
		$bufferCounter=0;
		$recepient_array=array_values($userStringIds);
		
		$progress['progress_messages']['starttime']=microtime(true);
		$message_counter = 0;
		for ($i=1; $i<=$num_of_users; $i++) {
			$emails_max=mt_rand(0,$num_of_emails*2);
			$from_user = $userStringIds[$lastEntityId+$i]; 
			for ($j=0; $j<$emails_max; $j++) {
				$subjectLength=mt_rand(1,5); // subject is minimum 1, maximum 5 words
				$messageLength=mt_rand(3,20); // message is minimum 3, maximum 20 words
				$subjectStart=mt_rand(0,count($mailWords)-$subjectLength-1);  // random start position for subject text in the mail resource text
				$messageStart=mt_rand(0,count($mailWords)-$messageLength-1);  // random start position for message text in the mail resource text
				$title=implode(' ',array_slice($mailWords,$subjectStart,$subjectLength));
				$message_contents=implode(' ',array_slice($mailWords,$messageStart,$messageLength));
				$action_time = time();
				$new_guid=$lastEntityId+$num_of_users + ($message_counter*2) + 1;
				$to_user_index =  mt_rand(0,count($recepient_array)-1);
				$to_user_guid = $lastEntityId + $to_user_index + 1;
				$to_user = $recepient_array[$to_user_index];

				// Recieved mail object creation
				$createEntityStatement.='(' . ($new_guid) . ',"object",' . $message_subtype . ',' . ($to_user_guid) . ',' . $site_guid . ',' . ($to_user_guid) . ',' . $access_id. ',' . $action_time. ',' . $action_time . ',' . $action_time . ',"' . $enabled . '"),';
				$createEntityObjectStatement.='(' . ($new_guid) . ',"' . ($title) . '","' . ($message_contents) . '"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $to_id . ',' . $to_user . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $from_id . ',' . $from_user . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $readYet_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $hiddenFrom_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $hiddenTo_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid) . ',' . $msg_id . ',' . $one_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				
				// Sent mail object creation
				$createEntityStatement.='(' . ($new_guid+1) . ',"object",' . $message_subtype . ',' . ($lastEntityId+$i) . ',' . $site_guid . ',' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',' . $action_time . ',' . $action_time . ',"' . $enabled . '"),';
				$createEntityObjectStatement.='(' . ($new_guid+1) . ',"' . ($title) . '","' . ($message_contents) . '"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $to_id . ',' . $to_user . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $from_id . ',' . $from_user . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $readYet_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $hiddenFrom_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $hiddenTo_id . ',' . $zero_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$createMetadataStatement.='(' . ($new_guid+1) . ',' . $msg_id . ',' . $one_id . ',"integer",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				
				$message_counter++;
				if ($bufferCounter++ == $insertBufferSize) {

					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
					
					$createEntityStatement='';
					$createEntityObjectStatement='';
					$createMetadataStatement='';
					$bufferCounter=0;
					$progress_percent=intval(100*$message_counter/($num_of_users*$num_of_emails));
					if ($progress_percent>100) $progress_percent=100;
					$progress['progress_messages']['percent']=$progress_percent;
					$elapsed=microtime(true)-$progress['progress_messages']['starttime'];
					$progress['progress_messages']['elapsed']=gmdate("H:i:s",$elapsed);
					if ($progress['progress_messages']['percent']>=$min_percent_for_estimate) {
						$progress['progress_messages']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_messages']['percent'])-$elapsed);
					}
					write_progress($progress);
				}

			}
		}

// Flush any pending mails from insert buffer
		if ($bufferCounter>0) {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
		}
		$progress['progress_messages']['percent']=100;
		$elapsed=microtime(true)-$progress['progress_messages']['starttime'];
		$progress['progress_messages']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_messages']['estimated']="00:00:00";
		write_progress($progress);
		
	}

// Create blog entries

	if ($num_of_blogs>0) {
		$blog_counter=0;

// Create blog entries with bulk database inserts
		$blog_subtype=add_subtype('object','blog');

		if (!$tags_id = get_metastring_id('tags')) $tags_id=add_metastring('tags');

		$lastMetastringRow=get_data_row("SELECT MAX(id) id from {$CONFIG->dbprefix}metastrings");
		$lastMetastringId=$lastMetastringRow->id;
		$tags_start_id=$lastMetastringId;

// Generate some tags that will be attached to blog entries
		$num_of_tags=100;
		$metastringStatement='';
		$tagsStart=mt_rand(0,count($mailWords)-$num_of_tags-1);  // random start position for tags text
		$bufferCounter=0;
		$tagStringIds=array();
		for ($i=1; $i<=$num_of_tags; $i++){
			if (!$temp_tag_id = get_metastring_id($mailWords[$tagsStart+$i-1])) { 
				$temp_tag_id=add_metastring($mailWords[$tagsStart+$i-1]);
				$metastrings_cleanup[]=$temp_tag_id;
			}
			$tagStringIds[]=$temp_tag_id;
		}

		$createEntityStatement='';
		$createEntityObjectStatement='';
		$createMetadataStatement='';
		$bufferCounter=0;

		$progress['progress_blogs']['starttime']=microtime(true);
		for ($i=1; $i<=$num_of_users; $i++) {
			$blogs_max=mt_rand(0,$num_of_blogs*2);
			for ($j=0; $j<$blogs_max; $j++) {
				$titleLength=mt_rand(1,5); // title is minimum 1, maximum 5 words
				$blogLength=mt_rand(3,20); // blog is minimum 3, maximum 20 words
				$titleStart=mt_rand(0,count($mailWords)-$titleLength-1);  // random start position for subject text
				$blogStart=mt_rand(0,count($mailWords)-$blogLength-1);  // random start position for message text
				$title=implode(' ',array_slice($mailWords,$titleStart,$titleLength));
				$blog_content=implode(' ',array_slice($mailWords,$blogStart,$blogLength));
				$tags_for_this_blog=mt_rand(1,5);
				$action_time=time();
				$new_guid=$lastEntityId+$num_of_users+($message_counter*2)+$blog_counter+1;

				$createEntityStatement.='(' . ($new_guid) . ',"object",' . $blog_subtype . ',' . ($lastEntityId+$i) . ',' . $site_guid . ',' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',' . $action_time . ',' . $action_time . ',"' . $enabled . '"),';
				$createEntityObjectStatement.='(' . ($new_guid) . ',"' . ($title) . '","' . ($blog_content) . '"),';
				for ($k=0; $k<$tags_for_this_blog; $k++)
					$createMetadataStatement.='(' . ($new_guid) . ',' . $tags_id . ',' . ($tagStringIds[mt_rand(0,count($tagStringIds)-1)]). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				
				$blog_counter++;
				if ($bufferCounter++ == $insertBufferSize)  {

					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
					
					$createEntityStatement='';
					$createEntityObjectStatement='';
					$createMetadataStatement='';
					$bufferCounter=0;

					$progress_percent=intval(100*$blog_counter/($num_of_users*$num_of_blogs));
					if ($progress_percent>100) $progress_percent=100;
					$progress['progress_blogs']['percent']=$progress_percent;
					$elapsed=microtime(true)-$progress['progress_blogs']['starttime'];
					$progress['progress_blogs']['elapsed']=gmdate("H:i:s",$elapsed);
					if ($progress['progress_blogs']['percent']>=$min_percent_for_estimate) {
						$progress['progress_blogs']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_blogs']['percent'])-$elapsed);
					}
					write_progress($progress);
				}

			}
		}

// Flush pending blog entries from insert buffer
		if ($bufferCounter>0)  {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
		}

		$progress['progress_blogs']['percent']=100;
		$elapsed=microtime(true)-$progress['progress_blogs']['starttime'];
		$progress['progress_blogs']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_blogs']['estimated']="00:00:00";
		write_progress($progress);


// Create comments for blog entries
		if ($num_of_blogcomments>0) {

// Create blog comments with bulk database inserts	
			if (!$comment_id = get_metastring_id('generic_comment')) $comment_id=add_metastring('generic_comment');

			$lastMetastringRow=get_data_row("SELECT MAX(id) id from {$CONFIG->dbprefix}metastrings");
			$lastMetastringId=$lastMetastringRow->id;
	
			$createAnnotationStatement='';
			$createMetastringsStatement='';
			$bufferCounter=0;
			$blogcomment_counter=0;
			$progress['progress_blogcomments']['starttime']=microtime(true);
			for ($i=1; $i<=$num_of_users; $i++) {
				$blogcomments_max=mt_rand(0,$num_of_blogcomments*2);
				for ($j=0; $j<$blogcomments_max; $j++) {
					$blogcommentLength=mt_rand(3,20); // blog is minimum 3, maximum 20 words
					$blogcommentStart=mt_rand(0,count($mailWords)-$blogcommentLength-1);  // random start position for comment text
					$blog_comment='blog_comment_'.$i.' '.implode(' ',array_slice($mailWords,$blogcommentStart,$blogcommentLength));
					$metastrings_cleanup[]=$lastMetastringId+$blogcomment_counter+1;

					$entity_guid=$lastEntityId+$num_of_users+($message_counter*2)+mt_rand(1,$blog_counter);
					$owner_guid=$lastEntityId+mt_rand(1,$num_of_users);
					$action_time=time();

					$createMetastringsStatement.="(" . ($lastMetastringId+$blogcomment_counter+1) . ",'" . $blog_comment . "'),";
					$createAnnotationStatement.="(" . $entity_guid . "," . $comment_id . "," . ($lastMetastringId+$blogcomment_counter+1) . ",'text'," . $owner_guid . "," .  $access_id. ',' . $action_time. ',"yes"),';

					$blogcomment_counter++;
					if ($bufferCounter++ == $insertBufferSize)  {
	
						$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
						$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}annotations (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createAnnotationStatement,0,strlen($createAnnotationStatement)-1));
						
						$createAnnotationStatement='';
						$createMetastringsStatement='';
						$bufferCounter=0;

						$progress_percent=intval(100*$blogcomment_counter/($num_of_users*$num_of_blogcomments));
						if ($progress_percent>100) $progress_percent=100;
						$progress['progress_blogcomments']['percent']=$progress_percent;
						$elapsed=microtime(true)-$progress['progress_blogcomments']['starttime'];
						$progress['progress_blogcomments']['elapsed']=gmdate("H:i:s",$elapsed);
						if ($progress['progress_blogcomments']['percent']>=$min_percent_for_estimate) {
							$progress['progress_blogcomments']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_blogcomments']['percent'])-$elapsed);
						}
						write_progress($progress);
					}
					
				}
			}
			if ($bufferCounter>0)  {
				$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
				$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}annotations (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createAnnotationStatement,0,strlen($createAnnotationStatement)-1));
			}
		}
		$progress['progress_blogcomments']['percent']=100;
		$elapsed=microtime(true)-$progress['progress_blogcomments']['starttime'];
		$progress['progress_blogcomments']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_blogcomments']['estimated']="00:00:00";
		write_progress($progress);

	}		
// Create uploaded content

	if ($num_of_uploads>0) {

// Read template files from upload directory
		$uploadDirName=$CONFIG->pluginspath . "hu_skawa_genusers/resources/uploads/";
		$uploadFiles=array();
		$uploadImages=array();
		$uploadIndex=0;
		if ($dh = opendir($uploadDirName)) {
			while (($file = readdir($dh)) !== false) {
				if (filetype($uploadDirName . $file)=='file' && strripos($file, '.jpg')>0) {
					$uploadFiles[$uploadIndex]=$file;
					$uploadImages[$uploadIndex++]=array();
				}
			}
			closedir($dh);
   		}
   		
		$file_subtype=add_subtype('object','file','ElggFile');
   		
// check and create necessary entries in the metastrings table 
		if (!$filename_id = get_metastring_id('filename')) $filename_id=add_metastring('filename');
		if (!$mimetype_id = get_metastring_id('mimetype')) $mimetype_id=add_metastring('mimetype');
		if (!$imagejpeg_id = get_metastring_id('image/jpeg')) $imagejpeg_id=add_metastring('image/jpeg');
		if (!$originalfilename_id = get_metastring_id('originalfilename')) $originalfilename_id=add_metastring('originalfilename');
		if (!$simpletype_id = get_metastring_id('simpletype')) $simpletype_id=add_metastring('simpletype');
		if (!$image_id = get_metastring_id('image')) $image_id=add_metastring('image');
		if (!$filestoredirroot_id = get_metastring_id('filestore::dir_root')) $filestoredirroot_id=add_metastring('filestore::dir_root');
		if (!$dataroot_id = get_metastring_id($CONFIG->dataroot)) $dataroot_id=add_metastring($CONFIG->dataroot);
		if (!$filestorestore_id = get_metastring_id('filestore::filestore')) $filestorestore_id=add_metastring('filestore::filestore');
		if (!$elggdiskfilestore_id = get_metastring_id('ElggDiskFilestore')) $elggdiskfilestore_id=add_metastring('ElggDiskFilestore');
		if (!$thumbnail_id = get_metastring_id('thumbnail')) $thumbnail_id=add_metastring('thumbnail');
		if (!$smallthumb_id = get_metastring_id('smallthumb')) $smallthumb_id=add_metastring('smallthumb');
		if (!$largethumb_id = get_metastring_id('largethumb')) $largethumb_id=add_metastring('largethumb');
		
		$lastMetastringRow=get_data_row("SELECT MAX(id) id from {$CONFIG->dbprefix}metastrings");
		$lastMetastringId=$lastMetastringRow->id;

		$createEntityStatement='';
		$createEntityObjectStatement='';
		$createMetadataStatement='';
		$createMetastringsStatement='';
		$bufferCounter=0;
		$upload_counter=0;
		$metastring_counter=0;
		$insertBufferSize=300;

		if (!$tagStringIds) { // if there were no blog entries generated, create tags now for uploaded content - otherwise use the same tags as for blog entries
			$tagStringIds=array();
			for ($i=1; $i<=$num_of_tags; $i++){
				if (!$temp_tag_id = get_metastring_id($mailWords[$tagsStart+$i-1])) { 
					$temp_tag_id=add_metastring($mailWords[$tagsStart+$i-1]);
					$metastrings_cleanup[]=$temp_tag_id;
				}
				$tagStringIds[]=$temp_tag_id;
			}
		}


		$filehandler = new ElggFile();
		$progress['progress_uploads']['starttime']=microtime(true);
		for ($i=1; $i<=$num_of_users; $i++) {
			$filehandler->owner_guid = $lastEntityId+$i;
			$max_files=mt_rand(0,$num_of_uploads*2);
			for ($j=0; $j<$max_files; $j++) {				
			
				$fileIndex=mt_rand(0,count($uploadFiles)-1);
				$filePath=$CONFIG->pluginspath . "hu_skawa_genusers/resources/uploads/" . $uploadFiles[$fileIndex];

// create thumbnails if not yet created for this file	
				if (!isset($uploadImages[$fileIndex]['thumb']))
					$uploadImages[$fileIndex]['thumb']=get_resized_image_from_existing_file($filePath,60,60, true);
					
				if (!isset($uploadImages[$fileIndex]['smallthumb']))
					$uploadImages[$fileIndex]['smallthumb'] = get_resized_image_from_existing_file($filePath,153,153, true);

				if (!isset($uploadImages[$fileIndex]['largethumb']))
					$uploadImages[$fileIndex]['largethumb'] = get_resized_image_from_existing_file($filePath,600,600, false);

// write thumbnail files into user's filestore						
				$filehandler->setFilename("file/thumb" . $uploadFiles[$fileIndex]);
				$filehandler->open("write");
				$filehandler->write($uploadImages[$fileIndex]['thumb']);
				$filehandler->close();
				$filehandler->setFilename("file/smallthumb" . $uploadFiles[$fileIndex]);
				$filehandler->open("write");
				$filehandler->write($uploadImages[$fileIndex]['smallthumb']);
				$filehandler->close();
				$filehandler->setFilename("file/largethumb" . $uploadFiles[$fileIndex]);
				$filehandler->open("write");
				$filehandler->write($uploadImages[$fileIndex]['largethumb']);
				$filehandler->close();
// copy original file
				$filehandler->setFilename("file/" . $uploadFiles[$fileIndex]);
				copy($filePath, $filehandler->getFilenameOnFilestore());
//set title and description of image
				$titleLength=mt_rand(1,5); // title is minimum 1, maximum 5 words
				$descriptionLength=mt_rand(3,20); // description is minimum 3, maximum 20 words
				$titleStart=mt_rand(0,count($mailWords)-$titleLength-1);  // random start position for title text
				$descriptionStart=mt_rand(0,count($mailWords)-$descriptionLength-1);  // random start position for description text
				$title=implode(' ',array_slice($mailWords,$titleStart,$titleLength));
				$description=implode(' ',array_slice($mailWords,$descriptionStart,$descriptionLength));
				$tags_for_this_upload=mt_rand(1,5);
				$action_time=time();
				$new_guid=$lastEntityId+$num_of_users+($message_counter*2)+$blog_counter+$upload_counter+1;

// create entity and object records
				$createEntityStatement.='(' . ($new_guid) . ',"object",' . $file_subtype . ',' . ($lastEntityId+$i) . ',' . $site_guid . ',' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',' . $action_time . ',' . $action_time . ',"' . $enabled . '"),';
				$createEntityObjectStatement.='(' . ($new_guid) . ',"' . ($title) . '","' . ($description) . '"),';

// filename
				$createMetastringsStatement.="(" . ($lastMetastringId+$metastring_counter+1) . ",'file/" . $uploadFiles[$fileIndex] . "'),";
				$metastrings_cleanup[]=$lastMetastringId+$metastring_counter+1;
				$createMetadataStatement.='(' . ($new_guid) . ',' . $filename_id . ',' . ($lastMetastringId+$metastring_counter+1). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$metastring_counter++;
//mimetype
				$createMetadataStatement.='(' . ($new_guid) . ',' . $mimetype_id . ',' . $imagejpeg_id . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
// original file name
				$createMetastringsStatement.="(" . ($lastMetastringId+$metastring_counter+1) . ",'" . $uploadFiles[$fileIndex] . "'),";
				$metastrings_cleanup[]=$lastMetastringId+$metastring_counter+1;
				$createMetadataStatement.='(' . ($new_guid) . ',' . $originalfilename_id . ',' . ($lastMetastringId+$metastring_counter+1). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$metastring_counter++;
// simpletype
				$createMetadataStatement.='(' . ($new_guid) . ',' . $simpletype_id . ',' . $image_id . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
// filestore root dir
				$createMetadataStatement.='(' . ($new_guid) . ',' . $filestoredirroot_id . ',' . $dataroot_id . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
// filestore type
				$createMetadataStatement.='(' . ($new_guid) . ',' . $filestorestore_id . ',' . $elggdiskfilestore_id . ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
// thumbnail image
				$createMetastringsStatement.="(" . ($lastMetastringId+$metastring_counter+1) . ",'file/thumb" . $uploadFiles[$fileIndex] . "'),";
				$metastrings_cleanup[]=$lastMetastringId+$metastring_counter+1;
				$createMetadataStatement.='(' . ($new_guid) . ',' . $thumbnail_id . ',' . ($lastMetastringId+$metastring_counter+1). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$metastring_counter++;
// smallthumb image
				$createMetastringsStatement.="(" . ($lastMetastringId+$metastring_counter+1) . ",'file/smallthumb" . $uploadFiles[$fileIndex] . "'),";
				$metastrings_cleanup[]=$lastMetastringId+$metastring_counter+1;
				$createMetadataStatement.='(' . ($new_guid) . ',' . $smallthumb_id . ',' . ($lastMetastringId+$metastring_counter+1). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$metastring_counter++;
// largethumb image
				$createMetastringsStatement.="(" . ($lastMetastringId+$metastring_counter+1) . ",'file/largethumb" . $uploadFiles[$fileIndex] . "'),";
				$metastrings_cleanup[]=$lastMetastringId+$metastring_counter+1;
				$createMetadataStatement.='(' . ($new_guid) . ',' . $largethumb_id . ',' . ($lastMetastringId+$metastring_counter+1). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				$metastring_counter++;

// add some tags to the uploaded content
				for ($k=0; $k<$tags_for_this_upload; $k++)
					$createMetadataStatement.='(' . ($new_guid) . ',' . $tags_id . ',' . ($tagStringIds[mt_rand(0,count($tagStringIds)-1)]). ',"text",' . ($lastEntityId+$i) . ',' . $access_id. ',' . $action_time. ',"yes"),';
				
				$upload_counter++;

				$progress_percent=intval(100*$upload_counter/($num_of_users*$num_of_uploads));
				if ($progress_percent>100) $progress_percent=100;
				$progress['progress_uploads']['percent']=$progress_percent;
				$elapsed=microtime(true)-$progress['progress_uploads']['starttime'];
				$progress['progress_uploads']['elapsed']=gmdate("H:i:s",$elapsed);
				if ($progress['progress_uploads']['percent']>=$min_percent_for_estimate) {
					$progress['progress_uploads']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_uploads']['percent'])-$elapsed);
				}
				write_progress($progress);

				if ($bufferCounter++ == $insertBufferSize)  {

					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
					$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
					
					$createEntityStatement='';
					$createEntityObjectStatement='';
					$createMetadataStatement='';
					$createMetastringsStatement='';
					$bufferCounter=0;
				}
			}						
		}

		if ($bufferCounter>0)  {

			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}entities VALUES ".substr($createEntityStatement,0,strlen($createEntityStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}objects_entity VALUES ".substr($createEntityObjectStatement,0,strlen($createEntityObjectStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metadata (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createMetadataStatement,0,strlen($createMetadataStatement)-1));
			
			$createEntityStatement='';
			$createEntityObjectStatement='';
			$createMetadataStatement='';
			$createMetastringsStatement='';
			$bufferCounter=0;
		}

		$progress['progress_uploads']['percent']=100;
		$elapsed=microtime(true)-$progress['progress_uploads']['starttime'];
		$progress['progress_uploads']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_uploads']['estimated']="00:00:00";
		write_progress($progress);

// Create comments on uploaded content with bulk database inserts	
		if ($num_of_uploadcomments>0) {
			if (!$comment_id = get_metastring_id('generic_comment')) $comment_id=add_metastring('generic_comment');

			$lastMetastringRow=get_data_row("SELECT MAX(id) id from {$CONFIG->dbprefix}metastrings");
			$lastMetastringId=$lastMetastringRow->id;
	
			$createAnnotationStatement='';
			$createMetastringsStatement='';
			$bufferCounter=0;
			$uploadcomment_counter=0;
			$progress['progress_uploadcomments']['starttime']=microtime(true);
			for ($i=1; $i<=$num_of_users; $i++) {
				$uploadcomments_max=mt_rand(0,$num_of_uploadcomments*2);
				for ($j=0; $j<$uploadcomments_max; $j++) {
					$uploadcommentLength=mt_rand(3,20); // upload comment is minimum 3, maximum 20 words
					$uploadcommentStart=mt_rand(0,count($mailWords)-$uploadcommentLength-1);  // random start position for comment text
					$upload_comment='upload_comment_'.$i.' '.implode(' ',array_slice($mailWords,$uploadcommentStart,$uploadcommentLength));
					$metastrings_cleanup[]=$lastMetastringId+$uploadcomment_counter+1;

					$entity_guid=$lastEntityId+$num_of_users+($message_counter*2)+$blog_counter+mt_rand(1,$upload_counter);
					$owner_guid=$lastEntityId+mt_rand(1,$num_of_users);
					$action_time=microtime(true);

					$createMetastringsStatement.="(" . ($lastMetastringId+$uploadcomment_counter+1) . ",'" . $upload_comment . "'),";
					$createAnnotationStatement.="(" . $entity_guid . "," . $comment_id . "," . ($lastMetastringId+$uploadcomment_counter+1) . ",'text'," . $owner_guid . "," .  $access_id. ',' . $action_time. ',"yes"),';

					$uploadcomment_counter++;
					if ($bufferCounter++ == $insertBufferSize)  {
	
						$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (id,string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
						$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}annotations (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createAnnotationStatement,0,strlen($createAnnotationStatement)-1));
						
						$createAnnotationStatement='';
						$createMetastringsStatement='';
						$bufferCounter=0;

						$progress_percent=intval(100*$uploadcomment_counter/($num_of_users*$num_of_uploadcomments));
						if ($progress_percent>100) $progress_percent=100;
						$progress['progress_uploadcomments']['percent']=$progress_percent;
						$elapsed=microtime(true)-$progress['progress_uploadcomments']['starttime'];
						$progress['progress_uploadcomments']['elapsed']=gmdate("H:i:s",$elapsed);
						if ($progress['progress_uploadcomments']['percent']>=$min_percent_for_estimate) {
							$progress['progress_uploadcomments']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_uploadcomments']['percent'])-$elapsed);
						}
						write_progress($progress);
					}
					
				}
			}
			if ($bufferCounter>0)  {
				$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
				$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}annotations (entity_guid,name_id,value_id,value_type,owner_guid,access_id,time_created,enabled) VALUES ".substr($createAnnotationStatement,0,strlen($createAnnotationStatement)-1));
			}

			$progress['progress_uploadcomments']['percent']=100;
			$elapsed=microtime(true)-$progress['progress_uploadcomments']['starttime'];
			$progress['progress_uploadcomments']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_uploadcomments']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_uploadcomments']['percent'])-$elapsed);
			write_progress($progress);
		}
		
	}	

	write_cleanup($metastrings_cleanup, $insertBufferSize,$logfile_path);

	$first_user = $user_prefix.'1';
	$last_user = $user_prefix.$num_of_users;
	$endTimeStamp = microtime(true);
	
	file_put_contents($logfile_path,date(DATE_RFC822).' User generation finished.'."\r\n",FILE_APPEND);
	
	if (get_input('dojmeter'))	{
		create_jmeter_test($input_variables);
	}

//	forward($_SERVER['HTTP_REFERER']);
//	echo '{ message: "' . sprintf(elgg_echo('messages:success'),$first_user,$last_user, $endTimeStamp-$startTimeStamp) . '" }';	

/********************  General functions **********************/

	
	function debug_insert_data($logfile_path,$statement) {
		file_put_contents($logfile_path,date(DATE_RFC822).' ',FILE_APPEND);

		if (strpos($statement,'),(')>0) {
			$truncated_statement=substr($statement,0,strpos($statement,'),(')+2);
			$truncated_statement.=' ... ';
			$truncated_statement.=substr($statement,strrpos($statement,'),(')+1);
		} else
			$truncated_statement=$statement;
		
		try {
			$result=insert_data($statement);
			file_put_contents($logfile_path,$truncated_statement.'  OK.'."\r\n",FILE_APPEND);
		} catch  (DatabaseException $dbe) {
			file_put_contents($logfile_path,$truncated_statement.'  ERROR! '.$dbe->getMessage()."\r\n",FILE_APPEND);
		}
				
		return $result;
	}
	
	function write_cleanup($cleanup_array, $insertBufferSize,$logfile_path) {

		global $CONFIG;

		$counter=0;
		$fromIndices=array();
		$toIndices=array();

		sort($cleanup_array);
		$i=0;
		
		while ($i<count($cleanup_array)) {
			$fromIndices[$counter]=$cleanup_array[$i];
			while ($i+1<count($cleanup_array) and $cleanup_array[$i+1]==$cleanup_array[$i]+1) $i++; //find consecutive sequences
			$toIndices[$counter++]=$cleanup_array[$i++];
		}

		$bufferCounter=0;
		for ($i=0; $i<count($fromIndices); $i++) {
			$cleanup_text='hu_skawa_genusers_cleanup:'.$fromIndices[$i].':'.$toIndices[$i];
			$createMetastringsStatement.="('" . $cleanup_text . "'),";

			if ($bufferCounter++ == $insertBufferSize)  {
				$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
				$createMetastringsStatement='';
				$bufferCounter=0;
			}
		}
		if ($bufferCounter>0)  {
			$result = debug_insert_data($logfile_path,"INSERT INTO {$CONFIG->dbprefix}metastrings (string) VALUES ".substr($createMetastringsStatement,0,strlen($createMetastringsStatement)-1));
			$createMetastringsStatement='';
			$bufferCounter=0;
		}		
	}
	
	
?>