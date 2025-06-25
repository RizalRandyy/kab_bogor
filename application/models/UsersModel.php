<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class UsersModel extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getUser($params)
	{
		$select = [
			'users.full_name',
			'users.email',
			'users.phone',
			'users_role.name as role_name'
		];
		$query = $this->db->select($select)
			->from("users")
			->join("users_role", "users_role.id = users.role_id", "inner")
			->where("users.email", $params)
			->or_where("users.email_kode", $params)
			->or_where("users.phone", $params)
			->get()
			->first_row();

		return $query;
	}

	public function register($params)
	{
		$this->db->insert("users", $params);
		return $this->db->insert_id();
	}

	public function update_user($params,$email)
	{
		$update = $this->db->where('email', $email)->or_where('email_kode', $email)->update('users', $params);
		return $update;
	}
}
