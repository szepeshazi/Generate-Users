<?php

	$english = array(
		'genusers' => "Generate test users for your Elgg installation",

		'parameters:user' => "User parameters",
		'parameters:jmeter' => "JMeter test parameters",
		'label:users' => "Generated users",
	
		'genusers:short' => "Generate users",
		'mysqldump:path' => 'Path to your mysqldump command (like /usr/bin/ or C:/Program Files/MySQL/bin/)',
		'mysql:path' => 'Path to your mysql command (like /usr/bin/ or C:/Program Files/MySQL/bin/, only for fast deletion)',
		'users:number' => "Number of users to be generated (1 .. 1,000,000)",
		'users:prefix' => "Username prefix (4 .. 8 characters, starting with a letter)",
		'users:password' => "Default password for generated users (6 .. 10 characters)",
		'users:createicons' => "Create icons for users from image template files (slows down user generation)",
		'users:friends' => "Average number of friends for any given generated user (0 .. number of users-1)",
		'users:emails' => "Average number of internal mails sent by any given user (0 .. 1000)",
		'users:blogs' => "Average number of blog entries created by any given user (0 .. 100)",
		'users:blogcomments' => "Average number of comments on blog entries created by any given user (0 .. 100)",
		'users:uploads' => "Average number of files uploaded by any given user (0 .. 100, slows down user generation)",
		'users:uploadcomments' => "Average number of comments on uploaded contents created by any given user (0 .. 100)",

		'users:generate' => "Generate users now!",
		'users:delete' => "Delete all previously generated users!",
		
		'labels:general' => "General section",
		'labels:icons' => "Icons for generated users",
		'labels:friends' => "Friends",
		'labels:emails' => "Internal messages",
		'labels:blogs' => "Blog entries",
		'labels:uploads' => "File uploads",

		'jmeter:dotest' => "Create Apache JMeter test for generated users",
		'jmeter:newtest' => "New JMeter test configuration",
		
		'jmeter:labels:general' => "General JMeter test parameters",
		'jmeter:labels:onlineusers' => "Number of generated users to keep online during load test (1 .. number of generated users)",
		'jmeter:labels:reenterpassword' => "Reenter password for previously generated users",
		'jmeter:labels:rampup' => "Ramp up period for all test users in seconds (all threads will start within this timeframe, 1 .. 10,000)",
		'jmeter:labels:testlength' => "Total duration of a user session in seconds (approximate value, 5 .. 50,000)",
		'jmeter:labels:minpause' => "Minimum idle time between two activities for any given user in seconds (1 .. 300)",
		'jmeter:labels:maxpause' => "Maximum idle time between two activities for any given user in seconds (minimum idle time .. 600)",

		'jmeter:labels:activities' => "Select what kind of activities users should perform during a login session",

		'jmeter:labels:dashboard' => "View dashboard page",
		'jmeter:labels:friends' => "View friends page",
		'jmeter:labels:friendsof' => "View \"friends of\" page",
		'jmeter:labels:messages' => "Check messages in inbox",
		'jmeter:labels:sendmessage' => "Send a random message to another generated user",
		'jmeter:labels:blogs' => "Read a random blog entry",
		'jmeter:labels:blogcomment' => "Create a random comment on a random blog entry",
		'jmeter:labels:profile' => "View own profile",
		'jmeter:labels:profileupdate' => "Update \"About me\" section of own profile with random text",
		'jmeter:labels:uploads' => "View a random uploaded file",
		'jmeter:labels:uploadcomment' => "Create a random comment on a random uploaded file",
		'jmeter:labels:newupload' => "Upload a new random image file",
		'jmeter:labels:createnew' => "Create new JMeter test scenario now!",
		
		'labels:deletegeneral' => "Delete generated users",
		'labels:deleteicons' => "Also remove all icon files and uploaded content from disk",
		'labels:userlist' => "List of previously generated users",
		'labels:method:fast:enabled' => "Fast deletion (restore database)",
		'labels:method:fast:disabled' => "Fast deletion (requires exec() function and previous backup file)",
		'labels:method:old' => "Slow deletion (with delete statements per db table)",

		'progress:label' => "Progress report",
		'progress:activity' => "Activity",
		'progress:current' => "Current progress",
		'progress:elapsed' => "Elapsed time",
		'progress:estimated' => "Estimated time left",
		'progress:users' => "Users",
		'progress:friends' => "Friend relationships",
		'progress:icons' => "Icons",
		'progress:messages' => "Messages",
		'progress:blogs' => "Blogs",
		'progress:blogcomments' => "Blog comments",
		'progress:files' => "Uploaded files",
		'progress:filecomments' => "Uploaded file comments",
		'progress:total' => "Total",

		'progress:users:metadata' => "Users/Metadata",
		'progress:users:table' => "Users table",
		'progress:users:entities' => "Users/Entities",
		'progress:objects:metadata' => "Objects/Metadata",
		'progress:objects:table' => "Objects table",
		'progress:objects:entities' => "Objects/Entities",
		'progress:annotations' => "Annotations",
		'progress:metastrings' => "Metastrings",
		'progress:dbrestore' => "Database restore",
	
		
		'progress:finished' => "User generation successfully finished.",
		'progress:deleted' => "Previously generated users successfully deleted.",
		'progress:continue' => "Click here to continue.",
	
	
		'messages:success' => "Users successfully generated with user identifiers from \"%s\" to \"%s\" in %s seconds.",
		'messages:jmeter:success' => "Jmeter test file successfully generated in %s seconds.",
		'messages:deletedallusers' => "All previously generated users successfully deleted in %s seconds.",

		'errors:num_of_users' => "Number of users is invalid.",
		'errors:num_of_friends' => "Number of friends is invalid.",
		'errors:user_prefix' => "User prefix is invalid.",
		'errors:default_password' => "Default password is invalid.",
		'errors:num_of_emails' => "Number of emails is invalid.",
		'errors:num_of_blogs' => "Number of blog entries is invalid.",
		'errors:num_of_blogcomments' => "Number of blog comments is invalid.",
		'errors:num_of_uploads' => "Number of files to be uploaded is invalid.",
		'errors:num_of_uploadcomments' => "Number of uploaded file comments is invalid.",
		
		'errors:jmeter_onlineusers' => "Invalid number of users to keep online during load test.",
		'errors:jmeter_rampup' => "Invalid ramp up period.",
		'errors:jmeter_testlength' => "Invalid duration for a user session.",
		'errors:jmeter_minpause' => "Invalid minimum idle time between two activities for any given user.",
		'errors:jmeter_maxpause' => "Invalid maximum idle time between two activities for any given user."
	);
					
	add_translation("en",$english);

?>