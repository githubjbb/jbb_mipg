<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
        $this->load->model("settings_model");
        $this->load->model("general_model");
		$this->load->helper('form');
    }
	
	/**
	 * employee List
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function employee($state)
	{			
			$data['state'] = $state;
			
			if($state == 1){
				$arrParam = array("filtroState" => TRUE);
			}else{
				$arrParam = array("state" => $state);
			}
			
			$data['info'] = $this->general_model->get_user($arrParam);
			
			$data["view"] = 'employee';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario Employee
     * @since 15/12/2016
     */
    public function cargarModalEmployee() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idEmployee"] = $this->input->post("idEmployee");	
			
			$arrParam = array("filtro" => TRUE);
			$data['roles'] = $this->general_model->get_roles($arrParam);

			if ($data["idEmployee"] != 'x') {
				$arrParam = array(
					"table" => "usuarios",
					"order" => "id_user",
					"column" => "id_user",
					"id" => $data["idEmployee"]
				);
				$data['information'] = $this->general_model->get_basic_search($arrParam);
			}
			
			$this->load->view("employee_modal", $data);
    }
	
	/**
	 * Update Employee
     * @since 15/12/2016
     * @author BMOTTAG
	 */
	public function save_employee()
	{			
			header('Content-Type: application/json');
			$data = array();
			
			$idUser = $this->input->post('hddId');

			$msj = "Se adicionó un nuevo Usuario!";
			if ($idUser != '') {
				$msj = "Se actualizó el Usuario!";
			}			

			$log_user = $this->input->post('user');
			$email_user = $this->input->post('email');
			
			$result_user = false;
			$result_email = false;
			
			//verificar si ya existe el usuario
			$arrParam = array(
				"idUser" => $idUser,
				"column" => "log_user",
				"value" => $log_user
			);
			$result_user = $this->settings_model->verifyUser($arrParam);
			
			//verificar si ya existe el correo
			$arrParam = array(
				"idUser" => $idUser,
				"column" => "email",
				"value" => $email_user
			);
			$result_email = $this->settings_model->verifyUser($arrParam);

			$data["state"] = $this->input->post('state');
			if ($idUser == '') {
				$data["state"] = 1;//para el direccionamiento del JS, cuando es usuario nuevo no se envia state
			}

			if ($result_user || $result_email)
			{
				$data["result"] = "error";
				if($result_user)
				{
					$data["mensaje"] = " Error. El Usuario ya existe.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El Usuario ya existe.');
				}
				if($result_email)
				{
					$data["mensaje"] = " Error. El correo ya existe.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El correo ya existe.');
				}
				if($result_user && $result_email)
				{
					$data["mensaje"] = " Error. El Usuario y el Correo ya existen.";
					$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> El Usuario y el Correo ya existen.');
				}
			} else {
					if ($this->settings_model->saveEmployee()) {
						$data["result"] = true;					
						$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
					} else {
						$data["result"] = "error";					
						$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
					}
			}

			echo json_encode($data);
    }
	
	/**
	 * Reset employee password
	 * Reset the password to '123456'
	 * And change the status to '0' to changue de password 
     * @since 11/1/2017
     * @author BMOTTAG
	 */
	public function resetPassword($idUser)
	{
			if ($this->settings_model->resetEmployeePassword($idUser)) {
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> You have reset the Employee pasword to: 123456');
			} else {
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}
			
			redirect("/settings/employee/",'refresh');
	}	

	/**
	 * Change password
     * @since 15/4/2017
     * @author BMOTTAG
	 */
	public function change_password($idUser)
	{
			if (empty($idUser)) {
				show_error('ERROR!!! - You are in the wrong place. The ID USER is missing.');
			}
			
			$arrParam = array(
				"table" => "usuarios",
				"order" => "id_user",
				"column" => "id_user",
				"id" => $idUser
			);
			$data['information'] = $this->general_model->get_basic_search($arrParam);
		
			$data["view"] = "form_password";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Update user´s password
	 */
	public function update_password()
	{
			$data = array();			
			
			$newPassword = $this->input->post("inputPassword");
			$confirm = $this->input->post("inputConfirm");
			$userState = $this->input->post("hddState");
			
			//Para redireccionar el usuario
			if($userState!=2){
				$userState = 1;
			}
			
			$passwd = str_replace(array("<",">","[","]","*","^","-","'","="),"",$newPassword); 
			
			$data['linkBack'] = "settings/employee/" . $userState;
			$data['titulo'] = "<i class='fa fa-unlock fa-fw'></i>CAMBIAR CONTRASEÑA";
			
			if($newPassword == $confirm)
			{					
					if ($this->settings_model->updatePassword()) {
						$data['msj'] = 'Se actualizó la contrasela del usuario.';
						$data['msj'] .= '<br>';
						$data['msj'] .= '<br><strong>Nombre Usuario: </strong>' . $this->input->post('hddUser');
						$data['msj'] .= '<br><strong>Contraseña: </strong>' . $passwd;
						$data['clase'] = 'alert-success';
					}else{
						$data['msj'] = '<strong>Error!!!</strong> Ask for help.';
						$data['clase'] = 'alert-danger';
					}
			}else{
				//definir mensaje de error
				echo "pailas no son iguales";
			}
						
			$data['view'] = "template/answer";
			$this->load->view("layout", $data);
	}
	
	/**
	 * Lista de procesos
     * @since 18/5/2021
     * @author BMOTTAG
	 */
	public function procesos()
	{
			$data['infoProcesos'] = $this->general_model->get_procesos();
			$data["view"] = 'procesos';
			$this->load->view("layout_calendar", $data);
	}
	
    /**
     * Cargo modal - formulario PROCESOS
     * @since 19/3/2021
     */
    public function cargarModalProcesos() 
	{
			header("Content-Type: text/plain; charset=utf-8"); //Para evitar problemas de acentos
			
			$data['information'] = FALSE;
			$data["idProcesoInfo"] = $this->input->post("idProcesoInfo");	
			
			if ($data["idProcesoInfo"] != 'x') {
				$arrParam = array("idProcesoInfo" => $data["idProcesoInfo"]);
				$data['information'] = $this->general_model->get_procesos_info($arrParam);
			}
			
			$this->load->view("procesos_modal", $data);
    }
	
	/**
	 * Save procesos
     * @since 19/3/2021
     * @author BMOTTAG
	 */
	public function save_procesos()
	{			
			header('Content-Type: application/json');
			$data = array();
		
			$idProcesoInfo = $this->input->post('hddId');
			
			$msj = "Se adicionó un nuevo Proceso!";
			if ($idProcesoInfo != '') {
				$msj = "Se actualizó el Proceso!";
			}

			if ($idProcesoInfo = $this->settings_model->saveProceso()) 
			{
				//guardo regitro en la tabla auditoria
				$this->settings_model->saveAuditoriaProceso();
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', '<strong>Correcto!</strong> ' . $msj);
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!</strong> Ask for help');
			}

			echo json_encode($data);	
    }

	/**
	 * Bloquear/Desbloqear procesos
     * @since 24/3/2021
     * @author BMOTTAG
	 */
	public function bloquear_procesos($state)
	{	
			if ($this->settings_model->actualizarEstadoProcesos($state)) {
				$data["result"] = true;
				$this->session->set_flashdata('retornoExito', "Se actualizó el estado de los Procesos!!");
			} else {
				$data["result"] = "error";
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}

			redirect(base_url('settings/procesos/1'), 'refresh');
	}

	/**
	 * Historial de procesos
     * @since 18/5/2021
     * @author BMOTTAG
	 */
	public function historial_procesos()
	{
			$arrParam = array('idProcesoInfo' => $this->input->post('hddidProcesosInfo'));
			$data['infoProcesos'] = $this->general_model->get_procesos_info($arrParam);
			$data['infoProcesosHistorial'] = $this->general_model->get_procesos_historial($arrParam);
			$data['view'] = 'procesos_historial';
			$this->load->view("layout_calendar", $data);
	}
	
	/**
	 * Documentos
     * @since 18/5/2021
     * @author BMOTTAG
	 */
	public function documentos_procesos($idProcesoInfo, $estado)
	{
			$data['estado'] = $estado;
			$arrParam = array('idProcesoInfo' => $idProcesoInfo);
			$data['infoProcesos'] = $this->general_model->get_procesos_info($arrParam);

			$data['listaTemas'] = $this->general_model->get_temas($arrParam);
			$data['view'] = 'documentos';
			$this->load->view('layout_calendar', $data);
	}

	/**
	 * Form Upload Documents 
     * @since 25/5/2021
     * @author BMOTTAG
	 */
	public function documents_form($idProcesoInfo, $idTema, $idDocumento='x', $error = '')
	{			
			$arrParam = array('idProcesoInfo' => $idProcesoInfo);
			$data['infoProcesos'] = $this->general_model->get_procesos_info($arrParam);

			
			$data['listaTemas'] = $this->general_model->get_temas($arrParam);

			$data['idTema'] = $idTema;
			$data['information'] = FALSE;

			if ($idDocumento != 'x' && $idDocumento != '') {
				$arrParam = array('idDocumento' => $idDocumento);
				$data['information'] = $this->general_model->get_documentos_procesos($arrParam);
			}
			
			$data['error'] = $error; //se usa para mostrar los errores al cargar la imagen 			

			$data["view"] = "form_documentos";
			$this->load->view("layout", $data);
	}

	/**
	 * FUNCIÓN PARA SUBIR el archivo
	 */
    function do_upload_doc() 
	{
        $idProcesoInfo = $this->input->post("hddidProcesosInfo");
        $idTema = $this->input->post("hddidTema");
        $nuevoDocumento = $idDocumento = $this->input->post("hddidDocumento");
        $codigo = $this->input->post("hddCodigo");
 
        //$config['upload_path'] = './files/' . $codigo . '/';

		$config['upload_path'] = '../doc/' . $codigo . '/';
        $config['overwrite'] = FALSE;
        $config['allowed_types'] = 'pdf|xls|xlsx|xltx|xlsm|doc|docx';
        $config['max_size'] = '9000';
        $config['max_width'] = '2024';
        $config['max_height'] = '2008';

        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload() && $_FILES['userfile']['name']!= "") {
        	//SI EL ARCHIVO FALLA AL SUBIR MOSTRAMOS EL ERROR EN LA VISTA 
            $error = $this->upload->display_errors();
            $this->documents_form($idProcesoInfo,$idTema,$idDocumento,$error);
        }else{
			if($_FILES['userfile']['name']== ""){
				$archivo = 'xxx';
			}else{
	            $file_info = $this->upload->data();//subimos ARCHIVO
				
				$data = array('upload_data' => $this->upload->data());
				$archivo = $file_info['file_name'];			
			}
			//insertar datos
			if($idDocumento = $this->settings_model->saveDocumento($archivo))
			{
				//si es nuevo documento entonces actualizo el orden de los demas documentos
				//si es actaulizacion verifica si el tema es diferente
				$idTemaNuevo = $this->input->post('id_tema');
				$hddOrden = $this->input->post('hddOrden');
				$orden = $this->input->post('orden');
				if ($nuevoDocumento == '' || ($nuevoDocumento != '' && $idTemaNuevo != $idTema) || ($nuevoDocumento != '' && $hddOrden != $orden)) {
					$this->updateOrdeDocumentos($idProcesoInfo, $idTemaNuevo, $idDocumento, $orden);
				}
				//guardo regitro en la tabla auditoria
				$this->settings_model->saveAuditoriaDocumentos($idDocumento, $archivo);
				$this->session->set_flashdata('retornoExito', 'Se guardó la información.');
			}else{
				$this->session->set_flashdata('retornoError', '<strong>Error!!!</strong> Ask for help');
			}
			redirect('settings/documentos_procesos/' . $idProcesoInfo . '/1');
        }
    }

	/**
	 * Historial de documentos
     * @since 18/5/2021
     * @author BMOTTAG
	 */
	public function historial_documentos()
	{
			$arrParam = array('idDocumento' => $this->input->post('hddidDocumento'));
			$data['infoDocumento'] = $this->general_model->get_documentos_procesos($arrParam);
			$data['infoDocumentoHistorial'] = $this->general_model->get_documentos_historial($arrParam);
			$data['view'] = 'documentos_historial';
			$this->load->view("layout_calendar", $data);
	}

	/**
	 * Actaulizacion del orden de los demas documentos
     * @since 2/8/2021
     * @author BMOTTAG
	 */
	public function updateOrdeDocumentos($idProcesoInfo, $idTema, $idDocumento, $orden)
	{
			//listad de los documentos a cambiar
			$arrParam = array(
				"idProcesoInfo" => $idProcesoInfo,
				"idTema" => $idTema,
				"idDocumento" => $idDocumento,
				"orden" => $orden
			);
			$infoDocumentosOrder = $this->general_model->get_documentos_procesos_orden($arrParam);

			if($infoDocumentosOrder){
				$this->settings_model->upadateDocumentosProcesosOrden($infoDocumentosOrder);
			}

			return true;

	}	

	
}