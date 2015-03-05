<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Xml extends Sara {

    public function __construct() {
        parent::__construct();
    }

    public function exportar() {


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
        $ruta = APPPATH . "/tmp/";

        file_put_contents( $ruta . $nombre_fichero, $xml);

        header('Content-Type: application/x-download');
        header('Content-Disposition: attachment; filename="' . $nombre_fichero . '"');
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');
        
        
        unlink($ruta . $nombre_fichero);

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
