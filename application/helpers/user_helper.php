<?php

class User_helper
{
    public static $logged_user = null;
    function __construct($id)
    {
        $CI = & get_instance();
        $user = $CI->db->get_where($CI->config->item('table_users'), array('user_id' => $id))->row();
        if ($user)
        {
            foreach ($user as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }
    public static function login($username, $password)
    {
        $CI = & get_instance();
        $user = $CI->db->get_where($CI->config->item('table_users'), array('pms_group >'=>0,'user_name' => $username, 'user_pass' => md5(md5($password))))->row();
        if ($user)
        {
            $CI->session->set_userdata("user_id", $user->user_id);
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }



    public static function get_user()
    {
        $CI = & get_instance();
        if (User_helper::$logged_user) {
            return User_helper::$logged_user;
        }
        else
        {
            if($CI->session->userdata("user_id")!="")
            {
                User_helper::$logged_user = new User_helper($CI->session->userdata('user_id'));
                return User_helper::$logged_user;
            }
            else
            {
                return null;
            }

        }
    }
    public static function get_permission($controller_name)
    {
        $CI = & get_instance();
        $user=User_helper::get_user();
        $CI->db->from($CI->config->item('table_user_group_role').' ugr');
        $CI->db->select('ugr.*');

        $CI->db->join($CI->config->item('table_task').' task','task.id = ugr.task_id','INNER');
        $CI->db->like("controller",$controller_name,"after");
        $CI->db->where("user_group_id",$user->pms_group);
        $result=$CI->db->get()->row_array();
        return $result;
    }
}