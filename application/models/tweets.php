<?php
	class Tweets extends CI_Model
	{
		public function countByUser($id_usuario)
		{
			return $this->db->where('codigo_usuario', $id_usuario)->count_all_results('tweets');
		}

		public function insert($dados)				
		{
			$this->db->insert('tweets', $dados);
			return $this->db->insert_id();
		}

	}

/* End of file tweets.php */