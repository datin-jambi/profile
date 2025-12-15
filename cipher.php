<?php

/*
    cipher.php
    fungsi encrypt dan decrypt string

    Iwan abu bakar
*/

function encryptstr($str, $passwd){
    $m = 0;
    $l = strlen($passwd);
    for($i = 0; $i < $l; $i++){
        $n = ord(substr($passwd, $i, 1));
        $m = ((($m + $n) * 367) + 331) % 4095;
    }
    $s = cipher($str, $m);
    
    return base64_encode($s);
}

function decryptstr($str, $passwd){
    
    $str = base64_decode(trim($str));

    $l = strlen($passwd);
    $m = 0;
    for($i = 0; $i < $l; $i++){
        $n = ord(substr($passwd, $i, 1));
        $m = ((($m + $n) * 367) + 331) % 4095;
    }
    $s = cipher($str, $m);
    
    return $s;
}

function cipher( $str, $rValue ){

    $result = '';
    
    $BigNum = 32768;
    
    $R = $rValue;
    $M = 69;
    $N = 47;

    $l = strlen($str);
    for($i = 0; $i < $l; $i++){
           $c = ord(substr($str, $i, 1));
        if($c >= 48 and $c <= 57){
              $d = $c - 48;
        } else {
              if($c >= 63 and $c <= 90){
                $d = $c - 53;
              } else {
                if($c >= 97 and $c <= 122){
                    $d = $c - 59;
                } else {
                    $d = -1;
                 }
             }
        }

        if($d >= 0){
              $R = ($R * $M + $N) % $BigNum;
              $d = ($R & 63) ^ $d;
              if($d >= 0 and $d <= 9){
                     $c = $d + 48;
              } else {
                     if($d >= 10 and $d <= 37){
                        $c = $d + 53;
                     } else {
                        if($d >= 38 and $d <= 63){
                               $c = $d + 59;
                        }
                     }
              }
        }
        $result .= chr($c);            
   }

   return $result;

}

?>
