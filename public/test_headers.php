<html>
<head>
<title>Mail header testing</title>
</head>
<body>
<?php
require_once('class.phpmailer.php');
//require_once('../class.pop3.php'); // required for POP before SMTP
//$pop = new POP3();
//$pop->Authorise('host.seocredible.com', 995, 30, 'system@iacirclemail.com', 'DBNaccess4406', 1);

	$mail = new PHPMailer(true);
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
	$mail->IsHTML(true);

$mail->IsHTML(true);

	/* $mail->Host = 'demo.yo-kart.com';
    $mail->Port = 25;
    $mail->Username = 'demo@demo.yo-kart.com';
    $mail->Password = '8dG@q39*TrU;'; */


	/* $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
    $mail->Port = 587;
	$mail->Username = 'AKIAJEVNPAG66E4X6LTA';
    $mail->Password = 'ArbK3NEsQLeIOVqZpWDeVl427OZHuQVuEDdqvWu1jq3K'; */

	/* $mail->Host = 'smtp.mandrillapp.com';
    $mail->Port = 587;
	$mail->Username = 'TeaMarket';
    $mail->Password = 'yNVZ7PpvRAQ6e3GuviowGg';  */
    /*$mail->Username = 'AKIAIA5VIU2ZLV4A7SYQ';
    $mail->Password = 'ArbK3NEsQLeIOVqZpWDeVl427OZHuQVuEDdqvWu1jq3K';   */

	/*$mail->Host = 'email.us-west-2.amazonaws.com';
    $mail->Port = 587;
	$mail->Username = 'AKIAJNWX6NKE5WT6NB4Q';
    $mail->Password = 'Am/bi2As42JRuqk+N+ougy23fXBarlLyqcZkvfUTcopw'; */


	/* $mail->Host = 'us169.siteground.us';
    $mail->Port = 25;
    $mail->Username = 'admin@deferia.pe';
    $mail->Password = 'WelcomeDeFeria02'; */

/* 	$mail->Host = 'smtp.office365.com';
    $mail->Port = 25;
    $mail->Username = 'hossam@renttitude.com';
    $mail->Password = 'Ho$$am123456';  */
	/* $mail->Host = 'smtp.gmail.com';
    $mail->Port = 25;
    $mail->Username = 'info@dealwithu.ca';
    $mail->Password = 'Test1234!!';
		 */
	
	$mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->Username = 'manpreet.kaur@fatbit.in';
    $mail->Password = 'nishakaur123';
	
	$mail->SMTPSecure = 'tls';
    $mail->SMTPDebug = 4;
	$mail->Timeout  =   60;
    $mail->SetFrom('info@rxall.net', 'test');
    $mail->Subject = 'test Headers test From test '.time();
	$mail->AltBody="This is text only alternative body.";
    $mail->MsgHTML('<b>Headers test</b><br><br>Port: 587, Secure: tls' );
    $mail->AddAddress('test@dummyid.com', 'testing');
	/* echo "Here"; */

	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Message sent!";
	}
?>
</body>
</html>
