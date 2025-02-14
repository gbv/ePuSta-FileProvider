<?php

/* Provide to download the epustalogfile
 * @author Paul Grimm <paul.grimm@gbv...>
 *
*/

include ('config.php');

function send404() {
    // TO DO 404 Page
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Cache-Control: must-revalidate');
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    exit;
};

$filename=$_GET['filename'];
if (strpos($filename,'/') !== false) {
    send404();
}
$filepath=$config['epustalog_dir'].'/'.$filename;
if (! is_file ($filepath) ) {
    send404();
}
$filesize=filesize( $filepath );

header('Content-Description: File Transfer');
// ToDo Download as ZIP
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Length: '.$filesize );
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

readfile( $filepath );

?>
