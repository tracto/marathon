<?php
namespace TdS\MarathonBundle\Services;



class StringToolbox {
	public function replaceAllAccents($string){
	 	$newStr = $string;
	    $newStr = preg_replace('#Ç#', 'C', $newStr);
	    $newStr = preg_replace('#ç#', 'c', $newStr);
	    $newStr = preg_replace('#è|é|ê|ë#', 'e', $newStr);
	    $newStr = preg_replace('#È|É|Ê|Ë#', 'E', $newStr);
	    $newStr = preg_replace('#à|á|â|ã|ä|å#', 'a', $newStr);
	    $newStr = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $newStr);
	    $newStr = preg_replace('#ì|í|î|ï#', 'i', $newStr);
	    $newStr = preg_replace('#Ì|Í|Î|Ï#', 'I', $newStr);
	    $newStr = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $newStr);
	    $newStr = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $newStr);
	    $newStr = preg_replace('#ù|ú|û|ü#', 'u', $newStr);
	    $newStr = preg_replace('#Ù|Ú|Û|Ü#', 'U', $newStr);
	    $newStr = preg_replace('#ý|ÿ#', 'y', $newStr);
	    $newStr = preg_replace('#Ý#', 'Y', $newStr);
	    $newStr=preg_replace('/[^A-Za-z0-9\-]/', '', $newStr); 
	    return ($newStr);
	}

}