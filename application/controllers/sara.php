<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sara extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }

    public function vista($contenido) {
        $cabecera = [
            "logueado" => $this->logueado(),
            "login" => $this->session->flashdata("login")
                ];
        $parametros['cabecera'] = $this->load->view("cabecera", $cabecera, TRUE);

        $parametros['menu'] = $this->load->view("menu", ["categorias" => $this->productos_model->listar_categorias()], TRUE);

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
