<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of modelo
 *
 * @author Sara Alamillo Arroyo
 */
class Pedidos_model extends CI_Model {

    public function ultimo_pedido($usuario) {
        $this->db->select("max(id) as id");
        $this->db->where('usuario', $usuario);
        $resultado = $this->db->get("pedido");
        return $resultado->row()->id;
    }

    public function crear_pedido($usuario) {
        $datosUsuario = $this->usuarios_model->listar_usuario($usuario);
        $datos = [
            'usuario' => $datosUsuario->id,
            'nombre' => $datosUsuario->nombre,
            'apellidos' => $datosUsuario->apellidos,
            'direccion' => $datosUsuario->direccion,
            'cp' => $datosUsuario->cp,
            'dni' => $datosUsuario->dni
        ];
        $this->db->insert("pedido", $datos);

        return $this->ultimo_pedido($usuario);
    }

    public function agregar_productos($pedido, array $productos) {
        foreach ($productos as $p) {
            $datosProducto = $this->productos_model->listar_producto($p['id']);
            $datos = [
                "producto" => $p['id'],
                "pedido" => $pedido,
                "cantidad" => $p['cantidad'],
                "precio" => $datosProducto->precio
            ];

            $this->db->insert("linea_pedido", $datos);
        }
    }

    public function listar_pedidos($usuario = NULL) {
        if (!is_null($usuario)) {
            $this->db->where("usuario", $usuario);
        }
        $resultado = $this->db->get("pedido");
        return $resultado->result();
    }

    public function listar_pedido($pedido) {
        $this->db->where("id", $pedido);
        $resultado = $this->db->get("pedido");
        return $resultado->row();
    }

    public function listar_productos_pedido($pedido) {
        $this->db->where("pedido", $pedido);
        $resultado = $this->db->get("linea_pedido");
        foreach ($resultado->result() as &$r) {
            $producto = $this->productos_model->listar_producto($r->producto);
            $r->nombre = $producto->nombre;
            $r->descuento = $producto->descuento;
            $r->iva = $producto->iva;
        }
        return $resultado->result();
    }

    public function total_pedido($pedido) {
        $this->db->select("precio, cantidad");
        $this->db->where("pedido", $pedido);
        $resultado = $this->db->get("linea_pedido");
        $total = 0;
        foreach ($resultado->result() as $r) {
            $total += ($r->precio * $r->cantidad);
        }
        return $total;
    }

    public function actualizar_estado($pedido, $estado) {
        $this->db->where("id", $pedido);
        $this->db->set("estado", $estado);
        $this->db->update("pedido");
    }

}
