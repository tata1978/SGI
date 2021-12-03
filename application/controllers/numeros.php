<?php

/**
 * Ejemplo en CodeIgniter 2 para convertir números a letras
 *
 * @author Ultiminio Ramos Galán <contacto@ultiminioramos.com>
 */
class numeros extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('numeros');
        $this->load->view('v_numeros');
    }

    public function index()
    {
        $this->load->helper('numeros');

        $this->load->view('v_numeros');
    }

    public function convertir($cantidad)
    {
        $cantidad = trim($this->input->post('cantidad'));

        if (empty($cantidad)) {
            echo json_encode(array('leyenda' => 'Debe introducir una cantidad.'));
            
            return;
        }
        
        # verificar si el número no tiene caracteres no númericos, con excepción
        # del punto decimal
        $xcantidad = str_replace('.', '', $cantidad);
        
        if (FALSE === ctype_digit($xcantidad)){
            echo json_encode(array('leyenda' => 'La cantidad introducida no es válida.'));
            
            return;
        }

        # procedemos a covertir la cantidad en letras
        $this->load->helper('numeros');
        $response = array(
            'leyenda' => num_to_letras($cantidad)
            , 'cantidad' => $cantidad
            );
        echo json_encode($response);
    }

}
/* EOF */