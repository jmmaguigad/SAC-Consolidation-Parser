<?php
require_once('Classes/PHPExcel.php');
 
function convertXLStoCSV($infile,$outfile){
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}

function eraseFiles($path){
  $files = glob($path.'*');  
  foreach($files as $file) {      
    if(is_file($file))
      unlink($file);  
  } 
}

$dir = "files/For_Conversion/";
$converted_dir = "files/Processed_Excel/";

$row = 0;

// Erase files before conversion
eraseFiles($converted_dir);

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
        if (!empty($file) && $row > 1) {
            $newfilename = str_replace(".csv","-output",$file);
            convertXLStoCSV($dir.$file,$converted_dir.$newfilename.'.csv');
            echo "Successfully converted $file to ".$newfilename.'.csv'.'<br/>';
        }
        $row++;
    }
    closedir($dh);
  }
}
