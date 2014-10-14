<?php
	class Inicio extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();

			$this->load->model('Usuarios');
			$this->load->model('Seguidores');
			$this->load->model('Tweets');
		} // FUNCTION

		public function index()
		{
			if ($this->session->userdata('user_id'))
			{
				$usuario = $this->Usuarios->get($this->session->userdata('user_id'));
				$dados = array();
				$dados['usuario']        = $usuario;
				$dados['num_seguidores'] = $this->Seguidores->countFollowers($usuario->codigo);
				$dados['num_seguindo']   = $this->Seguidores->countFollowing($usuario->codigo);
				$dados['num_tweets']     = $this->Tweets->countByUser($usuario->codigo);

				$this->load->view('principal', $dados);
				return TRUE;
			}

			// REDIRECIONA A PÁGINA CADASTRO / AUTENTICAÇÃO DE USUÁRIO
			redirect(base_url().'usuario');
		} // function
	}

/* End of file usuarios.php */