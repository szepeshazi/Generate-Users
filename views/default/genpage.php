<?php

echo "<div id=\"hu_skawa_genusers\">";

$parameters_user = elgg_echo('parameters:user');
$parameters_jmeter = elgg_echo('parameters:jmeter');


// User parameters display section
$labels_general = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:general').'</div>';

$mysqldump_label = elgg_echo('mysqldump:path');
$mysqldump_field = elgg_view('input/text', array('name' => 'path_to_mysqldump', 'value' => '' ));

$users_number_label = elgg_echo('users:number');
$users_number_field = elgg_view('input/text', array('name' => 'numofusers', 'value' => '50' ));
$users_prefix_label = elgg_echo('users:prefix');
$users_prefix_field = elgg_view('input/text', array('name' => 'userprefix', 'value' => 'user'));
$users_password_label = elgg_echo('users:password');
$users_password_field = elgg_view('input/text', array('name' => 'defaultpassword', 'value' => 'abc123'));

$labels_icons = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:icons').'</div>';
$users_createicons_label = elgg_echo('users:createicons');
$users_createicons_field = elgg_view('input/checkboxes', array('name' => 'createicons', 'value' => '1' ,'options' => array(' ' => '1')));

$labels_friends = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:friends').'</div>';
$users_friends_label = elgg_echo('users:friends');
$users_friends_field = elgg_view('input/text', array('name' => 'numoffriends', 'value' => '5'));

$labels_emails = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:emails').'</div>';
$users_mails_label = elgg_echo('users:emails');
$users_mails_field = elgg_view('input/text', array('name' => 'numofemails', 'value' => '5'));

$labels_blogs = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:blogs').'</div>';
$users_blogs_label = elgg_echo('users:blogs');
$users_blogs_field = elgg_view('input/text', array('name' => 'numofblogs',  'value' => '1'));
$users_blogcomments_label = elgg_echo('users:blogcomments');
$users_blogcomments_field = elgg_view('input/text', array('name' => 'numofblogcomments',  'value' => '1'));

$labels_uploads = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:uploads').'</div>';
$users_uploads_label = elgg_echo('users:uploads');
$users_uploads_field = elgg_view('input/text', array('name' => 'numofuploads', 'value' => '2'));
$users_uploadcomments_label = elgg_echo('users:uploadcomments');
$users_uploadcomments_field = elgg_view('input/text', array('name' => 'numofuploadcomments', 'value' => '2'));

// JMeter test parameters display section
$jmeter_dojmeter_label = elgg_echo('jmeter:dotest');
$jmeter_dojmeter_field = elgg_view('input/checkboxes', array('name' => 'dojmeter', 'value' => '1' ,'options' => array(' ' => '1')));

$jmeter_labels_general = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('jmeter:labels:general').'</div>';
$jmeter_onlineusers_label = elgg_echo('jmeter:labels:onlineusers');
$jmeter_onlineusers_field = elgg_view('input/text', array('name' => 'jmeter_onlineusers', 'value' => '50' ));
$jmeter_rampup_label = elgg_echo('jmeter:labels:rampup');
$jmeter_rampup_field = elgg_view('input/text', array('name' => 'jmeter_rampup', 'value' => '25' ));
$jmeter_testlength_label = elgg_echo('jmeter:labels:testlength');
$jmeter_testlength_field = elgg_view('input/text', array('name' => 'jmeter_testlength', 'value' => '600' ));
$jmeter_minpause_label = elgg_echo('jmeter:labels:minpause');
$jmeter_minpause_field = elgg_view('input/text', array('name' => 'jmeter_minpause', 'value' => '5' ));
$jmeter_maxpause_label = elgg_echo('jmeter:labels:maxpause');
$jmeter_maxpause_field = elgg_view('input/text', array('name' => 'jmeter_maxpause', 'value' => '60' ));

$jmeter_labels_activities = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('jmeter:labels:activities').'</div>';

$jmeter_dashboard_label = elgg_echo('jmeter:labels:dashboard');
$jmeter_dashboard_field = elgg_view('input/checkboxes', array('name' => 'jmeter_dashboard', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_friends_label = elgg_echo('jmeter:labels:friends');
$jmeter_friends_field = elgg_view('input/checkboxes', array('name' => 'jmeter_friends', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_friendsof_label = elgg_echo('jmeter:labels:friendsof');
$jmeter_friendsof_field = elgg_view('input/checkboxes', array('name' => 'jmeter_friendsof', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_messages_label = elgg_echo('jmeter:labels:messages');
$jmeter_messages_field = elgg_view('input/checkboxes', array('name' => 'jmeter_messages', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_sendmessage_label = elgg_echo('jmeter:labels:sendmessage');
$jmeter_sendmessage_field = elgg_view('input/checkboxes', array('name' => 'jmeter_sendmessage', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_blogs_label = elgg_echo('jmeter:labels:blogs');
$jmeter_blogs_field = elgg_view('input/checkboxes', array('name' => 'jmeter_blogs', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_blogcomment_label = elgg_echo('jmeter:labels:blogcomment');
$jmeter_blogcomment_field = elgg_view('input/checkboxes', array('name' => 'jmeter_blogcomment', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_profile_label = elgg_echo('jmeter:labels:profile');
$jmeter_profile_field = elgg_view('input/checkboxes', array('name' => 'jmeter_profile', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_profileupdate_label = elgg_echo('jmeter:labels:profileupdate');
$jmeter_profileupdate_field = elgg_view('input/checkboxes', array('name' => 'jmeter_profileupdate', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_uploads_label = elgg_echo('jmeter:labels:uploads');
$jmeter_uploads_field = elgg_view('input/checkboxes', array('name' => 'jmeter_uploads', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_uploadcomment_label = elgg_echo('jmeter:labels:uploadcomment');
$jmeter_uploadcomment_field = elgg_view('input/checkboxes', array('name' => 'jmeter_uploadcomment', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_newupload_label = elgg_echo('jmeter:labels:newupload');
$jmeter_newupload_field = elgg_view('input/checkboxes', array('name' => 'jmeter_newupload', 'value' => '1' ,'options' => array(' ' => '1')));

$generate = elgg_view('input/submit', array('value' => elgg_echo('users:generate'), 'id' => 'gensubmit'));


$progress_label = elgg_echo('progress:label');
$progress_activity = elgg_echo('progress:activity');
$progress_current = elgg_echo('progress:current');
$progress_elapsed = elgg_echo('progress:elapsed');
$progress_estimated = elgg_echo('progress:estimated');
$progress_users = elgg_echo('progress:users');
$progress_friends = elgg_echo('progress:friends');
$progress_icons = elgg_echo('progress:icons');
$progress_messages = elgg_echo('progress:messages');
$progress_blogs = elgg_echo('progress:blogs');
$progress_blogcomments = elgg_echo('progress:blogcomments');
$progress_files = elgg_echo('progress:files');
$progress_filecomments = elgg_echo('progress:filecomments');
$progress_total = elgg_echo('progress:total');
$progress_finished = elgg_echo('progress:finished');
$progress_continue = elgg_echo('progress:continue');

$site_url = $vars['url'];
$continue_url = $site_url.'pg/genusers';
$progress_bg = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbar.gif";
$progress_yellow = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbg_yellow.gif";
$progress_green = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbg_green.gif";


$gen_form = <<< END
	<script language="JavaScript" type="text/javascript">
		var progress_key = '<?= $uuid ?>';
		var keepTimerAlive=true;
		$(document).ready(function() {
		    $('#gentabs').tabs({ disabled: [2] });

			$("#progress_users").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_friends").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_icons").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_messages").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_blogs").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_blogcomments").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_uploads").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_uploadcomments").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_total").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$.ajaxSetup({cache:false}); 

		    var formoptions = { 
		        beforeSubmit:	startProgress, 
		        success:		afterGen
		    }; 
 		    $('#genform').ajaxForm(formoptions); 
		});

		function startProgress(formData, jqForm, options) {
			$("#gensubmit").addClass("pbrow-hidden");
			$("#progress-tab").removeClass("ui-tabs-hide");
			$('#gentabs').tabs('enable', 2);
			$('#gentabs').tabs('select', 2);
			var firstrun=true;
			$.timer(1000, function (timer) {
				$.getJSON("$site_url"+"mod/hu_skawa_genusers/actions/progress.json", function(json){
					if (firstrun) {
						firstrun=false;
						var rowcount=0;
						for (var singleprop in json) {
							if (eval('json.'+singleprop+'.enabled')) {
								$("#"+singleprop+"_div").removeClass("pbrow-hidden");
								$("#"+singleprop+"_div").addClass("pbrow-visible");
								if (rowcount==0) $("#"+singleprop+"_div").addClass("pbrow-toprow");
								if ((rowcount%2==1) && (singleprop!="progress_total")) {
									$("#"+singleprop+"_div").removeClass("pbrow");
									$("#"+singleprop+"_div").addClass("pbrow-act");

									$("#"+singleprop+"_label").removeClass("pbcol1");
									$("#"+singleprop).removeClass("pbcol2");
									$("#"+singleprop+"_elapsed").removeClass("pbcol3");
									$("#"+singleprop+"_estimated").removeClass("pbcol4");

									$("#"+singleprop+"_label").addClass("pbcol1-act");
									$("#"+singleprop).addClass("pbcol2-act");
									$("#"+singleprop+"_elapsed").addClass("pbcol3-act");
									$("#"+singleprop+"_estimated").addClass("pbcol4-act");
								}
								rowcount++;
							}
						}
					}
					for (var singleprop in json) {
						if (eval('json.'+singleprop+'.enabled')) {
							$("#"+singleprop).progressBar(eval('json.'+singleprop+'.percent'));
							$("#"+singleprop+"_elapsed").attr("innerHTML",eval('json.'+singleprop+'.elapsed'));
							$("#"+singleprop+"_estimated").attr("innerHTML",eval('json.'+singleprop+'.estimated'));
						}
					}
				});
				if (!keepTimerAlive) timer.stop();
			});
			return true;
		}		

		function afterGen(responseText, statusText)  { 
			keepTimerAlive=false;
			$.getJSON("$site_url"+"mod/hu_skawa_genusers/actions/progress.json", function(json){
				for (var singleprop in json) {
					if (eval('json.'+singleprop+'.enabled')) {
						$("#"+singleprop).progressBar(eval('json.'+singleprop+'.percent'));
						$("#"+singleprop+"_elapsed").attr("innerHTML",eval('json.'+singleprop+'.elapsed'));
						$("#"+singleprop+"_estimated").attr("innerHTML",eval('json.'+singleprop+'.estimated'));
					}
				}
				if (json.status.currentstatus!='ok') {
					$("#status_message").attr("innerHTML",json.status.message);
					$("#status_message").addClass("error-div");
				}
				$("#progress_status_div").removeClass("pbrow-hidden");
			});

		}
	</script>
	<div>
		<div id="gentabs" class="ui-tabs-nav" style="width: 99%">
			<ul>
				<li class="ui-tabs-selected"><a href="#section-1">$parameters_user</a></li>
				<li class=""><a href="#section-2">$parameters_jmeter</a></li>
				<li id="progress-tab" class="ui-tabs-hide"><a href="#section-3">$progress_label</a></li>
			</ul>
			<div id="section-1" class="ui-tabs-panel">
				<br />
				$labels_general
				$mysqldump_label
				$mysqldump_field
				$users_number_label
				$users_number_field
				$users_prefix_label
				$users_prefix_field
				$users_password_label
				$users_password_field
				<br /><br />
				$labels_icons
				$users_createicons_label
				<br />
				$users_createicons_field
				<br />
				$labels_friends
				$users_friends_label
				$users_friends_field
				<br /><br />
				$labels_emails
				$users_mails_label
				$users_mails_field
				<br /><br />
				$labels_blogs
				$users_blogs_label
				$users_blogs_field
				$users_blogcomments_label
				$users_blogcomments_field
				<br /><br />
				$labels_uploads
				$users_uploads_label
				$users_uploads_field
				$users_uploadcomments_label
				$users_uploadcomments_field
			</div>
			<div id="section-2" class="ui-tabs-panel">
				<br />
				$jmeter_dojmeter_label
				$jmeter_dojmeter_field
				<br />
				$jmeter_labels_general
				$jmeter_onlineusers_label
				$jmeter_onlineusers_field
				$jmeter_rampup_label
				$jmeter_rampup_field
				$jmeter_testlength_label
				$jmeter_testlength_field
				$jmeter_minpause_label
				$jmeter_minpause_field
				$jmeter_maxpause_label
				$jmeter_maxpause_field
				<br /><br />
				$jmeter_labels_activities
				$jmeter_dashboard_label
				$jmeter_dashboard_field
				$jmeter_friends_label
				$jmeter_friends_field
				$jmeter_friendsof_label
				$jmeter_friendsof_field
				$jmeter_messages_label
				$jmeter_messages_field
				$jmeter_sendmessage_label
				$jmeter_sendmessage_field
				$jmeter_profile_label
				$jmeter_profile_field
				$jmeter_profileupdate_label
				$jmeter_profileupdate_field
				$jmeter_blogs_label
				$jmeter_blogs_field
				$jmeter_blogcomment_label
				$jmeter_blogcomment_field
				$jmeter_uploads_label
				$jmeter_uploads_field
				$jmeter_uploadcomment_label
				$jmeter_uploadcomment_field
				$jmeter_newupload_label
				$jmeter_newupload_field
			</div>
			<div id="section-3" class="ui-tabs-panel" style="font-size: 0.9em;">
				<div class="pbrow pbrow-headerrow">
					<span class="pbcol1">$progress_activity</span>
					<span class="pbcol2">$progress_current</span>
					<span class="pbcol3">$progress_elapsed</span>
					<span class="pbcol4">$progress_estimated</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_users_div">
					<span class="pbcol1" id="progress_users_label">$progress_users</span>
					<span class="progressBar pbcol2" id="progress_users">0%</span>
					<span class="pbcol3" id="progress_users_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_users_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_friends_div">
					<span class="pbcol1" id="progress_friends_label">$progress_friends</span>
					<span class="progressBar pbcol2" id="progress_friends">0%</span>
					<span class="pbcol3" id="progress_friends_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_friends_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_icons_div">
					<span class="pbcol1" id="progress_icons_label">$progress_icons</span>
					<span class="progressBar pbcol2" id="progress_icons">0%</span>
					<span class="pbcol3" id="progress_icons_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_icons_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_messages_div">
					<span class="pbcol1" id="progress_messages_label">$progress_messages</span>
					<span class="progressBar pbcol2" id="progress_messages">0%</span>
					<span class="pbcol3" id="progress_messages_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_messages_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_blogs_div">
					<span class="pbcol1" id="progress_blogs_label">$progress_blogs</span>
					<span class="progressBar pbcol2" id="progress_blogs">0%</span>
					<span class="pbcol3" id="progress_blogs_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_blogs_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_blogcomments_div">
					<span class="pbcol1" id="progress_blogcomments_label">$progress_blogcomments</span>
					<span class="progressBar pbcol2" id="progress_blogcomments">0%</span>
					<span class="pbcol3" id="progress_blogcomments_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_blogcomments_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_uploads_div">
					<span class="pbcol1" id="progress_uploads_label">$progress_files</span>
					<span class="progressBar pbcol2" id="progress_uploads">0%</span>
					<span class="pbcol3" id="progress_uploads_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_uploads_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_uploadcomments_div">
					<span class="pbcol1" id="progress_uploadcomments_label">$progress_filecomments</span>
					<span class="progressBar pbcol2" id="progress_uploadcomments">0%</span>
					<span class="pbcol3" id="progress_uploadcomments_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_uploadcomments_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-totalrow">
					<span class="pbcol1">$progress_total</span>
					<span class="progressBar pbcol2" id="progress_total">0%</span>
					<span class="pbcol3" id="progress_total_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_total_estimated">&nbsp;</span>
				</div>
				<div id="progress_status_div" class="pbrow-hidden status-div">
					<div id="status_message" style="display:block;">$progress_finished</div> 
					<div style="display:block;"><a href="$continue_url">$progress_continue</a></div>
				</div>
			</div>
		</div>
		$generate
	</div>
END;

echo elgg_view('input/form', array('action' => "{$vars['url']}action/hu_skawa_genusers/generate", "id" => "genform", "method" => "post", "body" => $gen_form));

echo "</div>";
?>
