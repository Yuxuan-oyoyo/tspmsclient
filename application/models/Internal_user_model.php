<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/17/2015
 * Time: 4:16 PM
 */

namespace models;


class Internal_user_model
{
    public function __construct(){
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve($input_u_id){
        if(isset($input_c_id)){
            $query = $this->db->get_where("internal_user",["u_id"=>$input_u_id]);
            if($row= $query->num_rows()>0){
                return $query->row_array;
            }
        }
        return null;
    }

    /**
     * @param $update_array including all attributes including u_id
     * @return affected rows
     */
    public function update($update_array){
        $this->db->set('last_updated', mdate());
        $this->db->update('internal_user', $update_array, array('u_id' => $update_array['u_id']));
        return $this->db->affected_rows();
    }

    /**
     * @param $insert_array all attributes. must not include u_id
     * @return bool
     */
    public function insert($insert_array){
        $this->db->set('last_updated', mdate());
        return $this->db->insert('internal_user', $insert_array);
    }

    /**
     * @param $input_c_id
     * @return affected rows
     */
    public function deactive($input_c_id){
        $this->db->update('customer', ["is_active"=>0], array('c_id' => $input_c_id));
        return $this->db->affected_rows();
    }
}