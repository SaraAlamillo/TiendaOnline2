<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("carrito", ["session" => $this->session]);
    }

    public function index() {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $parametrosVistas['contenido'] = CargaVista("productos", [
            "productos" => $this->productos_model->listar_destacados(),
            "error" => $this->session->flashdata("mensaje")
        ]);

        $this->load->view("home", $parametrosVistas);
    }

    public function ver_categoria($categoria = NULL) {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $parametrosVistas['contenido'] = CargaVista("productos", [
            "productos" => $this->productos_model->listar_productos($categoria),
            "error" => $this->session->flashdata("mensaje")
        ]);

        $this->load->view("home", $parametrosVistas);
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
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $carrito = $this->carrito->get_contenido();
        foreach ($carrito as &$c) {
            $datos = $this->productos_model->listar_producto($c['id']);
            $c['nombre'] = $datos->nombre;
            $c['precio'] = $datos->precio;
        }
        $parametrosVistas['contenido'] = CargaVista("carrito", ["productos" => $carrito, "logueado" => $this->logueado()]);

        $this->load->view("home", $parametrosVistas);
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

    public function logueado() {
        if ($this->session->userdata('usuario')) {
            return TRUE;
        } else {
            return FALSE;
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
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $pedidos = $this->pedidos_model->listar_pedidos($this->session->userdata('usuario'));
        foreach ($pedidos as &$p) {
            $p->total = $this->pedidos_model->total_pedido($p->id);
        }
        $parametrosVistas['contenido'] = CargaVista("pedidos", ["pedidos" => $pedidos]);

        $this->load->view("home", $parametrosVistas);
    }

    public function consultar_pedido($pedido) {
        $parametrosVistas['cabecera'] = CargaVista("cabecera");
        $parametrosVistas['menu'] = CargaVista("menu", ["categorias" => $this->productos_model->listar_categorias(), "logueado" => $this->logueado()]);

        $contenidoPedido = $this->pedidos_model->listar_productos_pedido($pedido);

        $parametrosVistas['contenido'] = CargaVista("contenido_pedido", ["contenido" => $contenidoPedido]);

        $this->load->view("home", $parametrosVistas);
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

        // Creacion del PDF

        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $this->pdf = new Pdf();
        // Agregamos una página
        $this->pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $this->pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $this->pdf->SetTitle("Factura " . $pedido->id);
        $this->pdf->SetLeftMargin(15);
        $this->pdf->SetRightMargin(15);
        $this->pdf->SetFillColor(200, 200, 200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);
        /*
         * TITULOS DE COLUMNAS
         *
         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
         */

        $this->pdf->Cell(15, 7, utf8_decode('Número'), 'TBL', 0, 'L', '1');
        $this->pdf->Cell(105, 7, 'Producto', 'TB', 0, 'C', '1');
        $this->pdf->Cell(20, 7, 'Precio', 'TB', 0, 'C', '1');
        $this->pdf->Cell(20, 7, 'Cantidad', 'TB', 0, 'C', '1');
        $this->pdf->Cell(20, 7, 'Total', 'TBR', 0, 'C', '1');
        $this->pdf->Ln(7);
        // La variable $x se utiliza para mostrar un número consecutivo
        $x = 1;
        foreach ($lineas_pedido as $l) {
            // se imprime el numero actual y despues se incrementa el valor de $x en uno
            $this->pdf->Cell(15, 7, $x++, 'BL', 0, 'C', '0');
            // Se imprimen los datos de cada alumno
            $this->pdf->Cell(105, 7, $l->nombre, 'B', 0, 'C', '0');
            $this->pdf->Cell(20, 7, $l->precio, 'B', 0, 'C', '0');
            $this->pdf->Cell(20, 7, $l->cantidad, 'B', 0, 'C', '0');
            $this->pdf->Cell(20, 7, $l->precio * $l->cantidad, 'BR', 0, 'C', '0');
            //Se agrega un salto de linea
            $this->pdf->Ln(7);
        }

        /*
         * Se manda el pdf al navegador
         *
         * $this->pdf->Output(nombredelarchivo, destino);
         *
         * I = Muestra el pdf en el navegador
         * D = Envia el pdf para descarga
         *
         */
        $this->pdf->Output("Lista de alumnos.pdf", 'I');
    }

}
