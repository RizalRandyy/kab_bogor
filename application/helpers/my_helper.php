<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


require(FCPATH . 'vendor/phpmailer/phpmailer/src/PHPMailer.php');
require(FCPATH . 'vendor/phpmailer/phpmailer/src/Exception.php');
require(FCPATH . 'vendor/phpmailer/phpmailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!function_exists('setEncrypt')) {
	function setEncrypt($psswd)
	{
		$cost = 8;
		$salt = strtr(base64_encode(bin2hex(random_bytes($cost))), '+', '.');
		$salt = sprintf("$2a$%02d$", $cost) . $salt;
		$hash = crypt($psswd, $salt);
		return $hash;
	}
}

if (!function_exists('setDecrypt')) {
	function setDecrypt($hash, $psswd)
	{
		if (hash_equals($hash, crypt($psswd, $hash))) {
			return true;
		} else
		return false;
	}
}

if (!function_exists('sendEmail')) {
	function sendEmail($receive, $subject, $body, $imgembed = null , int $id = null, $cc = null, $bcc = null, $attachment = null)
	{
		$ci = &get_instance();
		$mail = new PHPMailer(true);
		try {
			$mail->SMTPDebug = 0;
			$mail->isSMTP();
            $mail->Host = '';
            $mail->SMTPAuth = true;
            $mail->Username = '';
            $mail->Password = '';
            $mail->SMTPSecure = '';
            $mail->Port = '';

			foreach (explode(",", $receive) as $address)
				$mail->addAddress($address);


			if ($cc != null) {
				foreach (explode(",", $cc) as $ecc)
					$mail->addCC($ecc);
			}

			if ($bcc != null) {
				foreach (explode(",", $bcc) as $ebcc)
					$mail->addBCC($ebcc);
			}

			$mail->isHTML(true);
			$mail->Subject = $subject;

			$mail->Body    = $body;
			$mail->send();

			return [
				'status' => 200,
				'message' => 'send email success'
			];
		} catch (Exception $e) {
			print_r($e->getMessage()); exit();
			return [
				'status' => 500,
				'message' => 'send email failed, error code: 500'
			];
		}
	}
}

if(!function_exists("generate_string")):
	function generate_string($strength = 16)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length    = strlen($permitted_chars);
        $random_string   = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
endif;
