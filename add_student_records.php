<?php
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

	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $rollno = $student_name = $semester = $mobno = "";
    $rollno_err = $student_name_err = $semester_err = $mobno_err = "";
    $final_result = "";
    $c = 0;

    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function validate_roll_number($phone)
    {       
        $filtered_roll_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);        
        $rollno_to_check = str_replace("-", "", $filtered_roll_number);

        // Check the lenght of number        
        if (strlen($rollno_to_check) > 0 && strlen($rollno_to_check) <= 5) 
        return(true);
        else
        return(false);
     }

    function validate_contact_number($phone)
    {
        // Allow +, - and . in phone number
        $filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);

        // Remove "-" from number
        $phone_to_check = str_replace("-", "", $filtered_phone_number);

        // Check the lenght of number        
        if (strlen($phone_to_check) < 10 || strlen($phone_to_check) > 13) 
        return(false);
        else
        return(true);
     }

     if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnClear", $_POST)) 
     {
     	$rollno = "";
     	$student_name = "";
     	$semester = "";
     	$mobno = "";
     	$rollno_err = "";
     	$student_name_err = "";
     	$semester_err = "";
     	$mobno_err = "";
     	$final_result = "";
     	$_POST = array();
     }
     else
     if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnSave", $_POST)) 
     {
     	if (empty(trim($_POST["TxtRollNo"])))
        {                     
            $rollno_err  = "Roll number is required";                    
            $c++;
        }                    
        elseif(!validate_roll_number(trim($_POST["TxtRollNo"])))
        {
            $rollno_err  = "Invalid roll number";                    
            $c++;            
        }       
        else		
            $rollno = intval(test_input(trim($_POST["TxtRollNo"])));              

     	if (empty(trim($_POST["TxtStudentName"])))
        {
            $student_name_err = "Student Name is required";                    
            $c++;
        }
        elseif(!preg_match("/^[a-zA-Z ]*$/", trim($_POST["TxtStudentName"])))
        {
        	$student_name_err = "Student Name must contain alphabets only";                    
            $c++;
        }
        else 
            $student_name = strtoupper(test_input($_POST["TxtStudentName"]));

        if (empty(trim($_POST["DdlSemester"])))
        {
            $semester_err = "Semester is required";                    
            $c++;
        }
        elseif (strcmp($_POST["DdlSemester"], "-1") == 0)    
        {
        	$semester_err = "Semester is required";                    
            $c++;
        }
        else 
            $semester = strtoupper(test_input($_POST["DdlSemester"]));

        if (empty(trim($_POST["TxtMobNo"])))
        {                     
            $mobno_err  = "Mobile number is required";                    
            $c++;
        }                    
        elseif(!validate_contact_number(trim($_POST["TxtMobNo"])))
        {
            $mobno_err  = "Invalid mobile number";                    
            $c++;            
        }       
        else        
            $mobno = test_input(trim($_POST["TxtMobNo"]));

        if($c == 0)
        {
        	$sql = "insert into students values(?,?,?,?)";
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
                    	mysqli_stmt_bind_param($ps,"isss", $rollno, $student_name, $semester, $mobno); 
						mysqli_stmt_execute($ps);
                        $n = mysqli_stmt_affected_rows($ps);
                        mysqli_stmt_close($ps);
                        mysqli_close($con);
                        if($n == 1)                        
                        $final_result = "<font face=calibri size=3pt color=green>Record saved</font><br/>";                        
                        else
                        $final_result = "<font face=calibri size=3pt color=red>Record not saved</font><br/>";
                    }
                    else
                    {
                        print("Prepared statement not created..<br/>");                
                        mysqli_close($con);
                    }
                }
                catch(Exception $ex)
                {
                    print($ex->getmessage()."<br/>");
                    mysqli_close($con);
                }
			}// end if $con == false
        }

     }// end if BtnSave
?>
<html>
<head>	
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="dd_menu/styles.css">
   <script src="dd_menu/jquery-latest.min.js" type="text/javascript"></script>   
   <link href="styles/regular_style.css" rel="stylesheet"/>    
</head>
<body style="background-image: url('images/pink.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
	<?php
		require("menus.php");
	?>
	<br />
	<center>
	<font face="calibri" size="5pt" color="#34495E"><u>Add Student Records</u></font>	
	<br/>
	</center>

	<center>
		<table style="font-family: calibri;font-size: 17px;">
			<tr>
				<td>Roll Number</td>
				<td><input type="text" name="TxtRollNo" <?php if(empty($_POST['TxtRollNo'])===false) echo "value=".$_POST['TxtRollNo']; ?>></td>
			</tr>
			<?php
				if(strlen($rollno_err)!= 0)
				print("<tr><td></td><td>$rollno_err<br/></td></tr>");
            ?>			
			<tr>
				<td>Student Name</td>
				<td><input type="text" name="TxtStudentName" <?php if(empty($_POST['TxtStudentName'])===false) echo "value='".$_POST['TxtStudentName']."'"; ?>></td>
			</tr>
			<?php
				if(strlen($student_name_err)!= 0)
				print("<tr><td></td><td>$student_name_err<br/></td></tr>");
            ?>
			<tr>
				<td>Semester</td>
				<td>
					<select name="DdlSemester">							
							<option value="-1">-- Select Semester --</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-I') { ?>selected="true" <?php }; ?>value="SEMESTER-I">SEMESTER-I</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-II') { ?>selected="true" <?php }; ?>value="SEMESTER-II">SEMESTER-II</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-III') { ?>selected="true" <?php }; ?>value="SEMESTER-III">SEMESTER-III</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-IV') { ?>selected="true" <?php }; ?>value="SEMESTER-IV">SEMESTER-IV</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-V') { ?>selected="true" <?php }; ?>value="SEMESTER-V">SEMESTER-V</option>
                        	<option <?php if(empty($_POST['DdlSemester'])===false) if($_POST['DdlSemester'] == 'SEMESTER-VI') { ?>selected="true" <?php }; ?>value="SEMESTER-VI">SEMESTER-VI</option>                        	
						</select>
				</td>
			</tr>
			<?php
				if(strlen($semester_err)!= 0)
				print("<tr><td></td><td><span>$semester_err<br/></span></td></tr>");
            ?>
			<tr>
				<td>Mobile Number</td>
				<td><input type="text" name="TxtMobNo" <?php if(empty($_POST['TxtMobNo'])===false) echo "value='".$_POST['TxtMobNo']."'"; ?>></td>
			</tr>
			<?php
				if(strlen($mobno_err)!= 0)
				print("<tr><td></td><td><span>$mobno_err<br/></span></td></tr>");
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
				if(strlen($final_result)!= 0)
				print("<tr><td></td><td><span>$final_result<br/></span></td></tr>");
            ?>
		</table>
	</center>
</form>
</body>
</html>