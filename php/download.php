<?php
$base_dir = "/usr/share/nginx/html/";
$myfile = $base_dir . $_GET["file"];
//echo $myfile;

if( ! file_exists($myfile) ) {
	echo "file: " . $myfile . " doesn't exist.";
} elseif ( is_dir($myfile) ) {
	echo "file: " . $myfile . " is a directory.";
}  else {
    header("Content-type: application/octet-stream");
    header('Content-Disposition: attachment; filename="' . basename($myfile) . '"');
    header("Content-Length: ". filesize($myfile));
    readfile($myfile);
}
?>
