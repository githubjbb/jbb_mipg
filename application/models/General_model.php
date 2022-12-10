<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Clase para consultas generales a una tabla
 */
class General_model extends CI_Model {

    /**
     * Consulta BASICA A UNA TABLA
     * @param $TABLA: nombre de la tabla
     * @param $ORDEN: orden por el que se quiere organizar los datos
     * @param $COLUMNA: nombre de la columna en la tabla para realizar un filtro (NO ES OBLIGATORIO)
     * @param $VALOR: valor de la columna para realizar un filtro (NO ES OBLIGATORIO)
     * @since 8/11/2016
     */
    public function get_basic_search($arrData) {
        if ($arrData["id"] != 'x')
            $this->db->where($arrData["column"], $arrData["id"]);
        $this->db->order_by($arrData["order"], "ASC");
        $query = $this->db->get($arrData["table"]);

        if ($query->num_rows() >= 1) {
            return $query->result_array();
        } else
            return false;
    }
	
	/**
	 * Delete Record
	 * @since 25/5/2017
	 */
	public function deleteRecord($arrDatos) 
	{
			$query = $this->db->delete($arrDatos ["table"], array($arrDatos ["primaryKey"] => $arrDatos ["id"]));
			if ($query) {
				return true;
			} else {
				return false;
			}
	}
	
	/**
	 * Update field in a table
	 * @since 11/12/2016
	 */
	public function updateRecord($arrDatos) {
		$data = array(
			$arrDatos ["column"] => $arrDatos ["value"]
		);
		$this->db->where($arrDatos ["primaryKey"], $arrDatos ["id"]);
		$query = $this->db->update($arrDatos ["table"], $data);
		if ($query) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Lista de menu
	 * Modules: MENU
	 * @since 30/3/2020
	 */
	public function get_menu($arrData) 
	{		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('menu_state', $arrData["menuState"]);
		}
		if (array_key_exists("columnOrder", $arrData)) {
			$this->db->order_by($arrData["columnOrder"], 'asc');
		}else{
			$this->db->order_by('menu_order', 'asc');
		}
		
		$query = $this->db->get('param_menu');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}	

	/**
	 * Lista de roles
	 * Modules: ROL
	 * @since 30/3/2020
	 */
	public function get_roles($arrData) 
	{		
		if (array_key_exists("filtro", $arrData)) {
			$this->db->where('id_role !=', 99);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('id_role', $arrData["idRole"]);
		}
		
		$this->db->order_by('role_name', 'asc');
		$query = $this->db->get('param_role');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * User list
	 * @since 30/3/2020
	 */
	public function get_user($arrData) 
	{			
		$this->db->select();
		$this->db->join('param_role R', 'R.id_role = U.fk_id_user_role', 'INNER');
		if (array_key_exists("state", $arrData)) {
			$this->db->where('U.state', $arrData["state"]);
		}
		
		//list without inactive users
		if (array_key_exists("filtroState", $arrData)) {
			$this->db->where('U.state !=', 2);
		}
		
		if (array_key_exists("idUser", $arrData)) {
			$this->db->where('U.id_user', $arrData["idUser"]);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('U.fk_id_user_role', $arrData["idRole"]);
		}

		$this->db->order_by("first_name, last_name", "ASC");
		$query = $this->db->get("usuarios U");

		if ($query->num_rows() >= 1) {
			return $query->result_array();
		} else
			return false;
	}
	
	/**
	 * Lista de enlaces
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_links($arrData) 
	{		
		$this->db->select();
		$this->db->join('param_menu M', 'M.id_menu = L.fk_id_menu', 'INNER');
		
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('id_link', $arrData["idLink"]);
		}
		if (array_key_exists("linkType", $arrData)) {
			$this->db->where('link_type', $arrData["linkType"]);
		}			
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('link_state', $arrData["linkState"]);
		}
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_links L');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * Lista de permisos
	 * Modules: MENU
	 * @since 31/3/2020
	 */
	public function get_role_access($arrData) 
	{		
		$this->db->select('P.id_access, P.fk_id_menu, P.fk_id_link, P.fk_id_role, M.menu_name, M.menu_order, M.menu_type, L.link_name, L.link_url, L.order, L.link_icon, L.link_type, R.role_name, R.style');
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');
		$this->db->join('param_menu_links L', 'L.id_link = P.fk_id_link', 'LEFT');
		$this->db->join('param_role R', 'R.id_role = P.fk_id_role', 'INNER');
		
		if (array_key_exists("idPermiso", $arrData)) {
			$this->db->where('id_access', $arrData["idPermiso"]);
		}
		if (array_key_exists("idMenu", $arrData)) {
			$this->db->where('P.fk_id_menu', $arrData["idMenu"]);
		}
		if (array_key_exists("idLink", $arrData)) {
			$this->db->where('P.fk_id_link', $arrData["idLink"]);
		}
		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("linkState", $arrData)) {
			$this->db->where('L.link_state', $arrData["linkState"]);
		}
		if (array_key_exists("menuURL", $arrData)) {
			$this->db->where('M.menu_url', $arrData["menuURL"]);
		}
		if (array_key_exists("linkURL", $arrData)) {
			$this->db->where('L.link_url', $arrData["linkURL"]);
		}		
		
		$this->db->order_by('M.menu_order, L.order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
	
	/**
	 * menu list for a role
	 * Modules: MENU
	 * @since 2/4/2020
	 */
	public function get_role_menu($arrData) 
	{		
		$this->db->select('distinct(fk_id_menu), menu_url,menu_icon,menu_name,menu_order');
		$this->db->join('param_menu M', 'M.id_menu = P.fk_id_menu', 'INNER');

		if (array_key_exists("idRole", $arrData)) {
			$this->db->where('P.fk_id_role', $arrData["idRole"]);
		}
		if (array_key_exists("menuType", $arrData)) {
			$this->db->where('M.menu_type', $arrData["menuType"]);
		}
		if (array_key_exists("menuState", $arrData)) {
			$this->db->where('M.menu_state', $arrData["menuState"]);
		}
					
		//$this->db->group_by("P.fk_id_menu"); 
		$this->db->order_by('M.menu_order', 'asc');
		$query = $this->db->get('param_menu_access P');

		if ($query->num_rows() > 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

		/**
		 * Consultar registros de procesos
		 * @since 18/5/2021
		 */
		public function get_procesos()
		{
				$this->db->select();
				$this->db->order_by('P.id_proceso', 'asc');

				$query = $this->db->get('procesos P');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consultar registros de procesos
		 * @since 19/5/2021
		 */
		public function get_procesos_info($arrData)
		{
				$this->db->select();
				$this->db->join('procesos_informacion I', 'I.fk_id_proceso = P.id_proceso', 'INNER');
				if (array_key_exists("idProceso", $arrData)) {
					$this->db->where('P.id_proceso ', $arrData["idProceso"]);
				}
				if (array_key_exists("codigo", $arrData)) {
					$this->db->where('I.codigo', $arrData["codigo"]);
				}
				if (array_key_exists("idProcesoInfo", $arrData)) {
					$this->db->where('I.id_proceso_informacion', $arrData["idProcesoInfo"]);
				}
				$this->db->order_by('P.id_proceso, I.codigo', 'asc');

				$query = $this->db->get('procesos P');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consultar registros de historial de procesos
		 * @since 19/5/2021
		 */
		public function get_procesos_historial($arrData)
		{
				$this->db->select("A.*, CONCAT(first_name, ' ', last_name) name");
				$this->db->join('usuarios U', 'U.id_user = A.fk_id_usuario_api ', 'INNER');
				if (array_key_exists("idProcesoInfo", $arrData)) {
					$this->db->where('A.fk_id_proceso_informacion_api', $arrData["idProcesoInfo"]);
				}
				$this->db->order_by('A.id_auditoria_proceso_informacion', 'desc');

				$query = $this->db->get('auditoria_procesos_informacion A');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consultar registros de temas
		 * @since 18/5/2021
		 */
		public function get_temas($arrData)
		{
				$this->db->select();
				if (array_key_exists("idTema", $arrData)) {
					$this->db->where('T.id_tema', $arrData["idTema"]);
				}
				$this->db->order_by('T.id_tema', 'asc');

				$query = $this->db->get('temas T');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consultar ducumentos de proceso
		 * @since 25/5/2021
		 */
		public function get_documentos_procesos($arrData)
		{
				$this->db->select('D.*, I.codigo');
				$this->db->join('procesos_informacion I', 'I.id_proceso_informacion = D.fk_id_proceso_informacion', 'INNER');
				if (array_key_exists("idDocumento", $arrData)) {
					$this->db->where('D.id_procesos_documento', $arrData["idDocumento"]);
				}
				if (array_key_exists("idProcesoInfo", $arrData)) {
					$this->db->where('D.fk_id_proceso_informacion', $arrData["idProcesoInfo"]);
				}
				if (array_key_exists("idTema", $arrData)) {
					$this->db->where('D.fk_id_tema', $arrData["idTema"]);
				}
				if (array_key_exists("estado", $arrData)) {
					$this->db->where('D.estado', $arrData["estado"]);
				}
				$this->db->order_by('D.orden', 'asc');

				$query = $this->db->get('procesos_documentos D');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}


		/**
		 * Consultar registros de historial de documentos
		 * @since 28/5/2021
		 */
		public function get_documentos_historial($arrData)
		{
				$this->db->select("A.*, CONCAT(first_name, ' ', last_name) name, T.descripcion");
				$this->db->join('usuarios U', 'U.id_user = A.fk_id_usuario', 'INNER');
				$this->db->join('temas T', 'T.id_tema = A.fk_id_tema', 'INNER');
				if (array_key_exists("idDocumento", $arrData)) {
					$this->db->where('A.fk_id_proceso_documento', $arrData["idDocumento"]);
				}
				$this->db->order_by('A.id_auditoria_documento', 'desc');

				$query = $this->db->get('auditoria_documentos A');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}

		/**
		 * Consultar ducumentos para actualizar el orden
		 * @since 2/08/2021
		 */
		public function get_documentos_procesos_orden($arrData)
		{
				$this->db->select('id_procesos_documento, orden ');
				if (array_key_exists("idDocumento", $arrData)) {
					$this->db->where('D.id_procesos_documento !=', $arrData["idDocumento"]);
				}
				if (array_key_exists("idProcesoInfo", $arrData)) {
					$this->db->where('D.fk_id_proceso_informacion', $arrData["idProcesoInfo"]);
				}
				if (array_key_exists("idTema", $arrData)) {
					$this->db->where('D.fk_id_tema', $arrData["idTema"]);
				}
				if (array_key_exists("orden", $arrData)) {
					$this->db->where('D.orden >=', $arrData["orden"]);
				}
				$this->db->where('D.estado', 1);


				$query = $this->db->get('procesos_documentos D');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}



}