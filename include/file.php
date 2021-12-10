<?php
/*
* Classe que implementa les crides a fitxers
*
* Data Modif.   : 2009-12-11
*
* Des de        : 0.1
* Data Creacio  : 2009-12-11
*
* Autor         : Josep Llaurado Selvas <pep@susipep.com>
*/

class File {

        var $filename;
        var $content;

        function __construct($filename,$perm){

                $this->filename= $filename;
                $this->content= "";
        }

        function write($cadena){
                $this->content.= $cadena;
        }

        function __destruct(){
                return file_put_contents($this->filename, $this->content);
        }

        function readline(){
                return file_read_contents($this->filename);
        }

        function eof(){
                return false;
        }
}
