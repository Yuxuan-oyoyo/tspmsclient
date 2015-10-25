<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/17/2015
 * Time: 3:11 PM
 */

//namespace model;


class Customer_model extends CI_Model
{
//    public $c_id;
//    public $tile;
//    public $first_name;
//    public $last_name;
//    public $company_name;
//    public $password_hash;
//    public $hp_number;
//    public $other_number;
//    public $email;
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }
    public function retrieve($input_c_id){
        if(isset($input_c_id)){
            $query = $this->db->get_where("customer",["c_id"=>$input_c_id]);
            if( $query->num_rows()>0){
                return $query->row_array();
            }
        }
        return null;
    }
    public function retrieve_by_username($username){
            $query = $this->db->get_where("customer",["username"=>$username]);
            return $query->row_array();
    }

    public function retrieveAll($only_active=true,$limit=0,$offset=0){
        $where = [];
        if(isset($input_c_id)){
            if($only_active){
                $where["is_active"]=1;
            }
        }
        $query = $this->db->get_where("customer",$where,$limit,$offset);
        return $query->result_array();
    }

    public function update($update_array){
        $date = date('Y-m-d H:i:s');
        $update_array['last_updated'] = $date;
        $this->db->update('customer', $update_array, array('c_id' => $update_array['c_id']));
        return $this->db->affected_rows();
    }
    public function insert($insert_array){

        $date = date('Y-m-d H:i:s');
        $update_array['last_updated'] = $date;
        $this->db->insert('customer', $insert_array);
        return $this->db->insert_id();
    }
    //not in use
    public function deactivate($input_c_id){
        $date = date('Y-m-d H:i:s');
        $update_array['last_updated'] = $date;
        $this->db->update('customer', ["is_active"=>0], array('c_id' => $input_c_id));
        return $this->db->affected_rows();
    }
    private function field_check($customer_array){
        $fields=['c_id','title','first_name','last_name','company_name','password_hash'
            ,'hp_number','email','is_active'];
        foreach( $fields as $field){
            if(!array_key_exists($field,$customer_array)){
                return false;
            }
        }
        return true;
    }
    public function delete($input_c_id){
        $this->db->delete("customer",['c_id'=>$input_c_id]);
        return $this->db->affected_rows();
    }
}