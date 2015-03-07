<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Home extends Sara {

    const maxPorPagina = 5;

    public function __construct() {
        parent::__construct();
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

    public function index($pagina = 0) {
        $parametrosContenido = [
            "productos" => $this->productos_model->listar_destacados(NULL, $pagina),
            "error" => $this->session->flashdata("mensaje"),
            "paginador" => $this->paginar(site_url("home/index/"), $this->productos_model->num_total_destacados(), 3)
        ];
        $contenido = $this->load->view("productos", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }

    public function ver_categoria($categoria = NULL, $pagina = 0) {
        $parametrosContenido = [
            "productos" => $this->productos_model->listar_productos($categoria, $pagina, TRUE),
            "error" => $this->session->flashdata("mensaje"),
            "paginador" => $this->paginar(site_url("home/ver_categoria/" . $categoria . "/"), $this->productos_model->num_total_productos($categoria))
        ];

        $contenido = $this->load->view("productos", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }
    public function ver_producto($producto) {
        $parametrosContenido = [
            "producto" => $this->productos_model->listar_producto($producto),
            "error" => $this->session->flashdata("mensaje")
        ];

        $contenido = $this->load->view("ver_producto", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }

    public function comprar() {
        if ($this->input->post('cantidad') <= $this->productos_model->obtener_stock($this->input->post('id'))) {
            $this->productos_model->modificar_stock($this->input->post('id'), "-", $this->input->post('cantidad'));
            $this->carrito->set_contenido([
                "id" => $this->input->post('id'),
                "cantidad" => $this->input->post('cantidad')
            ]);
        } else {
            $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => 'No hay suficiente stock']);
        }
        redirect($this->input->post('url'));
    }

    public function consultar_carrito() {
        $carrito = $this->carrito->get_contenido();

        foreach ($carrito as &$c) {
            $datos = $this->productos_model->listar_producto($c['id']);
            $c['nombre'] = $datos->nombre;
            $c['precio'] = $datos->precio;
        }

        $parametrosContenido = [
            "productos" => $carrito,
            "logueado" => $this->logueado(),
            "mensaje" => $this->session->flashdata("mensaje")
        ];
        $contenido = $this->load->view("carrito", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }

    public function acceder() {
        if ($this->usuarios_model->existe_usuario($this->input->post('usuario'), $this->input->post('clave'))) {
            $id = $this->usuarios_model->conseguir_id("usuario", $this->input->post('usuario'));
            $this->session->set_userdata('usuario', $id);
            redirect(site_url());
        } else {
            redirect(site_url());
        }
    }

    public function cerrar_sesion() {
        $this->session->unset_userdata('usuario');
        redirect(site_url());
    }

    public function consultar_pedidos() {
        $pedidos = $this->pedidos_model->listar_pedidos($this->session->userdata('usuario'));

        foreach ($pedidos as &$p) {
            $p->total = $this->pedidos_model->total_pedido($p->id);
        }

        $parametrosContenido = [
            "pedidos" => $pedidos,
            "mensaje" => $this->session->flashdata("mensaje")
        ];

        $contenido = $this->load->view("pedidos", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }

    public function consultar_pedido($pedido) {
        $parametrosContenido = [
            "contenido" => $this->pedidos_model->listar_productos_pedido($pedido)
        ];

        $contenido = $this->load->view("contenido_pedido", $parametrosContenido, TRUE);

        $this->vista($contenido);
    }

    public function eliminar_producto_carrito($producto) {
        $cantidad = $this->carrito->quitar_producto($producto);
        $this->productos_model->modificar_stock($producto, "+", $cantidad);
        redirect(site_url("home/consultar_carrito"));
    }

    public function vaciar_carrito() {
        foreach ($this->carrito->get_contenido() as $c) {
            $cantidad = $this->carrito->quitar_producto($c['id']);
            $this->productos_model->modificar_stock($c['id'], "+", $cantidad);
        }
        redirect(site_url("home/consultar_carrito"));
    }

    public function generar_factura($pedido) {
        $this->factura->generar($pedido);
    }

    public function cancelar_pedido($pedido, $estado) {
        if ($estado == 'Pendiente') {
            $this->pedidos_model->actualizar_estado($pedido, 'Cancelado');
        } else {
            $this->session->set_flashdata("mensaje", "Si un pedido ya ha sido procesado, no se puede cancelar.");
        }
        $this->consultar_pedidos();
    }

}
