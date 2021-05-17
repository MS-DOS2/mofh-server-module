<?php
use WHMCS\Database\Capsule;
add_hook("ClientAreaPrimarySidebar", -1, "Myownfreehost_defineSsoSidebarLinks");
function Myownfreehost_defineSsoSidebarLinks($sidebar) {
    if (!$sidebar->getChild("Service Details Actions")) {
        return NULL;
    }
    $service = Menu::context("service");
    if ($service instanceof WHMCS\Service\Service && $service->product->module != "myownfreehost") {
        return NULL;
    }
    $ssoPermission = checkContactPermission("productsso", true);
	$username = $service->username;
	$command = 'DecryptPassword';
	$postData = array('password2' => $service->password);
	$results = localAPI($command, $postData);
	$password = $results['password'];
	$result = Capsule::select(Capsule::raw('SELECT configoption10,configoption11 FROM tblproducts WHERE id = '.$service->product->id.''))[0];
	$cpanelurl = $result->configoption10;
	$lang = $result->configoption11;
	$sidebar->getChild("Service Details Actions")->addChild('cPanel Login', array('label' => '<form action="https://cpanel.'.$cpanelurl.'/login.php" method="post" name="login" >
	<input name="uname" id="mod_login_username" type="hidden" class="inputbox" alt="username" size="10" value="'.$username.'" />
	<input type="hidden" id="mod_login_password" name="passwd" class="inputbox" size="10" alt="password" value="'.$password.'"/>
	<input type="hidden" name="language" value="'.$lang.'" />
	<input type="hidden" type="Submit" name="Submit" value="Login to Cpanel" class="btn btn-primary modulebutton" />
	<a style="color: #495057" href="#" onclick="parentNode.submit();">'.Lang::trans('cpanellogin').'</a> 
	</form>', "disabled" => $service->status != "Active", 'order' => 1));
    $sidebar->getChild("Service Details Actions")->addChild("Login to Webmail", array("uri" => "http://185.27.134.244/roundcubemail/", "label" => Lang::trans("cpanelwebmaillogin"), "attributes" => array("target" => "_blank"), "disabled" => $service->status != "Active", "order" => 2));
	$sidebar->getChild("Service Details Actions")->addChild("Request Cancellation", array("uri" => "clientarea.php?action=cancel&id=" . $service->id, "label" => Lang::trans("cancellationrequested"), "attributes" => ["target" => "_blank"], "disabled" => $service->status != "Active", "order" => 4));
}
?>