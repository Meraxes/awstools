<style>

	#presignedwrapper
	{
		width:300px;
		height:200px;
		margin:10px
		border:1px solid grey;
		font-family:arial;
	}
	
	#presignedtitle
	{
		width:100%;
		height:10%;
		margin:5px;
		font:20px bold;
		font-family:arial;
		padding:10px;
		color:#FF8000;
		text-shadow: 0.1em 0.1em 0.4em grey;
		text-align:center;
	}	

	#presignedform
	{
		width:100%;
		height:90%;
		margin:5px;
		padding:10px;
		background-color:#EEEEEE;
		
	}

	#presignedform input
	{
		width:190px;

	}

</style>
<?php
$region = $_POST['region'];
$location = $_POST['location'];
$object = $_POST['object'];
$accesskey = $_POST['accesskey'];
$secretkey = $_POST['secretkey'];
?>
<div id="presignedwrapper">
	<div id="presignedtitle">AWS Presigned URL Generator</div>
		<div id="presignedform" align="right">
			<form action="s3signedurl.php" method="post">
				Region: <input type="text" name="region" value="<?php echo $region;  ?>"></input><br>
				Location: <input type="text" name="location" value="<?php echo $location;  ?>"></input><br>
				Object Name: <input type="text" name="object" value="<?php echo $object;  ?>"></input><br>
				Access Key: <input type="text" name="accesskey" value="<?php echo $accesskey;  ?>"></input><br>
				Secret Key: <input type="text" name="secretkey" value="<?php echo $secretkey;  ?>"></input><br><br>
				<input type="submit" name="submit" value="Generate URL"></input>
			</form>
		</div>
	</div>
<br><br><br><br>
<?php

#Set url expiration (current-time + epoch).
$expire = time() + 31536000; 
#Aggregate hash stamp.
$stamp = "GET\n\n\n$expire\n/$location/$object";
#Generate hash signature using hash_hmac.
$hashsignature = hash_hmac('sha1', $stamp, $secretkey, $raw_output = TRUE);
#Format signature.
$signature = urlencode(base64_encode($hashsignature));
#Formalise final url for output.
$link = "https://s3-$region.amazonaws.com/$location/$object?AWSAccessKeyId=$accesskey&Expires=$expire&Signature=$signature";
#Print URL
echo $link;

?>
