<script type="text/javascript" src="<?php echo base_url("assets/js/validate/settings/proceso.js"); ?>"></script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="exampleModalLabel">Formulario de Procesos
	<br><small>Editar Proceso: <?php echo $information?$information[0]["codigo"]:""; ?></small>
	</h4>
</div>

<div class="modal-body">
	<form name="form" id="form" role="form" method="post" >
		<input type="hidden" id="hddId" name="hddId" value="<?php echo $information?$information[0]["id_proceso_informacion"]:""; ?>"/>
		<input type="hidden" id="hddTitulo" name="hddTitulo" value="<?php echo $information?$information[0]["title"]:""; ?>"/>
		<input type="hidden" id="hddTexto" name="hddTexto" value="<?php echo $information?$information[0]["texto"]:""; ?>"/>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="titulo">Título: *</label>
					<input type="text" id="titulo" name="titulo" class="form-control" value="<?php echo $information?$information[0]["title"]:""; ?>" placeholder="Título" required >
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group text-left">
					<label class="control-label" for="texto">Texto: *</label>
					<textarea id="texto" name="texto" placeholder="Texto" class="form-control" rows="3"><?php echo $information?$information[0]["texto"]:""; ?></textarea>
				</div>
			</div>
		
		</div>
		
		<div class="form-group">
			<div id="div_load" style="display:none">		
				<div class="progress progress-striped active">
					<div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
						<span class="sr-only">45% completado</span>
					</div>
				</div>
			</div>
			<div id="div_error" style="display:none">			
				<div class="alert alert-danger"><span class="glyphicon glyphicon-remove" id="span_msj">&nbsp;</span></div>
			</div>	
		</div>

		<div class="form-group">
			<div class="row" align="center">
				<div style="width:50%;" align="center">
					<button type="button" id="btnSubmit" name="btnSubmit" class="btn btn-success" >
						Guardar <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true">
					</button> 
				</div>
			</div>
		</div>
			
	</form>
</div>