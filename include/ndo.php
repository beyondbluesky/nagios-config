<?php
/*
* Classe que implementa les crides a NDO
* 
* Data Modif.	: 2009-12-10
* 
* Des de	: 0.1
* Data Creacio	: 2009-12-10
*
* Autor		: Josep Llaurado Selvas <pep@susipep.com>
*/

include_once "nagios-config.php";
include_once "DB.php";

class NDOServer {

	var $db;

	function NDOServer(){
		global $NDO_HOST,$NDO_DB,$NDO_USER,$NDO_PASS;

		//echo "NDO Server: ".$NDO_HOST;
		$this->db= new DB($NDO_HOST, $NDO_DB, $NDO_USER, $NDO_PASS);
	}

	function getHosts(){
		$res= $this->db->query("SELECT host_id,display_name FROM nagios_hosts");

		while( $r= $this->db->fetch_row($res)){
			$host[$r[0]]=$r[1];
		}

		return $host;
	}

	function getServiceGroups(){
		$res= $this->db->query("select o.name1,sg.alias from nagios_servicegroups sg,nagios_objects o where sg.servicegroup_object_id=o.object_id");
		while( $r= $this->db->fetch_row($res)){
			$sg[$r[0]]=$r[1];
		}

		return $sg;
	}

        function getHostGroups(){
                $res= $this->db->query("select o.name1,hg.alias from nagios_hostgroups hg,nagios_objects o where hg.hostgroup_object_id=o.object_id");
                while( $r= $this->db->fetch_row($res)){
                        $sg[$r[0]]=$r[1];
                }

                return $sg;
        }

        function getContactGroups(){
                $res= $this->db->query("select o.name1,cg.alias from nagios_contactgroups cg,nagios_objects o where cg.contactgroup_object_id=o.object_id");
                while( $r= $this->db->fetch_row($res)){
			if( $r[0] != "jllaurado")
                        $sg[$r[0]]=$r[1];
                }

                return $sg;
        }

}
