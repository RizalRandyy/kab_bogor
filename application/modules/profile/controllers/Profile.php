<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_get();

class Profile extends My_Controller {

    
    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index_get()
    {
        $this->data['title'] = 'profile';
        $this->data['version'] = $this->uri->segment(1);


        $this->data['js'] = array(
            'assets/js/app/profile.js?'.rand(),
            'assets/plugins/sweetalert2/dist/sweetalert2.all.min.js',
        );  

        $this->data['css'] = array(
        );

        $this->data['id_user'] = $this->data['users']['id'];
        $this->template->load($this->data, null, 'index');

    }

    public function detail_user_post(){
        $id = $this->post('id', TRUE);

        $detail = $this->User_model->detail_user($id);

        if($detail){
            $this->set_response(array(
                'status'    => true,
                'message'   => '',
                'data'      => $detail
            ));
        }else{
            $this->set_response(array(
                'status'    => False,
                'message'   => 'Article Not Found'
            ));
        }
    }

    public function getRoleUsers_get()
    {
        $return = $this->User_model->getRoleUsers();
        $this->response($return, $return['status']);
    }

    public function save_user_post()
    {   
        $data = $this->post(NULL, TRUE);

        // $path = realpath(APPPATH . '../resources/img/profile');
        // $filename = '';
        // if (!empty($_FILES) && empty($this->post('photo'))) {
        //     $temp = explode(".", $_FILES["photo"]["name"]);
        //     $filename = "profile_" . substr(md5(time()), 0, 10) . '.' . end($temp);

        //     if (!file_exists($path) && !is_dir($path)) {
        //         mkdir($path, 0777, true);
        //     }

        //     if (move_uploaded_file($_FILES['photo']['tmp_name'], $path . '/' . $filename)){
        //         $data['photo'] = $filename;
        //     }
        // }

        $id_user = !empty($data['id']) ? $data['id'] : false;
        unset($data['id']);

        $return = $this->User_model->saveUser($data, $id_user);
        $this->response($return);
    }

    public function update_password_post()
    {
        $id = $this->input->post('id', TRUE);
        $password = $this->input->post('password', TRUE);
        $source = $this->User_model->update_password($password, $id);
        if($source){
            $this->set_response(array(
                'status'    => true,
                'message'   => 'password changed successfully'
            ));
        }else{
            $this->set_response(array(
                'status'    => failed,
                'message'   => 'password failed to change'
            ));
        }
    }

}
