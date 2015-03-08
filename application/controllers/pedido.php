<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Pedido extends Sara {

    public function listado() {
        $pedidos = $this->pedidos_model->listar_pedidos($this->session->userdata('usuario'));

        foreach ($pedidos as &$p) {
            $p->total = $this->pedidos_model->total_pedido($p->id);
        }

        $vista = [
            "pedidos" => $pedidos,
            "mensaje" => $this->session->flashdata("mensaje")
        ];

        $this->vista("pedidos", $vista);
    }

    public function ver_pedido($pedido) {
        $vista = [
            "contenido" => $this->pedidos_model->listar_productos_pedido($pedido)
        ];

        $this->vista("contenido_pedido", $vista);
    }
    public function factura($pedido) {
        $this->factura->generar($pedido);
    }

    public function cancelar($pedido, $estado) {
        if ($estado == 'Pendiente') {
            $this->pedidos_model->actualizar_estado($pedido, 'Cancelado');
        } else {
            $this->session->set_flashdata("mensaje", "Si un pedido ya ha sido procesado, no se puede cancelar.");
        }
        $this->listado();
    }

}
