<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->library("carrito", ["session" => $this->session]);
    }

    public function index($categoria = NULL) {
	$parametrosVistas['cabecera'] = CargaVista("cabecera");
	$parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias()]);
	$parametrosVistas['contenido'] = CargaVista("contenido", [
	    "destacados" => $this->productos_model->listarDestacados($categoria),
	    "productos" => $this->productos_model->listarProductos($categoria)
	]);

	$this->load->view("home", $parametrosVistas);
	
    }

    public function comprar() {
	$this->productos_model->modificarStock($this->input->post('id'), "-", $this->input->post('cantidad'));
	$this->carrito->setContenido($this->input->post());
	redirect(site_url());
    }

    public function consultarCarrito() {
	$parametrosVistas['cabecera'] = CargaVista("cabecera");
	$parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias()]);
	$carrito = $this->carrito->getContenido();
	foreach ($carrito as &$c) {
	    $datos = $this->productos_model->listarProducto($c['id']);
	    $c['nombre'] = $datos->nombre;
	    $c['precio'] = $datos->precio;
	}
	$parametrosVistas['contenido'] = CargaVista("carrito", ["productos" => $carrito]);

	$this->load->view("home", $parametrosVistas);
    }

}
