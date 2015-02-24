<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }

    public function index() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);
       
        $parametrosVistas['contenido'] = CargaVista("productos", [
            "productos" => $this->productos_model->listar_destacados(),
            "error" => $this->session->flashdata("mensaje")
        ]);

        $this->load->view("home", $parametrosVistas);
    }

    public function ver_categoria($categoria = NULL) {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);
       
        $parametrosVistas['contenido'] = CargaVista("productos", [
            "productos" => $this->productos_model->listar_productos($categoria),
            "error" => $this->session->flashdata("mensaje")
        ]);

        $this->load->view("home", $parametrosVistas);
    }

    public function comprar() {
        if ($this->input->post('cantidad') <= $this->productos_model->obtener_stock($this->input->post('id'))) {
            $this->productos_model->modificar_stock($this->input->post('id'), "-", $this->input->post('cantidad'));
            $this->carrito->set_contenido([
                "id" => $this->input->post('id'), 
                "cantidad" => $this->input->post('cantidad')
                    ]);
        } else {
            $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => 'No hay suficiente stock']);
        }
        redirect($this->input->post('url'));
    }

    public function consultar_carrito() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $carrito = $this->carrito->get_contenido();
        foreach ($carrito as &$c) {
            $datos = $this->productos_model->listar_producto($c['id']);
            $c['nombre'] = $datos->nombre;
            $c['precio'] = $datos->precio;
        }
        $parametrosVistas['contenido'] = CargaVista("carrito", ["productos" => $carrito, "logueado" => $this->logueado()]);

        $this->load->view("home", $parametrosVistas);
    }

    public function acceder() {
        if ($this->usuarios_model->existe_usuario($this->input->post('usuario'), $this->input->post('clave'))) {
            $id = $this->usuarios_model->conseguir_id("usuario", $this->input->post('usuario'));
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

    public function cerra_sesion() {
        $this->session->unset_userdata('usuario');
        redirect(site_url());
    }

    public function tramitar_compra() {
        $pedido = $this->pedidos_model->crear_pedido($this->session->userdata('usuario'));
        $this->pedidos_model->agregar_productos($pedido, $this->carrito->get_contenido());
        $this->carrito->vaciar_carrito();
        redirect(site_url());
    }

    public function consultar_pedidos() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $pedidos = $this->pedidos_model->listar_pedidos($this->session->userdata('usuario'));
        foreach ($pedidos as &$p) {
            $p->total = $this->pedidos_model->total_pedido($p->id);
        }
        $parametrosVistas['contenido'] = CargaVista("pedidos", ["pedidos" => $pedidos]);

        $this->load->view("home", $parametrosVistas);
    }

    public function consultar_pedido($pedido) {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $contenidoPedido = $this->pedidos_model->listar_productos_pedido($pedido);

        $parametrosVistas['contenido'] = CargaVista("contenido_pedido", ["contenido" => $contenidoPedido]);

        $this->load->view("home", $parametrosVistas);
    }

}
