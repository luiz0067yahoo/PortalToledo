<?php
	
    use PHPMailer\PHPMailer\PHPMailer;
     if (!function_exists('loadPage')){
    		function loadPage($url){
                $doc = new DOMDocument();
                $doc->loadHTMLFile($url);
                return $doc->saveHTML();
    		}
     }	
     if (!function_exists('sendEmailUrl')){
    		function sendEmailUrl($host,$username,$name_contact,$password,$subject,$email,$name_send,$urlBody){
    		    require($_SERVER['DOCUMENT_ROOT'].'/library/vendor/autoload.php');
                
    		    $mail = new PHPMailer();
    		    $mail->IsSMTP();
                try {
                    $mail->Host = $host; // Endereço do servidor SMTP (Autenticação, utilize o host mail.seudomínio.com.br)
                    $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
                    //$mail->Port = 465; // Usar 587 porta SMTP
                    $mail->Port = 587; // Usar 587 porta SMTP
                    $mail->Username = $username; // Usuário do servidor SMTP (endereço de email)
                    $mail->Password = $password; // Senha do servidor SMTP (senha do email usado)
                    
                    //Define o remetente
                    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= 
                    $mail->SetFrom($username, $name_contact); //deve ser o mesmo da autenticação
                    $mail->AddReplyTo($username, $name_contact); //Seu e-mail
                    $mail->Subject = $subject;//Assunto do e-mail
                    
                    
                    //Define os destinatário(s)
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->AddAddress($email,$name_send);
                    
                    //Campos abaixo são opcionais 
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
                    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
                    //$mail->AddAttachment('images/phpmailer.gif'); // Adicionar um anexo
                    
                    
                    //Define o corpo do email
                    $body=loadPage($urlBody);
                    $mail->MsgHTML($body); 
                    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
                    //$mail->MsgHTML(file_get_contents('arquivo.html'));
                    
                    $mail->Send();

                    //caso apresente algum erro é apresentado abaixo com essa exceção.
                }catch (phpmailerException $e) {
                    //echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
                }
    		    
    		}
     }	
	
	if (!function_exists('sendEmailMessage')){
    		function sendEmailMessage($host,$username,$name_contact,$password,$subject,$email,$name_send,$body){
    		    require($_SERVER['DOCUMENT_ROOT'].'/library/vendor/autoload.php');
    		    $mail = new PHPMailer();
    		    $mail->IsSMTP();
                try {
                    $mail->Host = $host; // Endereço do servidor SMTP (Autenticação, utilize o host mail.seudomínio.com.br)
                    $mail->SMTPAuth = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
                    //$mail->Port = 465; // Usar 587 porta SMTP
                    $mail->Port = 587; // Usar 587 porta SMTP
                    $mail->Username = $username; // Usuário do servidor SMTP (endereço de email)
                    $mail->Password = $password; // Senha do servidor SMTP (senha do email usado)
                    
                    //Define o remetente
                    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= 
                    $mail->SetFrom($username, $name_contact); //deve ser o mesmo da autenticação
                    $mail->AddReplyTo($username, $name_contact); //Seu e-mail
                    $mail->Subject = $subject;//Assunto do e-mail
                    
                    
                    //Define os destinatário(s)
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    $mail->AddAddress($email,$name_send);
                    
                    //Campos abaixo são opcionais 
                    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
                    //$mail->AddCC('destinarario@dominio.com.br', 'Destinatario'); // Copia
                    //$mail->AddBCC('destinatario_oculto@dominio.com.br', 'Destinatario2`'); // Cópia Oculta
                    //$mail->AddAttachment('images/phpmailer.gif'); // Adicionar um anexo
                    
                    
                    //Define o corpo do email
                    $mail->MsgHTML($body); 
                    ////Caso queira colocar o conteudo de um arquivo utilize o método abaixo ao invés da mensagem no corpo do e-mail.
                    //$mail->MsgHTML(file_get_contents('arquivo.html'));
                    
                    $mail->Send();

                    //caso apresente algum erro é apresentado abaixo com essa exceção.
                }catch (Exception $e) {
                    echo $e->errorMessage(); //Mensagem de erro costumizada do PHPMailer
                }
    		    
    		}
     }	
?>