<?php  ?>

<?php
	require 'classes/Anuncios.php';
	require 'classes/Usuarios.php';
	require 'pages/header.php';
	$a = new Anuncios();
	$usuarios = new Usuarios();
	
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$id = addslashes($_GET['id']);
	}else{
		?>
		  	<script type="text/javascript">window.location.href="index.php"</script>
		<?php 
	}
	$info= $a->getAnuncio($id);

 ?>
		<div class="container-fluid">
			
			<div class="row">
				<div class="col-sm-4">
					<div class="carousel slide" data-ride="carousel" id="meuCarousel">
						<div class="carousel-inner" role="listbox">
							<?php foreach($info['fotos'] as $chave=>$foto): ?>
								<div class="item <?php echo($chave=='0')?'active':''; ?>">
									<img src="assets/images/anuncios/<?php echo $foto['url']; ?>" alt=""/>
								</div>
							<?php endforeach; ?>
						</div>
						<a class="left carousel-control" href="#meuCarousel" role="button" data-slide="prev">
							<span> < </span>							
						</a>
						<a class="right carousel-control" href="#meuCarousel" role="button" data-slide="next">
							<span> > </span>							
						</a>
					</div>
				</div>
				
				<div class="col-sm-8">
					<h1><?php echo $info['titulo'] ?></h1>
					<h4><?php echo $info['categoria'] ?></h4>
					<p><?php echo $info['descricao'] ?></p>
					<br>
					<h3>R$ <?php echo number_format($info['valor'],2); ?></h3>
					<h4>Telefone: <?php echo $info['telefone']; ?></h4>
				</div>
			</div>
		</div>

<?php require 'pages/footer.php'; ?>


<!--rua aindui n 520 bairro flores  elane instituto transire 9.40-->