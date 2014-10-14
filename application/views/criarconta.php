<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <title>Criação de conta</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?=base_url();?>css/bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
      body {
        padding-top: 70px;
      }
      .limitado {
        max-width: 1200px;
      }
      div.panel-heading {
        background-color: white !important;
      }
      button.btn-warning {
        background: linear-gradient(yellow, orange) !important;
        border: 1px solid orange;
        color: black;
      }
    </style>
  </head>
  <body>
  	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container limitado">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Tweeter</a>
        </div>

        
      </div><!-- /.container-fluid -->
    </nav>

    <div class="container w960">
      <div class="panel panel-default">
        <div class="panel-heading">
          <big>Complementar conta</big>
        </div>
        <div class="panel-body">

          <form role="form" method="post"
            action="<?=base_url();?>usuario/gravarcontacompleta">
            <input type="hidden" name="codigo"
              id="codigo" value="<?=$usuario->codigo?>">
            <div class="form-group">
              <label for="nome">Nome completo</label>
              <input type="text" name="nome" id="nome" 
              class="form-control" placeholder="Nome completo"
              value="<?=$usuario->nome?>">
            </div>
            <div class="form-group">
              <label for="email">e-mail</label>
              <input type="text" name="email" id="email" 
              class="form-control" placeholder="e-mail"
              value="<?=$usuario->email?>">
            </div>
            <div class="form-group">
              <label for="login">Nome de usuário</label>
              <input type="text" name="login" id="login" 
              class="form-control" placeholder="Login">
            </div>
            <div class="form-group">
              <label for="descricao">Descrição</label>
              <textarea name="descricao" id="descricao"
                class="form-control" rows="5" 
                placeholder="Descrição detalhada da conta"></textarea>
            </div>
            <div class="button-group">
              <button type="submit" 
                class="btn btn-warning pull-right">Gravar
              </button>
            </div>
          </form>
        </div>
      </div><!-- panel -->
    </div><!-- container -->

  	<script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
  </body>
</html>