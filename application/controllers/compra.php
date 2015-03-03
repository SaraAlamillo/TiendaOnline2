<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Compra extends Sara {

    public function __construct() {
        parent::__construct();
    }


    function confirmar_productos() {
        $carrito = $this->carrito->get_contenido();

        if (count($carrito) <= 0) {
            $this->session->set_flashdata("mensaje", "No sé si te has dado cuenta, pero está vacío...");
            redirect(site_url("home/consultar_carrito"));
        } else {

            foreach ($carrito as &$c) {
                $datos = $this->productos_model->listar_producto($c['id']);
                $c['nombre'] = $datos->nombre;
                $c['precio'] = $datos->precio;
            }

            $parametrosContenido = [
                "productos" => $carrito
            ];
            $contenido = $this->load->view("realizar_compra/confirmacion_productos", $parametrosContenido, TRUE);
            $this->vista($contenido);
        }
    }

    function confirmar_usuario() {
        $parametrosContenido = [
            "usuario" => $this->usuarios_model->listar_usuario($this->session->userdata('usuario'))
        ];
        $parametrosContenido["usuario"]->provincia = $this->usuarios_model->nombre_provincia($parametrosContenido["usuario"]->provincia);
        $contenido = $this->load->view("realizar_compra/confirmacion_usuario", $parametrosContenido, TRUE);
        $this->vista($contenido);
    }

    function realizar_pedido() {
        $pedido = $this->pedidos_model->crear_pedido($this->session->userdata('usuario'));
        $this->pedidos_model->agregar_productos($pedido, $this->carrito->get_contenido());
        $this->carrito->vaciar_carrito();

        $parametrosContenido["pedido"] = $pedido;
        $contenido = $this->load->view("realizar_compra/pedido_realizado", $parametrosContenido, TRUE);
        $this->vista($contenido);
    }

    function mensaje_final() {
        $contenido = $this->load->view("realizar_compra/mensaje_final", '', TRUE);
        $this->vista($contenido);
    }
    function email_detalle() {
        
    }
    function email_pdf() {
        
    }

}
