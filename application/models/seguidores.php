<?php
	class Seguidores extends CI_Model
	{
		public function countFollowers($id_usuario)
		{
			return $this->db->where('codigo_seguido', $id_usuario)->
				count_all_results('seguidores');
		}

		public function countFollowing($id_usuario)
		{
			return $this->db->where('codigo_seguidor', $id_usuario)->
				count_all_results('seguidores');
		}

		public function insert($dados)
		{
			return $this->db->insert('seguidores', $dados);
		}

		public function delete($id_seguidor, $id_seguido)
		{
			return $this->db->where(array('codigo_seguidor' => $id_seguidor, 
				'codigo_seguido' => $id_seguido))->delete('seguidores');
		}

		
		public function verificarSeguidor($seguidor,$seguido){
			return $this->db->where(array(
				'codigo_seguidor' => $seguidor,
				'codigo_seguido' => $seguido))->
			get('seguidores')->row();
		}
	}