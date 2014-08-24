<?php

/* -------------------------------------------------------+
  | PHP-Fusion Content Management System		   |
  | Copyright (c) 2002 - 2008 Nick Jones			   |
  | http://www.php-fusion.co.uk/			   |
  +--------------------------------------------------------+
  | Filename: pocp_infusion_admin.php			   |
  | Author: teeshock					   |
  +--------------------------------------------------------+
  | This program is released as free software under the    |
  | Affero GPL license. You can redistribute it and/or     |
  | modify it under the terms of this license which you    |
  | can read by viewing the included agpl.txt or online    |
  | at www.gnu.org/licenses/agpl.html. Removal of this     |
  | copyright header is strictly prohibited without        |
  | written permission from the original author(s).        |
  +-------------------------------------------------------- */
require_once "../../maincore.php";
require_once THEMES . "templates/admin_header.php";

include INFUSIONS . "print-on-custom-pages/infusion_db.php";

if (!defined("IN_FUSION")) {
    die("Access Denied");
}

//if (!checkrights("POC") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }
// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS . "print-on-custom-pages/locale/" . $settings['locale'] . ".php")) {
    // Load the locale file matching the current site locale setting.
    include INFUSIONS . "print-on-custom-pages/locale/" . $settings['locale'] . ".php";
} else {
    // Load the infusion's default locale file.
    include INFUSIONS . "print-on-custom-pages/locale/German.php";
}
$pocp_id = (isset($_POST['pocp_id']) && is_numeric($_POST['pocp_id']) ? mysql_real_escape_string($_POST['pocp_id']) : "FALSE");
$pocp_ckbox = (isset($_POST['pocp_ckbox']) ? filter_var_array($_POST['pocp_ckbox'], FILTER_VALIDATE_INT) : 0);
$pocp_title = (isset($_POST['pocp_title']) && !empty($_POST['pocp_title']) ? filter_var_array($_POST['pocp_title'], FILTER_SANITIZE_STRING) : 0);
$pocp_config = (isset($_POST['pocp_config']) && !empty($_POST['pocp_config']) ? filter_var_array($_POST['pocp_config'], FILTER_SANITIZE_STRING) : 0);

$ngespeichert = "ES traten fehler auf!";

if (isset($_POST['save'])) {
    
    var_dump($pocp_title);
    echo "<hr>";
    var_dump($pocp_config);
    echo "<hr>";
    var_dump($_POST['pocp_ckbox']);
    
   
    /*if ($pocp_id == FALSE) {
        for ($i = 0; $i < count($pocp_ckbox) && is_array($pocp_ckbox); $i++) {
            $result = dbquery("INSERT " . DB_PRINT_ON_CUSTOM_PAGES . " SET 
            pocp_title = '$pocp_title[$i]',
            pocp_config = '$pocp_config[$i]',
            pocp_ckbox = '$pocp_ckbox[$i]'");
        }
    } else {
        for ($i = 0; $i < count($pocp_ckbox) && is_array($pocp_ckbox); $i++) {
            $result = dbquery("UPDATE " . DB_PRINT_ON_CUSTOM_PAGES . " SET 
            pocp_title = '$pocp_title[$i]',
            pocp_config = '$pocp_config[$i]',
            pocp_ckbox = '$pocp_ckbox[$i]' WHERE id='$pocp_id'");
        }
    }
    redirect(FUSION_SELF); */
}
opentable($locale['pocp003']);

echo "<form name='pocp_settings' method='post' action='" . FUSION_SELF . $aidlink . "'>\n";

$result = dbquery("SELECT * FROM " . DB_CUSTOM_PAGES . " ORDER BY page_title ASC");
if (dbrows($result) > 0) {
    echo"<table align='center' class='tbl-border' width='80%'>";
    echo"
		<tr>
		<td align='center'>Page Title</td>
		<td align='center' width='90px'>printing aktiv</td>
		<td align='center' width='250px'>position</td>
		</tr>";
    $iii = 0;
    while ($data = dbarray($result)) {
        $result1 = dbquery("SELECT * FROM " . DB_PRINT_ON_CUSTOM_PAGES . " WHERE pocp_title ='" . $data['page_title'] . "'");
        $data1 = dbarray($result1);
        $cell_color = ($iii % 2 == 0 ? "tbl1" : "tbl2");        
        echo "<tr>";
        echo "<td class='$cell_color' align='center'>";
        if (dbrows($result1) > 0) {
            echo "<input type='textbox' name='pocp_id[]' value='" . $data1['id'] . "'>";
        }
        echo "<input type='hidden' name='pocp_title[]' value='" . $data['page_title'] . "'>" . $data['page_title'] . ""
        . "</td>";
        if (!empty($data1['pocp_title'])) {
            echo "<td class='$cell_color' align='center'><input type='checkbox' " . (($data1['pocp_ckbox'] == 1) ? "checked='checked'" : "") . " name='pocp_ckbox[]' value='$iii' style='width:10px; text-align:center'></td>";
        } else {
            echo "<td class='$cell_color' align='center'><input type='checkbox' name='pocp_ckbox[]' value='$iii' /></td>";
        }
        echo "<td class='tbl'><select name='pocp_config[]' class='textbox' style='width:100%'>\n";

        echo "<option" . ($data1['pocp_config'] == 0 ? " selected" : "") . " value='0'>Bitte w&auml;hlen</option>\n"
        . "<option" . ($data1['pocp_config'] == 3 ? " selected" : "") . " value='3'>Position Tabellenkopf</option>\n"
        . "<option" . ($data1['pocp_config'] == 4 ? " selected" : "") . " value='4'>Position oben & unten</option>\n"
        . "<option" . ($data1['pocp_config'] == 2 ? " selected" : "") . " value='2'>Position oben</option>\n"
        . "<option" . ($data1['pocp_config'] == 1 ? " selected" : "") . " value='1'>Position unten</option>\n";

        echo "</select></td>"
        . "</tr>";
        $iii++;
    }
    echo "<td align='center' class='tbl2' colspan='0'><input type='submit' name='save' value='" . $locale['pocp020'] . "' class='button' /></td>\n";
    echo "</table></form";
} else {
    echo " Keine Daten vorhanden";
}

closetable();

require_once THEMES . "templates/footer.php";
?>