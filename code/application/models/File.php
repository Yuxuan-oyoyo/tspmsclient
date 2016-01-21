<?php
class File extends CI_Model {

    function get_by_fid($fid=FALSE)
    {
        $query = $this->db->get_where('upload', array('fid' => $fid));
        return $query->row_array();
    }


    function insert($featured_image=FALSE)
    {
        if($featured_image){
            $temp_array = array(
                'image_url'=>$featured_image['file_url'],
                'image_key'=>$featured_image['file_key'],
                'filename'=>$featured_image['filename']
            );

            $now = new DateTime("now", new DateTimeZone(DATETIMEZONE));
            $this->db->set('last_updated', $now->format('c'));
            $this->db->insert('featured_image', $temp_array);
            return $this->db->insert_id();

        }else{
            return FALSE;
        }
    }

    function delete_by_fiid($fid=FALSE)
    {
        if($fid){
            $this->db->delete('upload', array('fid' => $fid));
            return $this->db->affected_rows();
        }else{
            return 0;
        }
    }
}