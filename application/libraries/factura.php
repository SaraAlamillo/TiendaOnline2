<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
// Incluimos el archivo fpdf
require_once APPPATH . "/third_party/fpdf/fpdf.php";

//Extendemos la clase Pdf de la clase fpdf para que herede todas sus variables y funciones
class Factura extends FPDF {
    
    private $pedido;

    public function __construct($datosPedido = NULL) {
        parent::__construct();
        $this->AddFont('JustAnotherHand', '');
        $this->pedido = $datosPedido;
    }

    function Footer() {

        $this->SetY(-10);

        $this->SetFont('Arial', 'I', 8);

        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function Header() {
        
        $fecha_pedido = new DateTime($this->pedido->fecha_pedido);
        
        $this->SetLeftMargin(15);
        $this->SetRightMargin(15);
        $this->SetFillColor(200, 200, 200);
        $this->SetFont('JustAnotherHand', '', 50);
        $this->Image(base_url() . 'assets/img/logo.png');
        $this->SetY(15);
        $this->SetX(-80);
        $this->Cell(65, 15, 'SaraSoft S.L.U.', '', 0, 'R');
        $this->Ln(21);
        if ($this->PageNo() == 1) {
            $this->SetFont('Arial', 'I', 8);
            $this->SetY(38);
            $this->SetX(15);
            $this->Cell(80, 7, "Vendedor:", 0, 1, 'L', 0);
            $this->SetY(38);
            $this->SetX(-95);
            $this->Cell(80, 7, "Comprador:", 0, 1, 'L', 0);
            $this->SetFont('Arial', 'I', 10);
            $this->SetY(45);
            $this->SetX(15);
            $this->MultiCell(80, 7, "SaraSoft S.L.U. \nCIF A28120368", 'TBLR', 'C', '1');
            $this->SetY(45);
            $this->SetX(-95);
            $this->SetFont('Arial', 'I', 8);
            $this->SetFont('Arial', 'I', 10);
            $this->MultiCell(80, 7, "{$this->pedido->nombre} {$this->pedido->apellidos} \nDNI {$this->pedido->dni}", 'TBLR', 'C', '1');
            $this->Ln(14);
        }
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(15, 7, "Referencia: {$this->pedido->id}", '', 0, 'L', 0);
            $this->SetX(-115);
        $this->Cell(100, 7, "Fecha: " . $fecha_pedido->format("d-m-Y") . "   Hora: " . $fecha_pedido->format("H:i:s"), '', 1, 'R', 0);
        $this->Cell(15, 7, utf8_decode('Número'), 'TBL', 0, 'C', '1');
        $this->Cell(85, 7, 'Producto', 'TB', 0, 'C', '1');
        $this->Cell(20, 7, 'Precio', 'TB', 0, 'C', '1');
        $this->Cell(20, 7, 'Cantidad', 'TB', 0, 'C', '1');
        $this->Cell(20, 7, 'Descuento', 'TB', 0, 'C', '1');
        $this->Cell(20, 7, 'Total', 'TBR', 0, 'C', '1');
        $this->Ln(7);
    }
    
    public function generar($id_pedido, $para_email = FALSE) {
        ob_clean();
        
        $CI =& get_instance();
        
        $pedido = $CI->pedidos_model->listar_pedido($id_pedido);
        $lineas_pedido = $CI->pedidos_model->listar_productos_pedido($id_pedido);

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

        if ($para_email) {
            $fichero = APPPATH . "/tmp/" . "Factura " . $pedido->id . ".pdf";
            $this->Factura->Output($fichero, 'F');
            return $fichero;
        } else {
            $this->Factura->Output("Factura " . $pedido->id, 'I');
        }
    }

}
