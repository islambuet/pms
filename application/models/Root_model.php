<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Root_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }



    public function get_modules()
    {
        $user=User_helper::get_user();
        $this->db->select('module.id,module.name,module.icon');
        $this->db->select('COUNT(module.id) subcount');
        $this->db->from('pms_user_group_role ugr');
        $this->db->join('pms_task task','task.id = ugr.task_id','INNER');
        $this->db->join('pms_task module','module.id = task.parent','INNER');
        $this->db->where('ugr.user_group_id',$user->rnd_group);
        $this->db->where('ugr.view',1);
        $this->db->group_by('module.id');
        $this->db->order_by('module.ordering','ASC');
        $result=$this->db->get()->result_array();
        return $result;
    }

}