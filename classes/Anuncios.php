<?php 

class Anuncios{

	public function getTotalAnuncios(){
		global $pdo;
		$sql = $pdo->query("SELECT COUNT(*) as c FROM anuncios");
		$row = $sql->fetch();

		return $row['c'];
	}

	public function getUltimosAnuncios($page, $perPage){
		global $pdo;
		
		$offSet = ($page - 1) * $perPage;

		$array = array();
		$sql = $pdo->prepare("SELECT *,(select anuncios_imagens.url from anuncios_imagens where anuncios_imagens.id_anuncio = anuncios.id limit 1)
			as url, (select categorias.nome from categorias where categorias.id = anuncios.id_categoria)as categoria FROM anuncios order by id DESC LIMIT $offSet, $perPage");
		$sql->execute();

		if($sql->rowCount() > 0){
			$array = $sql->fetchAll();
		}

		return $array;

	}
	
	public function getMeusAnuncios(){
		global $pdo;
		$array = array();

		$sql = $pdo->prepare("SELECT *,
									 (select anuncios_imagens.url from anuncios_imagens where anuncios_imagens.id_anuncio = anuncios.id limit 1) as url 
									 FROM anuncios
									 WHERE id_usuario = :id_usuario");
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->execute();

		if($sql->rowCount() > 0){
			$array = $sql->fetchAll();
		}

		return $array;

	}



	public function addAnuncio($titulo, $categoria, $valor, $descricao, $estado){
		global $pdo;

		$sql = $pdo->prepare("INSERT INTO anuncios SET titulo = :titulo, id_categoria = :id_categoria, id_usuario = :id_usuario, descricao = :descricao, valor = :valor, estado = :estado");
		$sql->bindValue(":titulo", $titulo);
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":descricao", $descricao);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":estado", $estado);
		$sql->execute();
	}


	public function excluirAnuncio($id){
		global $pdo;
		
		$sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
		$sql->bindValue(":id_anuncio", $id);
		$sql->execute();

		$sql = $pdo->prepare("DELETE FROM anuncios WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();
	}

	public function excluirFoto($id){
		global $pdo;
		$id_anuncio = 0;

		$sql = $pdo->prepare("SELECT id_anuncio FROM anuncios_imagens WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();
		
		if($sql->rowCount() > 0){
			$row = $sql->fetch();
			$id_anuncio = $row['id_anuncio'];
		}

		$sql = $pdo->prepare("DELETE FROM anuncios_imagens WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();

		return $id_anuncio;

	}


	public function getAnuncio($id){
		$array = array();
		global $pdo;
		
		$sql = $pdo->prepare("SELECT 
			*,(select categorias.nome from categorias where categorias.id = anuncios.id_categoria)as categoria,
			(select usuarios.telefone from usuarios where usuarios.id = anuncios.id_usuario)as telefone  
			FROM anuncios WHERE id = :id");
		$sql->bindValue(":id", $id);
		$sql->execute();
		
		if($sql->rowCount() > 0 ){
			$array = $sql->fetch();

			$sql = $pdo->prepare("SELECT id, url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio");
			$sql->bindValue(":id_anuncio", $id);
			$sql->execute();

			if($sql->rowCount() > 0){
				$array['fotos'] = $sql->fetchAll();
			}
		}

		return $array;
	}


	public function editAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id){
		global $pdo;

		$sql = $pdo->prepare("UPDATE anuncios SET titulo = :titulo, id_categoria = :id_categoria, id_usuario = :id_usuario, descricao = :descricao, valor = :valor, estado = :estado WHERE id = :id");
		$sql->bindValue(":titulo", $titulo);
		$sql->bindValue(":id_categoria", $categoria);
		$sql->bindValue(":id_usuario", $_SESSION['cLogin']);
		$sql->bindValue(":descricao", $descricao);
		$sql->bindValue(":valor", $valor);
		$sql->bindValue(":estado", $estado);
		$sql->bindValue(":id", $id);
		$sql->execute();

		if(count($fotos) > 0){
			for($q=0;$q<count($fotos['tmp_name']); $q++){
				$tipo = $fotos['type'][$q];
				if(in_array($tipo, array('image/jpeg', 'image/png'))){
					$tmpname = md5(time().rand(0,9999)).'.jpg';
					move_uploaded_file($fotos['tmp_name'][$q], 'assets/images/anuncios/'.$tmpname);
					//redimensionando a imagem
					list($width_orig, $height_orig) = getimagesize('assets/images/anuncios/'.$tmpname);
					$ratio = $width_orig / $height_orig;

					$width = 500;
					$height = 500;

					if($width/$height > $ratio){
						$width = $height*$ratio;
					}else{
						$height = $width/$ratio;
					}

					$img = imagecreatetruecolor($width, $height);
					if($tipo == 'image/jpeg'){
						$origi = imagecreatefromjpeg('assets/images/anuncios/'.$tmpname);
					}else{
						$origi = imagecreatefrompng('assets/images/anuncios/'.$tmpname);
					}
					//salva a imagem redimensionada
					imagecopyresampled($img, $origi, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

					imagejpeg($img, 'assets/images/anuncios/'.$tmpname, 80);

					//salva a imagem no banco
					$sql = $pdo->prepare("INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, url = :url");
					$sql->bindValue(":id_anuncio", $id);
					$sql->bindValue(":url", $tmpname);
					$sql->execute();
					header("Location: editar-anuncios.php");




				}	
			}
		}


	}






}


 ?>

 