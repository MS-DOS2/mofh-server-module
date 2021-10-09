<?php

if(!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function Myownfreehost_API(array $params, $endpoint, array $data = [], $dontLog = false) {
    if($params['serverport'] == 2086) $prefix = 'http://';
    if($params['serverport'] == 2087) $prefix = 'https://';
    $url = $prefix . $params['serverhostname'] . ':' . $params['serverport'] . '' . $endpoint;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);

    $headers[0] = "Authorization: Basic " . base64_encode($params['serverusername'] . ":" . $params['serverpassword']);

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
	$need = 'xml-api';
	if(strpos($url, $need) !== false) {
	$responseData0 = simplexml_load_string($response);
	$responseData1 = json_encode($responseData0, true);
	$responseData = json_decode($responseData1, true);
	}
	else
	{
		$responseData = json_decode($response, true);
	}
 
    if($responseData === 0 && !$dontLog) logModuleCall("MyOwnFreeHost", "CURL ERROR", curl_error($curl), "");

    curl_close($curl);

    if(!$dontLog) logModuleCall("MyOwnFreeHost", $method . "" . $url,
        isset($data) ? ($data) : "",
        print_r($responseData, true));

    return $responseData;
}

function Myownfreehost_Error($func, $params, Exception $err) {
    logModuleCall("MyOwnFreeHost", $func, $params, $err->getMessage(), $err->getTraceAsString());
}

function Myownfreehost_MetaData()
{
    return array( "DisplayName" => "MyOwnFreeHost", "APIVersion" => "1.0", "DefaultNonSSLPort" => "2086", "DefaultSSLPort" => "2087", "ServiceSingleSignOnLabel" => "Login to cPanel", "AdminSingleSignOnLabel" => "Login to MOFH" );
}


function Myownfreehost_ConfigOptions() {
    return [
        "WHM_Package_Name" => [
            "FriendlyName" => "WHM Package Name",
            "Type" => "text",
            "Size" => 25,
        ],
        "Web_Space_Quota" => [
            "FriendlyName" => "Web Space Quota",
            "Description" => "MB",
            "Type" => "text",
            "Size" => 10,
        ],
        "Bandwidth_Limit" => [
            "FriendlyName" => "Bandwidth Limit",
            "Description" => "MB",
            "Type" => "text",
            "Size" => 10,
        ],
        "Max_FTP_Accounts" => [
            "FriendlyName" => "Max FTP Accounts",
            "Type" => "text",
            "Size" => 10,
			"Default" => "1",
        ],
        "Max_Email_Accounts" => [
            "FriendlyName" => "Max Email Accounts",
            "Type" => "text",
			"Size" => 10,
			"Default" => "None",
        ],
        "Max_SQL_Databases" => [
            "FriendlyName" => "Max SQL Databases",
            "Type" => "text",
            "Size" => 10,
        ],
        "Max_Subdomains" => [
            "FriendlyName" => "Max Subdomains",
            "Type" => "text",
            "Size" => 10,
        ],
        "Max_Parked_Domains" => [
            "FriendlyName" => "Max Parked Domains",
            "Type" => "text",
            "Size" => 10,
        ],
        "Max_Addon_Domains" => [
            "FriendlyName" => "Max Addon Domains",
            "Type" => "text",
            "Size" => 10,
        ],
		"Cpanel" => [
            "FriendlyName" => "Cpanel Login Domain",
			"Description" => "Use for connection to the cpanel",
            "Type" => "text",
            "Size" => 10,
        ],
		"Lang" => [
            "FriendlyName" => "Cpanel Language",
			"Description" => "Use to set the language that cpanel should display if the value is empty the English language will be used",
            "Type" => "text",
            "Size" => 10,
        ],
    ];
}


function Myownfreehost_TestConnection(array $params) {
    $err = "";
    try {
        $response = Myownfreehost_API($params, '/json-api/listpkgs');

        if($response['package']['0']['FRONTPAGE'] !== 'n') {
		$status_code = $response['cpanelresult']['error'];
            $err = "" . $status_code . "";
        }
        
    } catch(Exception $e) {
        Myownfreehost_Error(__FUNCTION__, $params, $e);
        $err = $e->getMessage();
    }

    return [
        "success" => $err === "",
        "error" => $err,
    ];
}

function Myownfreehost_GetOption(array $params, $id, $default = NULL) {
    $options = Myownfreehost_ConfigOptions();

    $friendlyName = $options[$id]['FriendlyName'];
    if(isset($params['configoptions'][$friendlyName]) && $params['configoptions'][$friendlyName] !== '') {
        return $params['configoptions'][$friendlyName];
    } else if(isset($params['configoptions'][$id]) && $params['configoptions'][$id] !== '') {
        return $params['configoptions'][$id];
    } else if(isset($params['customfields'][$friendlyName]) && $params['customfields'][$friendlyName] !== '') {
        return $params['customfields'][$friendlyName];
    } else if(isset($params['customfields'][$id]) && $params['customfields'][$id] !== '') {
        return $params['customfields'][$id];
    }

    $found = false;
    $i = 0;
    foreach(Myownfreehost_ConfigOptions() as $key => $value) {
        $i++;
        if($key === $id) {
            $found = true;
            break;
        }
    }

    if($found && isset($params['configoption' . $i]) && $params['configoption' . $i] !== '') {
        return $params['configoption' . $i];
    }

    return $default;
}

function Myownfreehost_CreateAccount(array $params) {
    try {
		
	$package = Myownfreehost_GetOption($params, 'WHM_Package_Name');
    $space = Myownfreehost_GetOption($params, 'Web_Space_Quota');
    $banwidth = Myownfreehost_GetOption($params, 'Bandwidth_Limit');
    $ftp = Myownfreehost_GetOption($params, 'Max_FTP_Accounts');
    $email = Myownfreehost_GetOption($params, 'Max_Email_Accounts');
    $sql = Myownfreehost_GetOption($params, 'Max_SQL_Databases');
    $subdomains = Myownfreehost_GetOption($params, 'Max_Subdomains');
    $parked = Myownfreehost_GetOption($params, 'Max_Parked_Domains');
	$addons = Myownfreehost_GetOption($params, 'Max_Addon_Domains');
	
	
	$postfields = array(  );
    $postfields["username"] = $params["username"];
    $postfields["password"] = $params["password"];
    $postfields["domain"] = $params["domain"];
	$postfields["savepkg"] = 0;
	$postfields["quota"] = $space;
	$postfields["bwlimit"] = $banwidth;
	$postfields["contactemail"] = $params['clientsdetails']['email'];
	$postfields["maxftp"] = $ftp;
	$postfields["maxsql"] = $sql;
	$postfields["maxpop"] = $email;
	$postfields["maxsub"] = $subdomains;
	$postfields["parked"] = $maxpark;
	$postfields["maxaddon"] = $addons;
	$postfields["plan"] = $package;
	$postfields["api.version"] = 1;
    $postfields["reseller"] = 0;
	$output = Myownfreehost_API($params, "/xml-api/createacct", $postfields);
	
	    if($output["result"]["status"] !== "1" ) 
    {
        $error = $output["result"]["statusmsg"];
        throw new Exception(''.$error.'');
    }
	
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}


function Myownfreehost_SuspendAccount(array $params) {
    try {
	
    $output = Myownfreehost_API($params, "/xml-api/suspendacct?api.version=1&user=" . urlencode($params['username']) . "&reason=" . urlencode($params['suspendreason']));
	
	if($output == "") throw new Exception('The reason for cancellation is incorrect only these reasons are valid: PHISHING, VIRUS_MALWARE_HOSTING, NULLED_SCRIPT, CONTENT_VIOLATION, ABUSE_COMPLAINT, SPAM_DOMAIN_SIGNUP, REQUESTED and OTHER.');
	
	if($output["result"]["status"] !== "1" ) 
	{
		$error = $output["result"]["statusmsg"];
		throw new Exception(''.$error.'');
	}
	    } catch(Exception $err) {
        return $err->getMessage();
    }
	return 'success';
}

function Myownfreehost_UnsuspendAccount(array $params) {
    try {

        $output = Myownfreehost_API($params, "/xml-api/unsuspendacct?api.version=1&user=" . urlencode($params['username']) . "&keepdns=0");
		
		
	if($output["result"]["status"] !== "1" ) 
	{
		$error = $output["result"]["statusmsg"];
		throw new Exception(''.$error.'');
	}
	    } catch(Exception $err) {
        return $err->getMessage();
    }
	return 'success';
}

function Myownfreehost_TerminateAccount(array $params) {
    try {
	
	$output = Myownfreehost_API($params, "/xml-api/removeacct?user=" . $params["username"]);
	
	if($output["result"]["status"] !== "1" ) 
	{
		$error = $output["result"]["statusmsg"];
		throw new Exception(''.$error.'');
	}
	
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function Myownfreehost_ChangePassword(array $params) {
    try {
    
    $output = Myownfreehost_API($params, "/json-api/passwd?user=" . $params["username"] . "&pass=" . urlencode($params["password"]));


    if($output["passwd"]["0"]["status"] !== "1") 
    {
        $error = $output["passwd"]["0"]["statusmsg"];
            throw new Exception(''.$error.'');
    }
	
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}

function Myownfreehost_ChangePackage(array $params) {
    try {
     
	 $package = Myownfreehost_GetOption($params, 'WHM_Package_Name');
	 $output = Myownfreehost_API($params, "/xml-api/changepackage?user=" . $params["username"] . "&pkg=" . urlencode($package));
	 
	if($output["result"]["status"] !== "1" ) 
	{
		$error = $output["result"]["statusmsg"];
		throw new Exception(''.$error.'');
	}
		
    } catch(Exception $err) {
        return $err->getMessage();
    }

    return 'success';
}
function Myownfreehost_SingleSignOn($params)
{
	$cpanel = Myownfreehost_GetOption($params, 'Cpanel');
	$link = "https://cpanel." . $cpanel;
	return array( "success" => true, "redirectTo" => $link);
}
function Myownfreehost_ServiceSingleSignOn($params)
{
    return Myownfreehost_SingleSignOn($params);
}
function Myownfreehost_AdminSingleSignOn($params)
{
    return array( "success" => true, "redirectTo" => 'https://panel.myownfreehost.net' );
}

function Myownfreehost_ClientArea($params)
{
    return array( "overrideDisplayTitle" => ucfirst($params["domain"]), "tabOverviewReplacementTemplate" => "../cpanel/templates/overview.tpl", 'vars' => [ 'cpanelurl' => $cpanel, 'lang' => $country,]);
}