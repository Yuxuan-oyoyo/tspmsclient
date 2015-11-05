<?php

/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/28/2015
 * Time: 1:21 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library('session');
        $this->load->model('Customer_model');
    }
    public function index(){
        $this->load->view("chat/chat",[]);
    }
    public function get($user=null){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        $user = null;
        $thread_tracker = [];
        $result_col 	= [];
        //TODO: fetch data with model
        foreach($result as $row) {
            $new_m = ['author'  => $row["author_id"],
                'msgID'         => $row["msg_id"],
                'content'       => $row["content"],
                'timeStamp'     => $row["timestamp"],
            ];
            if (!in_array($row["chatid"], $thread_tracker)) {
                //add chatid to tracked threads
                array_push($thread_tracker, $row["chatid"]);
                // make the thread;
                $new_t = ['chatID'  => $row["chatid"],
                    'user1'         => $row["user1"],
                    'user2'         => $row["user2"],
                    'seen'          => $row["seen"],
                    'lastMsgTimeStamp' => $row["lastMsgTimeStamp"],
                    'lastMessage'   => $row["lastMessage"],
                    'messages'      => [$new_m],
                ];
                array_push($result_col, $new_t);
            } else {
                $chat_id = $row["chatid"];
                foreach ($result_col as $k=>$v) {
                    if ($v['chatID'] == $chat_id)
                        array_push($result_col[$k]['messages'], $new_m);
                }
            }
        }
        echo json_encode($result_col);
    }
    public function write(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        $chat_id 	= $this->input->get("chatID",true);
        $m_author 	= $this->input->get("author",true);
        $m_content 	= $this->input->get("content",true);
        $m_timestamp = $this->input->get("timeStamp",true);

        //TODO: link up with model
        $sql = "INSERT into chat_messages (chat_id, author_id, content, timeStamp)
			VALUES ('$chat_id', '$m_author', '$m_content', '$m_timestamp')";

        // does not cover situations where there is no initial messages

        $sql2 = "UPDATE chat_threads
				SET lastMessage='$m_content', lastMsgTimeStamp='$m_timestamp'
				WHERE chat_id='$chat_id'";

    }
}