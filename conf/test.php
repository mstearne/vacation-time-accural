<? ini_set('include_path',ini_get('include_path').':../includes:'); ?>
<?
//phpinfo();



require_once('nusoap/lib/nusoap.php');
/*
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
$client = new soapclient('http://www.abundanttech.com/WebServices/Population/population.asmx?WSDL', true,
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}
$client->setUseCurl($useCURL);
$result = $client->call('getCountries', array(), '', '', false, true);
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	$err = $client->getError();
	if ($err) {
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
*/



//exit();

require_once 'php-ews/ExchangeWebServices.php';
require_once 'php-ews/NTLMSoapClient.php';

//require_once 'php-ews/EWSType/CalendarItemType.php';
//require_once 'php-ews/EWSType/BodyType.php';


/**
 * Function to autoload the requested class name.
 * 
 * @param string $class_name Name of the class to be loaded.
 * @return boolean Whether the class was loaded or not.
 */
function __autoload($class_name)
{
    // Start from the base path and determine the location from the class name,
    $base_path = 'php-ews';
    $include_file = $base_path . '/' . str_replace('_', '/', $class_name) . '.php';

    return (file_exists($include_file) ? require_once $include_file : false);
}

//__autoload("ExchangeWebServices");
//__autoload("EWSType_FindItemType");


function ews_autoloader($className) {
  if($className != 'EWS_Exception') {
    $classPath = str_replace('_','/',$className);
  }
  if(file_exists("php-ews/{$classPath}.php") {
    include("php-ews/{$classPath}.php");
  }
}

spl_autoload_register('ews_autoloader');

__ews_autoloader("ExchangeWebServices");
__ews_autoloader("EWSType_FindItemType");


$host="connect.emailsrvr.com";
$username="conf1007@pathinteractive.com";
$password="Pathinc123";

$mail = 'conf1007@pathinteractive.com';
$startDateEvent = '2013-07-15T09:00:00'; //ie: 2010-09-14T09:00:00
$endDateEvent = '2013-07-20T17:00:00'; //ie: 2010-09-20T17:00:00

$ews = new ExchangeWebServices($host, $username, $password);
$request = new EWSType_FindItemType();
$request->Traversal = EWSType_FolderQueryTraversalType::SHALLOW;

$request->CalendarView->StartDate = $startDateEvent; 
$request->CalendarView->EndDate = $endDateEvent; 
$request->CalendarView->MaxEntriesReturned = 100;
$request->CalendarView->MaxEntriesReturnedSpecified = true;
$request->ItemShape->BaseShape = EWSType_DefaultShapeNamesType::ALL_PROPERTIES;

$request->ParentFolderIds->DistinguishedFolderId->Id = EWSType_DistinguishedFolderIdNameType::CALENDAR;   
$request->ParentFolderIds->DistinguishedFolderId->Mailbox->EmailAddress = $mail;
$response = $ews->FindItem($request);
echo '<pre>'.print_r($response, true).'</pre>';

?>
