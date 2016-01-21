<?php

require APPPATH.'libraries/aws/aws-autoloader.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Upload extends CI_Controller {

    public function file_upload(){
        /*
        $fid='';
        $image_url='';
        $image_key='';
        $filename='';
        */
        var_dump("I'm in");
        if($featured_image=$this->_upload_image_to_s3()){
            /*
            $json_response = array(
                'message'=>$featured_image,
                'status'=>'error'
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
            */
            $this->load->model('File');
            if($fid=$this->File->insert($featured_image)){
                //$this->User_log_model->log_message('Featured Image record created|ffid:'.$fiid);
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

    private function _upload_image_to_s3(){
        var_dump("b factory");
        $s3 = S3Client::factory(array('key' => 'AKIAIVYIQKFDHEK2JVWQ', 'secret' => 'remix'));
        var_dump("after factory");
        try {
            // Upload data.

            $featured_image=array();
            $featured_image['filename']=$_FILES['featured_image']['name'];
            $featured_image['image_key']=substr(md5(time().$featured_image['filename']),0,5)."_".$featured_image['filename'];
            $result = $s3 -> putObject(
                array(
                    'Bucket'       => 'test-upload-file',
                    'Key'          => $featured_image['image_key'],
                    'SourceFile'   => $_FILES['featured_image']['tmp_name'],
                    'ContentType'  => $_FILES['featured_image']['type'],
                    'ACL'          => 'public-read',
                    'Metadata'     => array(
                        'filename' => $_FILES['featured_image']['name']
                    )
                )
            );

            $featured_image['image_url']=$result['ObjectURL'];

            return $featured_image;
        } catch (S3Exception $e) {
            return FALSE;
        }
    }
}