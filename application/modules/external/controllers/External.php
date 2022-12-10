<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class External extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("general_model");
    }
	
	/**
	 * Vista del usuarios
     * @since 2/6/2021
     * @author BMOTTAG
	 */
	public function proceso($view)
	{
			$arrParam = array('codigo' => $view);
			$data['infoProcesos'] = $this->general_model->get_procesos_info($arrParam);
			$data['listaTemas'] = $this->general_model->get_temas($arrParam);
			$this->load->view('procesos', $data);
	}
	
	
}