<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Xml extends Sara {

    public function __construct() {
        parent::__construct();
    }

    public function exportar() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('ruta', 'usuario', 'callback_ruta_check');

            if ($this->form_validation->run()) {
                $categorias = $this->productos_model->listar_categorias();

                $xml = "<?xml version=\"1.0\" ?>\n"
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

                $nombre_fichero = "datos_tienda_" . date("dmY") . ".xml";

                file_put_contents($this->input->post('ruta') . $nombre_fichero, $xml);
                
                $mensaje = "Se ha exportado correctamente los productos de la tienda";
            }
        }
        
        $contenido = $this->load->view("exportar_xml", "", TRUE);
        
        
        $this->vista($contenido);
        
    }

    public function importar() {
        
    }

    public function ruta_check($input) {
        if (file_exists($input)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('ruta_check', 'No existe la ruta proporcionada.');
            return FALSE;
        }
    }

}
