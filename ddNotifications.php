<?php
/**
 *@ ddNotifications for MyBB
 *@
 *@ Author: DevDJ
 *@ Version: 1.1
 *@ Release Date: 19/06/2017
 *@ Contact: contact@devdj.pl
 */

// Disable direct access to this file
if(!defined("IN_MYBB")){
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook('global_start', 'ddnotifications_templates');
$plugins->add_hook('global_intermediate', 'ddnotifications_global');

/**
 * Function containing Plugin Information.
 *
 * @return array informations
 */
function ddnotifications_info(){
	global $mybb;

    // Check if the option is active
    if($mybb->settings['ddnotifications_active'] == 1){
		$ddnotifications_config_link = '<div style="float: right;"><a href="index.php?module=config&amp;action=change&amp;search=ddnotifications" style="color:#035488; background: url(../images/usercp/options.gif) no-repeat 0px 18px; padding: 18px; text-decoration: none;">Config</a></div>';
	}
	
	return [
        "name"			=> "ddNotifications",
    	"description"	=> "Notifications for Your Forum" . $ddnotifications_config_link,
		"website"		=> "https://www.mybb.com",
		"author"		=> "DevDJ",
		"authorsite"	=> "https://devdj.pl",
		"version"		=> "1.1",
		"codename" 		=> "ddnotifications",
		"compatibility" => "18*"
	];
} 

/**
 * Plugin activation function.
 *
 * @param  object  CreateUserDto
 * @return array message
 */
function ddnotifications_activate(){
   	global $mybb, $cache, $db, $lang, $templates;

    $lang->load("ddnotifications", false, true);

    // Create the options group
    $query = $db->simple_select("settinggroups", "COUNT(*) as rows");
    $rows = $db->fetch_field($query, "rows");

    $ddnotifications_groupconfig = [
        'name' => 'ddnotifications',
        'title' => "ddNotifications",
        'description' => "Display nice notifications to your forum",
        'disporder' => $rows+1,
        'isdefault' => 0
    ];

    $group['gid'] = $db->insert_query("settinggroups", $ddnotifications_groupconfig);

    // Create the plugin options to be used
    $ddnotifications_config = [];

	// Notifications enable option
    $ddnotifications_config[] = [
        'name' => 'ddnotifications_active',
        'title' => "Enable notifications on your forums",
        'description' => "Set to no if you want to disable this plugin (Not display Notifications on your forums if disabled)",
        'optionscode' => 'yesno',
        'value' => '1',
        'disporder' => 10,
        'gid' => $group['gid']
    ];

	// Notification #1 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_1_gid",
		"title"			=> "Notification #1 (Color: Yellow)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> '1,5,7',
		"disporder"		=> 20,
		"gid"			=> $group['gid']
	];	

	// Notification #2 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_2_gid",
		"title"			=> "Notification #2 (Color: Blue)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 30,
		"gid"			=> $group['gid']
	];	

	// Notification #3 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_3_gid",
		"title"			=> "Notification #3 (Color: Red)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> '3,6',
		"disporder"		=> 40,
		"gid"			=> $group['gid']
	];	

	// Notification #4 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_4_gid",
		"title"			=> "Notification #4 (Color: Purple)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 4,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];	
	
	// Notification #5 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_5_gid",
		"title"			=> "Notification #5 (Color: Pink)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];

	// Notification #6 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_6_gid",
		"title"			=> "Notification #6 (Color: Black)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];
	
	// Notification #7 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_7_gid",
		"title"			=> "Notification #7 (Color: Lime)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];
	
	// Notification #8 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_8_gid",
		"title"			=> "Notification #8 (Color: Orange)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];
	
	// Notification #9 - Option
	$ddnotifications_config[] = [
		"name"			=> "ddnotifications_config_9_gid",
		"title"			=> "Notification #9 (Color: Green-Yellow)",
		"description"   => "Select Groups",
		"optionscode" 	=> "groupselect",
		"value"			=> 2,
		"disporder"		=> 50,
		"gid"			=> $group['gid']
	];
	
    foreach($ddnotifications_config as $array => $content){
        $db->insert_query("settings", $content);
    }

	// Rebuild settings file to load new settings...
	rebuild_settings();

	// Adding new group of templates for this plugin...  
	$templategrouparray = [
		'prefix' => 'ddnotifications',
		'title'  => 'ddNotifications'
	];
	$db->insert_query("templategroups", $templategrouparray);
	
	// Adding new templates
	$templatearray = [
		'title' => 'ddnotifications_1',
		'template' => $db->escape_string('<div class="ddnotifications_2">Hi, this is an Notification #1</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_2',
		'template' => $db->escape_string('<div class="ddnotifications_2">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_4',
		'template' => $db->escape_string('<div class="ddnotifications_4">Hi, {$mybb->user[\'username\']} this is an Notification for Administrators only</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_3',
		'template' => $db->escape_string('<div class="ddnotifications_3">Hi, {$mybb->user[\'username\']} this is an Notification for all Moderators</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_5',
		'template' => $db->escape_string('<div class="ddnotifications_5">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_6',
		'template' => $db->escape_string('<div class="ddnotifications_6">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_7',
		'template' => $db->escape_string('<div class="ddnotifications_7">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_8',
		'template' => $db->escape_string('<div class="ddnotifications_8">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);
	$templatearray = [
		'title' => 'ddnotifications_9',
		'template' => $db->escape_string('<div class="ddnotifications_9">Hi, {$mybb->user[\'username\']} this is an Notification for all registered Users on the forum</div>'),
		'sid' => '-2',
		'version' => '1806',
		'dateline' => TIME_NOW
	];
	$db->insert_query("templates", $templatearray);	

	// Add stylesheet
	$ddnotifications_css = '.ddnotifications_4 {
	color: white;
	background-color: purple;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_2 {
	color: white;
	background-color: cyan;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_5 {
	color: white;
	background-color: pink;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_6 {
	color: white;
	background-color: black;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_7 {
	color: white;
	background-color: lime;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_8 {
	color: white;
	background-color: orange;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_9 {
	color: white;
	background-color: #ccff00;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_3 {
	color: white;
	background-color: red;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}

.ddnotifications_1 {
	color: white;
	background-color: yellow;
	padding: 10px;
	border-left: 6px solid green;
	border-right: 6px solid green;
	border-radius: 5px;
	margin: 0px;
	text-align: center;
	font-size: 20px;
	font-style: bold;
	margin-bottom: 10px;
	box-shadow:
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	position: center;
}';

	$stylesheet = [
		"name" => "ddnotifications.css",
		"tid" => 1,
		"attachedto" => '',		
		"stylesheet" => $db->escape_string($ddnotifications_css),
		"cachefile" => "ddnotifications.css",
		"lastmodified" => TIME_NOW,
	];
	$sid = $db->insert_query("themestylesheets", $stylesheet);

	// File required for style and template changes
	require_once MYBB_ADMIN_DIR.'/inc/functions_themes.php';
	cache_stylesheet($stylesheet['tid'], $stylesheet['cachefile'], $ddnotifications_css);
	update_theme_stylesheet_list(1, false, true);

    // File required for template replacement
   	require "../inc/adminfunctions_templates.php";
	
	/**
     * Replacements we are going to make in the templates
	 * 1) Template
	 * 2) Content to Replace
	 * 3) Content that replaces the previous.
	 */
    find_replace_templatesets("header", '#'.preg_quote('{$pm_notice}').'#', '{$pm_notice}{$ddnotifications}');

    // The template information is updated
   	$cache->update_forums();
}

/**
 * Plugin deactivation function.
 */
function ddnotifications_deactivate(){
	global $mybb, $cache, $db;
  
    // We delete the created style sheet...
   	$db->delete_query('themestylesheets', "name='ddnotifications.css'");
	$query = $db->simple_select('themes', 'tid');
	while($theme = $db->fetch_[$query)){
		require_once MYBB_ADMIN_DIR.'inc/functions_themes.php';
		update_theme_stylesheet_list($theme['tid']);
	}

    // File required for template replacement
 	require MYBB_ROOT.'inc/adminfunctions_templates.php';
	
	/**
     * Replacements we are going to make in the templates
	 * 1) Template
	 * 2) Content to Replace
	 * 3) Content that replaces the previous.
	 */
    find_replace_templatesets("header", '#'.preg_quote('{$ddnotifications}').'#', '',0);
	
	// Delete templates
	$db->delete_query("templategroups", "prefix='ddnotifications'");
	$db->delete_query("templates", "title IN('ddnotifications_1','ddnotifications_2','ddnotifications_3','ddnotifications_4','ddnotifications_5','ddnotifications_6','ddnotifications_7','ddnotifications_8','ddnotifications_9')");
	// Delete settings
	$db->delete_query("settings", "name IN ('ddnotifications_active', 'ddnotifications_config_1_gid', 'ddnotifications_config_2_gid', 'ddnotifications_config_3_gid', 'ddnotifications_config_4_gid', 'ddnotifications_config_5_gid', 'ddnotifications_config_6_gid', 'ddnotifications_config_8_gid', 'ddnotifications_config_8_gid')");
	$db->delete_query("settinggroups", "name='ddnotifications'");

    rebuild_settings();
	
    // The template information is updated
  	$cache->update_forums();
}

/**
 * Function returning the names of all templates.
 *
 * @return string templates
 */
function ddnotifications_templates(){
    global $mybb, $templates, $templatelist;

	if($mybb->settings['ddnotifications_active'] == 0){
        return false;
    }

	if(isset($templatelist)){
		$templatelist .= ",ddnotifications_1,ddnotifications_2,ddnotifications_3,ddnotifications_4,ddnotifications_5,ddnotifications_6,ddnotifications_7,ddnotifications_8,ddnotifications_9";
	}
}

/**
 * Function checking user groups and displaying the appropriate notification template.
 *
 * @return tempalte notification
 */
function ddnotifications_global(){
    global $mybb, $theme, $templates, $ddnotifications;

	if($mybb->settings['ddnotifications_active'] == 0){
        return false;
    }

	$ddnotifications = "";
	$ddnotifications_11 = $mybb->settings['ddnotifications_config_1_gid'];
	$ddnotifications_22 = $mybb->settings['ddnotifications_config_2_gid'];
	$ddnotifications_33 = $mybb->settings['ddnotifications_config_3_gid'];
	$ddnotifications_44 = $mybb->settings['ddnotifications_config_4_gid'];
	$ddnotifications_55 = $mybb->settings['ddnotifications_config_5_gid'];
	$ddnotifications_66 = $mybb->settings['ddnotifications_config_6_gid'];
	$ddnotifications_77 = $mybb->settings['ddnotifications_config_7_gid'];
	$ddnotifications_88 = $mybb->settings['ddnotifications_config_8_gid'];
	$ddnotifications_99 = $mybb->settings['ddnotifications_config_9_gid'];
	$mybb->user['usergroup'] = (int)$mybb->user['usergroup'];
	
	if(!empty($mybb->user['additionalgroups'])){
		$add_groups = explode(",", $mybb->user['additionalgroups']);
		foreach($add_groups as $add_group){
			$group = $add_group;
		}
	}
	
	if(!empty($ddnotifications_11)){
		$ddnotifications_11 = explode(",", $ddnotifications_11);
		foreach($ddnotifications_11 as $notifi1){
			if($mybb->user['usergroup'] == $notifi1){
				$notifi1_show = true;
			}
		}
	}

	if(!empty($ddnotifications_22)){
		$ddnotifications_22 = explode(",", $ddnotifications_22);
		foreach($ddnotifications_22 as $notifi2){
			if($mybb->user['usergroup'] == $notifi2){
				$notifi2_show = true;
			}
		}
	}

	if(!empty($ddnotifications_33)){
		$ddnotifications_33 = explode(",", $ddnotifications_33);
		foreach($ddnotifications_33 as $notifi3){
			if($mybb->user['usergroup'] == $notifi3){
				$notifi3_show = true;
			}
		}
	}

	if(!empty($ddnotifications_44)){
		$ddnotifications_44 = explode(",", $ddnotifications_44);
		foreach($ddnotifications_44 as $notifi4){
			if($mybb->user['usergroup'] == $notifi4){
				$notifi4_show = true;
			}
		}
	}
	
	if(!empty($ddnotifications_55)){
		$ddnotifications_55 = explode(",", $ddnotifications_55);
		foreach($ddnotifications_55 as $notifi5){
			if($mybb->user['usergroup'] == $notifi5){
				$notifi5_show = true;
			}
		}
	}

	if(!empty($ddnotifications_66)){
		$ddnotifications_66 = explode(",", $ddnotifications_66);
		foreach($ddnotifications_66 as $notifi6){
			if($mybb->user['usergroup'] == $notifi6){
				$notifi6_show = true;
			}
		}
	}
	
	if(!empty($ddnotifications_77)){
		$ddnotifications_77 = explode(",", $ddnotifications_77);
		foreach($ddnotifications_77 as $notifi7){
			if($mybb->user['usergroup'] == $notifi7){
				$notifi7_show = true;
			}
		}
	}
	
	if(!empty($ddnotifications_88)){
		$ddnotifications_88 = explode(",", $ddnotifications_88);
		foreach($ddnotifications_88 as $notifi8){
			if($mybb->user['usergroup'] == $notifi8){
				$notifi8_show = true;
			}
		}
	}
	
	if(!empty($ddnotifications_99)){
		$ddnotifications_99 = explode(",", $ddnotifications_99);
		foreach($ddnotifications_99 as $notifi9){
			if($mybb->user['usergroup'] == $notifi9){
				$notifi9_show = true;
			}
		}
	}
	
	if($mybb->user['uid'] == 0 || $notifi1_show == true){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_1",1,0)."\";");	
	}	
	if($notifi2_show == true || $group == $ddnotifications_22){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_2",1,0)."\";");	
	}	
	if($notifi3_show == true || $group == $ddnotifications_33){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_3",1,0)."\";");	
	}	
	if($notifi4_show == true || $group == $ddnotifications_44){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_4",1,0)."\";");	
	}
	if($notifi5_show == true || $group == $ddnotifications_55){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_5",1,0)."\";");	
	}	
	if($notifi6_show == true || $group == $ddnotifications_66){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_6",1,0)."\";");	
	}	
	if($notifi7_show == true || $group == $ddnotifications_77){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_7",1,0)."\";");	
	}
	if($notifi8_show == true || $group == $ddnotifications_88){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_8",1,0)."\";");	
	}	
	if($notifi9_show == true || $group == $ddnotifications_99){
		eval("\$ddnotifications .= \"".$templates->get("ddnotifications_9",1,0)."\";");	
	}	
	
	return $ddnotifications;
}

?>