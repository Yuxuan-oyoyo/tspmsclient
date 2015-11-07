<?php
/**
 * Created by PhpStorm.
 * User: pinguin
 * Date: 7/11/15
 * Time: 8:34 AM
 */


// msg extending the ci_controller class. new pages class can access the methods n variables in sys/core/controller.php
// referring to $this is how we will load libraries, views and generally command the framework
class Msg extends CI_controller
{
    //accepts one arg name page
    public function view($page = 'msg')
    {
        if ( ! file_exists(APPPATH.'/views/chat/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter


        $this->load->view('chat/'.$page, $data);

    }


}


