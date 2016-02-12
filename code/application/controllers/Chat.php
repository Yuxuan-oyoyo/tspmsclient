<?php

require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


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

        //session_start();
        $pm_id = $this->session->userdata('internal_uid');
        $customer_id = $this->session->userdata('Customer_cid');
        if(isset($pm_id)){
            session_write_close();
            return ["user_id"=>$pm_id, "user_type"=>"pm"];
        }else{
            session_write_close();
            return ["user_id"=>$customer_id, "user_type"=>"customer"];
        }

    }

    public function readmsg()
    {
        session_write_close();
        $values = [
            "pm_id" => $this->input->get("pmid"),
            "c_id" => $this->input->get("cid"),
            "to_pm" => $this->input->get("topm"),
        ];

        //echo json_encode($values);
        $this->Chat_model->read_msg($values);

    }

    public function notifications()
    {

        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");

        session_write_close();

        $user = $this->getUserInfo();

        $notify = $this->Chat_model->get_notifications($user["user_id"], $user["user_type"]);

        session_write_close();

        echo $notify;


    }

    public function get(){
        // @formatter:off
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");


        session_write_close();
        //$this->load->library('session');

        $user = $this->getUserInfo();

        $threads =  $this->Chat_model->retrieve($user["user_id"], $user["user_type"]);
        $user_type = $this->session->userdata('internal_type');
        $count_get = $this->session->userdata('count_get');


        //echo $count_get;
        //echo $user_type;
        //print_r($this->session->all_userdata());

        if($user_type == "PM")
        {
            $pm_id = $this->session->userdata('internal_uid');
            // if %5 = 0, do db write
            if ($count_get % 5 == 0) {
                $this->Chat_model->online_update($user_type, $pm_id);
            }
            //echo $user_type;
            //echo $pm_id;
            //session_start();
            $count_get = $count_get + 1;
            $this->session->set_userdata('count_get', $count_get);
            session_write_close();
        }


        //echo json_encode($user);
        echo json_encode($threads);
        session_write_close();
        //print_r($this->session->all_userdata());

    }
    public function new_write()
    {
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");

        //   data: {partner:target_partner, timeStamp: datetime, author: CurrentUser ,content: text },

        $values = [
            "partner_id" => $this->input->get("partner"),
            "m_author" => $this->input->get("author"),
            "m_content" => $this->input->get("content", true),
            "m_type" => 0,
        ];


        $this->Chat_model->new_write($values);

    }

    public function conversation_list()
    {

        $user_type = $this->session->userdata('internal_type');
        session_write_close();

        if($user_type == 'PM') {
            session_write_close();
            $partners = $this->Chat_model->convo_pm();
            echo json_encode($partners);
            session_write_close();
        }
        else
        {
            session_write_close();
            $c_id = $this->session->userdata('Customer_cid');
            $c_id =  (int) $c_id;
            $partners = $this->Chat_model->convo_client($c_id);
            echo json_encode($partners);
            session_write_close();
        }
    }


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



        session_write_close();

        if( $this->input->server('REQUEST_METHOD') == 'GET') {
            // m_type: if file 1 else 0
            $values = [
                "chat_id" => $this->input->get("chatID", true),
                "m_author" => $this->input->get("author", true),
                "m_content" => $this->input->get("content", true),
                "m_to_pm" => $this->input->get("to_the_pm"),
                "m_pm_id" => $this->input->get("pm_id"),
                "m_type" => "0",
            ];


            //echo json_encode($values);


            $this->Chat_model->write($values);

            $partner_status = 0;

            if($values["m_to_pm"] == 1)
            {
                $partner_status = $this->Chat_model->is_user_online($values["m_pm_id"], "PM");

                if($partner_status == 1)
                {
                    echo "partner is on";
                }
                else
                {
                    // if this message is to pm, author is definitely not pm
                    echo "partner is off";
                    $body = '<!DOCTYPE html><html lang=\"en\"><head><meta charset=\"utf-8\"><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
                            <title>TSPMS Offline Message</title><link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css\">
                            </head><body><p> Hello, you have received an message from ';
                    $body .= $values["m_author"];
                    $body .= " at ";
                    $body .= date('m-d-y h:m', time());
                    $body .= " when you were offline. </p> <br><br> <p>";
                    $body .= $values["m_content"];
                    $body .= "</p> </body> </html>";
                    /*
                    $message = array(
                        'html' => $body,
                        'subject' => 'TSPMS Offline Message',
                        'from_email' => 'donotreply@tspms.com',
                        'from_name' => TSPMS,
                        'to' => $send_to,
                        'important' => false,
                        'track_opens' => true,
                        'track_clicks' => false,
                        'auto_text' => true,
                        'auto_html' => false,
                        'inline_css' => false,
                    );
                    $mandrill = new Mandrill(MANDRILL_API_KEY);
                    $result = $mandrill->messages->send($message);
                    */
                }
            }
            else
            {
                echo "You are PM, you do not need to know if ur partner is on/off";
            }
        }
        else
        {
            $base_file = $_POST['test_data']; // base64 file
            $f_name = $_POST['f_name']; // file_name
            $ext = $_POST['ext'];
            $full_fn = $f_name;
            $user_msg = $_POST['user_msg'];

            //$full_fn .= ".";
            //$full_fn .= $ext;

            if(! isset($base_file))
            {
                $error = array('error' => $this->upload->display_errors());
                echo $error;
            }
            else
            {

                // Do s3
                $file_url = "";
                //ob_end_flush();
                $s3 = new S3Client([
                    'credentials' => [
                        'key'    => 'AKIAJCISFJKSJ7DGAM5A',
                        'secret' => '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'
                    ],
                    'version' => '2006-03-01',
                    'region'  => 'ap-southeast-1'
                ]);

                $data = explode(',', $base_file);
                $content = base64_decode($data[1]);

                $f = finfo_open();
                $mime_type = addslashes(finfo_buffer($f, $content, FILEINFO_MIME_TYPE));
                finfo_close($f);

                $mime_type = str_replace('/', '\/', $mime_type);
                $file_key = substr(md5(time().$full_fn),0,5)."_".$full_fn;






                try {

                    $file_key = substr(md5(time().$full_fn),0,5)."_".$full_fn;
                    $result = $s3 -> putObject(
                        array(
                            'Bucket'       => 'test-upload-file',
                            'Key'          => $file_key,
                            'SourceFile'   => $base_file,
                            'ContentType'  => $mime_type,
                            'ACL'          => 'public-read',
                            'Metadata'     => array(
                                'filename' => $full_fn
                            )
                        )
                    );

                    $file_url = $result['ObjectURL'];

                    //echo $file_url;

                } catch (S3Exception $e)
                {
                    // do nothing maybe print error?
                    $file_url = "error";
                }

                if($file_url != "error")
                {
                    // Do local DB
                    $values = [
                        "chat_id" => $this->input->post("chatID", true),
                        "m_author" => $this->input->post("author", true),
                        "m_content" => $user_msg, // content is no longer file name, its the message
                        "m_type" => $full_fn."^".$file_url,
                    ];

                    $retrieve_id = $this->Chat_model->write($values);

                }
                else
                {
                    echo "error";
                }

            }
        }
        session_write_close();
    }
}