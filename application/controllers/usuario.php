<?php
	class Usuario extends CI_Controller
	{
		private $erro;

		public function __construct()
		{
			parent::__construct();

			$this->load->model('Usuarios');
			$this->load->model('Tweets');		
			$this->load->model('Seguidores');	

			$this->erro = '';
		} // function

		public function index()
		{
			// INICIALIZAR O VETOR DE DADOS
			$dados = array();
			$dados['nome'] = '';
			$dados['email'] = '';
			$dados['login'] = '';
			$dados['erro'] = $this->erro;

			// SE OS DADOS FOREM SUBMETIDOS
			if($this->input->post('nome'))
			{
				// CAPTIRAR OS DADOS QUE FORAM ENVIADOS
				$dados['nome'] = $this->input->post('nome');
				$dados['email'] = $this->input->post('email');
			}

			if ($this->input->post('login'))
			{
				$dados['login'] = $this->input->post('login');
			}

			// CARRWGAR A VIEW PASSANDO O VETOR DE DAODS
			$this->load->view('inicio', $dados);
		} // function

		public function criarconta()
		{
			// REGRAS PARA VALIDAR O USUÁRIO
			$this->_set_validation_rules('criarconta');

			// SE A VALIDAÇÃO DO FORMULÁRIO FOR BEM SUCEDIDA
			if ($this->form_validation->run())
			{
				// INSERIR OS DADOS NO BANCO DE DADOS
				$id = $this->Usuarios->insert(
					$this->input->post());

				// MONTAR O LINK PARA COMPLEMENTAR A CONTA
				$link = base_url() . 'usuario/completarconta/' .
					$id . '/' . md5($this->input->post('email'));

				// MOSTRAR O LINK
				echo '<a href="' . $link . '">' . $link . '</a>';
			} // if
			else 
			{
				// RECARREGA O FORMULÁRIO PARA EXIBIR OS ERROS DE VALIDAÇÃO
				$this->index();
			}
		} // function

		public function autenticar()
		{
			$this->_set_validation_rules('autenticar');

			if($this->form_validation->run())
			{
				log_message('debug', 'Form validated.');
				if(strpos($this->input->post('login'), '@'))
				{
					$usuario = $this->Usuarios->
					getByEmail($this->input->post('login'));
				}
				else 
				{
					$usuario = $this->Usuarios->
					getByLogin($this->input->post('login'));
				}

				if(!$usuario)
				{
					$this->erro = 'Usuário inexistente.';
					$this->index();
					return TRUE;
				}

				if($this->input->post('senha_') != $usuario->senha)
				{
					$this->erro = 'A senha não confere.';
					$this->index();
					return TRUE;
				}

				$this->session->set_userdata('user_id', 
					$usuario->codigo);
				redirect(base_url());
			}
			else 
			{
				$this->index();
			}
		} // function

		public function completarconta($id, $chave)
		{	
			// RECUPERAR O REGISTRO DO BANCO DE DADOS
			$usuario = $this->Usuarios->get($id);

			// VERIFICAR SE A CHAVE DE SEGURANÇA CONFERE
			if($chave == md5($usuario->email))
			{
				// MONTA O VETOR DE DADOS
				$dados = array();
				$dados['usuario'] = $usuario;
				// CARREGAR A VIEW
				$this->load->view('criarconta', $dados);
			}
			else 
			{
				// EXIBIR UMA MENSAGEM DE ERRO
				echo "Chave inválida.";
			}
		} // function

		public function gravarcontacompleta()
		{
			$this->_set_validation_rules('completarconta');

			if ($this->form_validation->run())
			{
				// ATUALIZAÇÃO DE DADOS (COMPLEMENTAR A CONTA)
				$this->Usuarios->update($this->input->
					post('codigo'), $this->input->post());

				// GRAVAR OS DADOA NA SESSÃO ("SESSION")
				$this->session->set_userdata(
					array(
						'user_id' => $this->input->post('codigo')
						)
					);

				// REDIRECIONAR PARA A TIMELINE
				redirect(base_url());
			}
			else {
				// RECARREGA O FORMULÁRIO PARA EXIBIR OS ERROS DE VALIDAÇÃO
				$this->criarconta();
			}
		} // FUNCTION

		private function _set_validation_rules($grupo)
		{
			$rules = array(
				'criarconta' => array(
					array(
						'field' => 'nome',
						'label' => 'Nome',
						'rules' => 'required|min_length[5]'
					),
					array(
						'field' => 'email',
						'label' => 'e-mail',
						'rules' => 
							'required|valid_email|
							is_unique[usuarios.email]'
					),
					array(
						'field' => 'senha',
						'label' => 'Senha',
						'rules' => 'required|min_length[6]'
					)
				),
				'gravarcontacompleta' => array(
					array(
						'field' => 'nome',
						'label' => 'Nome',
						'rules' => 'required|min_length[5]'
					),
					array(
						'field' => 'email',
						'label' => 'e-mail',
						'rules' => 
							'required|valid_email|
							is_unique[usuarios.email]'
					),
					array(
						'field' => 'login',
						'label' => 'Nome de usuário',
						'rules' => 'required|min_length[6]|callback_login_check'
					)
				),
				'autenticar' => array(
					array(
						'field' => 'login',
						'label' => 'Nome de usuário ou e-mail',
						'rules' => 'required'.
						(strpos($this->input->post('login'), '@') ? 
							'|valid_email' : '|callback_login_check')
					),
					array(
						'field' => 'senha_',
						'label' => 'Senha',
						'rules' => 'required|min_length[6]'
					)
				),
				'postartweet' => array(		
					array(
						'field' => 'texto',
						'label' => 'Tweet',
						'rules' => 'required|min_length[1]'
					)
				),
				'buscar' => array(	
					array(
						'field' => 'buscar',
						'label' => 'Buscar',
						'rules' => 'required|min_length[1]'
					)
				)
			);

			$this->form_validation->set_rules($grupo);
		} // FUNCTION

		public function login_check($str)
		{
			if(preg_match('/[A-Za-z0-9]+/', $str))
			{
				return TRUE;
			}
			else 
			{
				$this->form_validation->set_message('login_check', 
					'O login pode conter apenas letras e números e não pode inlcuir espaços.');
				return FALSE;
			}
		} // FUNCTION


		public function sair()
		{
			$this->session->sess_destroy();
			redirect(base_url());
		}
        
        // FUNÇÃO PARA POSTAR UM TWEET
		public function postartweet()		

		{
			// REGRAS PARA VALIDAÇÃO DO FORMULÁRIO
			$this->_set_validation_rules('postartweet');

			// SE A VALIDAÇÃO DO FORMULÁRIO FOI BEM SUCEDIDA
			if ($this->form_validation->run())
			{
				$dados = array();
				$dados["texto"]=$this->input->post("texto");
				$dados["codigo_usuario"]=$this->session->userdata("user_id");
				$dados["data_hora_postagem"]=date("Y-m-d h:i:s");
				// INSERIR OS DADOS NO BANCO DE DADOS
				$id = $this->Tweets->insert($dados);

				redirect(base_url());
			}
		}
        
        // FUNÇÃO PARA SEGUIR UM USUÁRIO
		public function seguir()
		{
			$dados["codigo_seguidor"]=$this->session->userdata("user_id");
			$dados["codigo_seguido"]=$this->input->post("codigo");
			$id = $this->Seguidores->insert($dados);

			redirect(base_url());				
		}

		public function naoseguir()
		{
			$id_seguidor = $this->session->userdata("user_id");
			$id_seguido = $this->input->post("codigo");
			$id = $this->Seguidores->delete($id_seguidor,$id_seguido);

			redirect(base_url());
		}

        // FUNÇÃO PARA BUSCAR USUÁRIO   
		public function buscar()
		{ 
			// REGRAS PARA VALIDAÇÃO DO FORMULÁRIO
			$this->_set_validation_rules('buscar');

			// SE A VALIDAÇÃO DO FORMULÁRIO FOI BEM SUCEDIDA
			if ($this->form_validation->run())
			{
				
				// PROCURAR OS DADOS NO BANCO DE DADOS

				$usuario = $this->Usuarios->get($this->session->userdata('user_id'));
				$dados = array();
				$dados['usuario']        = $usuario;
				$dados['num_seguidores'] = $this->Seguidores->countFollowers($usuario->codigo);
				$dados['num_seguindo']   = $this->Seguidores->countFollowing($usuario->codigo);
				$dados['num_tweets']     = $this->Tweets->countByUser($usuario->codigo);
				
				$resultados = $this->Usuarios->buscar($this->input->post("buscar"));

				foreach ($resultados as $resultado) {
					$resultado->num_seguidores=$this->Seguidores->countFollowers($resultado->codigo);
					$resultado->num_seguindo=$this->Seguidores->countFollowing($resultado->codigo);
					$resultado->num_tweets=$this->Tweets->countByUser($resultado->codigo);
					//$resultado->codigo_seguidor=$this->Seguidores->getBySeguido($resultado->codigo)->codigo_seguidor;
					
					if (!$this->Seguidores->verificarSeguidor(
						$this->session->userdata('user_id'),
						$resultado->codigo)){
						$resultado->seguindo = FALSE;
					}
					else{
						$resultado->seguindo = TRUE;
					}


				}
				$dados["resultados"]=$resultados;
				$this->load->view("principal",$dados);

				
			}
		}


	} // CLASSE ENCERRADA

/* End of file usuario.php */