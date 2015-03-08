<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once __DIR__ . '/sara.php';

class Compra extends Sara {

    public function agregar() {
        if ($this->input->post('cantidad') <= 0 || !is_numeric($this->input->post('cantidad'))) {
            $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => '¿Enserio?']);
        } else {
            if ($this->input->post('cantidad') <= $this->productos_model->obtener_stock($this->input->post('id'))) {
                $this->productos_model->modificar_stock($this->input->post('id'), "-", $this->input->post('cantidad'));
                $this->carrito->set_contenido([
                    "id" => $this->input->post('id'),
                    "cantidad" => $this->input->post('cantidad')
                ]);
                $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => 'Añadido al carrito']);
            } else {
                $this->session->set_flashdata("mensaje", ['id' => $this->input->post('id'), 'mensaje' => 'No hay suficiente stock']);
            }
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

        $vista = [
            "productos" => $carrito,
            "logueado" => $this->logueado(),
            "mensaje" => $this->session->flashdata("mensaje")
        ];

        $this->vista("carrito", $vista);
    }
    public function vaciar_carrito() {
        foreach ($this->carrito->get_contenido() as $c) {
            $cantidad = $this->carrito->quitar_producto($c['id']);
            $this->productos_model->modificar_stock($c['id'], "+", $cantidad);
        }
        redirect(site_url("compra/consultar_carrito"));
    }
    public function eliminar_producto_carrito($producto) {
        $cantidad = $this->carrito->quitar_producto($producto);
        $this->productos_model->modificar_stock($producto, "+", $cantidad);
        redirect(site_url("compra/consultar_carrito"));
    }

    function confirmar_productos() {
        $carrito = $this->carrito->get_contenido();

        if (count($carrito) <= 0) {
            $this->session->set_flashdata("mensaje", "No sé si te has dado cuenta, pero está vacío...");
            redirect(site_url("compra/consultar_carrito"));
        } else {

            foreach ($carrito as &$c) {
                $datos = $this->productos_model->listar_producto($c['id']);
                $c['nombre'] = $datos->nombre;
                $c['precio'] = $datos->precio;
            }

            $vista = [
                "productos" => $carrito
            ];

            $this->vista("realizar_compra/confirmacion_productos", $vista);
        }
    }

    function confirmar_usuario() {
        $vista = [
            "usuario" => $this->usuarios_model->listar_usuario($this->session->userdata('usuario'))
        ];
        $vista["usuario"]->provincia = $this->usuarios_model->nombre_provincia($vista["usuario"]->provincia);

        $this->vista("realizar_compra/confirmacion_usuario", $vista);
    }

    function realizar_pedido() {
        $pedido = $this->pedidos_model->crear_pedido($this->session->userdata('usuario'));
        $this->pedidos_model->agregar_productos($pedido, $this->carrito->get_contenido());
        $this->carrito->vaciar_carrito();

        $vista = [
            "pedido" => $pedido
        ];

        $this->vista("realizar_compra/pedido_realizado", $vista);
    }

    function mensaje_final() {
        $this->vista("realizar_compra/mensaje_final", '');
    }

    function enviar_detalle($pedido) {
        $email = $this->usuarios_model->listar_usuario($this->session->userdata('usuario'))->email;

        $parametrosContenido = [
            "contenido" => $this->pedidos_model->listar_productos_pedido($pedido)
        ];

        $mensaje = $this->load->view("contenido_pedido", $parametrosContenido, TRUE);

        $this->email($pedido, $email, $mensaje);


        redirect(site_url("compra/mensaje_final"));
    }

    function enviar_pdf($pedido) {
        $factura = $this->factura->generar($pedido, TRUE);
        $email = $this->usuarios_model->listar_usuario($this->session->userdata('usuario'))->email;

        $mensaje = '<html><body><p>Adjunto a este correo electrónico podrá encontrar la factura del pedido ' . $pedido . '</body></html>';

        $this->email($pedido, $email, $mensaje, $factura);

        unlink($factura);

        redirect(site_url("compra/mensaje_final"));
    }

    function email($pedido, $destinatario, $mensaje, $adjunto = NULL) {
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'mail.iessansebastian.com';
        $config['smtp_user'] = 'aula4@iessansebastian.com';
        $config['smtp_pass'] = 'daw2alumno';
        $config['mailtype'] = 'html';

        $this->email->initialize($config);

        $this->email->from('pedidos@tiendaonline.com', 'Departamento de pedidos');
        $this->email->to($destinatario);

        $this->email->subject('Pedido ' . $pedido);

        $this->email->message($mensaje);

        if (!is_null($adjunto)) {
            $this->email->attach($adjunto);
        }

        return $this->email->send();
    }

}
