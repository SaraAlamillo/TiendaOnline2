<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }

    public function index($categoria = NULL) {
        echo "<pre>";
        print_r($this->session->all_userdata());
        echo "</pre>";
        
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias(), "logueado" => $this->logueado()]);
       
        $parametrosVistas['contenido'] = CargaVista("contenido", [
            "destacados" => $this->productos_model->listarDestacados($categoria),
            "productos" => $this->productos_model->listarProductos($categoria),
            "error" => $this->session->flashdata("mensaje")
        ]);

        $this->load->view("home", $parametrosVistas);
    }

    public function comprar() {
        if ($this->input->post('cantidad') <= $this->productos_model->obtenerStock($this->input->post('id'))) {
            $this->productos_model->modificarStock($this->input->post('id'), "-", $this->input->post('cantidad'));
            $this->carrito->setContenido([
                "id" => $this->input->post('id'), 
                "cantidad" => $this->input->post('cantidad')
                    ]);
        } else {
            $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => 'No hay suficiente stock']);
        }
        redirect($this->input->post('url'));
    }

    public function consultarCarrito() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias(), "logueado" => $this->logueado()]);

        $carrito = $this->carrito->getContenido();
        foreach ($carrito as &$c) {
            $datos = $this->productos_model->listarProducto($c['id']);
            $c['nombre'] = $datos->nombre;
            $c['precio'] = $datos->precio;
        }
        $parametrosVistas['contenido'] = CargaVista("carrito", ["productos" => $carrito, "logueado" => $this->logueado()]);

        $this->load->view("home", $parametrosVistas);
    }

    public function acceder() {
        if ($this->usuarios_model->existeUsuario($this->input->post('usuario'), $this->input->post('clave'))) {
            $id = $this->usuarios_model->conseguirID("usuario", $this->input->post('usuario'));
            $this->session->set_userdata('usuario', $id);
            redirect(site_url());
        } else {
            redirect(site_url());
        }
    }

    public function logueado() {
        if ($this->session->userdata('usuario')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function cerrarSesion() {
        $this->session->unset_userdata('usuario');
        redirect(site_url());
    }

    public function tramitarCompra() {
        $pedido = $this->pedidos_model->crearPedido($this->session->userdata('usuario'));
        $this->pedidos_model->agregarProductos($pedido, $this->carrito->getContenido());
        $this->carrito->vaciarCarrito();
        redirect(site_url());
    }

    public function consultarPedidos() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias(), "logueado" => $this->logueado()]);

        $pedidos = $this->pedidos_model->listarPedidos($this->session->userdata('usuario'));
        foreach ($pedidos as &$p) {
            $p->total = $this->pedidos_model->totalPedido($p->id);
        }
        $parametrosVistas['contenido'] = CargaVista("pedidos", ["pedidos" => $pedidos]);

        $this->load->view("home", $parametrosVistas);
    }

    public function consultarPedido($pedido) {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias(), "logueado" => $this->logueado()]);

        $contenidoPedido = $this->pedidos_model->listarProductosPedido($pedido);

        $parametrosVistas['contenido'] = CargaVista("contenido_pedido", ["contenido" => $contenidoPedido]);

        $this->load->view("home", $parametrosVistas);
    }

}
