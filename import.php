<?php
include "../Connections/connect_mysql.php";
$con=mysqli_connect("localhost","root","yong30323","edp_cnpromotion_test");
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
//echo "/// ".$_GET['docuno2']." ///";




  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
//echo $allowedFileType ;
  if(in_array($_FILES["fileimport"]["type"],$allowedFileType)){

        $targetPath = 'uploads/'.$_FILES['fileimport']['name'];
        move_uploaded_file($_FILES['fileimport']['tmp_name'], $targetPath);

        $Reader = new SpreadsheetReader($targetPath);

        $sheetCount = count($Reader->sheets());
        for($i=0;$i<$sheetCount;$i++)
        {

            $Reader->ChangeSheet($i);

            foreach ($Reader as $Row)
            {

                $goodcode = "";
                if(isset($Row[0])) {
                    $goodcode = mysqli_real_escape_string($con,$Row[0]);
                }



                if ( !empty($goodcode)) {
                    $query = "insert into imgoodcode(docuno,goodcode) values('".$_GET['docuno2']."','".$goodcode."')";
                    $result = mysqli_query($con,$query);


                    if (! empty($result)) {
                        $type = "success";
                        //$message = "Excel Data Imported into the Database";
                    } else {
                        $type = "error";
                        //$message = "Problem in Importing Excel Data";
                    }
                }
             }

         }
  }
  else
  {
        $type = "error";
        //$message = "Invalid File Type. Upload Excel File.";
  }
  echo $type;

?>
