<?php
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
    $sidebar->getChild("Service Details Actions")->addChild("Login to cPanel", array("uri" => "clientarea.php?action=productdetails&id=" . $service->id . "&dosinglesignon=1", "label" => Lang::trans("cpanellogin"), "attributes" => $ssoPermission ? array("target" => "_blank") : array(), "disabled" => $service->status != "Active", "order" => 1));
    $moduleInterface = new WHMCS\Module\Server();
    $moduleInterface->loadByServiceID($service->id);
    $serverParams = $moduleInterface->getServerParams($service->server);
    $domain = $serverParams["serverhostname"] ?: $serverParams["serverip"];
    $port = $serverParams["serversecure"] ? "2096" : "2095";
    $webmailUrl = $serverParams["serverhttpprefix"] . "://" . $domain . ":" . $port;
    $sidebar->getChild("Service Details Actions")->addChild("Login to Webmail", array("uri" => $webmailUrl, "label" => Lang::trans("cpanelwebmaillogin"), "attributes" => array("target" => "_blank"), "disabled" => $service->status != "Active", "order" => 3));
	$sidebar->getChild("Service Details Actions")->addChild("Request Cancellation", array("uri" => "clientarea.php?action=cancel&id=" . $service->id, "label" => Lang::trans("cancellationrequested"), "attributes" => ["target" => "_blank"], "disabled" => $service->status != "Active", "order" => 4));
}
?>