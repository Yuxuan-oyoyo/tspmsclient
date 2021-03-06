<?php

require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Upload extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('File');
        $this->load->model('Project_model');
    }

    public function index()
    {
    }
    public function upload($project_id){
        if($this->session->userdata('internal_uid')&&$this->session->userdata('internal_type')=="PM") {
            $project = $this->Project_model->retrieve_by_id($project_id);
            $this->load->view('file_repo/upload_view', $data = ['project' => $project]);
        }else{
            $this->session->set_userdata('message','You have not login / have no access rights. ');
            redirect('/internal_authentication/login/');
        }
    }

    public function customer_repo($project_id){

        if($this->session->userdata('Customer_cid')) {
            $project = $this->Project_model->retrieve_by_id($project_id);
            $this->load->view('file_repo/customer_repo', $data = ['project' => $project]);
        }else{
            $this->session->set_userdata('message','Please login first.');
            redirect('/customer_authentication/login/');
        }
    }

    public function file_upload($project_id){
        if($file_to_upload=$this->upload_file_to_s3($project_id)){
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

    private function upload_file_to_s3($project_id){
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
                    'Key'          => $project_id.'/'.$file_to_upload['file_key'],
                    'SourceFile'   => $_FILES['file_to_upload']['tmp_name'],
                    'ContentType'  => $_FILES['file_to_upload']['type'],
                    'ACL'          => 'public-read',
                    'Metadata'     => array(
                        'filename' => $_FILES['file_to_upload']['name']
                    )
                )
            );

            $file_to_upload['file_url'] = $result['ObjectURL'];
            $file_to_upload['pid'] = $project_id;

            return $file_to_upload;
        } catch (S3Exception $e) {
            return FALSE;
        }
    }

    public function delete_by_fid($fid){
        $file_to_delete=$this->File->get_by_fid($fid);
        if($file_to_delete){
            $s3 = new S3Client([
                'credentials' => [
                    'key'    => 'AKIAJCISFJKSJ7DGAM5A',
                    'secret' => '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'
                ],
                'version' => '2006-03-01',
                'region'  => 'ap-southeast-1'
            ]);

            $s3 -> deleteObject(
                array(
                    'Bucket'       => 'test-upload-file',
                    'Key'          => $file_to_delete['pid'].'/'.$file_to_delete['file_key']
                )
            );

            $this->File->delete_by_fid($fid);
            $json_response = array(
                'message'=>'Delete request received.',
                'status'=>'success'
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
        }else{
            $json_response = array(
                'message'=>'Invalid file id.',
                'status'=>'error'
            );

            $this->output->set_content_type('application/json')->set_output(json_encode($json_response));
        }
    }

    public function get_all_files($project_id){
        $file_list=$this->File->retrieveAll($project_id);

        $file_array=array();
        foreach($file_list as $value){
            $file=array();
            $file['id'] = $value['fid'];
            $file['text'] = $value['filename'];
            $file['type'] = 'default';

            switch (strrchr($value['filename'], '.')){
                case '.png':
                case '.jpg':
                case '.jpeg':
                case '.tif':
                case '.tiff':
                case '.gif':
                    $file['type'] = 'image';
                    break;
                case '.pdf':
                    $file['type'] = 'pdf';
                    break;
                case '.doc':
                case '.docx':
                case ".pages":
                    $file['type'] = 'word';
                    break;
                case '.ppt':
                case '.pptx':
                case '.pps':
                case '.ppsx':
                case ".key":
                    $file['type'] = 'ppt';
                    break;
                case '.zip':
                case '.rar':
                    $file['type'] = 'archive';
                    break;
                case '.xlsx':
                case '.xlsb':
                case '..xltx':
                case '.xls':
                case '.xlt':
                    $file['type'] = 'excel';
                    break;
                case '.mkv':
                case '.flv':
                case '.avi':
                case '.mov':
                case '.wmv':
                case '.rm':
                case '.rmvb':
                case '.mpg':
                case '.mp4':
                    $file['type'] = 'video';
                    break;
                case '.aac':
                case '.m4a':
                case '.m4b':
                case '.mp3':
                case '.wav':
                case '.wma':
                case ".iff":
                case ".m3u":
                    $file['type'] = 'audio';
                    break;
                case '.txt':
                case ".log":
                case ".csv":
                case ".xml":
                    $file['type'] = 'text';
                    break;
                case ".java":
                case ".js":
                case ".php":
                case ".css":
                case ".html":
                case ".py":
                case ".bat":
                case ".cpp":
                case ".tex":
                case ".sql":
                case ".swift":
                    $file['type'] = 'code';
                    break;
                default:
                    break;
            }

            $file['a_attr'] = array(
              'href' => $value['file_url']
            );
            array_push($file_array, $file);
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($file_array));
    }

    public function rename_file(){
        $fid=$this->input->post('fid');
        $name=$this->input->post('new_name');
        $file=$this->File->get_by_fid($fid);

        if($file['filename'] != $name){
            $updated_file=array(
                'fid'=>$fid,
                'file_url'=>$file['file_url'],
                'file_key'=>$file['file_key'],
                'filename'=>$name.strrchr($file['filename'], '.'),
                'last_updated'=>$file['last_updated'],
                'pid'=>$file['pid']
            );

            $this->File->rename_by_fid($updated_file);
        }
    }
}