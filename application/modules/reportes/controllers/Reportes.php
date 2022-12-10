<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
	
    public function __construct() {
        parent::__construct();
		$this->load->model("reportes_model");
		$this->load->library('PHPExcel.php');
    }
	
	/**
	/**
	 * Generate Reportes in XLS
     * @since 9/09/2021
     * @author BMOTTAG
	 */		
	public function generaReporteFinalXLS()
	{				
			//lista de todos los documentos activos
			$infoDocumentos = $this->reportes_model->get_documentos_totales();
			// Create new PHPExcel object	
			$objPHPExcel = new PHPExcel();
			// Set document properties
			$objPHPExcel->getProperties()->setCreator("JBB APP")
										 ->setLastModifiedBy("JBB APP")
										 ->setTitle("Report")
										 ->setSubject("Report")
										 ->setDescription("JBB Report")
										 ->setKeywords("office 2007 openxml php")
										 ->setCategory("Report");
			// Create a first sheet
			$objPHPExcel->setActiveSheetIndex(0);
						
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ITEM')
										  ->setCellValue('B1', 'TIPO DE PROCESO')
										  ->setCellValue('C1', 'PROCESO')
										  ->setCellValue('D1', 'CODIGO PROCESO')
										  ->setCellValue('E1', 'TIPO DE DOCUMENTO')
										  ->setCellValue('F1', 'NOMBRE DEL DOCUMENTO')
										  ->setCellValue('G1', 'CODIGO DEL DOCUMENTO')
										  ->setCellValue('H1', 'FECHA ELABORACIÓN DEL DOCUMENTO')
										  ->setCellValue('I1', 'FECHA ULTIMA ACTUALIZACION')
										  ->setCellValue('J1', 'VERSION DOCUMENTO')
										  ->setCellValue('K1', 'NÚMERO ACTA');
										
			$j=2;
			if($infoDocumentos){
				$i=1;
				foreach ($infoDocumentos as $info):
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$j, $i)
													  ->setCellValue('B'.$j, $info['proceso'])
													  ->setCellValue('C'.$j, $info['title'])
													  ->setCellValue('D'.$j, $info['codigo'])
													  ->setCellValue('E'.$j, $info['descripcion'])
													  ->setCellValue('F'.$j, $info['shortName'])
													  ->setCellValue('G'.$j, $info['cod'])
													  ->setCellValue('H'.$j, $info['fecha_elaboracion'])
													  ->setCellValue('I'.$j, $info['fecha_registro'])
													  ->setCellValue('J'.$j, 'V. ' . $info['version_documento'])
													  ->setCellValue('K'.$j, $info['numero_acta']);
						$j++;
						$i++;
				endforeach;
			}

			// Set column widths							  
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);


			// Add conditional formatting
			$objConditional1 = new PHPExcel_Style_Conditional();
			$objConditional1->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
							->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_BETWEEN)
							->addCondition('200')
							->addCondition('400');
			$objConditional1->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
			$objConditional1->getStyle()->getFont()->setBold(true);
			$objConditional1->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
			$objConditional1->getStyle()->getAlignment()->setVertical('center')->setHorizontal('center');
			$objConditional1->getStyle()->getBorders()->applyFromArray(array('allBorders' => 'thin'));

			$objConditional2 = new PHPExcel_Style_Conditional();
			$objConditional2->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS)
							->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_LESSTHAN)
							->addCondition('0');
			$objConditional2->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
			$objConditional2->getStyle()->getFont()->setItalic(true);
			$objConditional2->getStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

			$conditionalStyles = $objPHPExcel->getActiveSheet()->getStyle('B2')->getConditionalStyles();
			array_push($conditionalStyles, $objConditional1);
			array_push($conditionalStyles, $objConditional2);
			$objPHPExcel->getActiveSheet()->getStyle('B2')->setConditionalStyles($conditionalStyles);

			//	duplicate the conditional styles across a range of cells
			$objPHPExcel->getActiveSheet()->duplicateConditionalStyle(
							$objPHPExcel->getActiveSheet()->getStyle('B2')->getConditionalStyles(),
							'B3:B7'
						  );


			// Set fonts			  
			$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);

			// Set header and footer. When no different headers for odd/even are used, odd header is assumed.
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&BPersonal cash register&RPrinted on &D');
			$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N');

			// Set page orientation and size
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
			$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Procesos');

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			// redireccionamos la salida al navegador del cliente (Excel2007)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="listado_maestro_docuementos.xlsx"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
    }

	
}