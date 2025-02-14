<?php

/* 
 * author: Paul Grimm (paul.gimm@gbv...)
 *
*/

/**
 * Check if a given ip is in a network
 * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
 * @return boolean true if the ip is in this range / false if not.
 */
function ip_in_range( $ip, $range ) {
    if ( strpos( $range, '/' ) == false ) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list( $range, $netmask ) = explode( '/', $range, 2 );
    $range_decimal = ip2long( $range );
    $ip_decimal = ip2long( $ip );
    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}

function loadAndCheckConfig () {
    $msg=array();
    if (is_file('config.php')) {
        include ('config.php');
    } else {
        $msg['msg']="Error: config.php not found. Copy template and fill it with properties.";
        sendArrayAsJson($msg);
        exit();
    }

    if ( is_array(($config['IP Range'])) ) {
        $isInRange=false;
        foreach ($config['IP Range'] as $IP_Range) {
            if ((ip_in_range($_SERVER['REMOTE_ADDR'],$config['IP Range'])) ) $isInRange=true;
        }
        if (! $isInRange) {
            $msg['msg']="Error: IP not in IP Range.";
        }
    } else {
        $msg['msg']="Error: Property 'IP Range' is malformed (not an array). Set i.e. ['127.0.0.0/24']";
    }
    
    if (isset($msg['msg'])) {
        sendArrayAsJson($msg);
        exit();
    }
    
}

function sendArrayAsJson($arr) {
    $json = json_encode($arr);
    header('Content-Type: application/json');
    echo $json;
}