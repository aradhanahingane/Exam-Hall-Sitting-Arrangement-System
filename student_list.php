<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

	session_start();
	if(!array_key_exists("username", $_SESSION))
	{
		clearstatcache();		
		header("location:pagenotfound.php");
	} 
	elseif(empty($_SESSION["username"]))
	{
		clearstatcache();
		header("location:pagenotfound.php");
	}

	$semester = "";
?>
<html>
<head>	
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="dd_menu/styles.css">
   <script src="dd_menu/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>  

   <style>
	.table_header
	{
		font-family: calibri;
		font-size: medium;
		background-color: crimson;
		color: white;
		height: 35px;
	}
	</style> 
</head>
<body style="background-image: url('images/pink.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
	<?php
		require("menus.php");
	?>
	<br />

	<?php
		if(array_key_exists("semester", $_SESSION))
		{
			$semester = $_SESSION["semester"];
		}
	?>

	<center>
	<font face="calibri" size="5pt" color="#34495E"><u>Student Records<?php if(!empty($semester)) echo " : $semester";?></u></font>
	<br/>
	</center>

	<table style="width:100%;border: 1px solid black;text-align:center;font-family:Calibri;font-size: medium;" cellspacing="0" cellpadding="5" >
		<tr class="table_header">
			<td>Roll Number</td>				
			<td>Student Name</td>				
			<td>Semester</td>
			<td>Mobile Number</td>				
		</tr>
		<?php
			try
			{
				$con = mysqli_connect($server_name, $db_user, $db_password, $database);
				if($con == false)
				{
					$final_result = mysqli_connect_error()."<br/>";
					print($final_result);
				}
				else
				{
					$final_result="";
					$sql = "select * from students where semester = ?";
					$ps = mysqli_prepare($con, $sql);
					if($ps != false)
					{
						$rollno = $student_name = $sem = $mobno = "";
						mysqli_stmt_bind_param($ps, "s", $semester);
						mysqli_stmt_bind_result($ps, $rollno, $student_name, $sem, $mobno);
						mysqli_stmt_execute($ps);
						while (mysqli_stmt_fetch($ps)!= null) 
						{
							print("<tr>");
							print("<td>$rollno</td>");
							print("<td>$student_name</td>");
							print("<td>$sem</td>");
							print("<td>$mobno</td>");								
							print("</tr>");
						} // end while
						mysqli_stmt_close($ps);
						mysqli_close($con);					
					}
					else
					{
						mysqli_close($con);
						$final_result = "Prepared statement is not created<br/>";
						print($final_result);
					}
				} // end if $con = false
			}
			catch(Exception $ex)
			{
				print($ex->get_message()."<br/>");
			}
		?>
		<?php
				if(strlen($final_result)!= 0)
				print("<tr><td colspan=4>$final_result</td></tr>");
        ?>	
	</table>
</body>
</html>