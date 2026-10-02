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

	<center>
	<font face="calibri" size="5pt" color="#34495E"><u>Subject List<?php if(!empty($semester)) echo " : $semester";?></u></font>
	<br/>
	</center>

	<?php
		if(array_key_exists("semester", $_SESSION))
		{
			$semester = $_SESSION["semester"];
		}
	?>

	<center>
	<table style="font-family: calibri;font-size: 17px;border-collapse: collapse;width: 900px;" cellspacing="0" cellpadding="5" border="1" >				
		<tr class="table_header">
			<td>Semester</td>				
			<td>Subject Code</td>				
			<td>Subject Name</td>
			<td>Paper Date</td>				
			<td>Paper Time</td>				
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
					$sql = "select * from subjects where semester = ?";
					$ps = mysqli_prepare($con, $sql);
					if($ps != false)
					{
						$sem = $subject_code = $subject_name = $paper_date = $paper_time = "";
						mysqli_stmt_bind_param($ps, "s", $semester);
						mysqli_stmt_bind_result($ps, $sem, $subject_code, $subject_name, $paper_date, $paper_time);
						mysqli_stmt_execute($ps);
						while (mysqli_stmt_fetch($ps)!= null) 
						{
							print("<tr>");
							print("<td>$sem</td>");
							print("<td>$subject_code</td>");
							print("<td>$subject_name</td>");
							print("<td>$paper_date</td>");								
							print("<td>$paper_time</td>");								
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
	</center>
</body>
</html>