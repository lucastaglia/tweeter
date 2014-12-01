<?php
 //require_once("../config/databse.php");

 if (isset($_GET["cods"])){
 	
 	$codigo = $_GET["cods"];

 	$server = "localhost";
 	$user = "tweeter";
    $senha = "123";
    $base = "tweeter";

    $conexao = mysql_connect($serer, $user, $senha) or die("Erro na conexão!");
    mysql_select_db($base);

    if (empty($codigo)){
    	$sql = "select * from tweets";
    } else {
    	$sql = "select usuarios.nome, tweets.texto
    			from seguidores,usuarios,tweets
    			where seguidores.codigo_seguidor = $codigo and
    			      usuarios.codigo = seguidores.codigo_seguido and
    			      tweets.codigo_usuario = seguidores.codigo_seguido

    			union

    			select usuarios.nome, tweets.texto
    			from seguidores,usuarios,tweets
    			where seguidores.codigo_seguidor = $codigo and
    			      usuarios.codigo = seguidores.codigo_seguidor and
    			      tweets.codigo_usuario = seguidores.codigo_seguidor
               ";
    }

    sleep(1);

    $result = mysql_query($sql);
    $cont = mysql_affected_rows($conexao);

  if ($cont > 0) {
      $restweet = "<div class=\"panel-body\">";

      while ($linha = mysql_fetch_array($result)) {
		  	$restweet .= "<div class=\"col-lg-4 col-md-4 col-sm-4\">";
	      	$restweet .= "<label for=\"nome\">Nome</label>";
	    	$restweet .= "<input type=\"text\" name=\"nome\" id=\"nome\" readonly";
	    	$restweet .= "class=\"form-control\" placeholder=\"nome\" value='".utf8_encode($linha["nome"])."'>";
	        $restweet .= "</div>";
	        $restweet .= "<div class=\"col-lg-4 col-md-4 col-sm-4\">";
	    	$restweet .= "<input type=\"text\" name=\"texto\" id=\"texto\" readonly";
	    	$restweet .= "class=\"form-control\" placeholder=\"texto\" value='".utf8_encode($linha["texto"])."'>";
	        $restweet .= "</div>";

	    }

	    echo $restweet .= "</div>";
   } else {
   	 echo "Não foram encontrados registros";
   }
                                   
 }


?>