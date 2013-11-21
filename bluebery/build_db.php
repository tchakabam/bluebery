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
?>
<?php
    //HEADER PROTECTION FOR AUTHENTIFICATION
    //UNCOMMENT FOR PRODUCTION OR RENAME THIS FILE TO *._php or so...
    //COMMENT FOR SETUP (TO DISABLE AUTH FOR THIS FILE COZ AUTH AINT THA)
    //if(!strstr($_SERVER['PHP_SELF'], "index.php"))die("Unauthorized Access");
    //inserting default values login:admin, pass:admin
?>
<p style="font:'Courier New', Courier, monospace">
<center>
<?
include("header.php");
if (isset($_POST["build"]))
{
  $id = connectDB($sql_usr, $sql_pwd, $sql_host, $sql_db);
  $content = $content_table;
  dropTable($id, $content);
  dropTable($id, $auth_table);  
  createTableIndexed($id, $content, true, 5);
  createTableIndexed($id, "b_auth", true, 2);
  $cols = array('b_auth0', 'b_auth1');
  $values = array("'admin'", "'admin'"); //inserting default values login:admin, pass:admin
  insertRow($id, "b_auth", $cols, $values);   
  //echo(getHTMLTable($id, "b_auth", array("ID", "User", "Pwd"), ""));
  echo("done.");
} 
else 
{
?>
this will drop and build blue db..submit to proceed
<br />
<form action="build_db.php" method="post">
<input type="hidden" value="1" name="build" />
<input type="submit" value="Do" />
</form>
<?
}
?>