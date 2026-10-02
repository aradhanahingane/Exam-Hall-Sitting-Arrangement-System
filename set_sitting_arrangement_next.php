<?php
	
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $semester = $subject_name = "";
    $rollno = 0;
    $rollno_err = "";    
    $student_name = $hall_name = $sitting_position = $paper_date = $paper_time = "";
    $student_name_err = $hall_name_err = $sitting_position_err = "";
    $final_result = $result = "";    
    $c = 0;

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

	if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnClear", $_POST)) 
    {     	
     	$rollno = 0;
     	$student_name = "";
     	$hall_name = "";
     	$sitting_position = "";
     	$rollno_err = "";
     	$student_name_err = "";
     	$hall_name_err = "";
     	$sitting_position_err = "";     	     
     	$final_result = "";
     	$result = "";
     	$_POST = array();
    }
    else
    if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnSave", $_POST))
    {
    	if (empty(trim($_POST["TxtRollNo"])))
        {
            $rollno_err = "Roll number is required";                    
            $c++;
        }        
        else 
            $rollno = strtoupper($_POST["TxtRollNo"]);

        if (empty(trim($_POST["TxtStudentName"])))
        {
            $student_name_err = "Student name is required";                    
            $c++;
        }        
        else 
            $student_name = strtoupper($_POST["TxtStudentName"]);

        if (empty(trim($_POST["TxtHallName"])))
        {
            $hall_name_err = "Hall name and location is required";                    
            $c++;
        }        
        else 
            $hall_name = strtoupper($_POST["TxtHallName"]);

        if (empty(trim($_POST["TxtSittingPosition"])))
        {
            $sitting_position_err = "Sitting position is required";                    
            $c++;
        }        
        else 
            $sitting_position = strtoupper($_POST["TxtSittingPosition"]);
		
		if($c == 0 && array_key_exists("semester", $_SESSION) && array_key_exists("subject_name", $_SESSION))        
		{
			$semester = $_SESSION["semester"];
			$subject_name = $_SESSION["subject_name"];		
			$paper_date = $_SESSION["paper_date"];
			$paper_time = $_SESSION["paper_time"];	
			$sql = "insert into sitting_arrangement values(?,?,?,?,?,?,?,?)";
        	$con = mysqli_connect($server_name, $db_user, $db_password, $database);
			if($con ==  false)
			print("Error: ".mysqli_connect_error());
			else
			{
				try
                {
                	$ps = mysqli_prepare($con, $sql);
                	if($ps != false)
                    {
                    	mysqli_stmt_bind_param($ps,"isssssss", $rollno, $student_name, $semester, $subject_name, $hall_name, $sitting_position, $paper_date, $paper_time); 
						mysqli_stmt_execute($ps);
                        $n = mysqli_stmt_affected_rows($ps);
                        mysqli_stmt_close($ps);
                        mysqli_close($con);
                        if($n == 1)                        
                        $result = "<font face=calibri size=3pt color=green>Sitting position saved</font><br/>";                        
                        else
                        $result = "<font face=calibri size=3pt color=red>Sitting position not saved</font><br/>";
                    }
                    else
                    {
                        $result = "Prepared statement not created..<br/>";                
                        mysqli_close($con);
                    }
                }
                catch(Exception $ex)
                {
                    print($ex->getmessage()."<br/>");
                    mysqli_close($con);
                }
			} // end if $con == false
		} 
		else
		{
			$result = "semester and subject_name not in session<br/>";
		} // end if $c = 0
    }// end if BtnSave

?>
<html>
<head>	
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="dd_menu/styles.css">
   <script src="dd_menu/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script> 
   <link href="styles/regular_style.css" rel="stylesheet"/> 
   <script>
   		function break_str()
   		{
   			var str = document.f1.DdlStudents.value;
   			//alert(str);
   			var n = str.indexOf(" ");
   			var rn = str.substring(0, n);
   			var nm = str.substring(n+1);
   			document.f1.TxtRollNo.value = rn;
   			document.f1.TxtStudentName.value = nm;
   		}
   </script>
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
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
	<?php
		require("menus.php");
	?>
	<br />
	<?php
		if(array_key_exists("semester", $_SESSION) && array_key_exists("subject_name", $_SESSION))
		{
			print("<center><font face='calibri' size='5pt' color='red'>Semester: ". $_SESSION["semester"] ." &#38; Subject: ". $_SESSION["subject_name"]."</font></center><br/>");
			$semester = $_SESSION["semester"];
			$subject_name = $_SESSION["subject_name"];		
		} 
		else
		print("semester and subject_name not in session<br/>");
	?>
	
	<div style="position: absolute;left:10px;top:200px; width:360px;height: 500px;">
	<table style="font-family: calibri;font-size: 17px;">
		<tr>
			<td><font face="calibri" size="3pt" color="blue">Student List</font></td>
		</tr>
		<tr>
			<td>
				<select name="DdlStudents" size="20" onclick="break_str()">
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
							$sql = "select rollno, student_name from students where semester = ?";
							$ps = mysqli_prepare($con, $sql);
							if($ps != false)
							{
								$rollno = 0;
								$student_name = "";
								mysqli_stmt_bind_param($ps, "s", $semester);
								mysqli_stmt_bind_result($ps, $rollno, $student_name);
								mysqli_stmt_execute($ps);
								while (mysqli_stmt_fetch($ps)!= null) 
								{						
									print("<option>$rollno $student_name</option>");														
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
				</select>
			</td>
		</tr>
	</table>
	</div>

	<div style="position: absolute;left:370px;top:200px; width:900px;height: 250px;background-color: #FAE5D3">
		<br/>				
		<center>
			<font face="calibri" size="5pt" color="#34495E"><u>Sitting Arrangement</u></font>	
			<br/>
			<table style="font-family: calibri;font-size: 17px;">
				<tr>
					<td>Roll Number</td>
					<td><input type="text" readonly name="TxtRollNo" <?php if(empty($_POST['TxtRollNo'])===false) echo "value=".$_POST['TxtRollNo']; ?>></td>
				</tr>
				<?php
					if(strlen($rollno_err)!= 0)
					print("<tr><td></td><td><span>$rollno_err<br/></span></td></tr>");
            	?>
				<tr>
					<td>Student Name</td>
					<td><input type="text" readonly name="TxtStudentName" <?php if(empty($_POST['TxtStudentName'])===false) echo "value='".$_POST['TxtStudentName']."'"; ?>></td>
				</tr>
				<?php
					if(strlen($student_name_err)!= 0)
					print("<tr><td></td><td><span>$student_name_err<br/></span></td></tr>");
            	?>
				<tr>
					<td>Exam Hall Name & Location</td>
					<td><input type="text" name="TxtHallName" <?php if(empty($_POST['TxtHallName'])===false) echo "value='".$_POST['TxtHallName']."'"; ?>></td>
				</tr>
				<?php
					if(strlen($hall_name_err)!= 0)
					print("<tr><td></td><td><span>$hall_name_err<br/></span></td></tr>");
            	?>
				<tr>
					<td>Sitting Position</td>
					<td><input type="text" name="TxtSittingPosition" <?php if(empty($_POST['TxtSittingPosition'])===false) echo "value='".$_POST['TxtSittingPosition']."'"; ?>></td>
				</tr>
				<?php
					if(strlen($sitting_position_err)!= 0)
					print("<tr><td></td><td><span>$sitting_position_err<br/></span></td></tr>");
            	?>
				<tr>
					<td></td>
					<td>
						<table>
						<tr>
							<td><input type="submit" name="BtnSave" value="Save"></td>
							<td><input type="submit" name="BtnClear" value="Clear"></td>
						</tr>						
						</table>
					</td>
				</tr>
				<?php
					if(strlen($result)!= 0)
					print("<tr><td colspan=2>$result</td></tr>");
    			?>
			</table>
		</center>
	</div>	
	<div style="position: absolute;left:370px;top:470px; width:900px;height: auto;background-color: #FAE5D3">
		<br/>
		<center>
			<font face="calibri" size="5pt" color="#34495E"><u>View Sitting Arrangement</u></font>	
			<br/>			
			<table style="width:100%;border: 1px solid black;text-align: :center;font-family:Calibri;font-size: medium;" cellspacing="0" cellpadding="5">
				<tr class="table_header">
					<td>Roll Number</td>
					<td>Student Name</td>
					<td>Semester</td>
					<td>Subject Name</td>
					<td>Hall Name and Location</td>
					<td>Sitting Position</td>
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
					$sql = "select * from sitting_arrangement where semester=? and subject_name=?";
					$ps = mysqli_prepare($con, $sql);
					if($ps != false)
					{
						$rno = $sname = $sem = $sub = $hall = $sit_pos = $pd = $pt = "";
						mysqli_stmt_bind_param($ps, "ss", $semester, $subject_name);
						mysqli_stmt_bind_result($ps, $rno, $sname, $sem, $sub, $hall, $sit_pos, $pd, $pt);
						mysqli_stmt_execute($ps);
						while (mysqli_stmt_fetch($ps)!= null) 
						{
							print("<tr>");
							print("<td>$rno</td>");
							print("<td>$sname</td>");
							print("<td>$sem</td>");
							print("<td>$sub</td>");
							print("<td>$hall</td>");
							print("<td>$sit_pos</td>");							
							print("<td>".date('d-m-Y',strtotime($pd))."</td>");
							print("<td>$pt</td>");
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
		</table>
		</center>
	</div>
</form>
</body>
</html>