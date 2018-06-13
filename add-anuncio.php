<?php require 'pages/header.php'; ?>

<?php

if(empty($_SESSION['cLogin'])){
?>
<script type="text/javascript">window.location.href="./"</script>
<?php 
exit;
}

require 'classes/Anuncios.php';
$anuncios = new Anuncios();
if(isset($_POST['titulo']) && !empty($_POST['titulo'])){
	$titulo = addslashes($_POST['titulo']);
	$categoria = addslashes($_POST['categoria']);
	$valor = addslashes($_POST['valor']);
	$descricao = addslashes($_POST['descricao']);
	$estado = addslashes($_POST['estado']);

	$anuncios->addAnuncio($titulo, $categoria, $valor, $descricao, $estado);
	?>
	<div class="alert alert-success">
		Anúncio adicionado com sucesso!
	</div>

	<?php
}


 ?>

<div class="container">
	<h2>Meus anuncios - Adicionar Anúncio</h2>

	<form method="POST" enctype="multipart/form-data">
		<div class="form-group">
			<label for="categoria">Categoria:</label>
			<select name="categoria" id="categoria" class="form-control">
				<?php 
				require 'classes/Categorias.php';
				$categoria = new Categorias();
				$cate = $categoria->getLista();
				foreach($cate as $cat): ?>
				<option value="<?php echo $cat['id']; ?>"><?php echo $cat['nome'] ?></option>	
				<?php 
				endforeach;
				 ?>
			</select>
		</div>

		<div class="form-group">
			<label for="titulo">Titulo: </label>
			<input type="text" id="titulo" name="titulo" class="form-control" placeholder="Titulo do produto">
		</div>

		<div class="form-group">
			<label for="valor">Valor:</label>
			<input type="text" name="valor" id="valor" class="form-control" placeholder="Valor do produto">Valor</input>
		</div>

		<div class="form-group">
			<label for="descricao">Descrição</label>
			<textarea name="descricao" id="descricao" class="form-control" cols="10" rows="5" placeholder="Escreva aqui a descrição do produto"></textarea>
		</div>

		<div class="form-group">
			<label for="estado">Estado de conservação:</label>
			<select name="estado" id="estado" class="form-control">
				<option value="0">Ruim</option>
				<option value="1">Bom</option>
				<option value="2">Òtimo</option>
			</select>
		</div>
		<input type="submit" value="Adicionar" class="btn btn-default">

	</form>
</div>




 <?php require 'pages/footer.php'; ?>