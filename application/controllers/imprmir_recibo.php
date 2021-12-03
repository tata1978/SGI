<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

	class Recibo extends CI_Controller{	

    public function __construct() {
        parent::__construct();
        $this->load->model('pdfs_model');
    }
		public function index(){			
			$this->_example_output((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));	
		}
}		