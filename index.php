<?php
session_start();
$start = microtime(TRUE);

include_once 'lib/functions.php';

if ($_POST){
  $province = $_POST['province_name'];
  $municipality = $_POST['municipality_psgc'];
  $province_header = "Province";
  $municipality_header = "Municipality PSGC";
  $barangay_header = "Barangay PSGC";
  header("Content-type: text/csv");
  header("Content-disposition: attachment; filename = ".$province."_".$municipality."_Consolidated_Data.csv");
  $fp = fopen('php://output', 'w');
  $handle = fopen($_FILES["file"]["tmp_name"], "r");  
  $hhbarcode = "";
  $row = 1;
  if (($handle) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if (count(array_filter($data)) !== 0){
        $num = count($data);
        if ($row == 1){
          $line = $data[0].", ".$data[1].", ".$data[2].", ".$data[3].", ".$data[4].", ".$data[5].
          ", ".$data[6].", ".$data[7].", ".$data[10].", ".$data[11].", ".$province_header
          .", ".$municipality_header.", ".$barangay_header;
        } else {
          // declare needed cleansing technique here
          $psgc = $municipality;
          if (firstCharacter($psgc) != 0){
            $psgc = "0".$psgc;
          }
          if (!empty($data[1])){
            $data[1] = trim(formatBarcodeNumber(trim($psgc),$data[1]));
            $hhbarcode = trim($data[1]);
          } else {
            $data[1] = trim(formatBarcodeNumber(trim($psgc),$hhbarcode));
          }          
          if (($data[23] != "-" && $data[23] != "") && empty(trim($data[7]))) {
            $data[7] = "07/01/1980";
          } else {
            $data[7] = createDate($data[7]);
          }
          $data[10] = findSector($data[7],$data[8],$data[10]);
          $data[11] = findKondisyonNgKalusugan($data[11]);
          $line = $data[0].", ".$data[1].", ".cleanName($data[2]).", ".cleanName($data[3]).", ".cleanName($data[4]).", ".cleanName($data[5]).
          ", ".$data[6].", ".$data[7].", ".$data[10].", ".$data[11].", ".$province
          .", ".$municipality.", ".$data[12];
        }          
        $row++;
        $line .=  "\n";
        fputs($fp, $line);
      }
    }
    fclose($handle);
    ob_flush();
    exit();
  }
  $end = microtime(TRUE);
}  
?>
<?php include_once 'view/form.html'; ?>