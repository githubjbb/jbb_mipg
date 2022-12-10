<div id="page-wrapper">
	<br>
	<!-- /.row -->
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<a class="btn btn-primary btn-xs" href=" <?php echo base_url('settings/documentos_procesos/' . $infoDocumento[0]['fk_id_proceso_informacion'] . '/1'); ?> "><span class="glyphicon glyphicon glyphicon-chevron-left" aria-hidden="true"></span> Regresar </a> 
					<i class="fa fa-clock-o"></i> CAMBIOS REALIZADOS AL DOCUMENTO: <strong><?php echo $infoDocumento[0]['shortName'] ?></strong>
				</div>
				<div class="panel-body">

				<?php
					if($infoDocumentoHistorial){
				?>				
					<table class="table table-hover">
						<thead>
							<tr>
                                <th class='text-center'>Fecha cambio</th>
                                <th>Realizado por</th>
                                <th>Tipo Documento</th>
                                <th>Código</th>
                                <th>URL</th>
                                <th>Nombre Documento</th>
                                <th class='text-right'>No. Acta</th>
                                <th class='text-center'>Version</th>
								<th class='text-center'>Fecha Elaboración Documento</th>
                                <th class='text-center'>Orden</th>
                                <th class='text-center'>Estado</th>
                                <th>Observación</th>
							</tr>
						</thead>
						<tbody>							
						<?php
							foreach ($infoDocumentoHistorial as $lista):
								echo '<tr>';
                                echo '<td class="text-center">' . $lista['fecha_registro'] . '</td>';
                                echo '<td>' . $lista['name'] . '</td>';
                                echo '<td>' . $lista['descripcion'] . '</td>';
                                echo '<td>' . $lista['cod'] . '</td>';
                                echo '<td>';
								$codigoProceso = $infoDocumento[0]['codigo'];
								$enlace = '../../doc/' . $codigoProceso . '/' . $lista['url'];
								echo "<a href='" . $enlace . "' target='_blank'>" . $lista['url'] . "</a>";
                                echo  '</td>';
                                echo '<td>' . $lista['shortName'] . '</td>';
                                echo "<td class='text-right'>" . $lista['numero_acta'] . "</td>";
                                echo "<td class='text-center'>V. " . $lista['version_documento'] . "</td>";
								echo "<td class='text-center'>" . $lista['fecha_elaboracion'] . "</td>";
                                echo "<td class='text-center'>" . $lista['orden'] . "</td>";
								echo "<td class='text-center'>";
								switch ($lista['estado']) {
									case 1:
										$valor = 'Activo';
										$clase = "text-success";
										break;
									case 2:
										$valor = 'Inactivo';
										$clase = "text-danger";
										break;
								}
								echo '<p class="' . $clase . '"><strong>' . $valor . '</strong></p>';
								echo "</td>";
                                echo '<td>' . $lista['observacion'] . '</td>';
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