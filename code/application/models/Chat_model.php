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

    public function convo_pm()
    {
        // create dictionary of id:username
        $partners = [];



        $query = $this->db->query('SELECT c_id, title, first_name, last_name FROM customer');

        if( $query-> num_rows() > 0) {
            foreach ($query->result() as $row) {
                // echo $row->c_id; (working)

                array_push($partners, [
                    'type'      => "c",
                    'user_id'   => $row->c_id,
                    'title'     => $row->title,
                    'f_name'    => $row->first_name,
                    'l_name'    => $row->last_name
                ]);
            }
        }
        //echo json_encode($partners);

        return $partners;



    }

    public function retrieve($input_id, $user_type){
        if(isset($input_id)&& isset($user_type)){

            $sql = "select message_id, c.first_name as user1, customer_id, ".//convert customer id to user1
                "i.name as user2,pm_id , project_id, to_pm, body as content, ".
                "seen, time_created as timestamp, is_file from message m ".
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
                        'is_file'       => $last_message["is_file"],
                        'messages'      => $value
                    ]);
                    session_start();
                    $this->session->set_userdata("chat_id_".$k,[
                        'customer_id'=> $last_message["customer_id"],
                        'pm_id'=> $last_message["pm_id"]
                    ]);
                    session_write_close();
                    $k++;
                }
                session_write_close();
                return $threads;
            }
        }

        return null;
    }

    public function new_write(array $values)
    {
        // TODO: set users precisely
        //            "partner_id" => $this->input->get("partner"),
        //              "m_author" => $this->input->get("author"),
        //    "m_content" => $this->input->get("content", true),
        //$fromSession = $this->session->userdata("chat_id_".$values["chat_id"]);

        $out = explode("_", $values["partner_id"]);
        $to_the_pm = 0;
        $partner_id = $out[1];
        $c_id = 0;
        $p_id = 0;
        if($out[0] == 'c')
        {
            // pm selects client('c') as partner

            $to_the_pm = 0;
            $p_id = 5;
            $c_id = $partner_id;
        }

        $message = [
            "customer_id"   =>  $c_id,
            "pm_id"         =>  $p_id,
            "project_id"    =>  0,
            "to_pm"         =>  $to_the_pm,
            "body"          =>  $values["m_content"],
            "is_file"       =>  0,
            "time_created"  =>  time()
        ];

        $this->db->insert("message",$message);
    }


    public function write(array $values){
        session_start();
        $fromSession = $this->session->userdata("chat_id_".$values["chat_id"]);
        session_write_close();
        print_r($values);
        //print_r("space\n\n\n");

        //$cc = $this->session->all_userdata();
        //echo json_encode($cc);

        if(isset($values)){
            session_start();
            $message =[
                "customer_id"=>$fromSession["customer_id"],
                "pm_id"=>$fromSession["pm_id"],
                "project_id"=>0,
                //"to_pm"=>($values["user2"]==$values["m_author"])?0:1,
                "to_pm"=>($fromSession["pm_id"]==$values["m_author"])?0:1,
                "body"=> $values["m_content"],
                "is_file"=> $values["m_type"],
                "time_created"=>time()

            ];
            session_write_close();
//            print_r($message);
//            $sql = "insert into message (customer_id, pm_id, project_id, to_pm, body, file_id, timestamp) VALUES (?, ?, 0, ?,?,0, ?)";
//            $this->db->query($sql,[$values["user2"],$values["user1"],$values["user2"],$values["m_author"]],time());
            $this->db->insert("message",$message);
            // for debugging purposes
            print_r($this->db->error());

            // use unique db id for directory name
            $insert_id = $this->db->insert_id();

            return $insert_id;
        }
    }
}