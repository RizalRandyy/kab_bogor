<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function savearticle($data)
    {
        if (empty($data['id']))
            $source = $this->db->insert('article', $data);
        else
            $source = $this->db->where('id', $data['id'])->update('article', $data);

        if($source){
            if (empty($data['id'])){
                $last_id = $this->db->insert_id();
            }else{
                $last_id = $data['id'];
            }
        }else{
            $last_id ='';
        }

        return $last_id;
    }

    public function detail_user($id){
        $id = decrypt_url($id);

        $this->db->select("id,full_name,nick_name,email,phone,photo");
        $this->db->where("id",$id);
        $data = $this->db->get('users')->result_array();

        $data = $data[0];
        $data['id'] = encrypt_url($data['id']);
        $kode = $data['phone'] ? substr($data['phone'],0,2):'';

        if($kode == '62'){
            $data['phone'] = substr($data['phone'],2);
        }

        return $data;
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
        $data['phone'] = (int) $data['phone'];
        if(!empty($data['photo'])){
            $photo = explode("/",$data['photo']);

            $data['photo'] = '/resources/img/profile/'.end($photo);
        }

        if($id_users){
            $id_users = decrypt_url($id_users);

            $this->db->select('*');
            $this->db->where('id !=', $id_users);
            $this->db->group_start();
            $this->db->where('email', $data['email']);
            $this->db->or_where('phone', $data['phone']);
            $this->db->group_end();
            $validation_user = $this->db->get('users')->first_row();

        }else{
            $this->db->select('*');
            $this->db->group_start();
            $this->db->where('email', $data['email']);
            $this->db->or_where('phone', $data['phone']);
            $this->db->group_end();
            $validation_user = $this->db->get('users')->first_row();
        }


        if ($validation_user) {
            if($validation_user->email == $data['email']){
                $msg = 'Maaf email sudah terdaftar!';
            }else{
                $msg = 'Maaf No Telepon sudah terdaftar!';
            }
            return [
                'status' => false,
                'message' => $msg
            ];
        }

        if($id_users){
            $this->db->where('id', $id_users);
            if ($this->db->update('users', $data)) {
                return [
                    'status' => true,
                    'message' => 'Edit User berhasil'
                ];
            }
            $method = 'Edit';
        }else{
            if ($this->db->insert('users', $data)) {
                return [
                    'status' => true,
                    'message' => 'Tambah User berhasil'
                ];
            }
            $method = 'Tambah';
        }

        return [
            'status' => false,
            'message' => $method.' User Gagal'
        ];
    }

    public function update_password($password, $id){
        $password_encrypt = setEncrypt($password);
        $id = decrypt_url($id);

        $data = [
            "password" => $password_encrypt
        ];
        $source = $this->db->where('id', $id)->update('users', $data);

        return $source;
    }
}
