<?php
    $to = 'bpp@bppsupply.com';
    $to2 = 'visit2523@gmail.com';
    $firstname = $_POST["fname"];
    $email= $_POST["email"];
    $text= $_POST["message"];
    


    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= "From: " . $email . "\r\n"; // Sender's E-mail
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $message ='<table style="width:100%">
        <tr>
            <td>From: '.$firstname.'</td>
        </tr>
        <tr><td>Email: '.$email.'</td></tr>
        <tr><td>Detail: '.$text.'</td></tr>
        
    </table>';
	
	
	require_once('PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer();
	$mail->isSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 587;
	$mail->isHTML();
	$mail->CharSet = "utf-8";
	$mail->Username = "bpp@bppsupply.com";
	$mail->Password = "bpp12345678";
	$mail->Subject = $firstname." contact BPP via bppsupply.com"; 
	$mail->Body = $message;
	$mail->AddAddress($to,'BPP Supply Admin');
	$mail->AddAddress($to2,'BPP Supply Admin');
	$mail->Sender=$email;
	$mail->SetFrom($email, $firstname, FALSE);
	$mail->AddReplyTo($email, $firstname);
	
	if ($mail->Send()){
		echo 'The message has been sent.';
	}else{
		echo 'failed';
	}	
	

?>
