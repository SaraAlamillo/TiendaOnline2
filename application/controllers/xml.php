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
                        if ($key != 'descripcion') {
                            $xml .= "<$key>$value</$key>\n";
                        }
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
        $config['upload_path'] = APPPATH . "tmp/";
        $config['allowed_types'] = 'xml';
        $this->load->library('upload', $config);
        $parametros = [];
        if ($this->upload->do_upload()) {
            $fichero = $this->upload->data()["file_name"];
            $this->importar($fichero);
            exit();
        } else {
            $parametros['error'] = $this->upload->display_errors();
        }
        $this->load->view('importar_xml', $parametros);
    }

    public function importar($fichero) {
        echo $fichero;
        $categorias = simplexml_load_file(APPPATH . "tmp/" . $fichero);
        echo "<pre>";
        print_r($categorias);
        echo "</pre>";
    }

}
