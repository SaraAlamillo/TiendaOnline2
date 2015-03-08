<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Home extends Sara {

    const maxPorPagina = 5;

    public function index($pagina = 0) {
        $vista = [
            "productos" => $this->productos_model->listar_destacados(NULL, $pagina),
            "error" => $this->session->flashdata("mensaje"),
            "paginador" => $this->paginar(site_url("home/index/"), $this->productos_model->num_total_destacados(), 3)
        ];

        $this->vista("productos", $vista);
    }

    public function paginar($url, $total, $segmento = 4) {
        $config['base_url'] = $url;
        $config['per_page'] = self::maxPorPagina;
        $config['total_rows'] = $total;
        $config['uri_segment'] = $segmento;
        $config['first_link'] = 'Primero';
        $config['last_link'] = 'Último';

        $this->pagination->initialize($config);

        return $this->pagination->create_links();
    }

    public function ver_categoria($categoria = NULL, $pagina = 0) {
        $vista = [
            "productos" => $this->productos_model->listar_productos($categoria, $pagina, TRUE),
            "error" => $this->session->flashdata("mensaje"),
            "paginador" => $this->paginar(site_url("home/ver_categoria/" . $categoria . "/"), $this->productos_model->num_total_productos($categoria))
        ];

        $this->vista("productos", $vista);
    }

    public function ver_producto($producto) {
        $vista = [
            "producto" => $this->productos_model->listar_producto($producto),
            "error" => $this->session->flashdata("mensaje")
        ];

        $this->vista("ver_producto", $vista);
    }

}
