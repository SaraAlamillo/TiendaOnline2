<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Usuarios extends Sara {

    public function __construct() {
        parent::__construct();
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
    }
    
    public function acceder() {
        if ($this->usuarios_model->existe_usuario($this->input->post('usuario'), $this->input->post('clave'))) {
            $id = $this->usuarios_model->conseguir_id("usuario", $this->input->post('usuario'));
            $this->session->set_userdata('usuario', $id);
            redirect($this->input->post('url'));
        } else {
            $this->session->set_flashdata("login", "Login incorrecto.");
            redirect($this->input->post('url'));
        }
    }

    public function salir() {
        $this->session->unset_userdata('usuario');
        redirect(site_url());
    }

    public function registro() {

        $this->form_validation->set_rules('usuario', 'usuario', 'callback_usuario_check');
        if ($this->input->post()) {


            if ($this->form_validation->run()) {
                $this->usuarios_model->dar_de_alta($this->input->post());
                $id = $this->usuarios_model->conseguir_id("usuario", $this->input->post('usuario'));
                $this->session->set_userdata('usuario', $id);
                redirect(site_url());
            }
        }

        $vista = [
            "provincias" => $this->usuarios_model->listar_provincias()
        ];

        $this->vista("registro", $vista);
    }

    public function modificacion() {
        $this->form_validation->set_rules('usuario', 'usuario', 'required');
        $datos = $this->usuarios_model->listar_usuario($this->session->userdata('usuario'));
        if ($this->input->post()) {
            if ($this->form_validation->run()) {
                $datos_nuevos = [];
                $campos_actualizados = [];
                foreach ($this->input->post() as $key => $value) {
                    if ($value != $datos->$key) {
                        $datos_nuevos[$key] = $value;
                        array_push($campos_actualizados, $key);
                    }
                }
                if (!empty($datos_nuevos)) {
                    $this->usuarios_model->actualizar_datos($this->session->userdata('usuario'), $datos_nuevos);
                    $this->session->set_flashdata("mensaje", "Se han actualizado correctamente: " . implode(", ", $campos_actualizados));
                }
                redirect(site_url("usuarios/modificacion"));
            }
        }

        $vista = [
            "provincias" => $this->usuarios_model->listar_provincias(),
            "datos" => $datos,
            "mensaje" => $this->session->flashdata("mensaje")
        ];


        $this->vista("modificacion", $vista);
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

    public function quitar_usuario() {
        $this->usuarios_model->actualizar_datos($this->session->userdata('usuario'), ['activo' => 0]);
        $this->salir();
    }

}
