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
$version = "1.0";
//
//misc functions
//
function getBlueTitle($version) 
{
  return  "bluebery ".$version." on ". $_SERVER['SERVER_NAME'];
}
function printEntityContent($key, $type, $lang)
{
  echo getEntityContent( getEntity($key, $type, $lang) );
}
//
//auth functions
//
//include auth
function doAuth($file, $table) 
{
    
    $version = "1.0";
    $auth_text = getBlueTitle($version);
	$db = connectBlue();
	$result = getRows(connectBlue(), $table);
	$users = array();
	while($myrow = nextEntity($result))
    {
	     $users[$myrow[1]]=$myrow[2];
	}	
	if(!(empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) && $_SERVER['PHP_AUTH_PW']==$users[$_SERVER['PHP_AUTH_USER']])
	{
		include($file);
	}
	else{
		header("www-authenticate: basic realm=\"$auth_text\"");
		header("http/1.0 401 unauthorized");
	}
  
}
//header include verification
function doProtection($file, $message)
{
    if(!strstr($_SERVER['PHP_SELF'], $file)) die($message);
}

//
//basic peer functions
//
//return: connection id
function connectBlue()
{
    include "bluesql.php";
    return connectDB($sql_usr, $sql_pwd, $sql_host, $sql_db); 
}
//performing basic peer queries and returning several rows
//return: mysql result rows
function getAllEntities()
{
  $db = connectBlue();
  include "blueconst.php";
  $rows = getRows($db, $content_table);
  return $rows;
}
//return: mysql result rows
function getEntitiesByType($type, $lang, $orderby) 
{

  $db = connectBlue();
  include "blueconst.php";
  $rows = getRowsByColValuesInOrder($db, $content_table, array($type_col, $lang_col), array("'".$type."'", "'".$lang."'"), $orderby);
  return $rows;
}
//return: mysql result rows
function getEntitiesByKey($key, $lang, $orderby)
{

  $db = connectBlue();
  include "blueconst.php";
  $rows = getRowsByColValues($db, $content_table, array($key_col, $lang_col), array("'".$key."'", "'".$lang."'"));
  return rows;
}
//return: mysql result rows
function getEntities($key, $type, $lang)
{

  $db = connectBlue();
  include "blueconst.php";
  $rows = getRowsByColValuesInOrder($db, $content_table, array($key_col, $type_col, $lang_col), array("'".$key."'", "'".$type."'", "'".$lang."'"), "ORDER BY id DESC");
  return rows;
}

//iterator 
//return: next entity in a result set from the above basic query results. 
function nextEntity($result)
{
  return mysql_fetch_row($result);
}

//
//column to field mapping 
//
//mapping the fields of entities to columns to read peer query results.
//used by to read from the peer query results by the entity class.
function getEntityId($row)
{
  return $row[0];
}
function getEntityContent($row)
{
  return $row[1];
}
function getEntityDate($row)
{
  return $row[2];
}
function getEntityKey($row)
{
  return $row[3];
}
function getEntityType($row)
{
  return $row[4];
}
function getEntityLang($row)
{
  return $row[5];
}


//
//persistance fucntions.
//these functions essentially pass over the values of entities to perform corresponding peer queries using the PFFF functions.
//used by the entity class persistance features.
//result: mysql result 
//return : mysql row array
//
function getEntity($key, $type, $lang)
{
  $db = connectBlue();
  include "blueconst.php";  
  $row = getRowByColValues($db, $content_table, array($key_col, $type_col, $lang_col), array("'".$key."'", "'".$type."'", "'".$lang."'"));
  return $row;
}
function insertEntity($content, $date, $key, $type, $lang) 
{
  $db = connectBlue();
  include "blueconst.php";
  return insertRow($db, $content_table, array($content_col, $date_col, $key_col, $type_col, $lang_col), array("'".$content."'", "'".$date."'", "'".$key."'", "'".$type."'", "'".$lang."'"));
}
function updateEntity($key, $type, $lang) 
{
  include "blueconst.php"; 
  $entity = getRowByColValues($db, $content_table, array($key_col, $type_col, $lang_col), array("'".$key."'", "'".$type."'", "'".$lang."'"));
  $id = getEntityId($entity);
  $db = connectBlue();
  if ($write) $result = updateRowById($db, $content_table, $id, array($key_col, $type_col, $lang_col), array($key, $type, $lang));
  return $result;  
}
function removeEntity($key, $type, $lang, $probe)
{
  $db = connectBlue();
  include "blueconst.php";
  $entity = getRowByColValues($db, $content_table, array($key_col, $type_col, $lang_col), array("'".$key."'", "'".$type."'", "'".$lang."'"));
  $myid = getEntityId($entity);
  if ($probe) return true;
  return deleteRowByID($db, $content_table, $myid);
}
//
//key factory
//
//creates a new that doesnt exist on the peer
function createKey($min, $max, $param)
{
  
  $keys = array();
  $fetch = getAllEntities();
  while($e = nextEntity($fetch))
  {
    $keys[getEntityKey($e)] = true;
  }
  $key = 0;
  $i = 0;
  while(@$keys[$key = rand($min, $max)])
  {
    $i++;
    if($i >= $MAX_TRIES) { break; }
  }
  return $param.$key;
  
}
//checks if a key exists on the peer
function isUsedKey($key)
{
  $keys = array();
  $fetch = getAllEntities();
  while($e = nextEntity($fetch))
  {
    $keys[getEntityKey($e)] = true;
  }
  return $keys[$key];
}

//
//interactive HTML functions
//
//creates a form variable or parameter name for html elements
function createName($key, $type)
{
  return $key."_".$type;
}
//fetches get argument key
function getActionKey() 
{
  if (isset($_GET["key"])){ return $_GET["key"]; }
  else { return "";}
}
//fetches get argument type
function getActionType() 
{
  if (isset($_GET["type"])){ return $_GET["type"]; }
  else { return "";}
}
//checks if an action has been performed
function isAction()
{
  return ( strlen(getActionKey()) > 0 ) ;
}
function htmlActionLink($content, $url, $key, $type) {
  return "<a href='".$url."?key=".$key."&type=".$type."'>".$content."</a>";
}
function htmlActionField($key, $type, $text, $size) 
{
  return getTextField(createName($key, $type), $size, $text);  
}
function htmlActionText($key, $type, $text, $rows, $cols) 
{
  return getTextArea(createName($key, $type), $rows, $cols, $text); 
}
function htmlActionForm($href, $key = "", $type = "", $data)
{
  $getvars = "?key=".$key."&type=".$type; 
  return getForm($data, $href.$getvars);
}
function htmlActionSubmit($key, $type, $text)
{
  return getController($type, $key, $text);
}
function htmlFormTable($labels, $elements, $controls)
{
  return getFormTable($labels, $elements, $controls);
}
function htmlParagraph($data, $style = "", $breaks = 0)
{
  $para = "<p style='".$style."'>";
  for ($i=0;$i<$breaks;$i++) 
  {
    $para .= "<br/>"; 
  }
  $para .= $data;
  $para .= "</p>";  	
  return $para;
}






?>