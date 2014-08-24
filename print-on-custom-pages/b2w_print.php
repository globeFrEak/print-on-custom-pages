<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System					 |
| Copyright (C) 2002 - 2011 Nick Jones					 |
| http://www.php-fusion.co.uk/							 |
+--------------------------------------------------------+
| Filename: b2w_print.php								 |
| Author: Sebastian S.  |  teeshock						 |
| Version 2.00											 |
+--------------------------------------------------------+
| This program is released as free software under the	 |
| Affero GPL license. You can redistribute it and/or	 |
| modify it under the terms of this license which you	 |
| can read by viewing the included agpl.txt or online	 |
| at www.gnu.org/licenses/agpl.html. Removal of this	 |
| copyright header is strictly prohibited without	 	 |
| written permission from the original author(s).		 |
+--------------------------------------------------------*/
require_once "maincore.php";
include LOCALE.LOCALESET."print.php";

if ($settings['maintenance'] == "1" && ((iMEMBER && $settings['maintenance_level'] == "1" && $userdata['user_id'] != "1") || ($settings['maintenance_level'] > $userdata['user_level']))) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."', user_ip_type='".USER_IP_TYPE."' WHERE user_id='".$userdata['user_id']."'"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
//-------------- Original mit URL-Ausgabe -----
/*echo "<style type='text/css'>
	* { background: transparent !important; color: #444 !important; text-shadow: none; }
	body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }
	hr { display:block; height:1px; border:0; border-top:1px solid #ccc; margin:1em 0; padding:0; }
	.small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }
	.small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }
	a, a:visited { color: #444 !important; text-decoration: underline; }
	a:after { content: ' (' attr(href) ')'; }
	abbr:after { content: ' (' attr(title) ')'; }
	pre, blockquote { border: 1px solid #999; page-break-inside: avoid; }
	img { page-break-inside: avoid; }
	@page { margin: 0.5cm; }
	p, h2, h3 { orphans: 3; widows: 3; }
	h2, h3 { page-break-after: avoid; }
</style>\n";*/
//---- Ohne URL-Ausgabe ------
echo "<style type='text/css'>
	* { background: transparent !important; color: #444 !important; text-shadow: none; }
	body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }
	hr { display:block; height:1px; border:0; border-top:1px solid #ccc; margin:1em 0; padding:0; }
	.small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }
	.small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }
	a, a:visited { color: #444 !important; text-decoration: underline; }
	abbr:after { content: ' (' attr(title) ')'; }
	pre, blockquote { border: 1px solid #999; page-break-inside: avoid; }
	img { page-break-inside: avoid; }
	@page { margin: 0.5cm; }
	p, h2, h3 { orphans: 3; widows: 3; }
	h2, h3 { page-break-after: avoid; }
</style>\n";
//-----------------------------
echo "</head>\n<body>\n";
if ((isset($_GET['type']) && $_GET['type'] == "C") && (isset($_GET['item_id']) && isnum($_GET['item_id']))) {
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	$result = dbquery(
		"SELECT * FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$_GET['item_id']."'"
	);
	$res = false;
	if (dbrows($result)) {
		$data = dbarray($result);
		if (checkgroup($data['page_access'])) {
			$res = true;
			
			echo "<strong>".$data['page_title']."</strong><br />\n";
			
			ob_start();
			eval("?>".stripslashes($data['page_content'])."<?php ");
			$custompage = ob_get_contents();
			ob_end_clean();
			$custompage = preg_split("/<!?--\s*pagebreak\s*-->/i", $custompage);
			$pagecount = count($custompage);
			echo $custompage[$_GET['rowstart']];
			
		}
	}
	if (!$res) { redirect("index.php"); }
} else {
	redirect("index.php");
}
echo "</body>\n</html>\n";

if (ob_get_length() !== FALSE){
	ob_end_flush();
}

//---- mit Druckermenue ---------
echo "
<script type='text/javascript'>
   self.print();
</script>";

mysql_close($db_connect);
?>