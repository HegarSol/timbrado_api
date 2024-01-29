<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlServicio extends CI_Model {

    public function clientes()
    {
        $query = $this->db->query("SELECT clave, email, cantidad, Notificar, nombre, fecha_vence, referencia_compra FROM clientes INNER JOIN (SELECT clave_cliente, SUM(cantidad) as cantidad, fecha_vence, referencia_compra FROM paquetes_clientes WHERE cantidad != 0 GROUP BY clave_cliente) AS  paquetes_clientes ON clientes.rfc = paquetes_clientes.clave_cliente WHERE Notificar > 0 AND paquetes_clientes.cantidad <= clientes.Notificar AND paquetes_clientes.cantidad > 0");
        return $query->result();
    }
    public function getCliente($clave)
    {
        $query = $this->db->query("SELECT * FROM clientes WHERE clave = '$clave' ");
        return $query->result();
    }
    public function cambioFecha($clave)
    {
        $this->db->query("UPDATE clientes SET fecha_correo = DATE(NOW()) WHERE clave = '$clave' ");
    }
    public function cambionoti($clave)
    {
        $this->db->query("UPDATE clientes SET notificar_correo = 0 WHERE clave = '$clave' ");
    }
    public function cambionotiauno($clave)
    {
        $this->db->query("UPDATE clientes SET notificar_correo = 1 WHERE clave = '$clave' ");
    } 

}