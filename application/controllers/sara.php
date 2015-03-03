<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sara extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }

    public function vista($contenido) {
        $parametros['cabecera'] = $this->load->view("cabecera", "", TRUE);

        $parametrosMenu = [
            "categorias" => $this->productos_model->listar_categorias(),
            "logueado" => $this->logueado()
        ];
        $parametros['menu'] = $this->load->view("menu", $parametrosMenu, TRUE);

        $parametros['contenido'] = $contenido;

        $this->load->view("home", $parametros);
    }

    public function logueado() {
        if ($this->session->userdata('usuario')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}