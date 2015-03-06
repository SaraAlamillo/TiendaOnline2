<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Xml extends Sara {

    public function __construct() {
        parent::__construct();
    }

    public function exportar() {
        $this->load->helper('download');

        $categorias = $this->productos_model->listar_categorias();

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
                . "<categorias>\n";
        foreach ($categorias as $c) {
            $xml .= "<categoria>\n"
                    . "<id>{$c->id}</id>\n"
                    . "<nombre>{$c->nombre}</nombre>\n";
            $productos = $this->productos_model->listar_productos($c->id);
            if (count($productos) != 0) {
                $xml .= "<productos>\n";
                foreach ($productos as $p) {
                    $xml .= "<producto>\n";
                    foreach ($p as $key => $value) {
                        $value = str_replace("<", "&lt;", $value);
                        $value = str_replace(">", "&gt;", $value);
                        $xml .= "<$key>$value</$key>\n";
                    }
                    $xml .= "</producto>\n";
                }
                $xml .= "</productos>\n";
            } else {
                $xml .= "<productos />\n";
            }
            $xml .= "</categoria>\n";
        }
        $xml .= "</categorias>";

        $nombre_fichero = "datos_tienda_" . time() . ".xml";

        force_download($nombre_fichero, $xml);
    }

    public function subir_fichero() {
        $ruta = APPPATH . "tmp/";
        $config['upload_path'] = $ruta;
        $config['allowed_types'] = 'xml';
        $this->load->library('upload', $config);
        $parametros = [];
        if ($this->upload->do_upload()) {
            $fichero = $this->upload->data()["file_name"];
            $this->importar($fichero, $ruta);
            exit();
        } else {
            $parametros['error'] = $this->upload->display_errors();
        }
        $this->load->view('importar_xml', $parametros);
    }

    public function importar($fichero, $ruta) {
        $categorias = simplexml_load_file($ruta . $fichero);
        echo "<pre>";
        print_r($categorias);
        echo "</pre>";
        /*foreach ($categorias as $c) {
            $this->productos_model->insertar_categoria($c);
            /* foreach ($c->productos->producto as $p) {
              unset($p->id);

              $p->descripcion = utf8_decode($p->descripcion);

              $p->categoria = $c->id;

              $this->productos_model->insertar_productos($p);
              } */
        //}
        unlink($ruta . $fichero);
        echo "lista";
    }

}
