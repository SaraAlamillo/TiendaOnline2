<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
	parent::__construct();
	$this->load->library("carrito", ["session" => $this->session]);
    }
    public function index($categoria = NULL) {
	/* $parametrosVistas['cabecera'] = CargaVista("cabecera");
	  $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listarCategorias()]);
	  $parametrosVistas['contenido'] = CargaVista("contenido", [
	  "destacados" => $this->productos_model->listarDestacados($categoria),
	  "productos" => $this->productos_model->listarProductos($categoria)
	  ]);

	  $this->load->view("home", $parametrosVistas);

	  $c = Carrito::getInstance(); */
	$nuevosdatos = [
		[
		    "id" => "1234",
		    "cantidad" => "1"
		],
		[
		    "id" => "124",
		    "cantidad" => "7"
		],
		[
		    "id" => "234",
		    "cantidad" => "8"
		]
	    ];
	    $this->carrito->setContenido($nuevosdatos);
	echo "<pre>";
	print_r($this->carrito->getContenido());
	echo "</pre>";
	echo "<pre>";
	print_r($this->session->all_userdata());
	echo "</pre>";
    }

}
