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


//
//pfff - PHP Freaky Function Framework aka Pflaume
//
//Author : TcHakabAm aka Stephan Hesse
//

//
//framework parameters
//

$selected = 0;
$counter = -1;

//
//Global funtions
//
//@id : ID of an entity
//@selected : pointing to the value of the selected entity's id
//

function getSelectedID($control,$index)
{
  global $selected;
  return $selected;
}
function setSelectedID($id)
{
  global $selected;
  $selected = $id;
}
function nextInt()
{
  global $counter;
  $counter++;
  return $counter;
}


//
//mySQL functions
//
//
//@return : SQL transaction result pointers or row arrays for getrow-functions
//

function connectDB($user,$pwd,$host,$name)
{
  $id = mysql_connect($host,$user,$pwd); 
  mysql_select_db($name,$id);
  return $id;
}
function selectDB($name,$id)
{
  return mysql_select_db($name,$id);
}
function createTableNamed($id,$name,$autoincrement,$colsnames)
{
  $query = "";
  $query .= "CREATE TABLE";
  $query .= " ".$name." ";
  $query .= "(";
  if($autoincrement)
  {
    $query .= "id INT NOT NULL AUTO_INCREMENT,";
  }
  for($i=0;$i<sizeof($colsnames);$i++)
  {
    if($i != 0) $query .= ",";  
    $query .= $colsnames[$i]." TEXT"; 
    
  }
  if($autoincrement)
  {
    $query .= ", PRIMARY KEY (id)";
  }
  $query .= ")";  
  return mysql_query($query,$id);
  
}
function createTableIndexed($id,$name,$autoincrement,$cols)
{
  $query = "";
  $query .= "CREATE TABLE";
  $query .= " ".$name." ";
  $query .= "(";
  if($autoincrement)
  {
    $query .= "id INT NOT NULL AUTO_INCREMENT,";
  }
  for($i=0;$i<$cols;$i++)
  {
    if($i != 0 or $i != (sizeof($cols)-1)) $query .= ",";  
    $query .= $name.$i." TEXT"; 
    
  }
  if($autoincrement)
  {
    $query .= ", PRIMARY KEY (id)";
  }
  $query .= ")";  
  //echo $query;
  return mysql_query($query,$id);
  
}
function dropTable($id,$name)
{
  $query = "DROP TABLE ".$name;
  return mysql_query($query,$id);
}
function getRowByIndex($id,$name,$index)
{
  $query = "SELECT * FROM ".$name;
  $result = mysql_query($query,$id);
  while($row = mysql_fetch_row($result))
  {
	if($index == 0) return $row;
    $index--;
  }
  return 0;
}
function getCellByIndex($id,$name,$row,$col)
{
  $query = "SELECT * FROM ".$name;
  $result = mysql_query($query,$id);
  while($row = mysql_fetch_row($result))
  {
    $id--;
	if($id == 0) return $row[$col];
  }
  return 0;
}
function getRows($db,$name)
{
  $query = "SELECT * FROM ".$name." ORDER BY id DESC";
  $result = mysql_query($query,$db);
  //$rows = array();
  //$i=0;
  //while($rows[$i] = mysql_fetch_row($result)) $i++;
  //return $rows;
  return $result;
}
function getRowsInOrder($db,$name,$orderby,$app)
{
  $query = "SELECT * FROM ".$name." ORDER BY ".$orderby." ".$app;
  $result = mysql_query($query,$db);
  //$rows = array();
  //$i=0;
  //while($rows[$i] = mysql_fetch_row($result)) $i++;
  //return $rows;
  return $result;
}
function getRowByID($db,$name,$id)
{
  $query = "SELECT * FROM ".$name." WHERE id=".$id;
  //echo $query;
  $result = mysql_query($query,$db);
  return mysql_fetch_row($result);
}
function getRowByColValue($db, $name, $col, $value)
{
  $query = "SELECT * FROM ".$name." WHERE ".$col."=".$value;
  //echo $query;
  $result = mysql_query($query,$db);
  return mysql_fetch_row($result);
}
function getRowByColValues($db, $name, $cols, $values)
{
  $min = min(sizeof($cols), sizeof($values));
  $query = "SELECT * FROM ".$name." WHERE ";
  for ($i=0;$i<$min;$i++) 
  {
    $query .= $cols[$i]."=".$values[$i];
	if($i != $min - 1 ) $query .= " AND ";
  }
  //echo $query;
  $result = mysql_query($query,$db);
  return mysql_fetch_row($result);
}
function getRowsByColValues($db, $name, $cols, $values)
{
  $min = min(sizeof($cols), sizeof($values));
  $query = "SELECT * FROM ".$name." WHERE ";
  for ($i=0;$i<$min;$i++) 
  {
    $query .= $cols[$i]."=".$values[$i];
	if($i != $min - 1 ) $query .= " AND ";
  }
  //echo $query;
  $result = mysql_query($query,$db);
  return $result;
}
function getRowsByColValuesInOrder($db, $name, $cols, $values, $orderby)
{
  $min = min(sizeof($cols), sizeof($values));
  $query = "SELECT * FROM ".$name." WHERE ";
  for ($i=0;$i<$min;$i++) 
  {
    $query .= $cols[$i]."=".$values[$i];
	if($i != $min - 1 ) $query .= " AND ";
  }
  $query .= $orderby;
  $result = mysql_query($query,$db);
  return $result;
}
function deleteRows($db,$name)
{
  $query = "DELETE FROM ".$name;
  $result = mysql_query($query,$db);
  return $result;
}
function deleteRowByColValue($db,$name,$col,$value)
{
  $query = "DELETE FROM ".$name." WHERE ".$col."=".$value." LIMIT 1";
  $result = mysql_query($query,$db);
  return $result;
}
function deleteRowByID($db,$name,$id)
{
  $query = "DELETE FROM ".$name." WHERE id=".$id;
  $result = mysql_query($query,$db);
  return $result;
}
function updateCellByID($db,$name,$id,$col,$value)
{
  $query = "UPDATE ".$name." SET ".$col." = ".$value." WHERE id=".$id;
  $result = mysql_query($query,$db);
  return $result;
}
function updateRowByID($db,$name,$id,$cols,$values)
{
  $query = "UPDATE ".$name." SET ";
  for($i=0;$i<sizeof($cols);$i++)
  {
    if($i != 0 and $i != (sizeof($cols))) $query .= ",";  
    $query .= $cols[$i]." = ".$values[$i];
  }
  $query .= " WHERE id=$id"; 
  //echo $query;
  $result = mysql_query($query,$db);
  return $result;
}
function insertRow($id,$name,$cols,$values)
{
  $query = "INSERT INTO ".$name." (";
  for($i=0;$i<sizeof($cols);$i++)
  {
    if($i != 0 and $i != (sizeof($cols))) $query .= ",";  
    $query .= $cols[$i];
  }
  $query .= ") VALUES (";
  for($i=0;$i<sizeof($values);$i++)
  {
    if($i != 0 and $i != (sizeof($values))) $query .= ",";  
    $query .= $values[$i];
  }
  $query .= ")";
  echo $query;
  $result = mysql_query($query,$id);
  //echo $result;
  return $result;

}
function getTableColsNum($db,$table)
{
   $query = "SELECT * FROM ".$table;
   $result = mysql_query($query,$db);
   $myrow = mysql_fetch_row($result);
   return sizeof($myrow);
}
function getTableRowsNum($db,$table)
{
   $query = "SELECT * FROM ".$table;
   $result = mysql_query($query,$db);
   $i=0;
   while(mysql_fetch_row($result))
   {
     $i++;
   }
   return $i;
}
function getMaxRow($id,$name,$max)
{
  $query = "SELECT MAX(".$max.") FROM ".$name;
  $result = mysql_query($query,$id);
  return mysql_fetch_row($result);
}
function getMinRow($id,$name,$min)
{
  $query = "SELECT MIN(".$min.") FROM ".$name;
  $result = mysql_query($query,$id);
  return mysql_fetch_row($result);
}

function updateHTMLTable($db,$table)
{
  $result = 0;
  $query = "SELECT MAX(id) FROM ".$table;
  $result = mysql_query($query,$db);
  $myrow = mysql_fetch_row($result);
  $rowcount = $myrow[0];
  //echo $rowcount;
  $checked = array();
  $colsnum = getTableColsNum($db,$table);
  for($i=0;$i<=$rowcount;$i++)
  {
    if(isset($_POST[$table."select".$i]))
	{
	  $checked[$i] = 1;
	}
	else
	{
	  $checked[$i] = 0;
	}
  }
  if(isset($_POST[$table."_delete"]))
  {
    for($i=0;$i<=$rowcount;$i++)
	{
      if($checked[$i])
	  {
	    $query = "DELETE FROM ".$table." WHERE id=$i";
        $result = mysql_query($query,$db);	
      }
	}
  }
  if(isset($_POST[$table."_update"]))
  {
    for($i=0;$i<=$rowcount;$i++)
	{
	  if($checked[$i])
	  {
	    $query = "UPDATE ".$table." SET "; 
		for($k=0;$k<($colsnum-1);$k++)
		{
		  if($k!=0 and $k != ($colsnum-1)) $query .= ",";
		  $query .= $table.$k."='".$_POST[$table.($k+1)."_".$i]."'"; 
		}
		$query .= " WHERE id=$i";
		//echo $query;
        $result = mysql_query($query,$db);  		    
	  }
	
	}
  }
  if(isset($_POST[$table."_add"]))
  {
      $cols = array();
      for($i=0;$i<($colsnum-1);$i++)
	  {
	    $cols[$i] = $table.$i;
	  }  
      $values = array();
	  for($i=0;$i<($colsnum-1);$i++)
	  {
	    $values[$i] = "'".$_POST[$table.$i]."'";
	  }
      //$cols = array(0=>'news0');
      //$values = array(0=>"''");	  
      $result = insertRow($db,$table,$cols,$values);      
  }
  if(isset($_POST[$table."_edit"]))
  {

    for($i=0;$i<=$rowcount;$i++)
	{
	  if($checked[$i])
	  {
		  setSelectedID($i); 
		  //echo $selected
	  }
	}            
  }
  if(isset($_POST[$table."_save"]))
  { 
   $cols = array();
   for($i=0;$i<($colsnum-1);$i++)
   {
     $cols[$i] = $table.$i;
   }  
   $values = array();
   for($i=0;$i<($colsnum-1);$i++)
   {
	 $values[$i] = "'".$_POST[$table.$i]."'";
   }
   $result = updateRowByID($db,$table,getSelectedID($table,0),$cols,$values);               
  }      
  return $result;	
}


//
//HTML funtions
//
//
//@return : HTML entities
//

function getHTMLTable($id,$table,$labels,$stylesheet)
{
 
 $output = "";
 $output .= "";
 $output .= "<table border='2' cellspacing='0' cellpadding='0' style='".$stylesheet."'>";

 $query = "SELECT * FROM ".$table." ORDER BY id DESC";
 $result = mysql_query($query,$id);
 $output .= "<tr>";
 $output .= "<td>Select</td>";
 for($i=0;$i<sizeof($labels);$i++)
 {
    $output .= "<td align='center' class='".$table."_label_".$i."'>".$labels[$i]."</td>";
 }
 $output .= "</tr>";
 $rows = 0;
 while($myrow = mysql_fetch_row($result))
 {
   $output .= "<tr>";
   $output .=  "<td><input type='checkbox' value='1' name='".$table."select".$myrow[0]."' ></td>";
   for($cols=0;$cols<sizeof($myrow);$cols++)
   {
     $output .= "<td>";
     $output .=  "<input type='text' size='10' name='".$table.$cols."_".$myrow[0]."' value='".$myrow[$cols]."'>";
     $output .=  "</td>";   
   }
   $output .= "</tr>";
   $rows++;
 }
 $output .= "</table>";
 $output .= "<input type='submit' name='".$table."_delete' value='Delete'><input name='".$table."_update' type='submit' value='Update'>";
 return $output;
}
function getController($action,$control,$label)
{
  return "<input type='submit' name='".$control."_".$action."' value='".$label."'>";
}
function getHiddenValue($name,$value)
{
  return "<input type='hidden' name='".$name."' value='".$value."'>";
}
function getTextField($name,$size,$value)
{
  return "<input type='text' name='".$name."' size='".$size."' value='".$value."'>";
}
function getTextArea($name,$rows,$cols,$text)
{
  return "<textarea rows='".$rows."' cols='".$cols."' name='".$name."'>".$text."</textarea>";
}

function getSelectDefault($name,$labels)
{
  $output = "";
  $output .= "<select name='".$name."'>";
  for($i=0;$i<sizeof($labels);$i++)
  {
    $output .= "<option value='".$name.$i."'>".$labels[$i]."</option>";
  }
  $output .= "</select>"; 
  return $output;
}
function getSelect($name,$labels,$values)
{
  $output = "";
  $output .= "<select name='".$name."'>";
  if(sizeof($labels) == sizeof($values))
  {
    for($i=0;$i<sizeof($labels);$i++)
    {
      $output .= "<option value='".$values[$i]."'>".$labels[$i]."</option>";
    }
  }
  $output .= "</select>"; 
  return $output;
}

function getForm($controls,$action)
{
  return "<form action='".$action."' method='POST'>".$controls."</form>";
}

function getFormTable($labels, $elements, $controls)
{
  $table = "<table cellpading='5' border='0'>";
  for ($i=0;$i<sizeof($elements);$i++) {
	$table .= "<tr><td>".$labels[$i]."</td><td>".$elements[$i]."</td></tr>";  
  }
  $table .= "<tr>";
  for ($i=0;$i<sizeof($controls);$i++) {
	$table .= "<td>".$controls[$i]."</td>";  
  }  
  $table .= "</tr>";
  $table .= "</table>"; 
  return $table; 
}

//FROM HERE WE WILL CALL ALL FUNCTIONS THAT RENDER HTML html*

function htmlCenter($data)
{
  return "<center>".$data."</center>"; 
}

function htmlDiv($data = "", $style = "", $class = "", $id = "")
{
  return "<div style='".$style."' class='".$class."' id='".$id."'>".$data."</div>";
}
function htmlStyle($data)
{
  return "<style>".$data."</style>"; 
}
function htmlStruct($data, $title)
{
  return "<html><head><title>".$title."</title></head><body>".$data."</body><html>"; 
}
function htmlLink($data, $href, $target, $style)
{
  return "<a href='".$href."' target='".$target."' style='".$style."'>".$data."</a>";
}
function htmlImg($outsrc, $oversrc, $name)
{
  $onmouseover = "onmouseover='document.images.".$name.".src=".'"'.$oversrc.'"'."'";
  $onmouseout = "onmouseout='document.images.".$name.".src=".'"'.$outsrc.'"'."'";
  return "<img border='0' name='".$name."' src='".$outsrc."' ".$onmouseover."  ".$onmouseout.">";
}
function htmlImgGeneric($outsrc, $oversrc)
{
  $i = nextInt();
  $onmouseover = "onmouseover='document.images[".$i."].src=".'"'.$oversrc.'"'."'";
  $onmouseout = "onmouseout='document.images[".$i."].src=".'"'.$outsrc.'"'."'";
  return "<img border='0' src='".$outsrc."' ".$onmouseover."  ".$onmouseout.">";
} 
     
?>