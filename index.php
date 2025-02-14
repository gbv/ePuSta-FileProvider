<?php

/* creates a json array
 * author: Paul Grimm (paul.gimm@gbv...)
 *
*/

include 'lib.php';

loadAndCheckConfig();

$files=array();
foreach (new DirectoryIterator($config['epustalog_dir']) as $fileInfo) {
    if($fileInfo->isDot()) continue;
    if($fileInfo->isDir()) continue;
    $file=array();
    $file['filename']=$fileInfo->getFilename();
    $file['modifyTime']=$fileInfo->getMTime();
    $files[]=$file;
}

sendArrayAsJson($files);

?>