<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class UsersDB extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getUser($email)
	{
		$select = [
			'users.*',
			'users_role.name as role_name',
			'users_role.role_access',
		];
		$query = $this->db->select($select)
			->from("users")
			->join("users_role", "users_role.id = users.role_id", "inner")
			->where("users.email", $email)
			->get()
			->first_row();

		return $query;
	}

	public function update_password($password, $id){
		$password_encrypt = setEncrypt($password);

		$data = [
			"password" => $password_encrypt
		];
		$source = $this->db->where('id', $id)->update('users', $data);

		return $source;
	}

	public function insertUser($params, $tables = 'users')
	{
		$this->db->insert($tables, $params);
		return $this->db->insert_id();
	}
}
