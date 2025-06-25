<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


Class Logusers {

    protected $CI;
    protected $user_id = '';
    protected $email = '';
    protected $user_name = '';

    public function __construct()
    {
        $this->CI = &get_instance();
    }


    public function tracking_user_method()
    {
        if(is_cli()) return false;
        
        date_default_timezone_set('Asia/Jakarta');

        $temp_session = @$this->CI->session->userdata('kab_bogor') ? : '';
        $cek = $temp_session ? decrypt_url($temp_session['id']) : 0;
        if ($cek != 1){
            if ($this->CI->router->method != 'index'){
                $inputDB = array(
                    'email' => $temp_session ? $temp_session['email'] : null,
                    'name'  => $temp_session ? $temp_session['full_name'] : null,
                    'users_id' => $temp_session ? decrypt_url($temp_session['id']) : null,
                    'ip' => $this->CI->input->ip_address(),
                    'browser' => $_SERVER['HTTP_USER_AGENT'],
                    'folder_access' => $this->CI->uri->segment(1),
                    'controller_name' => $this->CI->router->class,
                    'methode' => $this->CI->router->method,
                    'access_time' => date('Y-m-d H:i:s'),
                    'post_data' => ($this->CI->router->class == 'login') ? json_encode(array()) : json_encode($this->CI->input->post()),
                    'get_data' => json_encode($this->CI->input->get())
                );

                $this->CI->db->insert("users_log", $inputDB);
            }
        }
    }

}

?>