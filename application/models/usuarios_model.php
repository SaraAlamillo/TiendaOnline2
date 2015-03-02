<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of modelo
 *
 * @author Sara Alamillo Arroyo
 */
class Usuarios_model extends CI_Model {

    /**
     * Devuelve un listado con todas las provincias
     * @return object Listado con todos los datos de las provincias
     */
    public function listar_provincias() {
        $resultado = $this->db->get("provincia");
        return $resultado->result();
    }
    
    public function nombre_provincia($id) {
        $this->db->select("nombre");
        $this->db->where("id", $id);
        $resultado = $this->db->get("provincia");
        return $resultado->row()->nombre;
    }

    /**
     * Devuelve si un usuario está recogido en la base de datos. Se puede buscar por su nombre o su nombre y contraseña.
     * @param string $nombre Nombre del usuario
     * @param string $clave Contraseña del usuario
     * @return boolean Devuelve TRUE si existe el usuario y FALSE en caso contrario
     */
    public function existe_usuario($nombre, $clave = NULL) {
        $datos = ['usuario' => $nombre];
        if (!is_null($clave)) {
            $datos['contrasenia'] = $clave;
        }
        $datos['activo'] = 1;
        $this->db->where($datos);
        $resultado = $this->db->get("usuario");
        if ($resultado->result()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Comprueba si un nombre de usuario está siendo utilizado por otro
     * @param string $nombre Nombre a buscar
     */
    public function nombre_libre($nombre) {
        $this->db->where('usuario', $nombre);
        $resultado = $this->db->get("usuario");

        if ($resultado->result()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Devuelve todos los datos de un usuario determinado
     * @param integer $id Identificador del usuario
     * @return object Datos del usuario
     */
    public function listar_usuario($id) {
        $where = [
            "id" => $id,
            "activo" => 1
        ];
        $this->db->where($where);
        $resultado = $this->db->get("usuario");
        return $resultado->row();
    }

    /**
     * Da de baja a un usuario de la tienda modificando el campo "Activo".
     * @param integer $id Identificador del usuario
     * @return boolean Devuelve TRUE si todo ha ido correctamente y FALSE en caso contrario.
     */
    public function dar_de_baja($id) {
        $this->db->where("id", $id);
        $this->db->update("usuario", ["activo" => 0]);

        $this->db->select("activo");
        $this->db->where("id", $id);
        $resultado = $this->db->get("usuario");
        if ($resultado->row()->activo == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Devuelve los datos del último usuario añadido en la base de datos
     * @return object Datos del usuario
     */
    public function ultimo_usuario() {
        $this->db->select("max(id) as id");
        $resultado = $this->db->get("usuario");
        return $this->listar_usuario($resultado->row()->id);
    }

    /**
     * Actualiza los datos de un usuario determinado
     * @param integer $id Identificador del usuario
     * @param array $datos Conjunto de datos nuevos que sustituirán a los antiguos.
     */
    public function actualizar_datos($id, $datos) {
        $this->db->where("id", $id);
        $this->db->update("usuario", $datos);
    }

    /**
     * Inserta un número usuario en la base de datos
     * @param array $datos Datos del nuevo usuario
     * @return boolean Devuelve el resultado de la operación: TRUE si todo ha ido correcto y FALSE en caso contrario
     */
    public function dar_de_alta($datos, $administrador = FALSE) {
        $datos['activo'] = 1;
        if ($administrador) {
            $datos['rol'] = 'Administrador';
        } else {
            $datos['rol'] = 'Usuario';
        }
        $this->db->insert("usuario", $datos);
        $ultimoUsuario = $this->ultimo_usuario();
        foreach ($datos as $key => $value) {
            if ($value != $ultimoUsuario->$key) {
                return FALSE;
            }
        }
        return TRUE;
    }

    public function restablecer_contraseña($id) {
        //apuntes en la agenda
    }

    public function conseguir_id($campo, $valor) {
        $this->db->select("id");
        $this->db->where($campo, $valor);
        $resultado = $this->db->get("usuario");
        return $resultado->row()->id;
    }

}
