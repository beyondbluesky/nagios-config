<?php

$NDO_HOST="localhost";
$NDO_DB="nagios";
$NDO_USER="ndoutils";
$NDO_PASS="xxxxx";

$SYS_SLASH="/";

$NAGIOS_BASE="/usr/local/nagios";
$NAGIOS_BASE_CFG= array(
	"network"=>"/usr/local/nagios/etc/objects/networks",
	"host"=>   "/usr/local/nagios/etc/objects/hosts",
	"service"=>"/usr/local/nagios/etc/objects/services");

$TEMPLATE_TO_PATH= array(
	"generic-firewall"=>"network",
	"generic-router"=>"network",
	"generic-switch"=>"network",
	"linux-server"=>"host",
	"windows-server"=>"host",
	"vmware-server"=>"host",
	"cluster-server"=>"host",
	"generic-host"=>"host"
	);

?>
