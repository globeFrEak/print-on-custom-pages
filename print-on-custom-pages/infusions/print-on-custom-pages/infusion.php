<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System					 |
| Copyright © 2002 - 2008 Nick Jones					 |
| http://www.php-fusion.co.uk/							 |
+--------------------------------------------------------+
| Filename: infusion.php								 |
| Author: teeshock										 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."print-on-custom-pages/infusion_db.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."print-on-custom-pages/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."print-on-custom-pages/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."print-on-custom-pages/locale/German.php";
}

// Infusion general information
$inf_title = $locale['pocp001'];
$inf_version = "2.0";
$inf_developer = "teeshock";
$inf_description = $locale['pocp002'];

$inf_folder = "print-on-custom-pages"; // The folder in which the infusion resides.

$inf_newtable[1] = DB_PRINT_ON_CUSTOM_PAGES." (
id int(11) NOT NULL auto_increment,
pocp_title varchar(100) NOT NULL,
pocp_config int(1) NOT NULL,
pocp_ckbox BOOL NOT NULL,
PRIMARY KEY (id)
) ENGINE=MyISAM;";

//$inf_insertdbrow[1] = DB_PRINT_ON_CUSTOM_PAGES." (pocp_title, pocp_id) VALUES('', '')";

$inf_droptable[1] = DB_PRINT_ON_CUSTOM_PAGES;

$inf_adminpanel[1] = array(
	"title" => $locale['pocp001'],
	"image" => "../infusions/print-on-custom-pages/images/pocp.png",
	"panel" => "pocp_infusion_admin.php",
	"rights" => "POC"
);
?>