<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }
    
    

    public function index() {
        $parametrosContenido = [
            "productos" => $this->productos_model->listar_destacados(),
            "error" => $this->session->flashdata("mensaje")
        ];
        $contenido = $this->load->view("productos", $parametrosContenido, TRUE);
        
        $this->vista($contenido);

    }

    public function ver_categoria($categoria = NULL) {
        $parametrosContenido = [
            "productos" => $this->productos_model->listar_productos($categoria),
            "error" => $this->session->flashdata("mensaje")
        ];

        $contenido = $this->load->view("productos", $parametrosContenido, TRUE);
        
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

    public function tramitar_compra() {
        $pedido = $this->pedidos_model->crear_pedido($this->session->userdata('usuario'));
        $this->pedidos_model->agregar_productos($pedido, $this->carrito->get_contenido());
        $this->carrito->vaciar_carrito();
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

    public function generar_factura($id_pedido) {
        ob_clean();
        $pedido = $this->pedidos_model->listar_pedido($id_pedido);
        $lineas_pedido = $this->pedidos_model->listar_productos_pedido($id_pedido);

        $this->Factura = new Factura($pedido);
        $this->Factura->AddPage();
        $this->Factura->AliasNbPages();

        $this->Factura->SetTitle("Factura " . $pedido->id);
        $this->Factura->SetLeftMargin(15);
        $this->Factura->SetRightMargin(15);
        $this->Factura->SetFillColor(200, 200, 200);

        $this->Factura->SetFont('Arial', 'B', 9);

        $x = 1;
        $subtotal = 0;
        $iva = 0;
        foreach ($lineas_pedido as $l) {
            $this->Factura->Cell(15, 7, $x++, 'BL', 0, 'C', '0');
            $this->Factura->Cell(85, 7, $l->nombre, 'B', 0, 'C', '0');
            $this->Factura->Cell(20, 7, $l->precio . iconv('UTF-8', 'windows-1252', " €"), 'B', 0, 'C', '0');
            $this->Factura->Cell(20, 7, $l->cantidad, 'B', 0, 'C', '0');
            $this->Factura->Cell(20, 7, $l->descuento . iconv('UTF-8', 'windows-1252', "%"), 'B', 0, 'C', '0');
            $total = ($l->precio * $l->cantidad - ($l->precio * $l->cantidad * ($l->descuento / 100)));
            $subtotal += $total;
            $this->Factura->Cell(20, 7, round($total, 2) . iconv('UTF-8', 'windows-1252', " €"), 'BR', 0, 'C', '0');
            $this->Factura->Ln(7);

            $iva += $total * ($l->iva / 100);
        }
        $this->Factura->Ln(7);
        $this->Factura->setX(155);
        $this->Factura->Cell(20, 7, "Subtotal", '', 0, 'R', '1');
        $this->Factura->Cell(20, 7, round($subtotal, 2) . iconv('UTF-8', 'windows-1252', " €"), 'B', 1, 'C', '0');
        $this->Factura->setX(155);
        $this->Factura->Cell(20, 7, "IVA", 'T', 0, 'R', '1');
        $this->Factura->Cell(20, 7, round($iva, 2) . iconv('UTF-8', 'windows-1252', " €"), 'B', 1, 'C', '0');
        $this->Factura->setX(155);
        $this->Factura->Cell(20, 7, "Total", 'TB', 0, 'R', '1');
        $this->Factura->Cell(20, 7, round($subtotal + $iva, 2) . iconv('UTF-8', 'windows-1252', " €"), 'B', 1, 'C', '0');



        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->Factura->Output("Lista de alumnos.pdf", 'I');
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
