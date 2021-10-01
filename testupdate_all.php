<?php
include '../include/include.php';

$dir = '../culture_file_scan/vortor';
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $split = substr($file, 0, strpos($file, '.'));
           $split1 = explode('_',$split);
            //echo $split1[1].$split1[2]."<br>";
            // $year = '25'.$split1[2];
            // $sql_update = "SELECT * FROM M_LICENSE_WAREHOUSE WHERE LICENSE_NO = '".$split1[1]."' AND LICENSE_YEAR = '".$year."'";
            // $query_chk_update = db::query($sql_update);
            // $row = db::num_rows($query_chk_update);
            // if($row > 0){
            //     while($rec = db::fetch_array($query_chk_update)){
            //         echo $rec['LICENSE_ID']." ".$file."<br>";
            //         unset($field_update);
            // $cond = ["LICENSE_ID" => $rec['LICENSE_ID']];
            // $field_update = [
            //     "WH_FILE_NAME" => iconv( 'TIS-620', 'UTF-8',$file),
            //     "WH_ATTACH_FILE" => $dir.'/'. iconv( 'TIS-620', 'UTF-8',$file)
            // ];
            // db::db_update("M_LICENSE_WAREHOUSE", $field_update, $cond);
            //     }
            // }
        }
        closedir($dh);
    }
}
?>