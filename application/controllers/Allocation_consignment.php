<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Allocation_consignment extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Allocation_consignment');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='allocation_consignment';
        $this->load->model("allocation_consignment_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="list")
        {
            $this->system_list();
        }
        elseif($action=="save")
        {
            $this->system_save();
        }
        else
        {
            $this->system_search();
        }
    }

    public function system_search()
    {

        if(isset($this->permissions['view'])&&($this->permissions['view']==1))
        {
            $data['title']="Assign Quantity";
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("allocation_consignment/search",$data,true));
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $ajax['system_page_url']=site_url($this->controller_url.'/index/search');
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }
    public function system_list()
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            $year=$this->input->post('year');
            $data['title']='Allocation list';
            $data['bookings']=$this->allocation_consignment_model->get_bookings($year);
            if(!(sizeof($data['bookings'])>0))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_NO_PERMANENT_BOOKING_FOUND");
                $this->jsonReturn($ajax);
            }
            $data['consignments']=$this->allocation_consignment_model->get_consignments($year);
            if(!(sizeof($data['consignments'])>0))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_NO_CONSIGNMENT_FOUND");
                $this->jsonReturn($ajax);
            }
            $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("allocation_consignment/list",$data,true));

            $ajax['status']=false;
            if($this->message)
            {
                $ajax['system_message']=$this->message;
            }
            $this->jsonReturn($ajax);
        }
        else
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
        }
    }

    public function system_save()
    {
        $user=User_helper::get_user();
        $id = $this->input->post("id");

        if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
            $this->jsonReturn($ajax);
            die();
        }

        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $booking_info=Query_helper::get_info($this->config->item('table_bookings'),'*',array('id ='.$id),1);
            $time=time();
            $this->db->trans_start();  //DB Transaction Handle START
            $data=$this->input->post('booking');
            $data['permanent_booking_date']=System_helper::get_time($data['permanent_booking_date']);
            $data['booking_status']=$this->config->item('booking_status_permanent');
            Query_helper::update($this->config->item('table_bookings'),$data,array("id = ".$id));
            if($booking_info['booking_status']==$this->config->item('booking_status_preliminary'))
            {
                $this->insert_booking_varieties($id);
                $payment_info=$this->input->post('payment');
                $payment_info['created_by'] = $user->user_id;
                $payment_info['creation_date'] = $time;
                $payment_info['booking_id'] = $id;
                $payment_info['payment_date'] = System_helper::get_time($payment_info['payment_date']);
                $payment_info['booking_status'] = $this->config->item('booking_status_permanent');

                Query_helper::add($this->config->item('table_payments'),$payment_info);
            }
            else
            {
                $this->db->where('booking_id',$id);
                $this->db->set('revision', 'revision+1', FALSE);
                $this->db->update($this->config->item('table_permanent_varieties'));
                $this->insert_booking_varieties($id);
                $payment_info=$this->input->post('payment');
                $payment_info['modified_by']=$user->user_id;
                $payment_info['modification_date']=$time;
                $payment_info['payment_date'] = System_helper::get_time($payment_info['payment_date']);
                Query_helper::update($this->config->item('table_payments'),$payment_info,array("booking_id = ".$id,'booking_status ="'.$this->config->item('booking_status_permanent').'"'));
            }

            $this->db->trans_complete();   //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                $this->system_edit($id);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                $this->jsonReturn($ajax);

            }

        }
    }
    private function insert_booking_varieties($booking_id)
    {
        $booked_varieties=$this->input->post('booked_varieties');
        $time=time();
        $user=User_helper::get_user();
        foreach($booked_varieties as $variety)
        {
            $data=array();
            $data['booking_id']=$booking_id;
            $data['date']=System_helper::get_time($variety['date']);
            $data['variety_id']=$variety['id'];
            $data['quantity']=$variety['quantity'];
            $data['unit_price']=$variety['unit_price'];
            $data['discount']=$variety['discount'];
            $data['revision']=1;
            $data['created_by'] = $user->user_id;
            $data['creation_date'] = $time;
            Query_helper::add($this->config->item('table_permanent_varieties'),$data);
        }
    }
    private function check_validation()
    {
        $booked_varieties=$this->input->post('booked_varieties');
        /*echo '<PRE>';
        print_r($booked_varieties);
        echo '</PRE>';*/

        if(sizeof($booked_varieties)>0)
        {
            $variety_ids=array();
            foreach($booked_varieties as $variety)
            {
                if(!is_numeric($variety['quantity']))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_MISSING");
                    return false;
                }
                if(!is_numeric($variety['discount']))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_DISCOUNT_MISSING");
                    return false;
                }
                if(!(($variety['quantity'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_QUANTITY_INVALID");
                    return false;
                }
                if(!(($variety['id'])>0))
                {
                    $this->message=$this->lang->line("MSG_BOOKING_VARIETY_MISSING");
                    return false;
                }
                $variety_ids[]=$variety['id'];

            }
            $this->selected_variety_ids=$variety_ids;
        }
        else
        {
            $this->message=$this->lang->line("MSG_REQUIRED_BOOKING");
            return false;

        }
        $payment=$this->input->post('payment');
        if(!is_numeric($payment['amount']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_MISSING");
            return false;
        }
        if(!(($payment['amount'])>0))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_INVALID");
            return false;
        }
        if(!($payment['payment_method']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_METHOD_INVALID");
            return false;
        }

        return true;
    }
    public function get_items()
    {
        //$crops=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name','remarks','status','ordering'),array('status !="'.$this->config->item('system_status_delete').'"'));
        $items=$this->booking_permanent_model->get_list();
        $this->jsonReturn($items);

    }

}
