<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $semester = $subject_code = $subject_name = $paper_date = $paper_time = "";
    $semester_err = $subject_code_err = $subject_name_err = "";
    $paper_date_err = $paper_time_err = "";
    $final_result = "";
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
     	$semester = "";
     	$subject_code = "";
     	$subject_name = "";
     	$semester_err = "";
     	$subject_code_err = "";
     	$subject_name_err = "";
     	$final_result = "";
        $paper_date = "";
        $paper_date_err = "";
        $paper_time = "";
        $paper_time_err = "";
     	$_POST = array();
     }
     else
     if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnAdd", $_POST))
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
            $semester = strtoupper($_POST["DdlSemester"]);

     	if (empty(trim($_POST["TxtSubjectCode"])))
        {
            $subject_code_err = "Subject Code is required";                    
            $c++;
        }        
        else 
            $subject_code = strtoupper($_POST["TxtSubjectCode"]);

     	if (empty(trim($_POST["TxtSubjectName"])))
        {
            $subject_name_err = "Subject Name is required";                    
            $c++;
        }        
        else 
            $subject_name = strtoupper($_POST["TxtSubjectName"]);

        if (empty(trim($_POST["TxtPaperDate"])))
        {
            $paper_date_err = "Paper date is required";                    
            $c++;
        }        
        else 
            $paper_date = strtoupper($_POST["TxtPaperDate"]);

        if (empty(trim($_POST["TxtPaperTime"])))
        {
            $paper_time_err = "Paper time is required";                    
            $c++;
        }        
        else 
            $paper_time = strtoupper($_POST["TxtPaperTime"]);

        if($c == 0)
        {
        	$sql = "insert into subjects values(?,?,?,?,?)";
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
                    	mysqli_stmt_bind_param($ps,"sssss", $semester, $subject_code, $subject_name, $paper_date, $paper_time); 
						mysqli_stmt_execute($ps);
                        $n = mysqli_stmt_affected_rows($ps);
                        mysqli_stmt_close($ps);
                        mysqli_close($con);
                        if($n == 1)                        
                        $final_result = "<font face=calibri size=3pt color=green>Subject added</font><br/>";                        
                        else
                        $final_result = "<font face=calibri size=3pt color=red>Subject not added</font><br/>";
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
			} // end if con == false
        }

     } // end if BtnAdd
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
   <script src="jquery_datetime_picker/jquery-1.12.4.js"></script>
    <link href="jquery_datetime_picker/jquery-ui.css" rel="stylesheet" />
    <script src="jquery_datetime_picker/jquery-ui.js"></script>
    <script>
        $(function () {
            $("[id$=TxtPaperDate]").datepicker({
                //showOn: 'button',
                //buttonImageOnly: true,
                //buttonImage: "images/cal.png",
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '2019:2025'
            });
        });
    </script>
</head>
<body style="background-image: url('images/pink.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
	<?php
		require("menus.php");
	?>
	<br />	
	<center>
	<font face="calibri" size="5pt" color="#34495E"><u>Add Subjets</u></font>	
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
				print("<tr><td></td><td><span>$semester_err<br/></span></td></tr>");
            ?>
			<tr>
				<td>Subject Code</td>
				<td><input type="text" name="TxtSubjectCode" <?php if(empty($_POST['TxtSubjectCode'])===false) echo "value='".$_POST['TxtSubjectCode']."'"; ?>></td>
			</tr>
			<?php
				if(strlen($subject_code_err)!= 0)
				print("<tr><td></td><td>$subject_code_err<br/></td></tr>");
            ?>
			<tr>
				<td>Subject Name</td>
				<td><input type="text" name="TxtSubjectName" <?php if(empty($_POST['TxtSubjectName'])===false) echo "value='".$_POST['TxtSubjectName']."'"; ?>></td>
			</tr>
			<?php
				if(strlen($subject_name_err)!= 0)
				print("<tr><td></td><td>$subject_name_err<br/></td></tr>");
            ?>

            <tr>
                <td>Paper Date</td>
                <td><input type="text" name="TxtPaperDate" id="TxtPaperDate" <?php if(empty($_POST['TxtPaperDate'])===false) echo "value='".$_POST['TxtPaperDate']."'"; ?>></td>
                </tr>
                <?php
                    if(strlen($paper_date_err)!= 0)
                    print("<tr><td></td><td><span>$paper_date_err<br/></span></td></tr>");
                ?>

            <tr>
                <td>Paper Time</td>
                <td><input type="text" name="TxtPaperTime" <?php if(empty($_POST['TxtPaperTime'])===false) echo "value='".$_POST['TxtPaperTime']."'"; ?>></td>
            </tr>
            <?php
                if(strlen($paper_time_err)!= 0)
                print("<tr><td></td><td>$paper_time_err<br/></td></tr>");
            ?>

            <tr>
            	<td></td>
				<td>
					<table>
					<tr>
						<td><input type="submit" name="BtnAdd" value="Add"></td>
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