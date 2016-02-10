<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/17/2015
 * Time: 4:16 PM
 */


class Internal_user_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve($input_u_id){

        if(isset($input_u_id)){
            $query = $this->db->get_where("internal_user",["u_id"=>$input_u_id]);
            if($query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieveAll($only_active=true,$limit=0,$offset=0){
        $where = [];
        if(isset($input_c_id)){
            if($only_active){
                $where["is_active"]=1;
            }
        }
        $query = $this->db->get_where("internal_user",$where,$limit,$offset);
        return $query->result_array();
    }

    /**
     * @param $update_array including all attributes including u_id
     * @return affected rows
     */
    public function update($update_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $this->db->update('internal_user', $update_array, array('u_id' => $update_array['u_id']));
        return $this->db->affected_rows();
    }

    /**
     * @param $insert_array all attributes. must not include u_id
     * @return bool
     */
    public function insert($insert_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $insert_array['last_updated'] = $date->format('c');
        return $this->db->insert('internal_user', $insert_array);
        //echo var_dump($this->db->error());
    }

    /**
     * @param $input_c_id
     * @return affected rows
     */
    public function deactivate($input_c_id){
        $this->db->update('customer', ["is_active"=>0], array('c_id' => $input_c_id));
        return $this->db->affected_rows();
    }

    public function retrieve_by_username($username){

        if(isset($username)){
            $query = $this->db->get_where("internal_user",["username"=>$username]);
            if($query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_by_bb_username($bb_username){

        if(isset($bb_username)){
            $query = $this->db->get_where("internal_user",["bb_username"=>$bb_username]);
            if($query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_by_email($email){

        if(isset($email)){
            $query = $this->db->get_where("internal_user",["email"=>$email]);
            if($query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }

    public function retrieve_all_pm(){
        $query = $this->db->query("SELECT * FROM internal_user where type='PM' and is_active=1");
        return $query->result_array();
    }
    public function retrieve_by_type($type){

        if(isset($type)){
            $query = $this->db->get_where("internal_user",["type"=>$type, "is_active"=>1]);
            if($query->num_rows()>0){
                return $query->result_array();
            }
        }
        return null;
    }
    public function retrieve_name($uid){
        $query = $this->db->query("SELECT name from internal_user where u_id =".$uid);
        if($query->num_rows()>0){
            $return_array = $query->result_array();
            return $return_array[0]['name'];
        }
    }
}