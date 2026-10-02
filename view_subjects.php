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

    $semester = $semester_err = $final_result = "";
    $c = 0;
	
	function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

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

	if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnShow", $_POST))
	{
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

        if($c == 0)
        {
        	$_SESSION["semester"] = $semester;
        	header("location:view_subjects_next.php");
        }

	} // end if BtnShow
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
    
</head>
<body style="background-image: url('images/pink.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
	<?php
		require("menus.php");
	?>
	<br />
	<center>
	<font face="calibri" size="5pt" color="#34495E"><u>View Subjects</u></font>	
	<br/>
	</center>

	<center>
		<table style="font-family: calibri;font-size: 17px;">
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
				print("<tr><td></td><td>$semester_err<br/></td></tr>");
            ?>
            <tr>
            	<td></td>
            	<td><input type="submit" name="BtnShow" value="Show Records"></td>
            </tr>
		</table>
	</center>
</form>
</body>
</html>