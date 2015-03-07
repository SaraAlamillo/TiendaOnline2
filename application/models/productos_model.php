<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'controllers/home.php';

/**
 * Description of modelo
 *
 * @author Sara Alamillo Arroyo
 */
class Productos_model extends CI_Model {

    /**
     * Devuelve el listado de todas las categorías.
     * @return object Listado de todos los datos de las categorías.
     */
    public function listar_categorias() {
        $resultado = $this->db->get("categoria");
        return $resultado->result();
    }

    public function listar_categoria($id) {
        $this->db->where("id", $id);
        $resultado = $this->db->get("categoria");
        return $resultado->row();
    }

    public function listar_producto($id) {
        $this->db->where("id", $id);
        $resultado = $this->db->get("producto");
        $resultado->row()->categoria_nombre = $this->listar_categoria($resultado->row()->categoria)->nombre;
        return $resultado->row();
    }

    /**
     * Devuelve un listado de productos. Si se pasa la categoría, se devuelven los productos de dicha categoría; en caso de no haber categoría, se devuelven todos los productos de la base de datos.
     * @param int $categoria Identificador de la categoría de la que se devolverán los productos.
     * @return object Listado de todos los datos de los productos.
     */
    public function listar_productos($categoria = NULL, $pagina = 0, $paginacion = NULL) {
        if (!is_null($categoria)) {
            $this->db->where("categoria", $categoria);
        }
        if (is_null($paginacion)) {
            $resultado = $this->db->get("producto");
        } else {
            $resultado = $this->db->get("producto", Home::maxPorPagina, $pagina);
        }

        return $resultado->result();
    }

    public function num_total_productos($categoria = NULL) {
        if (!is_null($categoria)) {
            $this->db->where("categoria", $categoria);
        }

        $resultado = $this->db->get("producto");
        return $resultado->num_rows();
    }

    /**
     * Devuelve un listado de productos destacados. Si se pasa la categoría, se devuelven los productos destacados de dicha categoría; en caso de no haber categoría, se devuelven todos los productos destacados de la base de datos.
     * @param int $categoria Identificador de la categoría de la que se devolverán los productos destacados.
     * @return object Listado de todos los datos de los productos destacados.
     */
    public function listar_destacados($categoria = NULL, $pagina = 0, $maximo_pagina = Home::maxPorPagina) {
        $this->db->limit($maximo_pagina, $pagina);

        $intervalo = [
            "fecha_inicio <" => date("Y-m-d H:i:s"),
            "fecha_fin >" => date("Y-m-d H:i:s")
        ];
        $this->db->from('producto');
        $this->db->join('destacado', 'producto.id = destacado.producto');
        $this->db->where($intervalo);
        if (!is_null($categoria)) {
            $this->db->where("producto.categoria", $categoria);
        }
        $resultado = $this->db->get();
        return $resultado->result();
    }

    public function num_total_destacados($categoria = NULL) {
        if (!is_null($categoria)) {
            $this->db->where("categoria", $categoria);
        }

        $resultado = $this->db->get("destacado");
        return $resultado->num_rows();
    }

    /**
     * Devuelve el stock existente de un producto
     * @param integer $id Identificador del producto
     * @return integer Número de elementos que hay del producto
     */
    public function obtener_stock($id) {
        $this->db->select("stock");
        $this->db->where("id", $id);
        $resultado = $this->db->get("producto");
        return $resultado->row()->stock;
    }

    /**
     * 
     * @param integer $id Identificador del producto
     * @param string $operacion Tipo de modificación: + para incremento, - para decremento
     * @param integer $cantidad Cantidad para modificar el stock
     * @return boolean Devuelve el resultado de la operación: TRUE si ha ido todo correcto y FALSE en caso contrario
     */
    public function modificar_stock($id, $operacion, $cantidad) {
        if ($operacion == "+") {
            $nuevoStock = $this->obtener_stock($id) + $cantidad;
        } elseif ($operacion == "-") {
            $nuevoStock = $this->obtener_stock($id) - $cantidad;
        } else {
            return FALSE;
        }

        $this->db->where("id", $id);
        $this->db->update("producto", ["stock" => $nuevoStock]);

        if ($this->obtener_stock($id) == $nuevoStock) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function insertar_categoria($datos) {
        $claves = [];
        $valores = [];
        foreach ($datos as $key => $value) {
            if ($key == "codigo" || $key == "nombre" || $key == "descripcion" || $key == "anuncio") {
                $value = "'$value'";
            }
            array_push($claves, $key);
            array_push($valores, $value);
        }
        $claves = implode(", ", $claves);
        $valores = implode(", ", $valores);

        $this->db->query("insert into categoria ($claves) values ($valores)");

        $this->db->select_max('id');
        $resultado = $this->db->get("categoria");
        return $resultado->row()->id;
    }

    public function insertar_productos($datos) {
        $claves = [];
        $valores = [];
        foreach ($datos as $key => $value) {
            if ($key == "codigo" || $key == "nombre" || $key == "imagen" || $key == "descripcion" || $key == "anuncio") {
                $value = "'$value'";
            }
            array_push($claves, $key);
            array_push($valores, $value);
        }
        $claves = implode(", ", $claves);
        $valores = implode(", ", $valores);

        $this->db->query("insert into producto ($claves) values ($valores)");
    }

}
