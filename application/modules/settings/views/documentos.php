<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url('settings/procesos'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar </a>
					<i class="fa fa-file-o"></i><strong> LISTA DE DOCUMENTOS</strong> - <?php echo $infoProcesos[0]['title']; ?>
				</div>
				<div class="panel-body">
					<ul class="nav nav-pills">
						<?php $idProcesoInfo =  $infoProcesos[0]['id_proceso_informacion'];  ?>
						<li <?php if($estado == 1){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/documentos_procesos/" . $idProcesoInfo . "/1" ); ?>">Documentos Activos</a>
						</li>
						<li <?php if($estado == 2){ echo "class='active'";} ?>><a href="<?php echo base_url("settings/documentos_procesos/" . $idProcesoInfo . "/2" ); ?>">Documentos Inactivos</a>
						</li>
					</ul>
					<br>
<?php
	$retornoExito = $this->session->flashdata('retornoExito');
	if ($retornoExito) {
?>
		<div class="alert alert-success ">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<?php echo $retornoExito ?>		
		</div>
<?php
	}
	$retornoError = $this->session->flashdata('retornoError');
	if ($retornoError) {
?>
		<div class="alert alert-danger ">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<?php echo $retornoError ?>
		</div>
<?php
	}
?> 


<!--INICIO LISTADO -->
<?php 
	if($listaTemas){
		$ci = &get_instance();
		$ci->load->model("general_model");

		foreach ($listaTemas as $lista):
			$arrParam = array(
				'idProcesoInfo' => $idProcesoInfo,
				'idTema' => $lista['id_tema'],
				'estado' => $estado
			);
			$documentoProcesos = $this->general_model->get_documentos_procesos($arrParam);	
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-info">
				<div class="panel-heading">
					<i class="fa fa-tag"></i> <strong><?php echo $lista['descripcion']; ?></strong>
				</div>
				<div class="panel-body">
					<ul class="nav nav-pills">
						<li class='active'>
							<?php 
								$idTema = $lista['id_tema'];
								$codigoProceso = $infoProcesos[0]['codigo'];

								if($estado == 1){
							?>
							<a href="<?php echo base_url('settings/documents_form/' . $idProcesoInfo . '/' . $idTema); ?>">Adicionar Documento</a>
							<?php } ?>
						</li>
					</ul>

				<?php 
					if($documentoProcesos){
				?>				
					<table class="table table-hover">
						<thead>
							<tr>
                                <th class='text-center'>#</th>
                                <th>Código</th>
                                <th>Nombre Documento</th>
                                <th class='text-right'>No. Acta</th>
                                <th class='text-center'>Versión</th>
								<th class='text-center'>Fecha Elaboración Documento</th>
                                <th class='text-center'>Orden</th>
                                <th class='text-center'>Enlaces</th>
							</tr>
						</thead>
						<?php
							$i=0;
							foreach ($documentoProcesos as $item):
								$i++;
								$estiloColor = '';
								if($estado == 2){
									$estiloColor = "class='text-danger'";
								}
								echo '<tr ' . $estiloColor . '>';
								echo "<td class='text-center'>" . $i . "</td>";
                                echo "<td>" . $item['cod'] . "</td>";
								echo "<td>" . $item['shortName'] . "</td>";
								echo "<td class='text-right'>" . $item['numero_acta'] . "</td>";
								echo "<td class='text-center'>V. " . $item['version_documento'] . "</td>";
								echo "<td class='text-center'>" . $item['fecha_elaboracion'] . "</td>";
								echo "<td class='text-center'>" . $item['orden'] . "</td>";
								echo "<td class='text-center'>";
								$enlace = '../../../../doc/' . $codigoProceso . '/' . $item['url'];
						?>
								<a href='<?php echo $enlace; ?>' target="_blank">Ver Documento</a>
								<br><br>
								<a class="btn btn-info btn-xs" href="<?php echo base_url('settings/documents_form/' . $idProcesoInfo . '/' . $idTema . '/' . $item['id_procesos_documento']); ?>">Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
								</a>
								<br><br>

	                            <form  name="formHistorial" id="formHistorial" method="post" action="<?php echo base_url("settings/historial_documentos"); ?>">
	                                <input type="hidden" class="form-control" id="hddidDocumento" name="hddidDocumento" value="<?php echo $item['id_procesos_documento']; ?>" />
	                                
	                                <button type="submit" class="btn btn-default btn-xs" id="btnSubmit2" name="btnSubmit2">
	                                    Ver Cambios <span class="fa fa-th-list" aria-hidden="true" />
	                                </button>
	         
	                            </form>

						<?php
								echo "</td>";
                                echo '</tr>';
							endforeach;
						?>
					</table>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>


<?php 
		endforeach;
} 
?>
<!--FIN LISTADO -->


				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
				
<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 25,
		 "ordering": false,
		 paging: false
	});
});
</script>