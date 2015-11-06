<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 11/6/2015
 * Time: 12:18 AM
 */
class Chat_model extends CI_Model{
    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->helper("date");
    }

    public function retrieve($input_id, $user_type){
        if(isset($input_id)&& isset($user_type)){
            $sql = "select customer_id as user1, ".//convert customer id to user1
                "pm_id as user2, project_id, to_pm, body as content, ".
                "seen, time_created as timestamp from message ".
                "where pm_id=? or customer_id=? order by time_created";
            /*use trick to bind query*/
            $binding = $user_type=="pm"? [$input_id,-1]:[-1,$input_id];
            $query = $this->db->query($sql,$binding);
            //print_r($query);
            if( $query->num_rows()>0){
                $tMsgs = [];
                $result = $query->result_array();
                /*translate direction to author id*/
                foreach( $result as $rKey=>$row){
                    /*depends on uer's nature and direction*/
                    if(($user_type!="pm" && $row["to_pm"]==1)
                            || $user_type=="pm" && $row["to_pm"]==0)
                        $result[$rKey]["author_id"]=$row["user1"];
                    else $result[$rKey]["author_id"]=$row["user2"];
                }
                /*group chats into threads based on other user.
                One other user means another thread*/
                $other_user = $user_type=="pm"? "user1":"user2";
                foreach ($result as $rKey=>$row){
                    if(isset($threads[$row[$other_user]]))
                        array_push($tMsgs[$row[$other_user]], $row);
                    else
                        $tMsgs[$row["user1"]] = [$row];
                }
                /*sort to make sure messages in time sequence*/
                uasort($tMsgs, function ($a, $b){
                    if ($a["timestamp"] == $b["timestamp"]) return 0;
                    return ($a["timestamp"] < $b["timestamp"]) ? -1 : 1;
                });
                $threads = [];
                foreach($tMsgs as $key=>$value){
                    $last_message = end($value);
                     array_push($threads,[
                         'user1'         => $last_message["user1"],
                         'user2'         => $last_message["user2"],
                         'seen'          => $last_message["seen"],
                         'lastMsgTimeStamp' => $last_message["timestamp"],
                         'lastMessage'   => $last_message["content"],
                         'messages'      => $value
                    ]);
                }
                return $threads;
            }
        }
        return null;
    }
    public function write(array $values){
        if(isset($values)){
            $values["m_author"] = $values["m_author"]==$values["user1"]? 1:0;
            $sql = "insert into message (customer_id, pm_id, project_id, to_pm, body, file_id, timestamp) VALUES (?, ?, 0, ?,?,0, ?)";
            $this->db->query($sql,[$values["user2"],$values["user1"],$values["user2"],$values["m_author"]],time());
            echo $this->db->_error_message();
        }
    }
}