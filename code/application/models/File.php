<?php
class File extends CI_Model {

    function get_by_fid($fid=FALSE){
        $query = $this->db->get_where('upload', array('fid' => $fid));
        return $query->row_array();
    }


    function insert($file_to_upload=FALSE){
        if($file_to_upload){
            $temp_array = array(
                'file_url'=>$file_to_upload['file_url'],
                'file_key'=>$file_to_upload['file_key'],
                'filename'=>$file_to_upload['filename'],
                //NEED TO INTEGRATE WITH PROJECT ID
                'pid'=>$file_to_upload['pid'],
            );

            $now = new DateTime("now", new DateTimeZone(DATETIMEZONE));
            $this->db->set('last_updated', $now->format('c'));
            $this->db->insert('upload', $temp_array);
            return $this->db->insert_id();
        }else{
            return FALSE;
        }
    }

    function delete_by_fid($fid=FALSE){
        if($fid){
            $this->db->delete('upload', array('fid' => $fid));
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }

    function retrieveAll($project_id){
        if($project_id) {
            $query = $this->db->get_where("upload",['pid' => $project_id]);
        }
        return $query->result_array();
    }

    function rename_by_fid($update_array){
        $date = new DateTime("now",new DateTimeZone(DATETIMEZONE));
        $update_array['last_updated'] = $date->format('c');
        $this->db->update('upload', $update_array, array('fid' => $update_array['fid']));
        return $this->db->affected_rows();
    }
}