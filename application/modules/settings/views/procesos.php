<script>
$(function(){ 
	$(".btn-default").click(function () {	
			var oID = $(this).attr("id");
            $.ajax ({
                type: 'POST',
				url: base_url + '/settings/cargarModalProcesos',
                data: {'idProcesoInfo': oID},
                cache: false,
                success: function (data) {
                    $('#tablaDatos').html(data);
                }
            });
	});	
});
</script>

<div id="page-wrapper">
	<br>
	
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-green">
				<div class="panel-heading">
					<i class="fa fa-dashboard"></i> <strong>LISTA DE PROCESOS</strong>
					<div class="pull-right">
						<div class="btn-group">
                            <a class="btn btn-success btn-xs" href='<?php echo base_url('reportes/generaReporteFinalXLS') ?>' target='_blank'><i class="fa fa-file-excel-o"></i> Descargar Listado Maestro de Documentos</a>
						</div>
					</div>
				</div>
				<div class="panel-body">

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


<!--INICIO HAZARDS -->
<?php 
	if($infoProcesos){
		$ci = &get_instance();
		$ci->load->model("general_model");
		foreach ($infoProcesos as $lista):
			$arrParam = array('idProceso' => $lista['id_proceso']);
			$detalleProcesos = $this->general_model->get_procesos_info($arrParam);		
?>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">				
			<div class="panel panel-success">
				<div class="panel-heading">
					<i class="fa fa-th-list"></i> <strong><?php echo $lista['proceso']; ?></strong>
				</div>
				<div class="panel-body">

				<?php 
					if($detalleProcesos){
				?>				
					<table class="table table-hover">
						<thead>
							<tr>
                                <th class='text-center'>Proceso</th>
                                <th class='text-center'>Código</th>
                                <th class='text-center'>Texto</th>
                                <th class='text-center'>Enlaces</th>
							</tr>
						</thead>
						<?php
							foreach ($detalleProcesos as $item):
								echo '<tr>';
                                echo "<td>" . $item['title'] . "</td>";
								echo "<td class='text-center'>" . $item['codigo'] . "</td>";
                                 echo "<td>" . $item['texto'] . "</td>";
								echo "<td class='text-center'>";
						?>
								<button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal" id="<?php echo $item['id_proceso_informacion']; ?>" >
									Editar <span class="glyphicon glyphicon-edit" aria-hidden="true">
								</button>
								<br><br>

								<a class="btn btn-success btn-xs" href="<?php echo base_url('settings/documentos_procesos/' . $item['id_proceso_informacion'] . '/1'); ?>">Ver Documentos <span class="fa fa-file-o" aria-hidden="true" /></a>
								<br><br>
	                            <form  name="formHistorial" id="formHistorial" method="post" action="<?php echo base_url("settings/historial_procesos"); ?>">
	                                <input type="hidden" class="form-control" id="hddidProcesosInfo" name="hddidProcesosInfo" value="<?php echo $item['id_proceso_informacion']; ?>" />
	                                
	                                <button type="submit" class="btn btn-success btn-xs" id="btnSubmit2" name="btnSubmit2">
	                                    Ver Cambios <span class="fa fa-clock-o" aria-hidden="true" />
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
<!--FIN HAZARDS -->


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
				
<!--INICIO Modal para adicionar PROCESOS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar PROCESOS -->

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