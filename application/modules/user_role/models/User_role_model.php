<?php
class User_role_model extends CI_Model
{

    public function getAll($keyword, $pagination, $column,$user)
    {
        if (!empty((array) json_decode($keyword))) {

            $w = 0;
            $where = "WHERE ";

            foreach (json_decode($keyword) as $key => $value) {

                $key = $key;
                if ($w == 0) {

                    if (is_array($value)) {
                        $where .= $key . " IN ('" . implode("','", $value) . "') ";
                    } else
                        $where .= $key . " LIKE '%" . $value . "%' ";
                } else {
                    $where .= " AND " . $key . " LIKE '%" . $value . "%' ";
                }

                $w++;
            }
        } else {
            $where = '';
        }

        if(decrypt_url($user['id']) != 1){
            if($where){
                $where .= "AND id != '1'";
            }else{
                $where .= "WHERE id != '1'";
            }
        }

        if ($pagination['page'] != null) {
            $skip = $pagination['page'] - 1;
            $skip = ($skip == 0) ? 0 : $skip * $pagination['limit'];
            $limit = " LIMIT " . $pagination['limit'] . " OFFSET " . $skip;
        } else
            $limit = "";

        $sql    = $this->db->query("SELECT * FROM users_role " . $where . " ORDER BY name ASC " . $limit)->result();
        $count = $this->db->query("SELECT count(*) total FROM users_role " . $where . " ")->result()[0]->total;

        $data = ['page' => $pagination['page'], 'total' => $count, 'data' => $sql];

        return $data;
    }

    public function add($data)
    {
        $res = $this->db->insert('users_role', $data);

        return $res;
    }
}