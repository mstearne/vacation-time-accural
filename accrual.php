<?
session_start();
//$_SESSION['username']=false;
if(!$_SESSION['username']){ header("Location: index.php"); exit();
	 
}
?><html>
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
	   background-color: #ddd;
	   
   }
   .employeeInfoNote{
	   font-size: 12px;
	   background-color: #ddd;
	   
   }
   input {
	   font-size: 18px;
	   background-color: #ddd;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   border: none;
   }
   .employeeField{
	   font-size: 18px;
	   font-weight: bold;
	   text-align: right;
	   background-color: #eee;
	   
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

.note{
	font-size: 10px;
	color: #777;
}

@media only screen and (max-width: 480px), only screen and (max-device-width: 480px) {

   input {
	   font-size: 18px;
	   background-color: #eee;
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
	   font-size: 1.4em;
	   background-color: #ddd;
	   
   }
   input {
	   font-size: 18px;
	   background-color: #ddd;
	   font-family: 'Trebuchet MS';
	   height: 30px;
	   border: none;
   }
   .employeeField{
	   font-size: .85em;
	   font-weight: bold;
	   text-align: right;
	   background-color: #eee;
	   
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
<div id="loading">
    <img src="ajax-loader.gif" class="ajax-loader"/>
    <div class="ajax-loader"><br> <br>
    <span style="left:-100px">please wait...it takes a few seconds<br>to update.</span></div>
</div>
<div style="float:left;xheight:150px"><img src="path.png" align="center" id="headerImg">
</div>

<?php
error_reporting("~E_WARNING");
   include_once("Google_Spreadsheet.php");
   include_once("config.php");

   $user = $config['user'];
   $pass = $config['pass'];
   $spreadsheetKey=$config['spreadsheetKey'];

   $ss = new Google_Spreadsheet($user, $pass);

$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
$feed = $spreadsheetService->getSpreadsheetFeed();

$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
$query->setSpreadsheetKey($spreadsheetKey);
//$query->setSpreadsheetQuery('eid=100026');
$feed = $spreadsheetService->getWorksheetFeed($query);


$entries = $feed->entries[0]->getContentsAsRows();

$eid=$_SESSION['eid'];

/*
if($_REQUEST['eid']){
	$eid=$_REQUEST['eid'];	
}else{
	$eid="100026";	
}
*/


?>
<div align="center"><br>
<div>
    <link href="glDatePicker/styles/glDatePicker.flatwhite.css" rel="stylesheet" type="text/css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
            <script src="glDatePicker/glDatePicker.min.js"></script>
                <script type="text/javascript">
        $(window).load(function()
        {

$.ajaxSetup({
    beforeSend:function(){
        // show gif here, eg:
        $("#loading").fadeIn();
    },
    complete:function(){
        // hide gif here, eg:
        $("#loading").hide();
    }
});

var myDatePicker = $('input').glDatePicker(
{
    showAlways: false,
    selectableYears: [<?=date("Y")?>],
    dowNames: ['S','M','T','W','T','F','S'],
    monthNames: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    cssName: 'flatwhite',
    onClick: function (target, cell, date, data) {
    $('#useDate').val(pad(date.getMonth()+1,2) + '/' + pad(date.getDate(),2) + '/' +date.getFullYear());
    jQuery.ajax( "curl.php?eid=<?=$eid?>&fxdate="+ pad(date.getMonth()+1,2) + '/' + pad(date.getDate(),2) + '/' +date.getFullYear() ).done(function(data) { 
//    alert("curl.php?fxdate="+ encodeURIComponent(pad(date.getMonth()+1,2) + '/' + pad(date.getDate(),2) + '/' +date.getFullYear()))+"&eid=<?=$eid?>";
    var pdata = data.split(",");
    $("#accuredvacation" ).hide();
    $("#accuredvacation" ).toggle( "highlight" )
    $('#accuredvacation').html(pdata[7]);
    $("#accuredpto" ).hide();
    $("#accuredpto" ).toggle( "highlight" )
    $('#accuredpto').html(pdata[8]);
    $("#accuredsick" ).hide();
    $("#accuredsick" ).toggle( "highlight" )
    $('#accuredsick').html(pdata[10]);
    
    });
    
//alert('s'+date)
    }
    
}).glDatePicker(true);



        });


function pad(number, length) {
   
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
   
    return str;

}

    </script>
<?
for($i=0;$i<count($entries);$i++){

	if($entries[$i]['eid']==$eid){
		print '<table cellpadding="8">';
?>
<tr>
<td colspan="3" align="center">
<?
		print '<h1>';
		$name=explode(",", $entries[$i]['employeename']);
		print $name[1]." ".$name[0];
		print '</h1>Time Accrual Information<br>
		<font color="red" size=-1><strong>Note:</strong> Available times listed below DO NOT reflect deductions for future time off requests.</font>';
?>
</td>
</tr>
<?
		print '<tr><td class="employeeField">Your information as of<br><span class=note>change the date to see future time accrual</span></td><td class="employeeInfo">';
		if($entries[$i]['accuredbyfuturedate']==""){
			$useDate=date("m/d/Y");
		}else{
			$useDate=$entries[$i]['accuredbyfuturedate'];
		}
		?>
		        <input type="text" id="useDate" style="width:175px" value="<?=$useDate?>" />
          <div gldp-el="useDate" style="width:400px; height:300px; position:absolute; top:70px; left:200px;">
		 </div>
<?
		print "</td><td class='employeeInfo'></td></tr>";
/*
		print '<tr><td class="employeeField">Start Date</td><td class="employeeInfo" id="startdate">';
		print $entries[$i]['startdate'];
		print "</td></tr>";
*/
		print '<tr><td class="employeeField">Vacation Days Per Year</td><td class="employeeInfo" id="vacationdaysyear">';
		print $entries[$i]['vacationdaysyear'];
		print "</td><td class='employeeInfoNote'></td></tr>";
		print '<tr><td class="employeeField">Vacation Carried From '.date("Y",strtotime("Last year")).'</td><td class="employeeInfo" id="carriedfromprioryear">';
		print $entries[$i]['carriedfromprioryear'];
		print "</td><td class='employeeInfo'></td></tr>";
		print '<tr><td class="employeeField">Vacation Used</td><td class="employeeInfo" id="vacationused">';
		print $entries[$i]['vacationused'];
		print "</td><td class='employeeInfoNote'>".$entries[$i]['vacationusednote']."</td></tr>";
		print '<tr><td class="employeeField">Personal Days Used</td><td class="employeeInfo" id="ptoused">';
		print $entries[$i]['ptoused'];
		print "</td><td class='employeeInfoNote'>".$entries[$i]['ptousednote']."</td></tr>";
		print '<tr><td class="employeeField">Floating Holidays Used</td><td class="employeeInfo" id="fhused">';
		print $entries[$i]['fhused'];
		print "</td><td class='employeeInfoNote'>".$entries[$i]['fhusednote']."</td></tr>";
		print '<tr><td class="employeeField">Sick Days Used</td><td class="employeeInfo" id="sickused">';
		print $entries[$i]['sickused'];
		print "</td><td class='employeeInfoNote'>".$entries[$i]['sickusednote']."</td></tr>";
		print '<tr><td class="employeeField">Vacation Days Accrued</td><td class="employeeInfo" id="accuredvacation">';
		print number_format($entries[$i]['accuredvacation'],2);
		print "</td><td class='employeeInfoNote'></td></tr>";
		print '<tr><td class="employeeField">Personal Days Accrued</td><td class="employeeInfo" id="accuredpto">';
		print number_format($entries[$i]['accuredpto'],2);
		print "</td><td class='employeeInfoNote'></td></tr>";
		print '<tr><td class="employeeField">Floating Holidays Unused</td><td class="employeeInfo" id="fhused">';
		print number_format($entries[$i]['fhavailable'],2)-number_format($entries[$i]['fhused'],2);
		print "</td><td class='employeeInfoNote'></td></tr>";
		print '<tr><td class="employeeField">Sick Days Accrued</td><td class="employeeInfo" id="accuredsick">';
		print number_format($entries[$i]['accuredsick'],2);
		print "</td><td class='employeeInfoNote'></td></tr>";
		print '<tr><td class="employeeField">Future Requested Time Off<br><strong><span style="color:red;font-size: 11px">NOT reflected in the available times above</span></strong></td><td class="employeeInfo" id="futurerequests" colspan=2>';
		print $entries[$i]['futurerequests'];
		print "</td></tr>";
		if($entries[$i]['notetoemployee']){
		print '<tr><td class="employeeInfo" colspan=3 style="text-align:left;background-color:#ccc" id="notetoemployee">'.$entries[$i]['notetoemployee'].'';
		print "</td></tr>";
		}
		print '<tr><td colspan=3 style="text-align:center;font-size:22px;font-weight:bold"><a href="mailto:hrrequests@pathinteractive.com">Send HR Request or Ask a Question</a>';
		print "</td></tr>";
/*
		print '<tr><td class="employeeField">Vacation/Year</td><td class="employeeInfo" id="vaccrualrate">';
		print $entries[$i]['vaccrualrate'];
		print "</td>";
		print "</tr>";
*/
	}	
	
}
?>
</table>
<br>
<br>
<br>

</div>
</div>
<?
//echo "<hr><h3>Example 1: Get cell data</h3>";
//echo var_export($entries, true);

?>
<script type="text/javascript">
</script>
</body>
</html>
