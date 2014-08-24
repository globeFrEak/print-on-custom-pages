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

if (file_exists(INFUSIONS . "print-on-custom-pages/locale/" . $settings['locale'] . ".php")) {
    include INFUSIONS . "print-on-custom-pages/locale/" . $settings['locale'] . ".php";
} else {
    include INFUSIONS . "print-on-custom-pages/locale/German.php";
}

if (!defined("DB_PRINT_ON_CUSTOM_PAGES")) {
	define("DB_PRINT_ON_CUSTOM_PAGES", DB_PREFIX."print_on_custom_pages");
}

?>