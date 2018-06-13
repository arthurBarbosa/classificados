<?php require 'pages/header.php'; ?>

<?php

if(empty($_SESSION['cLogin'])){
?>
<<script type="text/javascript">window.location.href="./"</script>
<?php 
exit;
}

 ?>


<div class="container">
	<h2>Meus anuncios</h2>
	
	<a href="add-anuncio.php" class="btn btn-default">Adiconar Anúncio</a>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Foto</th>
				<th>Titulo</th>
				<th>Valor</th>
				<th>Ações</th>
			</tr>
		</thead>
		<?php 
		require 'classes/Anuncios.php';
		$a = new Anuncios();
		$anuncios = $a->getMeusAnuncios();

		foreach ($anuncios as $anuncio): 
			?>
			<tr>
				<td>
					<?php if(!empty($anuncio['url'])): ?>
					<img src="assets/images/anuncios/<?php echo $anuncio['url']; ?>" alt="">
					<?php else: ?>
					<img src="assets/images/anuncios/default.png" height="50" alt="">
					<?php endif; ?>
				</td>
				<td><?php echo $anuncio['titulo']; ?></td>
				<td>R$ <?php echo number_format($anuncio['valor'],2); ?></td>
				<td>
					<a href="editar-anuncios.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-default">Editar</a>
					<a  href="excluir-anuncios.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-danger">Excluir</a>
				</td>
			</tr>
		<?php endforeach; ?>
			
		 
	</table>
</div>
<?php require 'pages/footer.php'; ?>