<div id="page-wrapper">
	<br>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url('settings/procesos'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar </a> 
					<i class="fa fa-clock-o"></i> CAMBIOS REALIZADOS AL PROCESO: <strong><?php echo $infoProcesos[0]['title'] ?></strong>
				</div>
				<div class="panel-body">

				<?php
					if($infoProcesosHistorial){
				?>				
					<table class="table table-hover">
						<thead>
							<tr>
                                <th class='text-center'>Fecha cambio</th>
                                <th>Realizado por</th>
                                <th>Proceso</th>
                                <th>Texto</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($infoProcesosHistorial as $lista):
								echo '<tr>';
                                echo '<td class="text-center">' . $lista['fecha_registro_informacion_api'] . '</td>';
                                echo '<td>' . $lista['name'] . '</td>';
                                echo '<td>' . $lista['title_api'] . '</td>';
                                echo '<td>' . $lista['texto_api'] . '</td>';
                                echo '</tr>';
							endforeach;
						?>
						</tbody>
					</table>

				<?php } ?>
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
		
				
<!--INICIO Modal para adicionar HAZARDS -->
<div class="modal fade text-center" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">    
	<div class="modal-dialog" role="document">
		<div class="modal-content" id="tablaDatos">

		</div>
	</div>
</div>                       
<!--FIN Modal para adicionar HAZARDS -->

<!-- Tables -->
<script>
$(document).ready(function() {
	$('#dataTables').DataTable({
		responsive: true,
		"pageLength": 100
	});
});
</script>