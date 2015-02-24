<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of modelo
 *
 * @author Sara Alamillo Arroyo
 */
class Pedidos_model extends CI_Model {

    public function ultimoPedido($usuario) {
        $this->db->select("max(id) as id");
        $this->db->where('usuario', $usuario);
        $resultado = $this->db->get("pedido");
        return $resultado->row()->id;
    }

    public function crearPedido($usuario) {
        $datosUsuario = $this->usuarios_model->listarUsuario($usuario);
        $datos = [
            'usuario' => $datosUsuario->id,
            'nombre' => $datosUsuario->nombre,
            'apellidos' => $datosUsuario->apellidos,
            'direccion' => $datosUsuario->direccion,
            'cp' => $datosUsuario->cp,
            'dni' => $datosUsuario->dni
        ];
        $this->db->insert("pedido", $datos);
        
        return $this->ultimoPedido($usuario);
    }

    public function agregarProductos($pedido, array $productos) {
        foreach ($productos as $p) {
            $datosProducto = $this->productos_model->listarProducto($p['id']);
            $datos = [
                "producto" => $p['id'],
                "pedido" => $pedido,
                "cantidad" => $p['cantidad'],
                "precio" => $datosProducto->precio
            ];

            $this->db->insert("linea_pedido", $datos);
        }
    }

    public function listarPedidos($usuario = NULL) {
        if (!is_null($usuario)) {
            $this->db->where("usuario", $usuario);
        }
        $resultado = $this->db->get("pedido");
        return $resultado->result();
    }
    
   
    public function listarProductosPedido($pedido) {
            $this->db->where("pedido", $pedido);
        $resultado = $this->db->get("linea_pedido");
        foreach ($resultado->result() as &$r) {
            $r->nombre = $this->productos_model->listarProducto($r->producto)->nombre;
        }
        return $resultado->result();
    } 
    
    public function totalPedido($pedido) {
        $this->db->select("precio, cantidad");
        $this->db->where("pedido", $pedido);
        $resultado = $this->db->get("linea_pedido");
        $total = 0;
        foreach ($resultado->result() as $r) {
            $total += ($r->precio * $r->cantidad);
        }
        return $total;
    }

}
