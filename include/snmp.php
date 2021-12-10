<?php
/*
* Classe que implementa les crides snmp
* 
* Data Modif.	: 2009-12-10
* 
* Des de	: 0.1
* Data Creacio	: 2009-12-10
*
* Autor		: Josep Llaurado Selvas <pep@susipep.com>
*/

class SnmpServer {

	var $hostname;
	var $port;
	var $snmpcommunity;

	var $STORAGE_RESOURCE_TYPES= array("HOST-RESOURCES-TYPES::hrStorageRam"=>"Memoria RAM",
	"HOST-RESOURCES-TYPES::hrStorageVirtualMemory"=>"Memoria Virtual",
	"HOST-RESOURCES-TYPES::hrStorageOther"=>"Memoria",
	"HOST-RESOURCES-TYPES::hrStorageVirtualMemory"=>"Swap",
	"HOST-RESOURCES-TYPES::hrStorageFixedDisk"=>"Disc"
	);

	function SnmpServer($hostname,$snmpcommunity){
		$this->hostname= $hostname;
		$this->snmpcommunity= $snmpcommunity;
		$this->port= 161;
	}

	function get($oid){
		$res= snmpget($this->hostname, $this->snmpcommunity, $oid);	
		//echo "--".$res."--";
		$res= substr(strchr($res, ":"),2);
		return $res;
	}

        function getNext($oid){
                $res= @snmpgetnext($this->hostname, $this->snmpcommunity, $oid);
                error_log("snmpgetnext: ".$res."--");
                $res= substr(strchr($res, ":"),2);
                return $res;
        }

        function walkKey($oid){
                $res= @snmprealwalk($this->hostname, $this->snmpcommunity, $oid);
		$res2= array();

		if(! empty($res)){
		    foreach($res as $key=>$value){
			$key2=substr(strrchr($key, "."),1);
			$value2=substr(strchr($value, ":"),2);
			$res2[$key2]=$value2;
		    }
		}
                return $res2;
        }

	function getDescr(){
		$desc= $this->get("SNMPv2-MIB::sysDescr.0");
		return $desc;
	}

	function getContact(){
		$desc= $this->get("SNMPv2-MIB::sysContact.0");
		return $desc;
	}

	function getSysName(){
		$desc= $this->get("SNMPv2-MIB::sysName.0");
		return $desc;
	}

	function getLocation(){
		$desc= $this->get("SNMPv2-MIB::sysLocation.0");
		return $desc;
	}

	function getIfaceNum(){
		$desc= $this->get("IF-MIB::ifNumber.0");
		return $desc;
	}

	function getIfaceOIDs(){
		$res=$this->walkKey("IF-MIB::ifIndex");
		$oids= array();
		if( ! empty($res)){
		foreach($res as $id=>$val)
			$oids[]= $val;
		}
		return $oids;
	}

	function getIfaceDescr($ifaceNum){
		$desc= $this->get("IF-MIB::ifDescr.$ifaceNum");
		return $desc;
	}

	function getIfaceMAC($ifaceNum){
		$desc= $this->get("IF-MIB::ifPhysAddress.$ifaceNum");
		return $desc;
	}

	function isStorage($id){
		$res= $this->get("HOST-RESOURCES-MIB::hrStorageIndex.$id");
		if( $res == $id )
			$desc=true;
		else
			$desc=false;
		return $desc;
	}

        function getStorageArray(){
                $desc= $this->walkKey("HOST-RESOURCES-MIB::hrStorageIndex");
                return $desc;
        }

	function getStorageDescr($storageNum){
		$desc= $this->get("HOST-RESOURCES-MIB::hrStorageDescr.$storageNum");
		return $desc;
	}

	function getStorageType($storageNum){
		$desc= $this->get("HOST-RESOURCES-MIB::hrStorageType.$storageNum");
		return $this->STORAGE_RESOURCE_TYPES[$desc];
	}

	function getStorageSize($storageNum){
		$units= $this->get("HOST-RESOURCES-MIB::hrStorageAllocationUnits.$storageNum");
		$size= $this->get("HOST-RESOURCES-MIB::hrStorageSize.$storageNum");
		$desc= $units*$size;
		return $desc;
	}

	function getStorageUsed($storageNum){
		$units= $this->get("HOST-RESOURCES-MIB::hrStorageAllocationUnits.$storageNum");
		$size= $this->get("HOST-RESOURCES-MIB::hrStorageUsed.$storageNum");
		$desc= $units*$size;
		return $desc;
	}

	function isProcess($id){
		$res= $this->get("HOST-RESOURCES-MIB::hrSWRunIndex.$id");
		if( $res == $id )
			$desc=true;
		else
			$desc=false;
		return $desc;
	}

	function getProcessArray(){
		$desc= $this->walkKey("HOST-RESOURCES-MIB::hrSWRunIndex");
		return $desc;
	}

	function getProcessName($pid){
		$desc= $this->get("HOST-RESOURCES-MIB::hrSWRunName.$pid");
		return $desc;
	}

	function getProcessPath($pid){
		$desc= $this->get("HOST-RESOURCES-MIB::hrSWRunPath.$pid");
		return $desc;
	}

	function getProcessParams($pid){
		$desc= $this->get("HOST-RESOURCES-MIB::hrSWRunParameters.$pid");
		return $desc;
	}

}
