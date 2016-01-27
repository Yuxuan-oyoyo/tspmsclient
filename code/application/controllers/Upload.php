<?php

//require APPPATH.'libraries/vendor/autoload.php';
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index()
    {
        $this->upload();
    }
    public function upload(){
        $this->load->view('upload_view');
    }

    public function file_upload(){
        if($file_to_upload=$this->upload_file_to_s3()){
            $this->load->model('File');
            if($fid=$this->File->insert($file_to_upload)){
                //$this->User_log_model->log_message('File record created|fid:'.$fid);
                $json_response = array(
                    'message'=>'Upload successful',
                    'status'=>'success'
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
            }else{
                $json_response = array(
                    'message'=>'Save to DB failed.',
                    'status'=>'error'
                );
                $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
            }

        }else{
            $json_response = array(
                'message'=>'Upload to S3 failed.',
                'status'=>'error'
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
        }
    }

    private function upload_file_to_s3(){
        $s3 = new S3Client([
            'credentials' => [
                'key'    => 'AKIAJCISFJKSJ7DGAM5A',
                'secret' => '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'
            ],
            'version' => '2006-03-01',
            'region'  => 'ap-southeast-1'
        ]);

        try {
            $file_to_upload=array();
            $file_to_upload['filename'] = $_FILES['file_to_upload']['name'];
            $file_to_upload['file_key'] = substr(md5(time().$file_to_upload['filename']),0,5)."_".$file_to_upload['filename'];
            $result = $s3 -> putObject(
                array(
                    'Bucket'       => 'test-upload-file',
                    'Key'          => $file_to_upload['file_key'],
                    'SourceFile'   => $_FILES['file_to_upload']['tmp_name'],
                    'ContentType'  => $_FILES['file_to_upload']['type'],
                    'ACL'          => 'public-read',
                    'Metadata'     => array(
                        'filename' => $_FILES['file_to_upload']['name']
                    )
                )
            );

            $file_to_upload['file_url'] = $result['ObjectURL'];

            return $file_to_upload;
        } catch (S3Exception $e) {
            return FALSE;
        }
    }
}