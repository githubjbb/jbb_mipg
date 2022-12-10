<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $infoProcesos[0]['codigo']; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>	
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="../../assets/lib/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="../../assets/lib/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="../../assets/lib/css/template.css" type="text/css" rel="stylesheet" media="screen,projection"/>
	</head>

	<body>
		<div class="body-innerwrapper">
	        <section id="sp-top-wrapper" class=" wow fadeIn  animated" style="visibility: visible;">
	        	<div class="container">
	        		<div class="row-fluid" id="top">
	        			<div id="sp-logo" class="span3">
	        				<div class="logo-wrapper">
	        					<img alt="" class="image-logo" src="../../images/logo_jardin_blanco.png" >
	        				</div>
	        			</div>
						<div id="sp-social" class="span9">
							<div class="module ">
								<div class="mod-wrapper clearfix">
									<div class="mod-content clearfix">	
										<div class="mod-inner clearfix">
											<div class="custom" style="margin-top: -72px;">
												<img src="../../images/bogota-blanco.png" alt="" width="134" height="61">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>

		<div id="background">
			<div class="row navegacion">
				<a href='../../../'><img src='../../images/back.png' alt='Mapa de procesos'><span class="titleNav">Mapa de procesos</span></a>
			</div>
			<div class="row">
				<div class="col m6 offset-m1">
					<h4 class="GTH"><?php echo $infoProcesos[0]['title']; ?></h4>
					<p class="completo"><?php echo $infoProcesos[0]['texto']; ?></p>
				</div>


				<div class="col m4 offset-m1">
					<ul class="collapsible">
						<?php 
				if($listaTemas){
					$ci = &get_instance();
					$ci->load->model("general_model");

					foreach ($listaTemas as $lista):
						$arrParam = array(
							'idProcesoInfo' => $infoProcesos[0]['id_proceso_informacion'],
							'idTema' => $lista['id_tema'],
							'estado' => 1
						);
						$documentoProcesos = $this->general_model->get_documentos_procesos($arrParam);	
						?>
							<li>
								<div class="collapsible-header"><?php echo $lista['descripcion']; ?></div>
								<div class="collapsible-body">
									<ul>
										<?php
										if($documentoProcesos){
											foreach ($documentoProcesos as $item):
												$enlace = '../../../doc/' . $infoProcesos[0]['codigo'] . '/' . $item['url'];
										?>
											<a href="<?php echo $enlace; ?>" alt="" target="_blank">
												<li><b><?php echo $item['cod']; ?></b> &nbsp;&nbsp;<?php echo $item['shortName']; ?></li>
											</a>
										<?php 
											endforeach; 
										}else{
											echo 'No se cuentan con documententos de este tipo para la gestión del proceso';
										}

										?>
									</ul>
								</div>
							</li>
						<?php 
					endforeach; 
				}
					?>
					</ul>
				</div>	
			</div>

			<div class="row navegacion" style="margin-top: 12vh;">
				<?php $nextLink = '../../external/proceso/' . $infoProcesos[0]['nextURL']; ?>
				<a href='<?php echo $nextLink; ?>'>
					<img src='../../images/next.png' alt='<?php echo $infoProcesos[0]['nextTitle']; ?>'>
					<span class="titleNav"><?php echo $infoProcesos[0]['nextTitle']; ?></span>
				</a>
			</div>
		</div>

	<script type="text/javascript" src="../../assets/lib/js/jquery-2.min.js"></script>
	<script type="text/javascript" src="../../assets/lib/js/sha512.js"></script>
	<script type="text/javascript" src="../../assets/lib/js/materialize.min.js"></script>
	<script type="text/javascript">$(document).ready(function(){
		$('.modal').modal();
		$('.collapsible').collapsible();
	});</script>
	</body>
</html>