<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    if(array_key_exists("dropdownValue", $_POST))
    {
    	$semester = $_POST["dropdownValue"];
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
					$sql = "select subject_name from subjects where semester = ?";
					$ps = mysqli_prepare($con, $sql);
					if($ps != false)
					{
						$subject_name = "";
						mysqli_stmt_bind_param($ps, "s", $semester);
						mysqli_stmt_bind_result($ps, $subject_name);
						mysqli_stmt_execute($ps);
						while (mysqli_stmt_fetch($ps)!= null) 
						{						

							print("<option>$subject_name</option>");							
							/*
							print("<option <?php if(empty($_POST['DdlSubject'])===false) if($_POST['DdlSubject'] == $subject_name) { ?>selected=true <?php }; ?>value=$subject_name>$subject_name</option>")
							*/
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
    }

	if(strlen($final_result)!= 0)
	print("<tr><td colspan=4>$final_result</td></tr>");
?>