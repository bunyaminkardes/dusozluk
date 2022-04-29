<?php
require_once("phpmailer/class.phpmailer.php");
define('MAILHOST','');
define('MAILUSERNAME','');
define('MAILPASSWORD','');
$hedefMail = $_POST['mailadresi'];
?>

<div class="row">
	<div class="col-12 col-sm-12 col-lg-12" style="padding-top:40px;">
		<h3 id="sifreyisifirla-baslik">Şifreyi Sıfırla</h3>
	</div>
	<div class="col-12 col-sm-12 col-lg-12" style="padding-bottom:40px;">
		<form id="sifreyisifirla-kapsayici" action="" method="POST" style="">
			<input id="sifreyisifirla-mail" type="textbox" name="mailadresi" placeholder=" Lütfen mail adresinizi giriniz.">
			<input id="sifreyisifirla-buton" type="submit" name="sifirla" value="gönder">
			<?php
			if(isset($_POST['sifirla']))
			{
				if(isset($hedefMail) && !empty($hedefMail))
				{
					try
					{
						$mail = new PHPMailer();

						//ayarlamalar
						$mail->SetLanguage('tr');
						//$mail->SMTPDebug = 2;
						$mail->IsSMTP();
						$mail->Host = MAILHOST;
						$mail->SMTPAuth = true;
						$mail->Username = MAILUSERNAME;
						$mail->Password = MAILPASSWORD;
						$mail->SMTPSecure = 'ssl';
						$mail->Port = 465;
						$mail->CharSet = 'UTF-8';
						$mail->SMTPOptions = array(
						 'ssl' => array(
						 'verify_peer' => false,
						 'verify_peer_name' => false,
						 'allow_self_signed' => true
						 )
						);

						//maili gönderen kişi
						$mail->SetFrom(MAILUSERNAME, 'DUSOZLUK');
						$mail->AddAddress($hedefMail, 'ALICININ ADI');

						//mailin içeriği
						$mail->isHTML(true);
						$mail->Subject = 'Şifre sıfırlama talebi';
						$mail->Body = 'Şifre sıfırlama talebinde bulundunuz.';

						//maili gönderme işlemi
						$mail->send();
						echo '<span id="sifreyisifirla-response">Şifre sıfırlama talebiniz alındı. Mail adresinize gelecek olan link yardımıyla şifrenizi sıfırlayabilirsiniz.</span>';

					} 
					catch (Exception $e) 
					{
						echo 'Mail gönderilirken bir hata oluştu: ' . $mail->ErrorInfo;	
					}
				}
				else
				{
					echo '<span id="sifreyisifirla-response">Lütfen mail adresinizi giriniz.</span>';
				}
			}
			?>
		</form>
	</div>
</div>
