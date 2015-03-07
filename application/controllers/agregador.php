<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(APPPATH . '/libraries/JSON_WebServer_Controller.php');

class Agregador extends JSON_WebServer_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->RegisterFunction('Total()', 'Devuelve el número de elementos que tenemos en la tienda');
        $this->RegisterFunction('Lista(offset, limit)', 'Devuelve una lista de productos de tamaño máximo [limit] comenzando desde la posición desde [offset]');
    }

    public function Total() {
        return $this->productos_model->num_total_destacados();
    }

    public function Lista($offset, $limit) {
        $destacados = $this->productos_model->listar_destacados(NULL, $offset, $limit);
        
        foreach ($destacados as $d) {
            $lista[] = [
                'nombre' => $d->nombre,
                'descripcion' => $d->descripcion,
                'precio' => $d->precio,
                'img' => base_url() . 'assets/img/productos/' . $d->imagen,
                'url' => site_url('/home/ver_producto/' . $d->id)
            ];
        }
        return $lista;
    }
}
