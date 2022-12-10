<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Reportes_model extends CI_Model {
	    
		/**
		 * Consultar registros de procesos
		 * @since 9/9/2021
		 */
		public function get_documentos_totales()
		{
				$this->db->select('P.proceso, D.fecha_elaboracion, I.title, I.codigo, T.descripcion, D.cod, D.shortName, D.fecha_registro, D.version_documento, D.numero_acta');
				$this->db->join('procesos_informacion I', 'I.id_proceso_informacion = D.fk_id_proceso_informacion', 'INNER');
				$this->db->join('procesos P', 'P.id_proceso = I.fk_id_proceso', 'INNER');
				$this->db->join('temas T', 'T.id_tema = D.fk_id_tema', 'INNER');

				$this->db->where('D.estado', 1);
				$this->db->order_by('P.proceso, I.title, T.id_tema, D.orden', 'asc');

				$query = $this->db->get('procesos_documentos D');

				if ($query->num_rows() > 0) {
					return $query->result_array();
				} else {
					return false;
				}
		}
	    
	}