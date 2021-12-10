<?php
/*
* Classe que implementa un servei del Nagios
* 
* Data Modif.	: 2009-12-11
* 
* Des de	: 0.1
* Data Creacio	: 2009-12-11
*
* Autor		: Josep Llaurado Selvas <pep@susipep.com>
*/

class Service {

	var $simple;
	var $type;
	var $host;
	var $name;
	var $desc;
	var $sg;

	var $service_command= array(
		"ping"=>"check_ping!100.0,20%!500.0,60%",
		"snmp-int"=>"check_snmp_int_v1!",
		"snmp-stor"=>"check_snmp_storage_v1!",
		"snmp-proc"=>"check_snmp_process_v1!"
		);

	var $service_post_command= array(
		"ping"=>"",
		"snmp-int"=>"",
		"snmp-stor"=>"!90!95!-r"
		);

	var $service_template= array(
		"ping"=>"generic-service",
		"snmp-int"=>"snmp-iface",
		"snmp-stor"=>"snmp-storage",
		"snmp-proc"=>"snmp-process"
		);

	function __construct($type,$params){
		$this->type= $type;
		if(isset($params)){
			//var_dump($params);
			foreach($params as $key=>$value){
				if( $key == "command" ){
					$this->service_command[$type]= $value;
				}
			}
		}
	}

	function setName($host){
		$this->name= $host;
	}

	function setHost($host){
		$this->host= $host;
	}

	function setSG($sg){
		$this->sg[]= $sg;
	}

	function setDesc($desc){
		$this->desc= $desc;
	}

	function addServiceGroup($sg){
		$this->sg[]= $sg;
	}

	function sg2str(){
		if( isset($this->sg)) foreach($this->sg as $val){ 
			if( isset($out)) $out .=",";
			$out .= $val;
		}
		return $out;
	}

	function __toString(){
		if( $this->simple ){
			$out_command="check_command                   ".$this->service_command[$this->type];
		}else{
			$out_command="check_command                   ".$this->service_command[$this->type]."\"".$this->name."\"".$this->service_post_command[$this->type];
		}

		$out= "
define service{
        use                             ".$this->service_template[$this->type]."        ; Name of service template to use
        ".$out_command;

		if( isset($this->host)) $out .="	
        host_name                       ".$this->host;
		if( isset($this->desc)) $out .="	
        service_description             ".$this->desc;
		if( isset($this->sg)) $out .="	
        servicegroups   ".$this->sg2str();

		$out .= "
}";
		return $out;
	}
}

class ProcessService extends Service{
	
	var $warn;
	var $err;

	function setWarn($warn){
		$this->warn= $warn;
	}
	function setErr($err){
		$this->err= $err;
	}

        function __toString(){
                if( $this->simple ){
                        $out_command="check_command                   ".$this->service_command[$this->type];
                }else{
                        $out_command="check_command                   ".$this->service_command[$this->type].$this->name.$this->service_post_command[$this->type]."!".$this->warn."!".$this->err;
                }

                $out= "
define service{
        use                             ".$this->service_template[$this->type]."        ; Name of service template to use
        ".$out_command;

                if( isset($this->host)) $out .="
        host_name                       ".$this->host;
                if( isset($this->desc)) $out .="
        service_description             ".$this->desc;
                if( isset($this->sg)) $out .="
        servicegroups   ".$this->sg2str();

                $out .= "
}";
                return $out;
        }

}
