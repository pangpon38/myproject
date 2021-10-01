<?php

// $key = "B=B,B8AWw%aLwF3";
// $key2 = "vUdVIjTvstf64Xs";

// $key = 'uWpJx"63j.D^7]W';
// $key2 = 'aXlZErpX0cCe@7/';

function url2code($paramLink)
{ global $key, $key2;
    $base64 = strtr($paramLink, $key, $key2);
    $plainText = base64_decode($base64);
    return ($plainText);
}

function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    // $secret_key = 'uWpJx"63j.D^7]W';
    // $secret_iv = 'aXlZErpX0cCe@7/';
    $secret_key = "B=B,B8AWw%aLwF3";
    $secret_iv = "vUdVIjTvstf64Xs";
    // hash
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}

function url2param($paramLink)
{
    global $key, $key2;
    return str_replace([$key, $key2], ['+', '/'], base64_encode($paramLink));
}

// $a = encrypt_decrypt("encrypt","depname=&province=71&amphur=20");
// echo $a;
$b = encrypt_decrypt("decrypt","U0pKUXdHSjBjTFova201NW16TEVpeTVWcnByZ2dXTkdEYmdFbkt0ZUlRUT0=");
echo "<br>".$b;
