<?php
session_start();

include_once("config.php");

//print_r($_SESSION);
if($_REQUEST['act']==1){
	
//error_reporting("~E_WARNING");
   include_once("Google_Spreadsheet.php");

   $user = $config['user'];
   $pass = $config['pass'];
   $spreadsheetKey=$config['spreadsheetKey'];

   $ss = new Google_Spreadsheet($user, $pass);

$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
$feed = $spreadsheetService->getSpreadsheetFeed();

    $query = new Zend_Gdata_Spreadsheets_ListQuery();
    $query->setSpreadsheetKey($spreadsheetKey);
    $query->setWorksheetId(3);
//    print md5($_REQUEST['password']);
/*
if(!$_REQUEST['username']){
	$_REQUEST['username']="null";
}
*/
    $_REQUEST['username']=str_replace($config['orgdomainname'], "", $_REQUEST['username']);

if(!$_REQUEST['username']){
	$_REQUEST['username']="null";
}

if(strstr($_REQUEST['username'], "@")){
	$_REQUEST['username']="null";
}

$query->setSpreadsheetQuery("username=".$_REQUEST['username']);
    $listFeed = $spreadsheetService->getListFeed($query);

/// all these loops because Google/Zend is choking on a muptiple field setSpreadsheetQuery
if(count($listFeed->entries)>0){

	for($x=0;$x<count($listFeed->entries);$x++){
	    $rowData = $listFeed->entries[$x]->getCustom();

	    foreach($rowData as $customEntry) {
	    	if($customEntry->getColumnName()=='password'&&$customEntry->getText()==md5($_REQUEST['password'])){
		    	$gotPasswordMatch=$customEntry->getText();
	    	}
	    	if($customEntry->getColumnName()=='username'){  
				$gotUsernameMatch=$customEntry->getText();
	    	}
	    	if($customEntry->getColumnName()=='eid'){ 
	    		$gotEIDMatch=$customEntry->getText();
	    	}
			
			}
	    }

		if($gotUsernameMatch && $gotPasswordMatch && $gotEIDMatch){
			if($gotUsernameMatch==$_REQUEST['username'] && $gotPasswordMatch==md5($_REQUEST['password'])){
						$_SESSION['eid']=$gotEIDMatch;
						$_SESSION['username']=$gotUsernameMatch;
		    	    	header("Location: accrual.php");
		    	    	exit();
			}
		}

}    

    
}
?>
<html>
<head>
   <title>Path HR</title>
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
   table{
	   padding: 0px;
	   margin: 0px;
   }
   .employeeInfo {
	   	   background-color: white;
	   	   opacity: .9;

   }
   input.bt {
	   width:90px;
	   background-color:orange;padding:4px;color:white;text-decoration:none;border:1px gray solid;
	   
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
	
#headerImg{
	width:100%;
	max-width:451px;
}	

@media only screen and (max-width: 480px), only screen and (max-device-width: 480px) {

   input {
	   font-size: 18px;
	   background-color: #eee;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   width: 155px;
	   
   }

   .employeeInfo{
	   font-size: .90em;
	   font-weight: bold;
	   text-align: right;
	   
   }

}

</style>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

</head>

<body>
<div id="loading">
    <img src="ajax-loader.gif" class="ajax-loader"/>
    <div class="ajax-loader"><br> <br>
    <span style="left:-100px">please wait...it takes a few seconds<br>to update Google Docs.</span></div>
</div>
<div style="float:left;xheight:130px"><img src="path.png" align="center" id="headerImg">
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
if(!$gotPasswordMatch&&$_REQUEST['act']==1){
	
	print "<div class='error'>Wrong username or password. Please try again.<br>&nbsp;</div>";
	
}
?>
<form method="post">
<table border="0">
<tr>
<td colspan="2" align="center"><h1>Path Interactive Login</h1></td>
</tr>
<tr>
<td class="employeeInfo">Path Email Address</td>
<td class="employeeField"><input type="text" name="username"></td>
</tr>
<tr>
<td class="employeeInfo">Password</td>
<td class="employeeField"><input type="password" name="password"></td>
</tr>
<tr>
<td class=""></td>
<td class=""><input type="submit" value="Login" class="bt" style="background-color:orange;padding:4px;color:white;text-decoration:none;border:1px gray solid;text-size:16px"></td>
</tr>
<tr>
<td class=""></td>
<td class=""> <br> <br></td>
</tr>

<tr>
<td ><a href="register.php" style="background-color:orange;padding:4px;color:white;text-decoration:none;border:1px gray solid;text-size:16px">Create account</a></td>
<td  align="right"><a href="register.php" style="background-color:orange;padding:4px;color:white;text-decoration:none;border:1px gray solid;text-size:16px">Forgot password?</a></td>
</tr>
<tr>
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
