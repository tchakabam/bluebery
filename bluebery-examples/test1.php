<?

require_once "../bluebery/header.php"; 

//
//Logic
//

//create apropriate keytype
$userinfo = new KeyType("USERINFO");
//add types
$userinfo->addType("FORNAME");
$userinfo->addType("NAME");
$userinfo->addType("ADDRESS1");
$userinfo->addType("ADDRESS2");
$userinfo->addType("ZIP");
$userinfo->addType("CITY");
$userinfo->addType("COUNTRY");
$userinfo->addType("PHONE");
$userinfo->addType("FAX");
$userinfo->addType("EMAIL");


if(isAction())
{

  //fetch post vars 
  $userinfo->fetchPost();

  //here we could validate...

  //store info to peer
  $userinfo->setKey(createKey(0, 100000, "USERINFO"));
  $userinfo->insertEntities();

}

//echo createKey(0, 100000, "USERINFO");

//
//I/O layout
//

//create form elements
$forname = htmlActionField("USERINFO", "FORNAME", $userinfo->getTypeValue("FORNAME"), 30);
$name = htmlActionField("USERINFO", "NAME", $userinfo->getTypeValue("NAME"), 30);
$address1 = htmlActionField("USERINFO", "ADDRESS1", $userinfo->getTypeValue("ADDRESS1"), 50);
$address2 = htmlActionText("USERINFO", "ADDRESS2", $userinfo->getTypeValue("ADDRESS2"), 5, 30);
$zip = htmlActionField("USERINFO", "ZIP", $userinfo->getTypeValue("ZIP"), 7);
$city = htmlActionField("USERINFO", "CITY", $userinfo->getTypeValue("CITY"), 30);
$country = htmlActionField("USERINFO", "COUNTRY", $userinfo->getTypeValue("COUNTRY"), 30);
$phone = htmlActionField("USERINFO", "PHONE", $userinfo->getTypeValue("PHONE"), 20);
$fax =  htmlActionField("USERINFO", "FAX", $userinfo->getTypeValue("FAX"), 20);
$email = htmlActionField("USERINFO", "EMAIL", $userinfo->getTypeValue("EMAIL"), 30);

//align them in a table
$labels = array("Forname:", "Surname:", "Address:", "Address extension:", "ZIP:", "City:", "Country:", "Phone:", "Fax:", "Email:");
$elements = array($forname, $name, $address1, $address2, $zip, $city, $country, $phone, $fax, $email);
$controls = array("", htmlActionSubmit("USERINFO", "SUBMIT", "Add address"));
$table = htmlFormTable($labels, $elements, $controls);

//create form out of table 
$form = htmlParagraph( htmlActionForm("test1.php", "USERINFO", "ADD", $table), "", 0 );

//body layout
$style =  htmlStyle("body{font-family:arial; }");
$body = htmlCenter( htmlParagraph("Input you data:", "font-size:20px;", 1).$form );
$title = "bluebery example 1";

echo htmlStruct($style.$body, $title);
?>
