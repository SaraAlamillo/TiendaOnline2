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
            $xml .= "<categoria>\n";
            foreach ($c as $key => $value) {
                $xml .= "<$key>$value</$key>\n";
            }
            $productos = $this->productos_model->listar_productos($c->id);
            if (count($productos) != 0) {
                $xml .= "<productos>\n";
                foreach ($productos as $p) {
                    $xml .= "<producto>\n";
                    foreach ($p as $key => $value) {
                        $xml .= "<$key>" . htmlspecialchars($value) . "</$key>\n";
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
        $vista = [
            "subido" => $this->session->flashdata("subido") != ""? $this->session->flashdata("subido") : NULL
        ];
        if ($this->upload->do_upload()) {
            $fichero = $this->upload->data()["file_name"];
            $this->importar($fichero, $ruta);
            exit();
        } else {
            $vista['error'] = $this->upload->display_errors();
        }
        $this->vista('importar_xml', $vista);
    }

    public function importar($fichero, $ruta) {
        $categorias = json_decode(json_encode((array) simplexml_load_file($ruta . $fichero)), 1);

        foreach ($categorias['categoria'] as &$c) {
            if (empty($c['anuncio'])) {
                        $c['anuncio'] = NULL;
                    } else {
                        $c['anuncio'] = htmlentities($c['anuncio']);
                    }
                    $c['nombre'] = htmlentities($c['nombre']);
                    $c['descripcion'] = htmlentities($c['descripcion']);
            $productos = $c['productos'];
            unset($c['productos']);
            unset($c['id']);
            
            $categoria = $this->productos_model->insertar_categoria($c);
            
            if (!empty($productos)) {
                foreach ($productos['producto'] as &$p) {
                    unset($p['id']);
                    if (empty($p['anuncio'])) {
                        $p['anuncio'] = NULL;
                    } else {
                        $p['anuncio'] = htmlentities($p['anuncio']);
                    }
                    $p['nombre'] = htmlentities($p['nombre']);
                    $p['descripcion'] = htmlentities($p['descripcion']);

                    $p['categoria'] = $categoria;
                   
                    $this->productos_model->insertar_productos($p);
                }
            }
        }
        unlink($ruta . $fichero);
        $this->session->set_flashdata("subido", "Se ha importado correctamente los datos");
        redirect(site_url("xml/subir_fichero"));
    }

}
