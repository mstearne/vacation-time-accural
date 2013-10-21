<?php
session_start();

include_once("config.php");

 
require("postmark.php");
$postmark = new Postmark($config['postmarkappapikey'],$config['adminemail'],$config['hremail']);

//exit();

//print_r($_SESSION);
if($_REQUEST['act']==1){

/** 
 * The letter l (lowercase L) and the number 1 
 * have been removed, as they can be mistaken 
 * for each other. 
 */ 

function createRandomPassword() { 

    $chars = "passwordgen"; 
    srand((double)microtime()*1000000); 
    $i = 1; 
    $pass = '' ; 

    while ($i < 5) { 
        $num = rand()%33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass.rand(1000,9999); 

} 

// Usage 
$password = createRandomPassword(); 
	
//error_reporting("~E_WARNING");
   include_once("Google_Spreadsheet.php");

   $user = $config['user'];
   $pass = $config['pass'];

   $ss = new Google_Spreadsheet($user, $pass);

$spreadsheetKey=$config['spreadsheetKey'];
$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
$feed = $spreadsheetService->getSpreadsheetFeed();

$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetKey);
//$query->setSpreadsheetQuery('eid=100026');
$query->setWorksheetId(3);
$cellFeed = $spreadsheetService->getCellFeed($query);

foreach($cellFeed as $cellEntry) {
//print "sss=".$cellEntry->cell->getColumn(4);
  $row = $cellEntry->cell->getRow();
  $col = $cellEntry->cell->getColumn();
  $val = $cellEntry->cell->getText();


if($val."@pathinteractive.com"==$_REQUEST['username']){
$updatedCell = $spreadsheetService->updateCell($row,
                                               2,
                                               md5($password),
                                               $spreadsheetKey,
                                               3);

$result = $postmark->to($val.$config['orgdomainname'])
->subject("Your ".$config['orgname']." HR Password")
->plain_message("Hi ".$val.$config['orgdomainname'],


Your ".$config['orgname']." password is: ".$password."


Log in to ".$config['orgname']." HR at:

".$config['applocation']."

username: $val@".$config['orgdomainname']."
password: $password


Thank You.
")
->send();
if($result === true)
$emailSent=true;

print '<meta http-equiv="refresh" content="10; url=index.php">';

	
	                                               
}


//  print "this";
  
}

    
}
?>
<html>
<head>
   <title><?=$config['orgname']?> HR</title>
   <link rel="stylesheet" href="css/style.css" type="text/css" />
   <style>
   body{
	   font-family: 'Trebuchet MS';
	   padding: 0px;
	   margin: 0px;
   }
   .employeeInfo{
	   font-size: 18px;
	   
   }
   input {
	   font-size: 18px;
	   background-color: #eee;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   width: 255px;
   }
   .employeeField{
	   font-size: 18px;
	   font-weight: bold;
	   text-align: right;
	   
   }
   input.bt {
	   background-color:orange;padding:4px;color:white;text-decoration:none;border:1px gray solid;
	   width:145px
   }

   table{
	   padding: 0px;
	   margin: 0px;
   }
   html { 
  background: url(bg2.jpg) no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.error{
	color: red;
	font-weight: bold;
}

#loading {  
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:1000;
    background-color:white;
    opacity: .9;
    display: none;
 }

.ajax-loader {
    position: absolute;
    left: 50%;
    top: 50%;
    margin-left: -32px; /* -1 * image width / 2 */
    margin-top: -32px;  /* -1 * image height / 2 */
    display: block;     
}   

#useDate {
	    background: url(cal.png) right no-repeat;
	    padding-right: 17px;
	}
@media only screen and (max-width: 480px), only screen and (max-device-width: 480px) {

   input {
	   font-size: 18px;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   width: 155px;
	   
   }

  body{
	   font-family: 'Trebuchet MS';
	   padding: 0px;
	   margin: 0px;
	   font-size: .7em;
   }
   .employeeInfo{
	   font-size: 1em;
	   	   background-color: white;
	   	   opacity: .9;
	   
   }
   input {
	   font-size: 18px;
	   background-color: #ddd;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   border: none;
   }
   .employeeField{
	   font-size: .65em;
	   font-weight: bold;
	   text-align: right;
	   
   }


}
#headerImg{
	width:100%;
	max-width:451px;
}	

</style>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

</head>

<body>
<div style="float:left;"><img src="path.png" align="center" id="headerImg">
</div>

<div align="center"><br>
<div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
                <script type="text/javascript">
        $(window).load(function()
        {

        });

    </script>

<?
if($emailSent){
		print "<div><strong>Please check your email.<br>We've sent new password to your email address.</strong><br>&nbsp;</div>";
}
?>
<form action="" method="post">
<table>
<tr><td colspan="2" align="center"><h1><?=$config['orgname']?> Login</h1>
</td></tr>
<tr>
<td class="employeeInfo"><?=$config['orgname']?> Address</td>
<td class="employeeField"><input type="text" name="username"></td>
</tr>
<tr>
<td class=""></td>
<td class=""><input type="submit" class="bt" value="Send Password"></td>
</tr>
<tr>
<td class=""></td>
<td class=""> <br> <br></td>
</tr>
<tr>
<td class="employeeInfo" colspan="2">Already have an account? <a href="index.php">Click here</a></td>
</tr>
</table>
<input type="hidden" name="act" value="1">
</form>
<br>
<br>
<br>

</div>
</div>
</body>
</html>
