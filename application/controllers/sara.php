<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sara extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function vista($vista, $parametros) {
        $parametros['cabecera'] = $this->cabecera();

        $parametros['menu'] = $this->menu();

        $parametros['contenido'] = $this->contenido($vista, $parametros);

        $this->load->view("home", $parametros);
    }
    
    private function cabecera() {
        $parametros = [
            "logueado" => $this->logueado(),
            "login" => $this->session->flashdata("login")
                ];
        return $this->load->view("cabecera", $parametros, TRUE);
    }
    
    private function menu() {
        return $this->load->view("menu", ["categorias" => $this->productos_model->listar_categorias()], TRUE);
    }
    
    private function contenido($vista, $parametros) {
        return $this->load->view($vista, $parametros, TRUE);
    }

    public function logueado() {
        if ($this->session->userdata('usuario')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
