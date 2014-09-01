<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Becker Garage Door Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

  </head>

  <body class="body-garage">

	<?php
	session_start();

	//todo: setup variables
	
	$doorState = getDoorState();
    $indoorTemp = getIndoorTemp();
    $outdoorTemp = getOutdoorTemp();
    
	if ($_SESSION["auth"] != "true")
	{
		header("Location:index.php");
	}


	if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
	{	
		//postback
		if (isset($_POST['toggle'])) 
		{
			exec("gpio write 1 0; sleep 1; gpio write 1 1; sleep 1;", $toggle_output, $toggle_return_var);
			$doorState = getDoorState();
		}  
		
	}
	else
	{
		//initial load, not a post back
	} 


	function getDoorState() {
		exec("gpio read 0", $output, $return_var);
		if (trim(implode(" ",$output)) == "0")
		{
		  $doorState = "open";
		}
		elseif (trim(implode(" ",$output)) == "1") {
		  $doorState = "closed";
		}
		else
		{
		  $doorState = "unknown";
		}
		getNewPicture();
		return $doorState;
	}

	function getNewPicture() {
		exec("sudo fswebcam /var/www/cam.jpg; sleep 2;", $cam_output, $cam_var);
        if ($cam_var != 0) {exec("sudo fswebcam /var/www/cam.jpg; sleep 2;", $cam_output, $cam_var);}
	}
	
	function getOutdoorTemp(){
		$json = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=Indianapolis,US&units=imperial');
		$data = json_decode($json);
		return "{$data->main->temp}°F";

	}

    function getIndoorTemp(){
        $output = shell_exec("sudo /var/www/getGarageTemp 2>&1; ");
        return $output;
    }
    
	?>



    <div class="container">

      <form class="form-signin" role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
		<table class="table"> 
			<tr>
				<td colspan="2" class="
					<?php if ($doorState == 'open')
					{ echo 'text-center danger'; }
					elseif ($doorState == 'closed')
					{ echo 'text-center success'; }
					else
					{ echo 'text-center warning'; }		
					?>
					"><h4>
					<?php if ($doorState == "open")
					{ echo "Door is Opened"; }
					elseif ($doorState == "closed")
					{ echo "Door is Closed"; }
					else
					{ echo "Door State Unknown"; }		
					?>
					</h4>
				</td>
			</tr>
			<tr>
				<td>
					<button name="toggle" class="btn btn-lg btn-primary btn-block" type="submit" onclick="return confirm('Are you sure you want to toggle the door?')">
						<?php if ($doorState == "open")
							{ echo "Close Door"; }
							elseif ($doorState == "closed")
							{ echo "Open Door"; }
							else
							{ echo "Toggle Door"; }		
						?>
					</button>
				</td>
				<td rowspan="2" class="text-center active"> <?php echo $indoorTemp;?><br/><br/>Outdoor Temp <?php echo $outdoorTemp;?></td>
			</tr>
			<tr>
				<td>
					<button name="refresh" class="btn btn-lg btn-primary btn-block" type="submit" >Refresh</button>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<img src="cam.jpg" class="img-responsive" alt="Garage Door Image"/>
				</td>
			</tr>
		</table>


      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>

</html>


