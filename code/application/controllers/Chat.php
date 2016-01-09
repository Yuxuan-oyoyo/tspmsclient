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
        $this->load->model("Chat_model");
        $this->load->library('session');
        $this->load->helper('file');
        $this->load->library('upload');
        $this->load->helper(array('form', 'url'));
    }
    public function index(){

        $user = $this->getUserInfo();
        $this->load->view("chat/chat",["user_id"=>$user["user_id"]]);
    }
    private function getUserInfo(){
        $pm_id = $this->session->userdata('internal_uid');
        $customer_id = $this->session->userdata('Customer_cid');
        if(isset($pm_id)){
            return ["user_id"=>$pm_id, "user_type"=>"pm"];
        }else{
            return ["user_id"=>$customer_id, "user_type"=>"customer"];
        }
    }
    public function get(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");

        $user = $this->getUserInfo();
        //TODO: fetch data with model
          $threads =  $this->Chat_model->retrieve($user["user_id"], $user["user_type"]);
          echo json_encode($threads);
//        foreach($result as $row) {
//            $new_m = ['author'  => $row["author_id"],
//                'msgID'         => $row["msg_id"],
//                'content'       => $row["content"],
//                'timeStamp'     => $row["timestamp"],
//            ];
//            if (!in_array($row["chatid"], $thread_tracker)) {
//                //add chatid to tracked threads
//                array_push($thread_tracker, $row["chatid"]);
//                // make the thread;
//                $new_t = ['chatID'  => $row["chatid"],
//                    'user1'         => $row["user1"],
//                    'user2'         => $row["user2"],
//                    'seen'          => $row["seen"],
//                    'lastMsgTimeStamp' => $row["lastMsgTimeStamp"],
//                    'lastMessage'   => $row["lastMessage"],
//                    'messages'      => [$new_m],
//                ];
//                array_push($result_col, $new_t);
//            } else {
//                $chat_id = $row["chatid"];
//                foreach ($result_col as $k=>$v) {
//                    if ($v['chatID'] == $chat_id)
//                        array_push($result_col[$k]['messages'], $new_m);
//                }
//            }
//        }
//        echo json_encode($result_col);
    }

    /*
    public function filey()
    {


        $base_file = $_POST['test_data'];
        $f_name = $_POST['f_name'];
        $ext = $_POST['ext'];

        if(! isset($base_file))
        {
            echo "sian man";
            $error = array('error' => $this->upload->display_errors());
            echo $error;


        }
        else
        {
            $data = explode(',', $base_file);
            $content = base64_decode($data[1]);
            $outfile = "./uploads/";
            $outfile .= $f_name;
            $outfile .= ".";
            $outfile .= $ext;
            file_put_contents($outfile, $content);



        }
    }
    // EOL: prototyping function
    */

    public function filesys($msgid, $fn)
    {
        /*
        $this->load->helper('download');
        $data = 'Here is some text!';
        $name = 'mytext.txt';

        force_download($name, $data);
        */
        $this->load->helper('download');
        $file_name = urldecode($fn);
        $file_path = "./uploads/";
        $file_path .= $msgid;
        $file_path .= "/";
        $file_path .= $file_name;

        // echo $file_path;

        $data = file_get_contents($file_path);
        $name = $file_name;

        force_download($name, $data);

    }


    public function write(){
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");



        if( $this->input->server('REQUEST_METHOD') == 'GET') {
            // m_type: if file 1 else 0
            $values = [
                "chat_id" => $this->input->get("chatID", true),
                "m_author" => $this->input->get("author", true),
                "m_content" => $this->input->get("content", true),
                "m_type" => 0,
            ];
            //TODO: link up with model

            $this->Chat_model->write($values);
        }
        else
        {
            $base_file = $_POST['test_data'];
            $f_name = $_POST['f_name'];
            $ext = $_POST['ext'];
            $full_fn = $f_name;
            $full_fn .= ".";
            $full_fn .= $ext;

            if(! isset($base_file))
            {
                $error = array('error' => $this->upload->display_errors());
                echo $error;
            }
            else
            {
                // insert into db
                // msg_id will be unique dir / body will be file name
                $values = [
                    "chat_id" => $this->input->post("chatID", true),
                    "m_author" => $this->input->post("author", true),
                    "m_content" => $full_fn,
                    "m_type" => 1,
                ];
                //TODO: link up with model

                $retrieve_id = $this->Chat_model->write($values);

                // create unique dir
                $out_path = "./uploads/";
                $out_path .= $retrieve_id;

                mkdir($out_path, 0777, TRUE);

                // store files in created dir
                $data = explode(',', $base_file);
                $content = base64_decode($data[1]);
                $outfile = $out_path;
                $outfile .= "/";
                $outfile .= $f_name;
                $outfile .= ".";
                $outfile .= $ext;
                file_put_contents($outfile, $content);


            }
        }

    }
}