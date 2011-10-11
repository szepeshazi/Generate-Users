<?php

require_once($CONFIG->pluginspath . "hu_skawa_genusers/common/common.php");
global $CONFIG;


$labels_deletegeneral = elgg_echo('labels:deletegeneral');
$users_deleteicons_label = elgg_echo('labels:deleteicons');
$users_deleteicons_field = elgg_view('input/checkboxes', array('internalname' => 'deleteicons', 'value' => '1' ,'options' => array(' ' => '1')));

$disabled_functions=ini_get("disabled_functions");
$backupfile=$CONFIG->pluginspath . "hu_skawa_genusers/log/elggdbdump.sql";
if ((strpos($disabled_functions,"exec")===false) && file_exists($backupfile)) {
	$default_method='fast';
	$disable_method_selection=false;
	$fastmethod=elgg_echo('labels:method:fast:enabled');
} else {
	$default_method='old';
	$disable_method_selection=true;
	$fastmethod=elgg_echo('labels:method:fast:disabled');
}
$oldmethod = elgg_echo('labels:method:old');
$users_delete_method=elgg_view('input/radio', array('value' => $default_method, 'class' => 'input-checkbox', 'disabled' => $disable_method_selection, 'internalname' => 'delete_method', 'options' => array($fastmethod => 'fast', $oldmethod => 'old')));
$mysql_label = elgg_echo('mysql:path');
$mysql_field = elgg_view('input/text', array('internalname' => 'path_to_mysql', 'value' => '' ));


$delete = elgg_view('input/submit', array('value' => elgg_echo('users:delete'), 'internalid' => 'delsubmit'));
$labels_userlist = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('labels:userlist').'</div>';

$jmeter_newtest = elgg_echo('jmeter:newtest');
$jmeter_labels_general = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('jmeter:labels:general').'</div>';
$jmeter_onlineusers_label = elgg_echo('jmeter:labels:onlineusers');
$jmeter_onlineusers_field = elgg_view('input/text', array('internalname' => 'jmeter_onlineusers', 'value' => '50' ));
$users_password_label = elgg_echo('jmeter:labels:reenterpassword');
$users_password_field = elgg_view('input/text', array('internalname' => 'defaultpassword', 'value' => 'abc123'));
$jmeter_rampup_label = elgg_echo('jmeter:labels:rampup');
$jmeter_rampup_field = elgg_view('input/text', array('internalname' => 'jmeter_rampup', 'value' => '25' ));
$jmeter_testlength_label = elgg_echo('jmeter:labels:testlength');
$jmeter_testlength_field = elgg_view('input/text', array('internalname' => 'jmeter_testlength', 'value' => '600' ));
$jmeter_minpause_label = elgg_echo('jmeter:labels:minpause');
$jmeter_minpause_field = elgg_view('input/text', array('internalname' => 'jmeter_minpause', 'value' => '5' ));
$jmeter_maxpause_label = elgg_echo('jmeter:labels:maxpause');
$jmeter_maxpause_field = elgg_view('input/text', array('internalname' => 'jmeter_maxpause', 'value' => '60' ));

$jmeter_labels_activities = '<div style="font-weight:bold; background-color:#dddddd;">'.elgg_echo('jmeter:labels:activities').'</div>';

$jmeter_dashboard_label = elgg_echo('jmeter:labels:dashboard');
$jmeter_dashboard_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_dashboard', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_friends_label = elgg_echo('jmeter:labels:friends');
$jmeter_friends_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_friends', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_friendsof_label = elgg_echo('jmeter:labels:friendsof');
$jmeter_friendsof_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_friendsof', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_messages_label = elgg_echo('jmeter:labels:messages');
$jmeter_messages_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_messages', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_sendmessage_label = elgg_echo('jmeter:labels:sendmessage');
$jmeter_sendmessage_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_sendmessage', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_blogs_label = elgg_echo('jmeter:labels:blogs');
$jmeter_blogs_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_blogs', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_blogcomment_label = elgg_echo('jmeter:labels:blogcomment');
$jmeter_blogcomment_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_blogcomment', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_profile_label = elgg_echo('jmeter:labels:profile');
$jmeter_profile_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_profile', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_profileupdate_label = elgg_echo('jmeter:labels:profileupdate');
$jmeter_profileupdate_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_profileupdate', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_uploads_label = elgg_echo('jmeter:labels:uploads');
$jmeter_uploads_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_uploads', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_uploadcomment_label = elgg_echo('jmeter:labels:uploadcomment');
$jmeter_uploadcomment_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_uploadcomment', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_newupload_label = elgg_echo('jmeter:labels:newupload');
$jmeter_newupload_field = elgg_view('input/checkboxes', array('internalname' => 'jmeter_newupload', 'value' => '1' ,'options' => array(' ' => '1')));
$jmeter_createnew = elgg_view('input/submit', array('value' => elgg_echo('jmeter:labels:createnew')));

$generated_user_count=search_generated_users(true);
$limit = get_input('limit', 10);
$offset = get_input('offset', 0);
$generated_users=search_generated_users(false, $offset, $limit);
$user_list= elgg_view_entity_list($generated_users, $generated_user_count, $offset, $limit, false, false, true);

$label_users = elgg_echo('label:users');

$progress_label = elgg_echo('progress:label');
$progress_activity = elgg_echo('progress:activity');
$progress_current = elgg_echo('progress:current');
$progress_elapsed = elgg_echo('progress:elapsed');
$progress_estimated = elgg_echo('progress:estimated');
$progress_icons = elgg_echo('progress:icons');
$progress_friends = elgg_echo('progress:friends');

$progress_users_metadata = elgg_echo('progress:users:metadata');
$progress_users_table = elgg_echo('progress:users:table');
$progress_users_entities = elgg_echo('progress:users:entities');
$progress_objects_metadata = elgg_echo('progress:objects:metadata');
$progress_objects_table = elgg_echo('progress:objects:table');
$progress_objects_entities = elgg_echo('progress:objects:entities');
$progress_annotations = elgg_echo('progress:annotations');
$progress_metastrings = elgg_echo('progress:metastrings');
$progress_dbrestore = elgg_echo('progress:dbrestore');
$progress_total = elgg_echo('progress:total');
$progress_deleted = elgg_echo('progress:deleted');
$progress_continue = elgg_echo('progress:continue');

$site_url=$vars['url'];
$continue_url = $site_url.'pg/genusers';
$progress_bg = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbar.gif";
$progress_yellow = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbg_yellow.gif";
$progress_green = $site_url."mod/hu_skawa_genusers/vendors/jquery.progressbar/images/progressbg_green.gif";

$delete_page_header = <<< END_HEADER
	<script language="JavaScript" type="text/javascript">
		var progress_key = '<?= $uuid ?>';
		var keepTimerAlive=true;
		$(document).ready(function() {
		    $('#deltabs').tabs({ disabled: [3] });

			$("#progress_icons").progressBar(0, {boxImage : "$progress_bg",
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
			$("#progress_users_metadata").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_users_users").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_users_entities").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_objects_metadata").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_objects_objects").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_objects_entities").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_annotations").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_metastrings").progressBar(0, {boxImage : "$progress_bg",
												barImage : {
													0:	"$progress_yellow",
													100: "$progress_green"
												}}
			);
			$("#progress_backup").progressBar(0, {boxImage : "$progress_bg",
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
		        success:		afterDel
		    }; 
 		    $('#delform').ajaxForm(formoptions); 
		});
		
		function startProgress(formData, jqForm, options) {
			$("#delsubmit").addClass("pbrow-hidden");
			$("#progress-tab").removeClass("ui-tabs-hide");
			$('#deltabs').tabs('enable', 3);
			$('#deltabs').tabs('select', 3);
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
						$("#"+singleprop).progressBar(eval('json.'+singleprop+'.percent'));
						$("#"+singleprop+"_elapsed").html(eval('json.'+singleprop+'.elapsed'));
						$("#"+singleprop+"_estimated").html(eval('json.'+singleprop+'.estimated'));
					}
				});
				if (!keepTimerAlive) timer.stop();
			});
			return true;
		}		

		function afterDel(responseText, statusText)  { 
			$("#progress_status_div").removeClass("pbrow-hidden");
			keepTimerAlive=false;
		}		
	</script>
	<div>
		<div id="deltabs" class="ui-tabs-nav" style="width: 99%">
			<ul>
				<li class="ui-tabs-selected"><a href="#section-1">$label_users</a></li>
				<li class=""><a href="#section-2">$jmeter_newtest</a></li>
				<li class=""><a href="#section-3">$labels_deletegeneral</a></li>
				<li id="progress-tab" class="ui-tabs-hide"><a href="#section-4">$progress_label</a></li>
			</ul>
			<div id="section-1" class="ui-tabs-panel">
				$labels_userlist
				$user_list
			</div>
			<div id="section-2" class="ui-tabs-panel">
END_HEADER;

$jmeter_form = <<< END_JMETER
					$jmeter_labels_general
					$jmeter_onlineusers_label
					$jmeter_onlineusers_field
					$users_password_label
					$users_password_field
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
					$jmeter_blogs_label
					$jmeter_blogs_field
					$jmeter_blogcomment_label
					$jmeter_blogcomment_field
					$jmeter_profile_label
					$jmeter_profile_field
					$jmeter_profileupdate_label
					$jmeter_profileupdate_field
					$jmeter_uploads_label
					$jmeter_uploads_field
					$jmeter_uploadcomment_label
					$jmeter_uploadcomment_field
					$jmeter_newupload_label
					$jmeter_newupload_field
					$jmeter_createnew
					<br /><br />
END_JMETER;

$separator = <<< END_SEPARATOR
				</div>
				<div id="section-3" class="ui-tabs-panel ui-tabs-hide">
END_SEPARATOR;


$delete_form = <<< END_DELETE
					<br />
					$users_delete_method
					$mysql_label
					$mysql_field
					<br />
					$users_deleteicons_label
					<br />
					$users_deleteicons_field
					$delete
END_DELETE;
$delete_page_footer = <<< END_FOOTER
			</div>
			<div id="section-4" class="ui-tabs-panel" style="font-size: 0.9em;">
				<div class="pbrow pbrow-headerrow">
					<span class="pbcol1">$progress_activity</span>
					<span class="pbcol2">$progress_current</span>
					<span class="pbcol3">$progress_elapsed</span>
					<span class="pbcol4">$progress_estimated</span>
								</div>
				<div class="pbrow pbrow-hidden" id="progress_icons_div">
					<span class="pbcol1" id="progress_icons_label">$progress_icons</span>
					<span class="progressBar pbcol2" id="progress_icons">0%</span>
					<span class="pbcol3" id="progress_icons_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_icons_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_friends_div">
					<span class="pbcol1" id="progress_friends_label">$progress_friends</span>
					<span class="progressBar pbcol2" id="progress_friends">0%</span>
					<span class="pbcol3" id="progress_friends_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_friends_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_users_metadata_div">
					<span class="pbcol1" id="progress_users_metadata_label">$progress_users_metadata</span>
					<span class="progressBar pbcol2" id="progress_users_metadata">0%</span>
					<span class="pbcol3" id="progress_users_metadata_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_users_metadata_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_users_users_div">
					<span class="pbcol1" id="progress_users_users_label">$progress_users_table</span>
					<span class="progressBar pbcol2" id="progress_users_users">0%</span>
					<span class="pbcol3" id="progress_users_users_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_users_users_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_users_entities_div">
					<span class="pbcol1" id="progress_users_entities_label">$progress_users_entities</span>
					<span class="progressBar pbcol2" id="progress_users_entities">0%</span>
					<span class="pbcol3" id="progress_users_entities_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_users_entities_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_objects_metadata_div">
					<span class="pbcol1" id="progress_objects_metadata_label">$progress_objects_metadata</span>
					<span class="progressBar pbcol2" id="progress_objects_metadata">0%</span>
					<span class="pbcol3" id="progress_objects_metadata_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_objects_metadata_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_objects_objects_div">
					<span class="pbcol1" id="progress_objects_objects_label">$progress_objects_table</span>
					<span class="progressBar pbcol2" id="progress_objects_objects">0%</span>
					<span class="pbcol3" id="progress_objects_objects_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_objects_objects_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_objects_entities_div">
					<span class="pbcol1" id="progress_objects_entities_label">$progress_objects_entities</span>
					<span class="progressBar pbcol2" id="progress_objects_entities">0%</span>
					<span class="pbcol3" id="progress_objects_entities_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_objects_entities_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_annotations_div">
					<span class="pbcol1" id="progress_annotations_label">$progress_annotations</span>
					<span class="progressBar pbcol2" id="progress_annotations">0%</span>
					<span class="pbcol3" id="progress_annotations_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_annotations_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_metastrings_div">
					<span class="pbcol1" id="progress_metastrings_label">$progress_metastrings</span>
					<span class="progressBar pbcol2" id="progress_metastrings">0%</span>
					<span class="pbcol3" id="progress_metastrings_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_metastrings_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-hidden" id="progress_backup_div">
					<span class="pbcol1" id="progress_backup_label">$progress_dbrestore</span>
					<span class="progressBar pbcol2" id="progress_backup">0%</span>
					<span class="pbcol3" id="progress_backup_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_backup_estimated">&nbsp;</span>
				</div>
				<div class="pbrow pbrow-totalrow">
					<span class="pbcol1">$progress_total</span>
					<span class="progressBar pbcol2" id="progress_total">0%</span>
					<span class="pbcol3" id="progress_total_elapsed">&nbsp;</span>
					<span class="pbcol4" id="progress_total_estimated">&nbsp;</span>
				</div>
				<div id="progress_status_div" class="pbrow-hidden status-div">
					<div style="display:block;">$progress_deleted</div> 
					<div style="display:block;"><a href="$continue_url">$progress_continue</a></div>
				</div>
				
			</div>
		<div>
	</div>
</div>		
END_FOOTER;



echo $delete_page_header;
echo elgg_view('input/form', array('action' => "{$vars['url']}action/hu_skawa_genusers/createtest", "method" => "post", "body" => $jmeter_form));
echo $separator;
echo elgg_view('input/form', array('action' => "{$vars['url']}action/hu_skawa_genusers/delete", "internalid" => "delform", "method" => "post", "body" => $delete_form));
echo $delete_page_footer;
?>
