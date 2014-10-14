<?php
	class Usuarios extends CI_Model
	{
		public function insert($dados)
		{
			$this->db->insert('usuarios', $dados);
			return $this->db->insert_id();
		}

		public function update($id, $dados)
		{
			return $this->db->where('codigo', $id)->
				update('usuarios', $dados);
		}

		public function get($id)
		{
			return $this->db->where('codigo', $id)->get('usuarios')->row();
		}

		public function getByEmail($email)
		{
			return $this->db->where('email', $email)->get('usuarios')->row();
		}

		public function getByLogin($login)
		{
			return $this->db->where('login', $login)->get('usuarios')->row();
		}

		public function buscar($name)
		{
			return $this->db->like('nome', $name)->get('usuarios')->result();
		}
	}
	

/* End of file usuarios.php */