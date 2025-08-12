<?php
class User_manage_model extends CI_Model
{
	public function getUsers($params, $user)
	{
		$start = ($params['offset'] - 1) * $params['limit'];
		$selector = [
			'users.id',
			'users.full_name',
			'users.nick_name',
			'users.phone',
			'users.email',
			'users.last_login',
			'users.role_id',
			'ur.name as role_name',
			'users.status',
			'users.photo'
		];
		/* end assets */
		$keyresult = (array)json_decode($params['keyword']);

		$this->db->select($selector)
			->from('users')
			->join('users_role ur', 'ur.id = users.role_id');

		if (decrypt_url($user['id']) != 1) {
			$this->db->where('users.id !=', '1');
		}

		if (!empty($keyresult)) {
			foreach ($keyresult as $key => $value) {
				if ($value) {
					if ($key == 'role_name') {
						$this->db->like('ur.name', $value);
					} else {
						$this->db->like($key, $value);
					}
				}
			}
		}
		$this->db->limit($params['limit'], $start)
			->order_by('users.full_name', 'asc');
		$get_data = $this->db->get()->result_array();

		// print_r($this->db->last_query()); exit();
		$this->db->select('users.id')
			->from('users')
			->join('users_role ur', 'ur.id = users.role_id')
			->where('users.id !=', '1');
		if (!empty($keyresult)) {
			foreach ($keyresult as $key => $value) {
				if ($value) {
					if ($key == 'role_name') {
						$this->db->like('ur.name', $value);
					} else {
						$this->db->like($key, $value);
					}
				}
			}
		}
		$this->db->order_by('users.full_name', 'asc');
		$get_count = $this->db->get()->num_rows();

		if ($get_count < 1) {
			return [
				'status' => 200,
				'message' => 'User Not Found!',
				'data' => [],
				'count' => 0,
			];
		}

		if (!empty($get_data)) {
			foreach ($get_data as $key => $value) {
				$get_data[$key]['id'] = encrypt_url($value['id']);
			}
		}

		return [
			'status' => 200,
			'message' => null,
			'data' => $get_data,
			'count' => $get_count
		];
	}

	public function getRoleUsers($where = false)
	{
		$this->db->select('id,name');
		if (!empty($params)) {
			$this->db->where('name', $params);
		}

		$data = $this->db->get('users_role')->result_array();

		return [
			'status' => !empty($data) ? 200 : 400,
			'message' => !empty($data) ? null : 'List users role not found!',
			'data' => !empty($data) ? $data : [],
		];
	}

	public function saveUser($data, $id_users = false)
	{
		$data['email_kode'] = null;
		$data['email_active'] = 1;

		date_default_timezone_set('Asia/Jakarta');
		$data['created_at'] = date('Y-m-d H:i:s');

		if (isset($data['password'])) {
			$data['password'] = setEncrypt($data['password']);
		} else {
			unset($data['password']); // jangan update password jika kosong
		}

		$data['phone'] = (int) $data['phone'];

		if ($id_users) {
			// update user
			$this->db->where('id', $id_users);
			if ($this->db->update('users', $data)) {
				return [
					'status' => true,
					'message' => 'Update user berhasil'
				];
			}
		} else {
			// insert user
			if ($this->db->insert('users', $data)) {
				return [
					'status' => true,
					'message' => 'Tambah user berhasil'
				];
			}
		}

		return [
			'status' => false,
			'message' => 'Gagal menyimpan user'
		];
	}


	public function saveChangeRole($id_user, $data)
	{
		$id_user = decrypt_url($id_user);
		if ($this->db->where('id', $id_user)->update('users', $data)) {
			return [
				'status' => 200,
				'message' => 'Changes role success',
			];
		}

		return [
			'status' => 500,
			'message' => 'Changes role failed, error code: 500'
		];
	}

	public function getheader()
	{
		$header  = array("No" => 'reset', "Name" => "users.full_name", "Email" => "users.email", "Last Login" => "users.last_login", "Role" => "role_name");
		return $header;
	}

	public function deleteUser($id)
	{
		$id = decrypt_url($id);
		$this->db->where('id = ', $id);
		$res = $this->db->delete('users');
		if ($res) {
			return [
				'status' => true,
				'message'	=> "Delete User successfull"
			];
		} else {
			return [
				'status' => false,
				'message'	=> "Delete User failed"
			];
		}
	}

	public function update_password($password, $id)
	{
		$password_encrypt = setEncrypt($password);
		$id = decrypt_url($id);

		$data = [
			"password" => $password_encrypt
		];
		$source = $this->db->where('id', $id)->update('users', $data);

		return $source;
	}
}
