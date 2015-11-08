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
        $this->load->library("session");
    }

    public function retrieve($input_id, $user_type){
        if(isset($input_id)&& isset($user_type)){
            $sql = "select c.first_name as user1, customer_id, ".//convert customer id to user1
                "i.name as user2,pm_id , project_id, to_pm, body as content, ".
                "seen, time_created as timestamp from message m ".
                ", customer c, internal_user i where m.pm_id=i.u_id and m.customer_id=c.c_id ".
                " and (pm_id=? or customer_id=? )".
               " order by time_created";
            /*use trick to bind query*/
            $filter = $user_type=="pm"? [$input_id,-1]:[-1,$input_id];
            $query = $this->db->query($sql,$filter);

            if( $query->num_rows()>0){
                $tMsgs = [];
                $result = $query->result_array();

                /*translate direction to author id*/
                foreach( $result as $rKey=>$row){
                    /*depends on uer's nature and direction*/
                    if ($row["to_pm"]==1)$result[$rKey]["author"]=$row["user1"];
                    else $result[$rKey]["author"]=$row["user2"];
                    //unset($result[$rKey]["to_pm"]);
                }

                /*group chats into threads based on other user.
                One other user means another thread*/
                $other_user = $user_type=="pm"? "user1":"user2";
                $i = 1;
                foreach ($result as $rKey=>$row){
                    $row["msgID"] = $i;
                    $row["seen"]   = $row["seen"]==1?true:false;
                    if(isset($tMsgs[$row[$other_user]])) {
                        array_push($tMsgs[$row[$other_user]], $row);

                    }
                    else {
                        $tMsgs[$row[$other_user]] = [$row];
                    }
                    //echo "push";
                    //print_r($row);
                    $i++;
                }
                //print_r($tMsgs);
                /*sort to make sure messages in time sequence*/
//                foreach($tMsgs as &$value){
//                    uasort($value, function ($a, $b){
//                        if ($a["timestamp"] == $b["timestamp"]) return 0;
//                        return ($a["timestamp"] < $b["timestamp"]) ? -1 : 1;
//                    });
//                }

                $threads = [];
                $k = 1;
                foreach($tMsgs as $key=>$value){
                    $last_message = end($value);
                     array_push($threads,[
                         'chatID'        => $k,
                         'user1'         => $last_message["user1"],
                         'user2'         => $last_message["user2"],
                         'seen'          => $last_message["seen"]==1?true:false,
                         'lastMsgTimeStamp' => $last_message["timestamp"],
                         'lastMessage'   => $last_message["content"],
                         'messages'      => $value
                    ]);
                    $this->session->set_userdata("chat_id_".$k,[
                        'customer_id'=> $last_message["customer_id"],
                        'pm_id'=> $last_message["pm_id"]
                    ]);
                    $k++;
                }
                return $threads;
            }
        }
        return null;
    }
    public function write(array $values){
        $fromSession = $this->session->userdata("chat_id_".$values["chat_id"]);
        print_r($values);
        print_r("space\n\n\n");
        print_r($fromSession);

        if(isset($values)){
            $message =[
                "customer_id"=>$fromSession["customer_id"],
                "pm_id"=>$fromSession["pm_id"],
                "project_id"=>0,
                //"to_pm"=>($values["user2"]==$values["m_author"])?0:1,
                "to_pm"=>($values["m_author"]==$values["m_author"])?0:1,
                "body"=> $values["m_content"],
                "time_created"=>time()

            ];
            print_r($message);
//            $sql = "insert into message (customer_id, pm_id, project_id, to_pm, body, file_id, timestamp) VALUES (?, ?, 0, ?,?,0, ?)";
//            $this->db->query($sql,[$values["user2"],$values["user1"],$values["user2"],$values["m_author"]],time());
            $this->db->insert("message",$message);
            print_r($this->db->error());
        }
    }
}