<?php
/*
* Funcions d'usuari emprant la classe ndo
*
* Data Modif.   : 2009-12-10
*
* Des de        : 0.1
* Data Creacio  : 2009-12-10
*
* Autor         : Josep Llaurado Selvas <pep@susipep.com>
*/
include_once "nagios-config.php";
include_once "ndo.php";
include_once "service.php";

function sNDOChooseParents($ndo){
        $nagios_hosts= $ndo->getHosts();

        $nagios_hosts_out="<SELECT name=\"sNagiosParent\">";
        foreach($nagios_hosts as $id=>$parent_name){
                $nagios_hosts_out .= "<option value=\"".$parent_name."\">".$parent_name."</option>\n";
        }
        $nagios_hosts_out .= "</select>";

        return $nagios_hosts_out;
}

function sNDOChooseServiceGroups($ndo){
        $nagios_sg= $ndo->getServiceGroups();

        $nagios_sg_out="<SELECT name=\"sNagiosSG[]\">";
        foreach($nagios_sg as $id=>$sg_name){
                $nagios_sg_out .= "<option value=\"".$id."\">".$sg_name."</option>\n";
        }
        $nagios_sg_out .= "</select>";

        return $nagios_sg_out;
}

function sNDOChooseHostGroups($ndo){
        $nagios_sg= $ndo->getHostGroups();

        $nagios_sg_out="<SELECT name=\"sNagiosHG[]\">";
        foreach($nagios_sg as $id=>$sg_name){
                $nagios_sg_out .= "<option value=\"".$id."\">".$sg_name."</option>\n";
        }
        $nagios_sg_out .= "</select>";

        return $nagios_sg_out;
}

function sNDOChooseContactGroups($ndo){
        $nagios_cg= $ndo->getContactGroups();

        $nagios_cg_out="<SELECT name=\"sNagiosCG[]\">";
        foreach($nagios_cg as $id=>$cg_name){
                $nagios_cg_out .= "<option value=\"".$id."\">".$cg_name."</option>\n";
        }
        $nagios_cg_out .= "</select>";

        return $nagios_cg_out;
}

function sNagiosHost($hostname,$hosttype,$hostip,$hours,$alias,$parents,$hostgroups,$contactgroups){

	$out= "define host {
        host_name                       ".$hostname."      ; The name of this host template
        use                             ".$hosttype."      ; This template inherits other values from the generic-host template
        address                         ".$hostip."    ;
        alias                           ".$alias."     ;
        check_period                    ".$hours."     ; By default, Linux hosts are checked round the clock
        check_interval                  5               ; Actively check the host every 5 minutes
        retry_interval                  1               ; Schedule host check retries at 1 minute intervals
        max_check_attempts              10              ; Check each Linux host 10 times (max)
        check_command                   check-host-alive ; Default command to check Linux hosts
        notification_period             ".$hours."       ; Linux admins hate to be woken up, so we only notify during th
                                                        ; Note that the notification_period variable is being overridde
                                                        ; the value that is inherited from the generic-host template!
        contact_groups                   ".$contactgroups." ; Notifications get sent to 
	hostgroups			".$hostgroups."
        parents                         ".$parents."
        register                        1               
        }
	";

	$ping= new Service("ping",true);
	$ping->setDesc("Servei de PING");
	$ping->setName($hostname);
	$ping->setHost($hostname);
	//$ping->addServiceGroup($sg);

	$out .= $ping;

	return $out;
}

function sConfigFile($host,$type){

	global $SYS_SLASH;
	global $NAGIOS_BASE;
	global $NAGIOS_BASE_NET;
	global $NAGIOS_BASE_HOST;
	global $NAGIOS_BASE_SRV;

	if( $type == "suse-server"){
		$base= $NAGIOS_BASE_HOST;

	}
	
	$config_file= $base.$SYS_SLASH.$host.".cfg";

	echo "-".$config_file."-".$type."-";

}

?>
