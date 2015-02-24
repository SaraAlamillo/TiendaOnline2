<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Carrito {

    private $contenido;
    private $sesion;

    public function __construct($parametros = NULL) {
	if (!is_null($parametros)) {
	    $this->sesion = $parametros['session'];
	    if ($parametros['session']->userdata('carrito')) {
		$this->contenido = $parametros['session']->userdata('carrito');
	    } else {
		$this->contenido = [];
	    }
	} else {
	    $this->contenido = [];
	}
    }

    public function getContenido() {
	return $this->contenido;
    }

    public function setContenido(array $producto) {
	array_push($this->contenido, $producto);

	$this->actualizarSesion();
    }

    public function actualizarSesion() {
	$this->sesion->set_userdata(["carrito" => $this->contenido]);
    }
    
    public function vaciarCarrito() {
        $this->contenido = [];
        $this->actualizarSesion();
    }

}
