<?php

class Home extends CI_Controller {
    public function index($categoria = NULL) {
	$this->load->helper('vistas');
	$parametrosVistas['cabecera'] = CargaVista("cabecera");
	$parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias()]);
	$parametrosVistas['contenido'] = CargaVista("contenido", [
	    "destacados" => $this->productos_model->listarDestacados($categoria), 
	    "productos" => $this->productos_model->listarProductos($categoria)
		]);
	
	$this->load->view("home", $parametrosVistas);
    }
}
