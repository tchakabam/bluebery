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



//Class representing a bluebery entity.
//Can be used to create, store and modify entities on the backend.
//The entity class is an encapsulation of the actual persitance unity defined by the implementation in bluelib. See bluelib persistance functions.
class Entity 
{
   
   //includes
   //require_once "blueconst.php";
   //require_once "bluelib.php";
   
   //
   //members
   //
   var $id;
   var $content;
   var $date;
   var $key;
   var $type;
   var $lang;
   
   //constructor
   function Entity($content = "", $date = "", $key = "", $type = "", $lang = "") 
   {
        $this->content = $content;
		$this->date = $date;
		$this->key = $key;
		$this->type = $type;
		$this->lang = $lang;
   }
   
   //
   //setters
   //
   function setContent($content) 
   {
     $this->$content = $content;
   }
   function setDate($date) 
   {
     $this->$date = $date;
   }
   function setKey($key) 
   {
     $this->$key = $key;
   }         
   function setType($type) 
   {
     $this->$type = $type;
   }
    function setLang($type) 
   {
     $this->$lang = $lang;
   }
   
   //
   //getters
   //
   function getId() 
   {
     return $this->$id;
   }
   function getContent() 
   {
     return $this->$content;
   }
   function getDate() 
   {
     return $this->$date;
   }
   function getKey() 
   {
     return $this->$key;
   }         
   function getType() 
   {
     return $this->$type;
   }
    function getLang() 
   {
     return $this->$lang;
   }
   
   //
   //persistance features
   //
   function insert() 
   {
     $result = insertEntity($this->content, $this->date, $this->key, $this->type, $this->lang);
	 return $result; 
   }
  //updates the entity objects values to the the peer if write is true and then from the peer.
  //choose write = false if you just want to update the objects from the peer.   
   function update($write = false) 
   {
	 if ($write) $result = updateEntity($this->$key, $this->$type, $this->$lang);
	 $entity = getEntity($this->$key, $this->$type, $this->$lang);
	 $this->$id = getEntityId($entity);
	 $this->$content = getEntityContent($entity);
	 $this->$date = getEntityDate($entity);
	 $this->$key = getEntityKey($entity);
	 $this->$type = getEntityType($entity);
	 $this->$lang = getEntityContent($lang);
	 return $result;
   }
   function delete($probe = false)
   {
     return removeEntity($this->$key, $this->$type, $this->$lang, $probe);
   }
   
   //
   //output methods
   //
   function printContent()
   {
     echo $this->$content;
   }
   function printDate()
   {
     echo $this->$date;
   }
      
}
require_once "blueconst.php";

//class representing a bluebery keytype.
//a keytype is a set of entities having the same key and all diffenrent types.
//mvcingly talking its like a controller. it can hold default values and fetch post data to pass it to entity constructors.
class KeyType 
{
  //includes
  
  //require_once "bluelib.php";
  //
  //members
  //
  var $data = array();
  var $index = 0;
  
  //
  //constructor
  //
  function KeyType($key = "DEFAULT", $types = array())
  {
    $this->data[-1] = $key;
    for($i=0;$i<sizeof($types);$i++)
    {
	  $this->data[ $i ] = $types[$i];
	  $this->data[ $types[$i] ] = "";
	  $this->index++;
    }
  }
  
  //
  //setters
  //
  //set the key
  function setKey($key)
  {
    $this->data[-1] = $key;
  }
  //modify or add a type and its value
  function setType($type, $value = "")
  {
    addType($type, $value);  
  }
  
  //
  //getters
  //
  function getKey()
  {
    return $this->data[-1];
  }
  //returns a list of the the existing types 
  function getTypes()
  {
    $types = array();
	$k = 0;
	for($i=0;$i<$this->index;$i++)
	{
	  if (isset($this->data[$type]) and $this->data[$type] != 0) {$types[$k] = $this->data[$i]; $k++;}  
    } 
	return $types;
  }
  //returns the value of a type
  function getTypeValue($type)
  {
    if (isset($this->data[$type])) return $this->data[$type];
    else return "";
  }
  //returns the indexed type
  function getTypeByIndex($i)
  {
    return $this->data[$i];
  }
  //returns the value of the indexed type
  function getTypeValueByIndex($i)
  {
    //return $this->getTypeValue(getTypeByIndex($i));
	return $this->data[$this->data[$i]];
  }      
  
  //
  //mapping
  //
  //adds a type
  function addType($type, $value = "") 
  {
    //look if type already exists
	//if not then create new type
    if (!isset($this->data[$type]) or $this->data[$type] == 0)
	{
	  
	  //look if there is room
	  $hole = -1;
	  //iterate thru index
	  for($i=0;$i<$this->index;$i++)
	  {
	    //hole
	    if ($this->data[$i] == 0) $hole = $i;
	  }
	  /*
	  if ($hole >= 0) { $this->data[$hole] = $type; echo $type; } //write to hole
	  else 
	  {
	  */ 
	    //echo $type;
	    $this->data[$this->index] = $type; //or write to end
		$this->index++; 
	  /*
	  }
	  */ 
	  $this->data[$type] = $value; //write value 
	}
	//if type already exists then just modify value
	else 
	{
	  $this->modifyType($type, $value);
	}   
  }
  //modifies a type, returns true if type exists, false if not.
  function modifyType($type, $value)
  {
    if (isset($this->data[$type]) or $this->data[$type] == 0)
	{
	  $this->data[$type] = $value;
	  return true;
	}
	else 
	{
	  return false;
	} 
  }
  //DO NOT USE
  //removes a type
  function removeType($type)
  {
    for($i=0;$i<$this->index;$i++)
	{
	  if($this->data[$i] == $type) { $this->data[$i] = 0; }
	}
	$this->data[$type] = 0;    
  }
  

  //
  //data passing
  //
  //set values for types by fetching post vars named by
  //function createname.
  function fetchPost() 
  {
  	  for($i=0;$i<$this->index;$i++)
      {
	    $varname = createName($this->getKey(), $this->data[$i]);
		if ( isset($this->data[$i]) and isset($_POST[$varname]) ) 
		{
		  $this->data[$this->data[$i]] = $_POST[$varname];
		}  
      }    
  }
  //returns an array of entity objects having the values of the existing types and the key of this keytype
  //creates entity objects from the keytype values
  function createEntities($lang = "en")
  {
    $entities = array();
    for($i=0; $i < $this->index; $i++)
	{
	  $entities[$i] = new Entity($this->getTypeValueByIndex($i), date("d-m-Y"), $this->getKey(), $this->getTypeByIndex($i), $lang);
	}
	return $entities;  
  }
  //updates the entity objects values to the the peer (if write is true) and then from the peer.
  //choose write = false if you just want to update the objects from the peer without updating the entities on the peer.
  function updateEntities($write, $lang = "en")
  {
     $entities = $this->createEntities($lang);
	 for($i = 0; $i < sizeof($entities); $i++)
	 {
       $entities[$i]->update($write);
	 }  
  }
  //creates entity objects from keytype values and inserts all entities in the peer
  function insertEntities($lang = "en")
  {
     $entities = $this->createEntities($lang);
	 for($i = 0; $i < sizeof($entities); $i++)
	 {
       $entities[$i]->insert();
	 }  
  }
  //creates entity objects from keytype values and deletes alle entities from the peer 
  function deleteEnties($lang = "en")
  {
     $entities = $this->createEntities($lang);
	 for($i = 0; $i < sizeof($entities); $i++)
	 {
       $entities[$i]->delete();
	 }     
  }
}


?>