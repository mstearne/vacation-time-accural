<?php
error_reporting("~E_WARNING");
	include_once("config.php");
	include_once("Google_Spreadsheet.php");
   
   $user = $config['user'];
   $pass = $config['pass'];

   $ss = new Google_Spreadsheet($user, $pass);

if($_REQUEST['eid']){
	$eid=$_REQUEST['eid'];	
}else{
	$eid="100026";	
}
//print $_REQUEST['eid']."s".$_REQUEST['fxdate'];
if($_REQUEST['fxdate']){
	$fdate=$_REQUEST['fxdate'];	
}else{
	$fdate=date("m/d/Y");	
}
//print $fdate;

$spreadsheetKey=$config['spreadsheetKey'];
$service = Zend_Gdata_Spreadsheets::AUTH_SERVICE_NAME;
$client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, $service);
$spreadsheetService = new Zend_Gdata_Spreadsheets($client);
$feed = $spreadsheetService->getSpreadsheetFeed();

$query = new Zend_Gdata_Spreadsheets_CellQuery();
$query->setSpreadsheetKey($spreadsheetKey);
//$query->setSpreadsheetQuery('eid=100026');
$query->setWorksheetId(1);
$cellFeed = $spreadsheetService->getCellFeed($query);

foreach($cellFeed as $cellEntry) {
//print "sss=".$cellEntry->cell->getColumn(4);
  $row = $cellEntry->cell->getRow();
  $col = $cellEntry->cell->getColumn();
  $val = $cellEntry->cell->getText();
//  print "this";
  if($col==1&&$val==$eid){
  
$updatedCell = $spreadsheetService->updateCell($row,
                                               13,
                                               $fdate,
                                               $spreadsheetKey,
                                               1);
  }
  
}
                                               
$query = new Zend_Gdata_Spreadsheets_DocumentQuery();
$query->setSpreadsheetKey($spreadsheetKey);
$feed = $spreadsheetService->getWorksheetFeed($query);


$entries = $feed->entries[0]->getContentsAsRows();

for($i=0;$i<count($entries);$i++){
	if($entries[$i]['eid']==$eid){
		$name=explode(",", $entries[$i]['employeename']);
		print $name[1]." ".$name[0].",";
		if($entries[$i]['accuredbyfuturedate']==""){
			$useDate=date("m/d/Y");
		}else{
			$useDate=$entries[$i]['accuredbyfuturedate'];
		}
		print $entries[$i]['vacationdaysyear'].",";
		print $entries[$i]['carriedfromprioryear'].",";
		print $entries[$i]['vacationused'].",";
		print $entries[$i]['ptoused'].",";
		print $entries[$i]['fhused'].",";
		print $entries[$i]['sickused'].",";
		print number_format($entries[$i]['accuredvacation'],2).",";
		print number_format($entries[$i]['accuredpto'],2).",";
		print 2-number_format($entries[$i]['fhused'],2).",";
		print number_format($entries[$i]['accuredsick'],2).",";
		print $entries[$i]['notetoemployee'].",";
	}
}
