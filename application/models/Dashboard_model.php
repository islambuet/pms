<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_tasks($module_id)
    {
        $user=User_helper::get_user();
        $this->db->select('task.id,task.name,task.controller,task.icon');
        $this->db->from("pms_task task");
        $this->db->join('pms_user_group_role ugr','task.id = ugr.task_id','INNER');
        $this->db->where("task.parent",$module_id);
        $this->db->where("ugr.user_group_id",$user->rnd_group);
        $this->db->order_by('task.ordering');
        $result=$this->db->get()->result_array();
        return $result;

    }

}