<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
try { 
		$database=new PDO("mysql:host=localhost;dbname=SSS","root","rootvm@kms");
		require_once("excel/PHPExcel.php");
		$path="stu.xlsx";
		$excelread = PHPExcel_IOFactory::CreateReaderForFile($path);
		$loadfile=$excelread->load($path);
		$totalsheetcount=$loadfile->getSheetCount();
		$sheetname=$loadfile->getSheetNames();
		$arraydata =[];
	    $setactive=$loadfile->setActiveSheetIndex();
	    $hrow=$setactive->getHighestRow();
		$hcolumn=$setactive->getHighestColumn();
		$hcolumnindex=PHPExcel_cell::ColumnIndexFromString($hcolumn);
	    function userExists($database, $empno)
		{
		    $userQuery = "SELECT * FROM employee  WHERE empno=:empno;";
			$stmt = $database->prepare($userQuery);
			$stmt->execute(array(':empno' => $empno));
			return !!$stmt->fetch(PDO::FETCH_ASSOC);
		}   
		function insert_data($empno,$empname) {
			$database=new PDO("mysql:host=localhost;dbname=SSS","root","rootvm@kms");
			$sql="INSERT INTO employee (empno,empname) VALUES (:empno,:empname)";
			$stmt=$database->prepare($sql);
			$stmt->bindParam(':empno',$empno);
			$stmt->bindParam(':empname',$empname);
			if($stmt->execute()) {
				return 0;
			}else {
				return 1;
			}
		} 
		echo "<table style='border: solid 1px black;'>";
		echo "<tr><th>Empno</th><th>Empname</th></tr>";
		for($row=2;$row<$hrow;$row++) 
		   {
				$empno=$setactive->getCellByColumnAndRow(0,$row)->getValue();
				$empname=$setactive->getCellByColumnAndRow(1,$row)->getValue(); 
				echo "<tr><td>".$empno."</td><td>".$empname."</td></tr>";
				$exists=userExists($database, $empno);
				if($exists==True){
				echo "user Already Exists  :".$empno;
			    }else {
				    $check_ins=insert_data($empno,$empname);
					if($check_ins===0){
					echo "INSERTED SUCCESSFULLY";
					} else {
					echo "Error";
					}
				}					
		    }
				 echo "</table>";
			   
}
catch(PDOException $e) 
{
  echo "Error :" . $e->getMessage();
}

?>