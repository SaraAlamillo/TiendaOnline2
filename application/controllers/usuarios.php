<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function registro() {

        if ($this->input->post()) {
            $this->form_validation->set_message('required', 'El campo %s no puede estar vacío');
            $this->form_validation->set_message('valid_email', 'El campo %s debe tener una dirección válida');

            $this->form_validation->set_rules('usuario', 'usuario', 'callback_usuario_check');
            $this->form_validation->set_rules('contrasenia', 'contraseña', 'required');
            $this->form_validation->set_rules('email', 'correo electrónico', 'required|valid_email');
            $this->form_validation->set_rules('nombre', 'nombre', 'callback_nombre_check');
            $this->form_validation->set_rules('apellidos', 'apellidos', 'callback_nombre_check');
            $this->form_validation->set_rules('dni', 'DNI', 'callback_dni_check');
            $this->form_validation->set_rules('direccion', 'dirección', 'callback_direccion_check');
            $this->form_validation->set_rules('cp', 'código postal', 'callback_cp_check');
            $this->form_validation->set_rules('provincia', 'provincia', 'callback_provincia_check');

            if ($this->form_validation->run()) {
                echo "<pre>";
                print_r($this->input->post());
                echo "</pre>";
            }
        }
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);


        $parametrosVistas['contenido'] = CargaVista("registro", ["provincias" => $this->usuarios_model->listar_provincias()]);

        $this->load->view("home", $parametrosVistas);
    }

    public function usuario_check($input) {
        if ($input == "") {
            $this->form_validation->set_message('usuario_check', 'El campo %s no puede estar vacío');
            return FALSE;
        } else if (!$this->usuarios_model->nombre_libre($input)) {
            $this->form_validation->set_message('usuario_check', "El nombre de usuario $input ya está siendo utilizado");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function nombre_check($input) {
        if (preg_match('/^[a-zA-ZüÜáéíóúÁÉÍÓÚñÑ ]+[a-zA-ZüÜáéíóúÁÉÍÓÚñÑª\. ]*$/', $input)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('nombre_check', 'El campo %s sólo puede contener letras, números y los carácteres (ª .)');
            return FALSE;
        }
    }

    public function dni_check($input) {
        if (preg_match('/^\d{8}[-]?[A-Za-z]{1}$/', $input)) {
            $dni = strtoupper($input);
            $letra = substr($dni, -1, 1);
            $numero = substr($dni, 0, 8);
            $numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);

            $modulo = $numero % 23;
            $letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
            $letra_correcta = substr($letras_validas, $modulo, 1);

            if ($letra_correcta != $letra) {
                $this->form_validation->set_message('dni_check', 'El campo %s no tiene la letra correcta');
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $this->form_validation->set_message('dni_check', 'El campo %s no es válido');
            return FALSE;
        }
    }

    public function cp_check($input) {
        if (preg_match('/^0[1-9][0-9]{3}|[1-4][0-9]{4}|5[0-2][0-9]{3}$/', $input)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('cp_check', 'El campo %s no es válido');
            return FALSE;
        }
    }

    public function provincia_check($input) {
        $provincias = $this->usuarios_model->listar_provincias();

        foreach ($provincias as $p) {
            if ($p->id == $input) {
                return TRUE;
            }
        }
        $this->form_validation->set_message('provincia_check', 'El campo %s no es válido');
        return FALSE;
    }

    public function direccion_check($input) {
        if (preg_match('/^[a-zA-Z0-9üÜáéíóúÁÉÍÓÚñÑ ]+[a-zA-Z0-9 üÜáéíóúÁÉÍÓÚñÑºª\/.-]*$/', $input)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('direccion_check', 'El campo %s sólo puede contener letras, números y los carácteres (º ª / . -)');
            return FALSE;
        }
    }

    public function logueado() {
        if ($this->session->userdata('usuario')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
