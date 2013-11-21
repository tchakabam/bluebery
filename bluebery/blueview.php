<?
/*
Copyright 2006 Stephan Hesse Stevomania

    This file is part of bluebery.

    bluebery is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    bluebery is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with bluebery; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/

include_once "header.php";
include "bluesql.php";
include "blueconst.php";

doProtection("index.php", "<center><p><p><font face='arial' color='blue'><h2>bluebery:invalid request");

$db = connectDB($sql_usr, $sql_pwd, $sql_host, $sql_db);
$content = $content_table;

$editmode = false;

if(isset($_POST["b_content_save"]))
{
  setSelectedID($_POST["saveid"]);
  $editmode = false;
}

if (isset($_POST["b_content_add"])) $_POST["b_content0"] = nl2br($_POST["b_content0"]);
updateHTMLTable($db, $content);

$editcontent = getTextArea("b_content0",6,50,"");
$editdate = getTextField("b_content1",30,date('d-m-Y'));
$editkey = getTextField("b_content2",30,"");
$edittype = getTextField("b_content3",30,"");
$editlang = getTextField("b_content4",30,"");

if(isset($_POST["b_content_edit"]))
{
  $editmode = true;
  $id = getSelectedID("b_content",0);
  $row = getRowByID($db,"b_content",$id);
  $editcontent = getTextArea("b_content0",6,50,$row[1]);
  $editdate = getTextArea("b_content1",6,50,$row[2]);
  $editkey = getTextField("b_content2",20,$row[3]);
  $edittype = getTextField("b_content3",20,$row[4]);
  $editlang = getTextField("b_content4",20,$row[5]);

}

$add = getController("add",$content,"Add");
$edit = getController("edit",$content,"Edit");
$save = getController("save",$content,"Save Entity ID ".getSelectedID($content,0));
if(!$editmode) $save = "";
$saveid = getHiddenValue("saveid",getSelectedID($content,0));

$labels = array('ID', 'Content', 'Date', 'Key', 'Type', "Language");
$table = getHTMLTable($db, "b_content", $labels, "background-color:#0000DF;");

$labels = array('Content', 'Date', 'Key', 'Type', "Language");
$elements = array($editcontent, $editdate, $editkey, $edittype, $editlang);
$controls = array($add, $save, $saveid);
$form = getFormTable($labels, $elements, $controls);


$form .= "<br><br><br><a href='http://www.getfirefox.com' target='links'><img height='60' src='imgs/firefox.jpg'></a><a href='http://www.mysql.com/why-mysql/' target='links'><img height='60' src='imgs/mysql.gif'></a><a href='http://www.php.net' target='links'><img height='60' src='imgs/php-logo.jpg'></a>";
$form = htmlDiv($form,   "position:fixed;top:70px;left:30px;", "", "");

$table = htmlDiv($edit.$table.$edit, "position:absolute;top:70px;left:550px;", "", "");

$body = "";
$body .= htmlStyle("body{background-color:#000099;color:white;font-family:Arial;}");
$body .= htmlStyle("img{border:0px;border-width:0px;}");
$body .= htmlDiv("bluebery ".$version." on ". $_SERVER['SERVER_NAME'], "position:fixed;top:20px;left:270px;font-family:Arial;font-size:20px;color:blue;", "", "title");
$body .= getForm($table.$form, $_SERVER['PHP_SELF']);

echo htmlStruct($body, getBlueTitle($version));
?>
