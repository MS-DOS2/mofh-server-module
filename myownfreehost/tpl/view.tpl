<link href="modules/servers/myownfreehost/css/client.css" rel="stylesheet">
<script src="modules/servers/myownfreehost/js/client.js"></script>

<div class="row">
    <div class="col-md-6">

        <div class="panel panel-default" id="cPanelPackagePanel">
            <div class="panel-heading">
                <h3 class="panel-title">{$LANG.cPanel.packageDomain}</h3>
            </div>
            <div class="panel-body text-center">

                <div class="cpanel-package-details">
                    <em>{$groupname}</em>
                    <h4 style="margin:0;">{$product}</h4>
                    <a href="http://{$domain}" target="_blank">www.{$domain}</a>
                </div>

                <p>
                    <a href="http://{$domain}" class="btn btn-default btn-sm" target="_blank">{$LANG.visitwebsite}</a>
                    {if $domainId}
                        <a href="clientarea.php?action=domaindetails&id={$domainId}" class="btn btn-success btn-sm" target="_blank">{$LANG.managedomain}</a>
                    {/if}
                </p>

            </div>
        </div>



    </div>
    <div class="col-md-6">

        <div class="panel panel-default" id="cPanelUsagePanel">
            <div class="panel-heading">
                <h3 class="panel-title">{$LANG.cPanel.usageStats}</h3>
            </div>
            <div class="panel-body text-center cpanel-usage-stats">

                <div class="row">
                    <div class="col-sm-5 col-sm-offset-1 col-xs-6" id="diskUsage">
                        <strong>{$LANG.cPanel.diskUsage}</strong>
                        <br /><br />
                        <input type="text" value="{$diskpercent|substr:0:-1}" class="usage-dial" data-fgColor="#444" data-angleOffset="-125" data-angleArc="250" data-min="0" data-max="{if substr($diskpercent, 0, -1) > 100}{$diskpercent|substr:0:-1}{else}100{/if}" data-readOnly="true" data-width="100" data-height="80" />
                        <br /><br />
                        {$diskusage} M / {$disklimit} M
                    </div>
                    <div class="col-sm-5 col-xs-6" id="bandwidthUsage">
                        <strong>{$LANG.cPanel.bandwidthUsage}</strong>
                        <br /><br />
                        <input type="text" value="{$bwpercent|substr:0:-1}" class="usage-dial" data-fgColor="#d9534f" data-angleOffset="-125" data-angleArc="250" data-min="0" data-max="{if substr($bwpercent, 0, -1) > 100}{$bwpercent|substr:0:-1}{else}100{/if}" data-readOnly="true" data-width="100" data-height="80" />
                        <br /><br />
                        {$bwusage} M / {$bwlimit} M
                    </div>
                </div>

                {if $bwpercent|substr:0:-1 > 75}
                    <div class="text-danger limit-near">
                        {if $bwpercent|substr:0:-1 > 100}
                            {$LANG.cPanel.usageStatsBwOverLimit}
                        {else}
                            {$LANG.cPanel.usageStatsBwLimitNear}
                        {/if}
                        {if $packagesupgrade}
                            <a href="upgrade.php?type=package&id={$serviceid}" class="btn btn-xs btn-danger">
                                <i class="fas fa-arrow-circle-up"></i>
                                {$LANG.cPanel.usageUpgradeNow}
                            </a>
                        {/if}
                    </div>
                {elseif $diskpercent|substr:0:-1 > 75}
                    <div class="text-danger limit-near">
                        {if $diskpercent|substr:0:-1 > 100}
                            {$LANG.cPanel.usageStatsDiskOverLimit}
                        {else}
                            {$LANG.cPanel.usageStatsDiskLimitNear}
                        {/if}
                        {if $packagesupgrade}
                            <a href="upgrade.php?type=package&id={$serviceid}" class="btn btn-xs btn-danger">
                                <i class="fas fa-arrow-circle-up"></i>
                                {$LANG.cPanel.usageUpgradeNow}
                            </a>
                        {/if}
                    </div>
                {else}
                    <div class="text-info limit-near">
                        {$LANG.cPanel.usageLastUpdated} {$lastupdate}
                    </div>
                {/if}

                <script src="{$BASE_PATH_JS}/jquery.knob.js"></script>
                <script type="text/javascript">
                jQuery(function() {
                    jQuery(".usage-dial").knob({
                        'format': function (value) {
                            return value + '%';
                        }
                    });
                });
                </script>

            </div>
        </div>

    </div>
</div>

{if $systemStatus == 'Active'}

    <div class="panel card panel-default mb-3" id="cPanelQuickShortcutsPanel">
        <div class="panel-heading card-header">
            <h3 class="panel-title card-title m-0">{$LANG.cPanel.quickShortcuts}</h3>
        </div>
        <div class="panel-body card-body text-center">

            <div class="row cpanel-feature-row">
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelEmailAccounts">
                    
                        <img src="modules/servers/cpanel/img/email_accounts.png" />
                        {$LANG.cPanel.emailAccounts}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelForwarders">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=emailaccounts" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/forwarders.png" />
                        {$LANG.cPanel.forwarders}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelAutoResponders">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=emailforwarder" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/autoresponders.png" />
                        {$LANG.cPanel.autoresponders}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelFileManager">
                    <a href="https://filemanager.ai/new/" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/file_manager.png" />
                        {$LANG.cPanel.fileManager}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelBackup">
                    <a href="clientarea.php?action=productdetails&amp;id={$serviceid}&amp;dosinglesignon=1&amp;app=Backups_Home" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/backup.png" />
                        {$LANG.cPanel.backup}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelSubdomains">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=subdomains" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/subdomains.png" />
                        {$LANG.cPanel.subdomains}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelAddonDomains">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=domains" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/addon_domains.png" />
                        {$LANG.cPanel.addonDomains}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelCronJobs">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=cron" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/cron_jobs.png" />
                        {$LANG.cPanel.cronJobs}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelMySQLDatabases">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=mysql" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/mysql_databases.png" />
                        {$LANG.cPanel.mysqlDatabases}
                    </a>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-6 col-6" id="cPanelPhpMyAdmin">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=pma" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/php_my_admin.png" />
                        {$LANG.cPanel.phpMyAdmin}
                    </a>
                </div>
                <div class="col-sm-3 col-xs-6" id="cPanelAwstats">
                    <a href="https://cpanel.{$cpanelurl}/panel/indexpl.php?option=stats2" target="_blank" class="d-block mb-3">
                        <img src="modules/servers/cpanel/img/awstats.png" />
                        {$LANG.cPanel.awstats}
                    </a>
                </div>
            </div>

        </div>
    </div>

{else}

    <div class="alert alert-warning text-center" role="alert" id="cPanelSuspendReasonPanel">
        {if $suspendreason}
            <strong>{$suspendreason}</strong><br />
        {/if}
        {$LANG.cPanel.packageNotActive} {$status}.<br />
        {if $systemStatus eq "Pending"}
            {$LANG.cPanel.statusPendingNotice}
        {elseif $systemStatus eq "Suspended"}
            {$LANG.cPanel.statusSuspendedNotice}
        {/if}
    </div>

{/if}

<div class="panel panel-default" id="cPanelBillingOverviewPanel">
    <div class="panel-heading">
        <h3 class="panel-title">{$LANG.cPanel.billingOverview}</h3>
    </div>
    <div class="panel-body">

        <div class="row">
            <div class="col-md-5">
                {if $firstpaymentamount neq $recurringamount}
                    <div class="row" id="firstPaymentAmount">
                        <div class="col-xs-6 text-right" >
                            {$LANG.firstpaymentamount}
                        </div>
                        <div class="col-xs-6">
                            {$firstpaymentamount}
                        </div>
                    </div>
                {/if}
                {if $billingcycle != $LANG.orderpaymenttermonetime && $billingcycle != $LANG.orderfree}
                    <div class="row" id="recurringAmount">
                        <div class="col-xs-6 text-right">
                            {$LANG.recurringamount}
                        </div>
                        <div class="col-xs-6">
                            {$recurringamount}

                        </div>
                    </div>
                {/if}
                <div class="row" id="billingCycle">
                    <div class="col-xs-6 text-right">
                        {$LANG.orderbillingcycle}
                    </div>
                    <div class="col-xs-6">
                        {$billingcycle}
                    </div>
                </div>
                <div class="row" id="paymentMethod">
                    <div class="col-xs-6 text-right">
                        {$LANG.orderpaymentmethod}
                    </div>
                    <div class="col-xs-6">
                        {$paymentmethod}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row" id="registrationDate">
                    <div class="col-xs-6 col-md-5 text-right">
                        {$LANG.clientareahostingregdate}
                    </div>
                    <div class="col-xs-6 col-md-7">
                        {$regdate}
                    </div>
                </div>
                <div class="row" id="nextDueDate">
                    <div class="col-xs-6 col-md-5 text-right">
                        {$LANG.clientareahostingnextduedate}
                    </div>
                    <div class="col-xs-6 col-md-7">
                        {$nextduedate}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{if $configurableoptions}
    <div class="panel panel-default" id="cPanelConfigurableOptionsPanel">
        <div class="panel-heading">
            <h3 class="panel-title">{$LANG.orderconfigpackage}</h3>
        </div>
        <div class="panel-body">
            {foreach from=$configurableoptions item=configoption}
                <div class="row">
                    <div class="col-md-5 col-xs-6 text-right">
                        <strong>{$configoption.optionname}</strong>
                    </div>
                    <div class="col-md-7 col-xs-6 text-left">
                        {if $configoption.optiontype eq 3}{if $configoption.selectedqty}{$LANG.yes}{else}{$LANG.no}{/if}{elseif $configoption.optiontype eq 4}{$configoption.selectedqty} x {$configoption.selectedoption}{else}{$configoption.selectedoption}{/if}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}
{if $metricStats}
    <div class="panel panel-default" id="cPanelMetricStatsPanel">
        <div class="panel-heading">
            <h3 class="panel-title">{$LANG.metrics.title}</h3>
        </div>
        <div class="panel-body">
            {include file="$template/clientareaproductusagebilling.tpl"}
        </div>
    </div>
{/if}
{if $customfields}
    <div class="panel panel-default" id="cPanelAdditionalInfoPanel">
        <div class="panel-heading">
            <h3 class="panel-title">{$LANG.additionalInfo}</h3>
        </div>
        <div class="panel-body">
            {foreach from=$customfields item=field}
                <div class="row">
                    <div class="col-md-5 col-xs-6 text-right">
                        <strong>{$field.name}</strong>
                    </div>
                    <div class="col-md-7 col-xs-6 text-left">
                        {if empty($field.value)}
                            {$LANG.blankCustomField}
                        {else}
                            {$field.value}
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}