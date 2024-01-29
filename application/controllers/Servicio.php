<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servicio extends CI_Controller {

	public function __construct(){
        parent::__construct();
                
       
        

		$this->load->model('MdlServicio','servicio');
    }

    public function index()
    {
        $res = $this->servicio->clientes();
        foreach($res as $resultado)
        {
            //var_dump($resultado);
            if($resultado->cantidad < $resultado->Notificar)
            {
                    $tablanoti = $this->servicio->getCliente($resultado->clave);
                    if($tablanoti[0]->fecha_correo >= date('Y-m-d'))
                    {
                        $this->servicio->cambioFecha($resultado->clave);
                        if($tablanoti[0]->notificar_correo == 1 && $tablanoti[0]->fecha_correo == date('Y-m-d'))
                        {

                            $correos = str_replace(";",",",$resultado->email);
                            $correo = explode(",",$correos);
                            
                            for($i=0;$i<count($correo);$i++)
                            {
                            
                                $this->servicio->cambionoti($resultado->clave);
                                require_once("class.phpmailer.php" );
                                require_once("class.smtp.php");
                                $mail = new PHPMailer(true);
                                $mail->IsSMTP();
                                try{
                                        $mail->SMTPDebug = 2;
                                        $mail->SMTPAuth = true;
                                        $mail->SMTPSecure = 'ssl';
                                        $mail->Host = 'mail.noip.com';
                                        $mail->Port = 465;
                                        $mail->Username = 'facturacion@hegarss.com';
                                        $mail->Password = 'Hegarss1906';
                                        $mail->AddAddress($correo[$i]);
                                        $mail->SetFrom('pagos@hegarss.com','HEGAR Soluciones en Sistemas S. de R.L.');
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Notificacion de timbres restantes.';
                                        $mail->AltBody = 'Funciona.';
                                        $mail->MsgHTML('<center><h1>'.$resultado->nombre.'</h1></center>' .
                                    '<br><br><br><br>' .
                                    'Se le notifica lo siguiente: ' .
                                    '<br>' .
                                    'Timbres restantes : ' . $resultado->cantidad .
                                    '<br><br><br>' . 
                                    'Fecha de Caducidad: ' . date( "d-m-Y", strtotime( $resultado->fecha_vence ) ) .
                                    '<br><br>' .
                                    'Fecha de Compra: '. date("d-m-Y", strtotime( $resultado->referencia_compra ) ) . 
                                    '<h1>¡HEGAR SOLUCIONES EN SISTEMAS LE AGRADECE SU COMPRA y ATENCION!</h1>' .
                                    'Dudas o aclaraciones, porfavor responder a pagos@hegarss.com'   
                                    );
                                        $mail->CharSet = 'UTF-8';
                                        $mail->Send();
                                    } 
                                    catch (phpmailerException $e)
                                    {
                            
                                    } 
                            }
                        }
                        else
                        {
                            var_dump('no se envio correo');
                        }
                    }
                    else
                    {
                        $this->servicio->cambionotiauno($resultado->clave);
                        $this->servicio->cambioFecha($resultado->clave);
                    }
                    // if($tablanoti[0]->notificar == 1)
                    // {
                    //      var_dump('enviar correo');
                    // }
            }
            else
            {

            }
        }
    }


}