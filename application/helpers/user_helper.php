<?php

class User_helper
{
    public static $logged_user = null;
    function __construct($id)
    {
        $CI = & get_instance();
        $user = $CI->db->get_where('ait_user_login', array('user_id' => $id))->row();
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
        $user = $CI->db->get_where('ait_user_login', array('pms_group >'=>0,'user_name' => $username, 'user_pass' => md5(md5($password))))->row();
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
}