<?php
//API Authentication:
//Curl authent:
$curl = curl_init();
$body = '{"username": "admin","password": "Pensando0$","tenant": "default"}';
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://10.9.20.71/v1/login',
  CURLOPT_SSL_VERIFYHOST => false,
  CURLOPT_SSL_VERIFYPEER => false,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
  CURLOPT_POSTFIELDS => $body,
  CURLOPT_COOKIEFILE => './cookie.txt',
  CURLOPT_COOKIEJAR => './cookie.txt',

  CURLOPT_HEADER => true
));

$response = curl_exec($curl);

//curl_close($curl);
//echo "toto";
//echo $response;
//echo $http_code;
if (curl_errno($curl)) {
			echo curl_error($curl);
			die();
		}
		
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($http_code == intval(200)){
			//echo $response;
		}
		else{
			echo "Ressource introuvable : " . $http_code;
		}
preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);
$cookies = array();
foreach($matches[1] as $item) {
	parse_str($item, $cookie);
	$cookies = array_merge($cookies, $cookie);
}
//print_r($cookies);

//control if rules already exist:
	$headers = ['Content-Type' => 'text/plain'];
	//print_r($headers);
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://10.9.20.71/configs/security/v1/tenant/default/networksecuritypolicies/pod1-vrf-auto',
	  CURLOPT_SSL_VERIFYHOST => false,
	  CURLOPT_SSL_VERIFYPEER => false,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'GET',
	  CURLOPT_HTTPHEADER => $headers,
	  CURLOPT_POSTFIELDS => $body,
	  CURLOPT_COOKIEFILE => './cookie.txt',
	  CURLOPT_COOKIEJAR => './cookie.txt',
	  CURLOPT_HEADER => true
	));

	$response = curl_exec($curl);
	//echo $response;
	if(str_contains($response, 'autoblock_mqtt'))
	{ 
		echo "<br><font size=6>rule already in place</font><br>";
		$MqttInPlace = true;
	}
	
	

if($_GET['action'] == 'post')
{
	if($_POST['check']=='on')
	{
		//2nd request to add policy
		$headers = ['Cookie'=> 'sid='.$cookies['sid'].'', 'Content-Type' => 'text/plain'];
		//print_r($headers);
		$body='{"kind": "NetworkSecurityPolicy","meta": {"name": "autodeny","tenant": "default"},"spec": {"attach-tenant": true,"rules": [{"from-ip-addresses": ["10.29.21.14"],"to-ip-addresses": ["10.29.21.15"],"proto-ports": [{ "protocol": "TCP","ports": "1883"}],"action": "deny","name": "autoblock_mqtt"},{"from-ip-addresses": ["any"],"to-ip-addresses": ["any"],"proto-ports": [{ "protocol": "any"}],"action": "permit","name": "permit_any"}]}}';
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://10.9.20.71/configs/security/v1/tenant/default/networksecuritypolicies/pod1-vrf-auto',
		  CURLOPT_SSL_VERIFYHOST => false,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_HTTPHEADER => $headers,
		  CURLOPT_POSTFIELDS => $body,
		  CURLOPT_COOKIEFILE => './cookie.txt',
		  CURLOPT_COOKIEJAR => './cookie.txt',
		  CURLOPT_HEADER => true
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
					echo curl_error($curl);
					die();
				}
				
				$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if($http_code == intval(200)){
					echo $response;
				}
				else{
					echo "Ressource introuvable : " . $http_code;
					echo $response;
				}
		header("Refresh:0,url=api-test.php");

	}
	else
	{
		//remove rule
		//2nd request to add policy
		$headers = ['Cookie'=> 'sid='.$cookies['sid'].'', 'Content-Type' => 'text/plain'];
		//print_r($headers);
		$body='{"kind": "NetworkSecurityPolicy","meta": {"name": "autodeny","tenant": "default"},"spec": {"attach-tenant": true,"rules": [{"from-ip-addresses": ["any"],"to-ip-addresses": ["any"],"proto-ports": [{ "protocol": "any"}],"action": "permit","name": "permit_any"}]}}';
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://10.9.20.71/configs/security/v1/tenant/default/networksecuritypolicies/pod1-vrf-auto',
		  CURLOPT_SSL_VERIFYHOST => false,
		  CURLOPT_SSL_VERIFYPEER => false,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'PUT',
		  CURLOPT_HTTPHEADER => $headers,
		  CURLOPT_POSTFIELDS => $body,
		  CURLOPT_COOKIEFILE => './cookie.txt',
		  CURLOPT_COOKIEJAR => './cookie.txt',
		  CURLOPT_HEADER => true
		));

		$response = curl_exec($curl);

		if (curl_errno($curl)) {
					echo curl_error($curl);
					die();
				}
				
				$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if($http_code == intval(200)){
					echo $response;
				}
				else{
					echo "Ressource introuvable : " . $http_code;
					echo $response;
				}
		header("Refresh:0,url=api-test.php");

	}
	


}

?>
<!DOCTYPE html> 
<html>
	<head> 
	<title>automation PSM</title> 
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="styles.css">
	</head>
<body>

<!-- Rounded switch -->
<form action="api-test.php?action=post" method="post">
<table>
	<tr>
		<td>
		<label class="switch">
			<input type="checkbox" name='check' <?php if($MqttInPlace == true) echo 'checked' ?>>
			<span class="slider round"></span>
		</label>
		</td>
		<td><font size='15'>block mqtt</font> </td>
		<td><input type="submit" style="height:50px;font-size: 30px;" value='submit'></td>
	</tr>
</table>
</form>

</body>
</html>

