<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $semester = $subject_name = $paper_date = $pater_time = "";
    $semester_err = $subject_name_err = "";
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


	if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnNext", $_POST))
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

        if (empty(trim($_POST["DdlSubject"])))
        {
            $subject_name_err = "Subject is required";                    
            $c++;
        }
        elseif (strcmp($_POST["DdlSubject"], "-1") == 0)    
        {
        	$subject_name_err = "Subject is required";                    
            $c++;
        }
        else 
            $subject_name = strtoupper($_POST["DdlSubject"]);

        if($c == 0)
        {
        	$_SESSION["semester"] = $semester;
        	$_SESSION["subject_name"] = $subject_name;

            $sql = "select paper_date, paper_time from subjects where semester=? and subject_name=? ";
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
                        mysqli_stmt_bind_param($ps,"ss", $semester, $subject_name); 
                        mysqli_stmt_bind_result($ps, $paper_date, $paper_time);
                        mysqli_stmt_execute($ps);
                        if(mysqli_stmt_fetch($ps)!= null)
                        {
                            $_SESSION["paper_date"] = $paper_date;
                            $_SESSION["paper_time"] = $paper_time;
                        }
                        mysqli_stmt_close($ps);
                        mysqli_close($con);
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

        	header("location:set_sitting_arrangement_next.php");
            }
        } // end if c == 0	
    }
?>
<html>
<head>	
   <meta charset='utf-8'>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="dd_menu/styles.css">
   <script src="dd_menu/jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
   <script src="jquery/jquery-3.1.1.min.js"></script> 
   <link href="styles/regular_style.css" rel="stylesheet"/> 

   <script>
            $(document).ready(function(){
            $('#DdlSemester').change(function(){
                //Selected value
                var inputValue = $(this).val();
                $.post('get_subjects.php', { dropdownValue: inputValue }, function(data){
                    $("#DdlSubject").empty();
                    $("#DdlSubject").append('<option value="-1">-- Select Subject --</option>');
                    $("#DdlSubject").append(data);
                });
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
	<font face="calibri" size="5pt" color="#34495E"><u>Set Sitting Arrangement</u></font>	
	<br/>
	</center>
	<center>
		<table style="font-family: calibri;font-size: 17px;">
			<tr>
				<td>Semester</td>
				<td>
					<select name="DdlSemester" id="DdlSemester">							
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
				<td>Subject</td>
				<td>
					<select name="DdlSubject" id="DdlSubject">							
						<option value="-1">-- Select Subject --</option>  
						<?php
							if(strlen($subject_name)!= 0)
							{
								print("<option value='$subject_name'>$subject_name</option>");
							}
						?>
					</select>
				</td>
			</tr>
			<?php
				if(strlen($subject_name_err)!= 0)
				print("<tr><td></td><td><span>$subject_name_err<br/></span></td></tr>");
            ?>
			<tr>
				<td></td>
				<td><input type="submit" name="BtnNext" value="Next"></td>
			</tr>
		</table>
	</center>
	</form>
</body>
</html>