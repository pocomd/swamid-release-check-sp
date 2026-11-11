<?php

$config = new \releasecheck\Configuration();
$html = $config->getExtendedClass('HTML');

$admin = $config->getExtendedClass('Admin');
$html->showHTMLHead();
$html->showContentHeader();

$admin->showUsageStats();

$collapseIcons[] = "idpList";
$collapseIcons[] = "regAuthList";

$html->addTableSort('idpTable');
$html->addTableSort('regAuthTable');
$html->showContentFooter();
$html->showScripts($collapseIcons);
