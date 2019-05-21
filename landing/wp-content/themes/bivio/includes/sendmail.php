<?php

//$sitename = get_bloginfo('name');
//$siteurl =  home_url();

// define variables and set to empty values
$name = $email = $subject = $phone = $website = $country = $city = $company = $content = "";

$to      = isset($_POST['to']) ? test_input($_POST['to']):'';
$name    = isset($_POST['name']) ? test_input($_POST['name']):'';
$email   = isset($_POST['email']) ? test_input($_POST['email']):'';
$subject = isset($_POST['subject']) ? test_input($_POST['subject']):'';
$phone   = isset($_POST['phone']) ? test_input($_POST['phone']):'';
$website = isset($_POST['website']) ? test_input($_POST['website']):'';
$country = isset($_POST['country']) ? test_input($_POST['country']):'';
$city    = isset($_POST['city']) ? test_input($_POST['city']):'';
$company = isset($_POST['company']) ? test_input($_POST['company']):'';
$content = isset($_POST['content']) ? test_input($_POST['content']):'';
$sitename = isset($_POST['sitename']) ? test_input($_POST['sitename']):'';
$siteurl = isset($_POST['siteurl']) ? test_input($_POST['siteurl']):'';


$error = false;

if($to === '' || $name === '' || $email === '' || $content === ''){
	$error = true;
}
if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $to)){
	$error = true;
}
if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)){
	$error = true;
}
if ($website != '' && !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
	$error = true; 
}

$error = false;

if($error == false){
		
	$title    = sprintf(('%1$s\'s message from %2$s'),$sitename,$name);
	$body     = 'Site: '.$sitename.' ('.$siteurl.')'."\n\n";
	$body    .= 'Name: '.$name."\n\n";
	$body    .= 'Email: '.$email."\n\n";
	
	$subject != '' ? $body .= 'Subject: '.$subject."\n\n" : '';
	$phone   != '' ? $body .= 'Phone: '.$phone."\n\n" : '';
	$website != '' ? $body .= 'Website: '.$website."\n\n" : '';
	$country != '' ? $body .= 'Country: '.$country."\n\n" : '';
	$city    != '' ? $body .= 'City: '.$city."\n\n" : '';
	$company != '' ? $body .= 'Company: '.$company."\n\n" : '';
	
	$body    .= 'Messages: '.$content;
	$headers  = "From: \"{$name}\" <{$email}>\r\n";
	$headers .= "Reply-To: $email\r\n";
	
	if(mail($to, $title, $body, $headers)){
		echo 'success';
	}else{
		echo 'fail';
	}
	
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}