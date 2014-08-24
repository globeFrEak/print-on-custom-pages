<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System					 |
| Copyright (C) 2002 - 2011 Nick Jones					 |
| http://www.php-fusion.co.uk/							 |
+--------------------------------------------------------+
| Filename: viewpage.php								 |
| Author: Nick Jones (Digitanium)						 |
| modfied by Sebastian S.  |  teeshock					 |
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
require_once THEMES."templates/header.php";
require_once INCLUDES."comments_include.php";
require_once INCLUDES."ratings_include.php";
include LOCALE.LOCALESET."custom_pages.php";
include INFUSIONS."print-on-custom-pages/infusion_db.php";

if (!isset($_GET['page_id']) || !isnum($_GET['page_id'])) { redirect("index.php"); }
if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

$cp_result = dbquery("SELECT * FROM ".DB_CUSTOM_PAGES." WHERE page_id='".$_GET['page_id']."'");
if (dbrows($cp_result)) {
	$cp_data = dbarray($cp_result);
	add_to_title($locale['global_200'].$cp_data['page_title']);
	echo "<!--custompages-pre-content-->\n";
	## modified by b2w and teeshock
	//--------- viewpages definieren, die druckbar sein sollen ------
	
	$result = dbquery("SELECT * FROM ".DB_PRINT_ON_CUSTOM_PAGES." WHERE pocp_title='".$cp_data['page_title']."'");
	$data = dbarray($result);
	
	if($data['pocp_ckbox'] == 1) {
		$drucken = 1;
	} else {$drucken = 0;}
	
	$PrinterConfig = $data['pocp_config'];
	
	//--------- viewpages definieren - Ende -------	
		if($PrinterConfig == 3 && checkgroup($cp_data['page_access']) && $drucken == 1) {
			$add = "<div align='right' style='float:right;'><a href='b2w_print.php?type=C&amp;item_id=".$_GET['page_id']."&amp;rowstart=".$_GET['rowstart']."' target='_blank'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a></div>\n";
		} else {
			$add = "";
		}
	opentable($cp_data['page_title'].$add);
	if (checkgroup($cp_data['page_access'])) {
		if($PrinterConfig == 2 || $PrinterConfig == 4 && $drucken == 1) {
			echo "<div align='right' style='float:right;'><a href='b2w_print.php?type=C&amp;item_id=".$_GET['page_id']."&amp;rowstart=".$_GET['rowstart']."' target='_blank'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a></div>\n";
		}
		## end b2w and teeshock
		ob_start();
		eval("?>".stripslashes($cp_data['page_content'])."<?php ");
		$custompage = ob_get_contents();
		ob_end_clean();
		$custompage = preg_split("/<!?--\s*pagebreak\s*-->/i", $custompage);
		$pagecount = count($custompage);
		echo $custompage[$_GET['rowstart']];

	} else {
		echo "<div class='admin-message' style='text-align:center'><br /><img style='border:0px; vertical-align:middle;' src ='".BASEDIR."images/warn.png' alt=''/><br /> ".$locale['400']."<br /><a href='index.php' onclick='javascript:history.back();return false;'>".$locale['403']."</a>\n<br /><br /></div>\n";
	}
} else {
	add_to_title($locale['global_200'].$locale['401']);
	echo "<!--custompages-pre-content-->\n";
	opentable($locale['401']);
	echo "<div style='text-align:center'><br />\n".$locale['402']."\n<br /><br /></div>\n";
}
closetable();
if (isset($pagecount) && $pagecount > 1) {
    echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 1, $pagecount, 3, FUSION_SELF."?page_id=".$_GET['page_id']."&amp;")."\n</div>\n";
}
echo "<!--custompages-after-content-->\n";
if (dbrows($cp_result) && checkgroup($cp_data['page_access'])) {
	if ($cp_data['page_allow_comments']) { showcomments("C", DB_CUSTOM_PAGES, "page_id", $_GET['page_id'],FUSION_SELF."?page_id=".$_GET['page_id']); }
	if ($cp_data['page_allow_ratings']) { showratings("C", $_GET['page_id'], FUSION_SELF."?page_id=".$_GET['page_id']); }
}

//------------Viewpage Drucker Anzeige am Ende der Seite - by teeshock -----------------
if($PrinterConfig == 1 || $PrinterConfig == 4) {
	$jahr = date("Y");
	if($drucken == 1){
		echo "<div align='center'><hr><br><b>&middot;</b>&nbsp;<a href='b2w_print.php?type=C&amp;item_id=".$_GET['page_id']."&amp;rowstart=".$_GET['rowstart']."' target='_blank'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>&nbsp;<a href='b2w_print.php?type=C&amp;item_id=".$_GET['page_id']."&amp;rowstart=".$_GET['rowstart']."' target='_blank' title='".$locale['global_075']."'>".$locale['409']."&nbsp;".$jahr."</a>&nbsp;<b>&middot;</b></div>\n";
	};
}
require_once THEMES."templates/footer.php";
?>
