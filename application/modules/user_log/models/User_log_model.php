<?php
class User_log_model extends CI_Model
{
    public function getData($params,$users)
    {
        $start = ($params['offset'] - 1) * $params['limit'];
        $keyresult = (array)json_decode($params['keyword']);

        $this->db->select('name,ip,browser,controller_name,access_time');

        if (!empty($keyresult)) {
            foreach ($keyresult as $key => $value) {
                if($key == 'date'){
                    if($value){
                        $this->db->where("DATE_FORMAT(`access_time`,'%Y-%m-%d')", $value);
                        $dates = $value;
                    }
                }else{
                    if($value){
                        $this->db->like($key, $value);
                    }
                }
            }
        }
        
        $tot = clone $this->db;
        $this->db->order_by('access_time', 'DESC');
        $get_data = $this->db->limit($params['limit'], $start)->get('users_log')->result_array();
        $get_count = $tot->get('users_log')->num_rows();
        
        return [
            'total' => $get_count,
            'data' => !empty($get_data) ? $get_data : [],
            'message' => !empty($get_data) ? null : 'Data Tidak Ada!',
            'dates' => $dates
        ];
    }

    public function getheader(){
        $header  = array("No" => 'reset', "Full Name" => "name", "IP" => "ip", "Browser" => "browser", "Page" => "controller_name", "Date" => "date");    
        return $header;
    }

}
