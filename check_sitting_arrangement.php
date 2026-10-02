<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $rollno = $c = $record_found = 0;
    $rollno_err = $final_Result = "";

    if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnShow", $_POST))
    {
    	if (empty(trim($_POST["TxtRollNo"])))
        {
            $rollno_err = "Roll number is required";                    
            $c++;
        }        
        else 
            $rollno = intval(strtoupper($_POST["TxtRollNo"]));

        if($c == 0)
        {

        }
    }  // end if BtnShow
?>
<html>
<head>
	<style>
		a
		{
			font-family: calibri;
			font-size: 17px;
		}
		a:link 
		{
			color: white;
		}
		a:visited 
		{
			color: white;
		}
		a:hover 
		{
			color: #ff3300;
		}
	</style>
	<style>
	.table_header
	{
		font-family: calibri;
		font-size: medium;
		background-color: crimson;
		color: white;
		height: 35px;
	}

	.myclass
	{
		font-family: calibri;
		font-size: medium;		
		font-weight: bold;
		color: black;
		height: 35px;
	}
	</style>	
	<link href="styles/login_style.css" rel="stylesheet"/>
</head>
<body style="background-image: url('images/pink.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
	<table cellpadding="5px" cellspacing="10px">
		<tr>
			<td><a href="start.php">Home</a></td>				
		</tr>
	</table>
	<br/>

	<center>
	<table style="font-family: calibri;font-size: 17px;">
		<tr>
			<td>Enter Roll Number</td>
			<td><input type="text" name="TxtRollNo" <?php if(empty($_POST['TxtRollNo'])===false) echo "value=".$_POST['TxtRollNo']; ?>></td>
		</tr>
		<?php
			if(strlen($rollno_err)!= 0)
			print("<tr><td></td><td>$rollno_err<br/></td></tr>");
        ?>
        <tr>
        	<td></td>
        	<td><input type="submit" name="BtnShow" value="Show"></td>
        </tr>
	</table>
	</center>
	<br/>
	<br/>
	<center>
		<div>
			<table style="font-family: calibri;font-size: 17px;border-collapse: collapse;width: 900px;" cellspacing="0" cellpadding="5" border="1" >				
			<?php
				if($c == 0)
				{
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
							$sql = "select student_name, semester, subject_name, hall_name_location, sitting_position, paper_date, paper_time from sitting_arrangement where rollno = ?";
							$ps = mysqli_prepare($con, $sql);
							if($ps != false)
							{
								$student_name = $semester = $subject_name = $hall_name = $sitting_position = $paper_date = $paper_time = "";
								mysqli_stmt_bind_param($ps, "i", $rollno);
								mysqli_stmt_bind_result($ps, $student_name, $semester, $subject_name, $hall_name, $sitting_position, $paper_date, $paper_time);
								mysqli_stmt_execute($ps);
								$i = 0;
								while (mysqli_stmt_fetch($ps)!= null) 
								{
									if ($i == 0)
									{
										print("<tr><td colspan='5' style='text-align: center;background-color: crimson;'><font face='calibri' size='5pt' color='white'>Sitting Arrangement</font></td></tr>");
										print("<tr><td class='myclass'>Roll Number</td><td colspan='4'>$rollno</td></tr>");
										print("<tr><td class='myclass'>Student Name</td><td colspan='4'>$student_name</td></tr>");
										print("<tr><td class='myclass'>Semester</td><td colspan='4'>$semester</td></tr>");
										$i++;
										$record_found++;
										print("<tr class='table_header'><td>Subject Name</td><td>Paper Date</td><td>Paper Time</td><td>Hall Name & Location</td><td>Sitting Position</td></tr>");
									}
									print("<tr>");
									print("<td>$subject_name</td>");
									print("<td>".date('d-m-Y',strtotime($paper_date))."</td>");
									print("<td>$paper_time</td>");
									print("<td>$hall_name</td>");
									print("<td>$sitting_position</td>");																
									print("</tr>");
								} // end while
								mysqli_stmt_close($ps);
								mysqli_close($con);	
								if($record_found == 0)				
								{
									print("<tr><td colspan='3' style='background-color:#FADBD8'><font face='calibri' size='5pt' color='#8E44AD'>No sitting arrangement found for this roll number</font></td></tr>");
								}
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
				}
			?>			
		</table>

		</div>
	</center>
</form>
</body>
</html>