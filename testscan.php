<?php
  if ($handle = opendir('../culture_file_scan/abc')) { // here add your directory
  //$keyword = "module_"; // your keyword
    while (false !== ($entry = readdir($handle))) {
        // (preg_match('/\.txt$/', $entry)) {
        if (preg_match('/'.$filename.'/i', $entry)) {
            echo "$entry\n";
        }
    }

    closedir($handle);
}

if($ARR_GCODE[$data_list["MEDIA_GCODE"]] == "ภย"){
    $filepath = "../culture_file_scan/ภย/";
    }else{
    $filepath = "../culture_file_scan/วท/";
    }
    $filename = str_replace(".","",$ARR_GCODE[$data_list["MEDIA_GCODE"]])."_".$data_list["LICENSE_NO"]."_".substr($data_list["LICENSE_YEAR"],-2);
    $full_path = $filepath.$filename;
    echo '<a href="downloadfile.php?path='.$full_path.'">'.$filename.'</a>';

// foreach(glob('../ภย/test/*.png') as $file) {
//     echo $file;
// }

$dir = "/etc/php5/";

// Open a known directory, and proceed to read its contents
$dir = '../culture_file_scan/ภย';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
        }
        closedir($dh);
    }
}

?>