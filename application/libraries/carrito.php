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

    public function get_contenido() {
	return $this->contenido;
    }

    public function set_contenido(array $producto) {
	array_push($this->contenido, $producto);

	$this->actualizar_sesion();
    }

    public function actualizar_sesion() {
	$this->sesion->set_userdata(["carrito" => $this->contenido]);
    }
    
    public function vaciar_carrito() {
        $this->contenido = [];
        $this->actualizar_sesion();
    }

}
