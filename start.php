<?php
	$server_name = "localhost"; $db_user = "root"; 
    $db_password = ""; $database = "halldb";

    $username = $password = "";
    $username_err = $password_err = $final_result = "";
    $c = 0;
    session_start();
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && array_key_exists("BtnLogin", $_POST)) 
    {
    	if (empty(trim($_POST["TxtUsername"])))
        {
            $username_err = "Username is required";                    
            $c++;
        }        
        else 
            $username = test_input($_POST["TxtUsername"]);

        if (empty(trim($_POST["TxtPassword"])))
        {
            $password_err = "Password is required";                    
            $c++;
        }        
        else 
            $password = test_input($_POST["TxtPassword"]);

        if($c == 0)
        {
        	if(strcmp($username, "admin") == 0 && strcmp($password, "super")==0)
        	{
        		$_SESSION["username"] = $username;
        		header("location:admin_home.php");
        	}
        	else
        		$final_result = "Invalid Username/Password";
        }

    } // end if btnregister
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
	<link href="styles/login_style.css" rel="stylesheet"/>
</head>
	<body style="background-image: url('images/exam.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" name="f1">        
		<table cellpadding="5px" cellspacing="10px">
			<tr>				
				<td><a href="check_sitting_arrangement.php">Check Seating Arrangement</a></td>
			</tr>
		</table>
		<br/>

		<center>
			<div style="background-image: url('images/back2.png');background-repeat: no-repeat;background-position: center;width: 500px;height: 250px;border:2px solid white;">
				<table style="font-family: calibri;font-size: 17px;color: white;">
					<tr>
						<td colspan="2">
							<font face="calibri" size="5pt" color="white">
								Admin Login
							</font>
						</td>						
					</tr>
					<tr>
						<td>Username</td>
						<td><input type="text" name="TxtUsername" <?php if(empty($_POST['TxtUsername'])===false) echo "value=".$_POST['TxtUsername']; ?>></td>
					</tr>
					<?php
						if(strlen($username_err)!= 0)
						print("<tr><td></td><td><span>$username_err<br/></span></td></tr>");
            		?>			
					<tr>
						<td>Password</td>
						<td><input type="password" name="TxtPassword" <?php if(empty($_POST['TxtPassword'])===false) echo "value=".$_POST['TxtPassword']; ?>></td>
					</tr>
					<?php
						if(strlen($password_err)!= 0)
						print("<tr><td></td><td><span>$password_err<br/></span></td></tr>");
            		?>			
					<tr>
						<td></td>
						<td><input type="submit" name="BtnLogin" value="LogIn"></td>
					</tr>
					<?php
						if(strlen($final_result)!= 0)
						print("<tr><td></td><td><span>$final_result<br/></span></td></tr>");
            		?>	
				</table>
			</div>
		</center>
	</form>
	</body>
</html>