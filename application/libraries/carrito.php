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

    public function ya_agregado($producto) {
        foreach ($this->contenido as $c) {
            if ($c['id'] == $producto) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function set_contenido(array $producto) {
        if ($this->ya_agregado($producto['id'])) {
            foreach ($this->contenido as &$c) {
                if ($c['id'] == $producto['id']) {
                    $c['cantidad'] += $producto['cantidad'];
                }
            }
        } else {
            array_push($this->contenido, $producto);
        }
        $this->actualizar_sesion();
    }

    public function actualizar_sesion() {
        $this->sesion->set_userdata(["carrito" => $this->contenido]);
    }

    public function vaciar_carrito() {
        $this->contenido = [];
        $this->actualizar_sesion();
    }

    public function quitar_producto($producto) {
        $cantidad = 0;
        foreach ($this->contenido as $key => $c) {
            if ($c['id'] == $producto) {
                $cantidad += $c['cantidad'];
                unset($this->contenido[$key]);
                break;
            }
        }

        $this->actualizar_sesion();
        return $cantidad;
    }

}
