<?php

	error_reporting(E_ALL ^ E_NOTICE);

	require_once($CONFIG->pluginspath . "hu_skawa_genusers/common/common.php");

	global $CONFIG;
	
	admin_gatekeeper();
	action_gatekeeper();

	$delete_method=get_input('delete_method','old');	

// Progress array for GUI feedback during generation process
	$min_percent_for_estimate=2;
	$progress = array ( 
					'progress_icons' => array ( 'enabled' => true, 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_friends' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_users_metadata' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_users_users' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_users_entities' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_objects_metadata' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 30, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_objects_objects' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 30, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_objects_entities' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_annotations' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_metastrings' => array ( 'enabled' => ($delete_method=='old'), 'percent' => 0, 'weight' => 10, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_backup' => array ( 'enabled' => ($delete_method=='fast'), 'percent' => 0, 'weight' => 1, 'starttime' => 0, 'elapsed' => '&nbsp;', 'estimated' => '&nbsp;' ),
					'progress_total' => array ( 'enabled' => true, 'percent' => 0, 'starttime' => 0, 'elapsed' => '', 'estimated' => '&nbsp;' )
				);

	if (!get_input('deleteicons')) {
		$progress['progress_icons']['enabled']=false;
	}

	$startTimeStamp=microtime(true);

// Mass deletion based on first and last guid's of generated users 
	$generated_id = get_metastring_id('hu_skawa_genusers_generated');
	$query="SELECT MIN(entity_guid) min_guid, MAX(entity_guid) max_guid FROM {$CONFIG->dbprefix}metadata WHERE name_id=".$generated_id;
	$row=get_data_row($query);
	$first_guid=$row->min_guid;
	$last_guid=$row->max_guid;

	$query="SELECT username FROM {$CONFIG->dbprefix}users_entity WHERE guid=".$first_guid;
	$row=get_data_row($query);
	$first_username=$row->username;
	$user_prefix=substr($first_username,0,strlen($first_username)-1);
	
// Removing all user files from the filestore
	$progress['progress_total']['starttime']=time();
	if (get_input('deleteicons')) {
		$progress['progress_icons']['starttime']=time();
		$filehandler = new ElggFile();
		for ($i=1; $i<=$last_guid-$first_guid+1; $i++) {
			$username=$user_prefix.$i;
			$filehandler->owner_guid = $first_guid+$i-1;
			$filehandler->setFilename("dummy.txt");
			$dirname=$filehandler->getFilenameOnFilestore();
			$dirname=str_replace('dummy.txt','',$dirname);
			rm($dirname);
			$progress['progress_icons']['percent']=intval(100*$i/($last_guid-$first_guid+1));
			$elapsed=time()-$progress['progress_icons']['starttime'];
			$progress['progress_icons']['elapsed']=gmdate("H:i:s",$elapsed);
			if ($progress['progress_icons']['percent']>=$min_percent_for_estimate) {
				$progress['progress_icons']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_icons']['percent'])-$elapsed);
			}
			write_progress($progress);
		}
		$progress['progress_icons']['percent']=100;
		$elapsed=time()-$progress['progress_icons']['starttime'];
		$progress['progress_icons']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_icons']['estimated']="00:00:00";
		write_progress($progress);
	}

	if ($delete_method=='fast') {
		$backupfile=$CONFIG->pluginspath . "hu_skawa_genusers/log/elggdbdump.sql";
		$progress['progress_backup']['starttime']=time();
		write_progress($progress);
		$mysql_path = get_input('path_to_mysql','');
		if (file_exists($mysql_path.'mysql')) {
			$abs_path = $mysql_path.'mysql';
		} else if (file_exists($mysql_path.'mysql.exe')) {
			$abs_path = $mysql_path.'mysql.exe';
		} else {
			$abs_path = 'mysql';
		}
		$command = '"' .$abs_path.'" --host='.$CONFIG->dbhost.' --user='.$CONFIG->dbuser.' --password='.$CONFIG->dbpass.' '.$CONFIG->dbname.' < "'.$backupfile.'"';
		error_log('Restoring database, executing command: ' . $command);
		if (strtoupper(substr(php_uname('s'), 0, 3)) === 'WIN') {
			$command = '"' . $command . '"';
		}
		exec($command);
		$progress['progress_backup']['percent']=100;
		$elapsed=time()-$progress['progress_backup']['starttime'];
		$progress['progress_backup']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_backup']['estimated']="00:00:00";
		write_progress($progress);
		
	} else {

	// Deleting friend relationships
		$progress['progress_friends']['starttime']=time();
		write_progress($progress);
		$statement="DELETE FROM {$CONFIG->dbprefix}entity_relationships WHERE (guid_one>=" . $first_guid . " AND guid_one<=" . $last_guid . ") OR (guid_two>=" . $first_guid . " AND guid_two<=". $last_guid . ")";
		delete_data($statement);
		$progress['progress_friends']['percent']=100;
		$elapsed=time()-$progress['progress_friends']['starttime'];
		$progress['progress_friends']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_friends']['estimated']="00:00:00";
		write_progress($progress);
	
	// Deleting metadata directly related to users
		$progress['progress_users_metadata']['starttime']=time();
		write_progress($progress);
		$statement="DELETE FROM {$CONFIG->dbprefix}metadata WHERE entity_guid>=".$first_guid." AND entity_guid<=".$last_guid;
		delete_data($statement);
		$progress['progress_users_metadata']['percent']=100;
		$elapsed=time()-$progress['progress_users_metadata']['starttime'];
		$progress['progress_users_metadata']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_users_metadata']['estimated']="00:00:00";
		write_progress($progress);
			
	// Deleting users from the elggusers_entity table
		$progress['progress_users_users']['starttime']=time();
		write_progress($progress);
		$statement="DELETE FROM {$CONFIG->dbprefix}users_entity WHERE guid>=".$first_guid." AND guid<=".$last_guid;
		delete_data($statement);
		$progress['progress_users_users']['percent']=100;
		$elapsed=time()-$progress['progress_users_users']['starttime'];
		$progress['progress_users_users']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_users_users']['estimated']="00:00:00";
		write_progress($progress);
	
	// Deleting users from the elggentities table
		$progress['progress_users_entities']['starttime']=time();
		write_progress($progress);
		$statement="DELETE FROM {$CONFIG->dbprefix}entities WHERE guid>=".$first_guid." AND guid<=".$last_guid;
		delete_data($statement);
		$progress['progress_users_entities']['percent']=100;
		$elapsed=time()-$progress['progress_users_entities']['starttime'];
		$progress['progress_users_entities']['elapsed']=gmdate("H:i:s",$elapsed);
		$progress['progress_users_entities']['estimated']="00:00:00";
		write_progress($progress);
		
	// Getting mail and blog entries of generated users
		$query="SELECT MIN(guid) min_guid, MAX(guid) max_guid FROM {$CONFIG->dbprefix}entities WHERE type='object' AND owner_guid>=".$first_guid." AND owner_guid<=".$last_guid;
		$row=get_data_row($query);
		$first_object_guid=$row->min_guid;
		$last_object_guid=$row->max_guid;
	
		if ($first_object_guid and $last_object_guid) {
		// Deleting metadata related to users' mails and blogs
			$progress['progress_objects_metadata']['starttime']=time();
			write_progress($progress);
			$statement="DELETE FROM {$CONFIG->dbprefix}metadata WHERE entity_guid>=".$first_object_guid." AND entity_guid<=".$last_object_guid;
			delete_data($statement);
			$progress['progress_objects_metadata']['percent']=100;
			$elapsed=time()-$progress['progress_objects_metadata']['starttime'];
			$progress['progress_objects_metadata']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_objects_metadata']['estimated']="00:00:00";
			write_progress($progress);
		
		// Deleting mails and blogs from the elggobjects_entity table
			$progress['progress_objects_objects']['starttime']=time();
			write_progress($progress);
			$statement="DELETE FROM {$CONFIG->dbprefix}objects_entity WHERE guid>=".$first_object_guid." AND guid<=".$last_object_guid;
			delete_data($statement);
			$progress['progress_objects_objects']['percent']=100;
			$elapsed=time()-$progress['progress_objects_objects']['starttime'];
			$progress['progress_objects_objects']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_objects_objects']['estimated']="00:00:00";
			write_progress($progress);
		
		// Deleting mails end blogs from the elggentities table
			$progress['progress_objects_entities']['starttime']=time();
			write_progress($progress);
			$statement="DELETE FROM {$CONFIG->dbprefix}entities WHERE guid>=".$first_object_guid." AND guid<=".$last_object_guid;
			delete_data($statement);
			$progress['progress_objects_entities']['percent']=100;
			$elapsed=time()-$progress['progress_objects_entities']['starttime'];
			$progress['progress_objects_entities']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_objects_entities']['estimated']="00:00:00";
			write_progress($progress);
			
		// Deleting comments on genereted blog entries and uploaded comments
			$progress['progress_annotations']['starttime']=time();
			write_progress($progress);
			$statement="DELETE FROM {$CONFIG->dbprefix}annotations WHERE entity_guid>=".$first_object_guid." AND entity_guid<=".$last_object_guid;
			delete_data($statement);
			$progress['progress_annotations']['percent']=100;
			$elapsed=time()-$progress['progress_annotations']['starttime'];
			$progress['progress_annotations']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_annotations']['estimated']="00:00:00";
			write_progress($progress);
			
		}	
		
	// Final cleanup of the elggmetastrings table: delete generated user guids and tags
	
		$query="SELECT * FROM {$CONFIG->dbprefix}metastrings WHERE string like \"hu_skawa_genusers_cleanup:%\"";
		$cleanup_ranges=get_data($query);
		
		if ($cleanup_ranges!==false) {
			$progress['progress_metastrings']['starttime']=time();
			write_progress($progress);
			for($i=0; $i<count($cleanup_ranges); $i++) {
				$cleanup_range=explode(':',$cleanup_ranges[$i]->string);
				$fromId=$cleanup_range[1];
				$toId=$cleanup_range[2];
				$statement="DELETE FROM {$CONFIG->dbprefix}metastrings WHERE id>=".$fromId." AND id<=".$toId;
				delete_data($statement);
				$progress['progress_metastrings']['percent']=intval(100*$i/count($cleanup_ranges));
				$elapsed=time()-$progress['progress_metastrings']['starttime'];
				$progress['progress_metastrings']['elapsed']=gmdate("H:i:s",$elapsed);
				if ($progress['progress_metastrings']['percent']>=$min_percent_for_estimate) {
					$progress['progress_metastrings']['estimated']=gmdate("H:i:s",((100*$elapsed)/$progress['progress_metastrings']['percent'])-$elapsed);
				}
				write_progress($progress);
			}
			$statement="DELETE FROM {$CONFIG->dbprefix}metastrings WHERE string like \"hu_skawa_genusers_cleanup:%\"";
			delete_data($statement);
			$progress['progress_metastrings']['percent']=100;
			$elapsed=time()-$progress['progress_metastrings']['starttime'];
			$progress['progress_metastrings']['elapsed']=gmdate("H:i:s",$elapsed);
			$progress['progress_metastrings']['estimated']="00:00:00";
			write_progress($progress);
		}
	}	

//	$endTimeStamp=microtime(true);
//	system_message(sprintf(elgg_echo('messages:deletedallusers'),$endTimeStamp-$startTimeStamp));
//	forward($_SERVER['HTTP_REFERER']);


	
	/**
	* rm() -- Recursive deletion of files and/or directories
	* 
	* @param $fileglob a file name (foo.txt), glob pattern (*.txt), or directory name.
	*/
	function rm($fileglob)
	{
       if (is_file($fileglob)) {
           return unlink($fileglob);
       } else if (is_dir($fileglob)) {
           $ok = rm("$fileglob/*");
           if (! $ok) {
               return false;
           }
           return rmdir($fileglob);
       } else {
           $matching = glob($fileglob);
           if ($matching === false) {
               trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
               return false;
           }       
           $rcs = array_map('rm', $matching);
           if (in_array(false, $rcs)) {
               return false;
           }
       }       
	   return true;
	}

?>