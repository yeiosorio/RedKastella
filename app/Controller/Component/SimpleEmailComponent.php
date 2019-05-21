<?php 
    
    /**
     * Carga de la libreria para el envio de emails 
     */
    App::import('Vendor', 'PHPMailerAutoload', array('file' => 'PHPMailer'.DS.'PHPMailerAutoload.php'));

    App::uses('Component', 'Controller');


    /**
     * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
     * Componente para envio de emails
     */
    class SimpleEmailComponent extends Component{


        /**
         * Envio de emails de manera masiva
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * @date     2016-09-26
         * @datetime 2016-09-26T11:31:09-0500
         * @param    [type]                   $mensaje         [description]
         * @param    [type]                   $emailsDestino   Arreglo de emails
         * @param    [type]                   $nombreRemitente [description]
         * @param    [type]                   $asunto          [description]
         * @return   [type]                                    [description]
         */
        function massiveMail($mensaje, $emailsDestino, $nombreRemitente ,$asunto){


            
            foreach ($emailsDestino as $email) {
            
                $this->sendMail($mensaje, $email, $nombreRemitente ,$asunto);

            }        

        }

        /**
         * Email simpmle de contacto
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * @date     2016-09-26
         * @datetime 2016-09-26T11:31:30-0500
         * @param    [type]                   $mensaje         [description]
         * @param    [type]                   $destino         [description]
         * @param    [type]                   $nombreRemitente [description]
         * @param    [type]                   $asunto          [description]
         * @return   [type]                                    [description]
         */
        function contactMail($mensaje, $destino, $nombreRemitente ,$asunto){


            $this->sendMail($mensaje, $destino, $nombreRemitente ,$asunto);
        

       
        }

        /**
         * Email con formato html
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * @date     2016-09-26
         * @datetime 2016-09-26T11:31:42-0500
         * @param    [type]                   $mensaje         [description]
         * @param    [type]                   $destino         [description]
         * @param    [type]                   $nombreRemitente [description]
         * @param    [type]                   $asunto          [description]
         * @return   [type]                                    [description]
         */
        function htmlEmail($mensaje, $destino, $nombreRemitente ,$asunto){


            $this->sendMail($mensaje, $destino, $nombreRemitente ,$asunto, true);
        

       
        }
        
        /**
         * Funcion de envio de email
         * @author Julián Andrés Muñoz Cardozo <julianmc90@gmail.com>
         * @date     2016-09-26
         * @datetime 2016-09-26T11:31:54-0500
         * @param    [type]                   $mensaje         [description]
         * @param    [type]                   $destino         [description]
         * @param    [type]                   $nombreRemitente [description]
         * @param    [type]                   $asunto          [description]
         * @param    [type]                   $isHtml          [description]
         * @return   [type]                                    [description]
         */
        function sendMail($mensaje, $destino, $nombreRemitente ,$asunto, $isHtml = null){


            // $origen = "desarrollo@gatolocostudios.com";
            
            $origen = "info@redkastella.com";

            // $origen = "info@redkastella.com";    

            //$origen = "admin@cdclaboratorio.com";
            
            // $passOrigen = "Ingeniero2015";

            $passOrigen = "fOJvl&II~x77";
            
            // $passOrigen = "elfL763~";

            //$destino = "desarrollo@gatolocostudios.com";
            
            //$remitente = $_POST['contactEmailField'];

            //instanciamos un objeto de la clase phpmailer al que llamamos
            //por ejemplo mail
            $mail = new phpmailer();

            //Definimos las propiedades y llamamos a los métodos
            //correspondientes del objeto mail

            //Con PluginDir le indicamos a la clase phpmailer donde se
            
            //encuentra la clase smtp que como he comentado al principio de
            
            //este ejemplo va a estar en el subdirectorio includes
            $mail->PluginDir = "vendors/PHPMailer/";

            //Con la propiedad Mailer le indicamos que vamos a usar un
            //servidor smtp
            // $mail->Mailer     = "smtp"; 

            //Asignamos a Host el nombre de nuestro servidor smtp
            $mail->Host       = "vps.gatolocostudios.com";

            $mail->Port       = 465;

            // $mail->Host = "smtp.redkastella.com";

            // $mail->Host = "smtp.gmail.com";

            //Le indicamos que el servidor smtp requiere autenticación
            $mail->SMTPAuth = true;

            $mail->SMTPSecure = "tls";

            //Le decimos cual es nuestro nombreRemitente de usuario y password  
            $mail->Username = $origen;

            $mail->Password = $passOrigen;

            //Indicamos cual es nuestra dirección de correo y el nombre que
            //queremos que vea el usuario que lee nuestro correo

            $mail->From = $origen;
            $mail->FromName = $nombreRemitente;

            //el valor por defecto 10 de Timeout es un poco escaso dado que
            //voy a usar una cuenta gratuita, por tanto lo pongo a 30
            $mail->Timeout = 30;

            //Indicamos cual es la dirección de destino del correo
            $mail->AddAddress($destino, $nombreRemitente);

            //Asignamos asunto y cuerpo del mensaje
            //El cuerpo del mensaje lo ponemos en formato html, haciendo
            //que se vea en negrita

            $mail->Subject = $asunto;

            $mail->Body = $mensaje;

            /**
             * Si es Html
             */
            if($isHtml == true){

                $mail->IsHTML(true);
            }
     
            //Definimos AltBody por si el destinatario del correo
            //no admite email con formato html
            $mail->AltBody = "Mensaje de prueba en formato solo texto";

            //se envia el mensaje, si no ha habido problemas
            //la variable $exito tendra el valor true
            $exito = $mail->Send();

            //Si el mensaje no ha podido ser enviado se realizaran 4 intentos mas
            //como mucho para intentar enviar el mensaje, cada intento se hará 5
            //segundos despues del anterior, para ello se usa la funcion sleep
            $intentos=1;

            while ((!$exito) && ($intentos < 5)) 
            {   
                
                sleep(5);

                //echo $mail->ErrorInfo;
                $exito = $mail->Send();
                $intentos=$intentos+1;
            }

            if(!$exito){
    

                // echo "falso";

                return false;

                // return "Problemas enviando correo electrónico a ".$destino;
                
                // echo "<br/>".$mail->ErrorInfo;
            
            }else{

                // echo "true";                
                return true;  
            }  



        }


        function checkMail(){

            $mail = new PHPMailer;

            //$mail->SMTPDebug = 3;                               // Enable verbose debug output

            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.redkastella.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'info@redkastella.com';                 // SMTP username
            $mail->Password = 'fOJvl&II~x77';                           // SMTP password
            // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                    // TCP port to connect to

            $mail->setFrom('info@redkastella.com', 'Mailer');

            $mail->addAddress('lordorlando2@hotmail.com', 'Joe User');     // Add a recipient

            // $mail->addAddress('ellen@example.com');               // Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }

        }


    



}
