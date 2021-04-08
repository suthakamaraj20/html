<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
class useradd
{
	private $err;
    public function get_data_from_post($param)
	{
        return isset($_POST[$param]) ? trim($_POST[$param]) : "";              
    }
	public function insert($empno,$empname) 
	{
		$database=new PDO("mysql:host=localhost;dbname=SSS","root","rootvm@kms");
        $sql="INSERT INTO employee (empno,empname) VALUES (:empno,:empname)";
		$stmt=$database->prepare($sql);
		$stmt->bindParam(":empno", $empno, PDO::PARAM_STR);
		$stmt->bindParam(":empname", $empname, PDO::PARAM_STR);
		try
		{           
            $stmt->execute();
            return $database->lastInsertId();
			
        } 
		catch (PDOException $e)
		{
             echo "sql = ".$sql."<br/>".$e->getMessage();
            return 0;
        }
		
	}
	public function valid_data($empno){
    //  echo "Regno".$regno;
    $database=new PDO("mysql:host=localhost;dbname=SSS","root","rootvm@kms");
    $sql = "SELECT ID FROM employee WHERE empno = :empno";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(":empno", $empno, PDO::PARAM_STR);
    try
	{           
		$stmt->execute();
        if($stmt->rowCount() == 1)
		{
              return true;
        }
		else
		{
             return  false;
        }
    } 
	catch (PDOException $e)
	{
             echo "sql = ".$sql."<br/>".$e->getMessage();
            // frame_session_log("SQL",$e->getMessage());
            return 0;
    }
}       	
	
	
}
$obj=new useradd();
$empno= $obj->get_data_from_post("empno");
$empname= $obj->get_data_from_post("empname");
//echo $empno."\t".$empname;
if($empno!="" && $empname!="")
{
	if($obj->valid_data($empno) ==true)
	{
		?>
		<script>
		setTimeout(function() {
               swal({
              title: "Error",
              text: "Entrollment Number Is Already Exist",
              type: "warning"
              });
            }, 100);
	</script>
		<?php
	}
	else
	{
	$i= $obj->insert($empno,$empname);
	if($i!=0)
	{
	?>
	<script>
	setTimeout(function() {
               swal({
              title: "Success!",
              text: "Inserted Successfully",
              type: "success"
              });
            }, 100);
	</script>
	
	<?php
	} 
	else 
	{
	?>
	<script>
	setTimeout(function() {
               swal({
              title: "ERROR!",
              text: "Oops ! Something Went Wrong",
              type: "error"
              });
            }, 100);
	</script>
	
	<?php
	
	}
	}
}
?>
<html>
<head>
<link rel="stylesheet" href="bootstrap.css">
<link href='sweetalert/sweetalert.css' type='text/css' rel='stylesheet'>
  <script src='sweetalert/jquery.min.js' type='text/javascript'></script>
  <script src='sweetalert/sweetalert.min.js' type='text/javascript'></script>
<style>
.wrapper{ width: 350px; padding: 10px;text-align:left;}

</style>
</head>
<body>
<center>
<h2>Registration Form </h2>
<div class="wrapper">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" name="myForm" onsubmit="return validateForm()" method="post" id="contact-form">
            <span style="color:red;font-size:10px;" id="err_ename"></span>
			<div class="form-group">
                <label>ICNO</label><br/>
				<input type="text" name="empno" class="form-control" autofocus>
			<!--	<span style="color:red;font-size:10px;" id="err_empno"></span>   -->
                
            </div>    
            <div class="form-group">
                <label>Employee Name</label>
                <input type="text" name="empname" autocomplete="off"  class="form-control">
				
                
            </div>
			<div class="form-group">
                <input type="submit" style="background-color:green;" class="btn" value="Save & Next"><a class="btn" style="background-color:red;" href="">Cancel</a>
            </div>
</div>
</center>
</form>
<script>
function validateForm() 
{
  var empno=document.forms["myForm"]["empno"].value;
  if (empno == "") 
  {
    swal('Error','Please Enter the ICNO','error');
    document.getElementById("err_ename").innerHTML="Please Enter the ICNO ";
    return false;
  }
  var empname=document.forms["myForm"]["empname"].value;
  if (empname == "") 
  {
    swal('Error','Please Enter the Employee Name','error');
    document.getElementById("err_ename").innerHTML="Please Enter the Employee Name";
    return false;
  }
}
</script>

</body>
</html>