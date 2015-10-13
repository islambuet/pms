<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Booking_permanent extends Root_Controller
{
    private  $message;
    public $permissions;
    public $controller_url;
    public $parent_module_id=0;
    public function __construct()
    {
        parent::__construct();
        $this->message="";
        $this->permissions=User_helper::get_permission('Booking_permanent');
        if(isset($this->permissions['task_id'])&&($this->permissions['task_id']>0))
        {
            $this->parent_module_id=System_helper::get_parent_id_of_task($this->permissions['task_id']);
        }
        $this->controller_url='booking_permanent';
        $this->load->model("booking_permanent_model");
    }

    public function index($action="search",$id=0)
    {
        if($action=="search")
        {
            $this->system_search();
        }
        elseif($action=="edit")
        {
            $this->system_edit($id);
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
            $data['title']="Permanent Booking";
            $data['zones']=Query_helper::get_info($this->config->item('table_zones'),array('id','zone_name'),array('status ="'.$this->config->item('system_status_active').'"'));
            $ajax['system_content'][]=array("id"=>"#system_content","html"=>$this->load->view("booking_permanent/search",$data,true));
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
    public function system_edit($id)
    {
        if(isset($this->permissions['edit'])&&($this->permissions['edit']==1))
        {
            if(($this->input->post('customer_id')))
            {
                $customer_id=$this->input->post('customer_id');
                $year=$this->input->post('year');
                $data_booking_info=Query_helper::get_info($this->config->item('table_bookings'),'*',array('customer_id ='.$customer_id,'year ='.$year),1);
                if(isset($data_booking_info['id']))
                {
                    $id=$data_booking_info['id'];
                }

            }
            if($id>0)
            {
                $time=time();
                $data['title']='Edit Permanent Booking( Booking id= '.$id.')';
                $data['booking']=Query_helper::get_info($this->config->item('table_bookings'),'*',array('id ='.$id),1);
                $data['varieties']=$this->booking_permanent_model->get_all_variety_price($data['booking']['year']);
                if($data['varieties']===false)
                {
                    $ajax['status']=false;
                    $ajax['system_message']=$this->lang->line("MSG_SET_VARIETY_PRICE");
                    $this->jsonReturn($ajax);
                }

                if($data['booking']['booking_status']==$this->config->item('booking_status_preliminary'))
                {
                    $data['booked_varieties']=array();
                    $preliminary_varieties=Query_helper::get_info($this->config->item('table_preliminary_varieties'),array('date','variety_id','quantity'),array('booking_id ='.$id,'revision =1'));
                    foreach($preliminary_varieties as $variety)
                    {
                        $info=$variety;
                        $info['unit_price']=$data['varieties'][$variety['variety_id']]['unit_price'];
                        $info['discount']=0;
                        $data['booked_varieties'][]=$info;
                    }


                    $data['payment']['amount']='';
                    $data['payment']['payment_method']='';
                    $data['payment']['payment_number']='';
                    $data['payment']['bank_name']='';
                    $data['payment']['branch_name']='';
                    $data['payment']['payment_date']=$time;
                    $data['payment']['remarks']='';

                }
                else
                {
                    $data['payment']=Query_helper::get_info($this->config->item('table_payments'),'*',array('booking_id ='.$id,'booking_status ="'.$this->config->item('booking_status_permanent').'"'),1);
                }

                //$data['booked_varieties']=Query_helper::get_info($this->config->item('table_preliminary_varieties'),array('date','variety_id','quantity'),array('booking_id ='.$id,'revision =1'));
                //$data['payment']=Query_helper::get_info($this->config->item('table_payments'),'*',array('booking_id ='.$id,'booking_status ="'.$this->config->item('booking_status_preliminary').'"'),1);*/
                $ajax['status']=true;
                $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("booking_permanent/edit",$data,true));
                if($this->message)
                {
                    $ajax['system_message']=$this->message;
                }
                $this->jsonReturn($ajax);
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_BOOKING_NOT_FOUND");
                $this->jsonReturn($ajax);

                /*$ajax['status']=true;


                $data['varieties']=System_helper::get_all_varieties_for_dropdown();

                $ajax['system_content'][]=array("id"=>"#detail_container","html"=>$this->load->view("booking_preliminary/edit",$data,true));

                if($this->message)
                {
                    $ajax['system_message']=$this->message;
                }


                $this->jsonReturn($ajax);*/

                /*$data['title']='New Preliminary Booking';
                $data['booking']['id']=0;
                $data['booking']['customer_id']=$this->input->post('customer_id');
                $data['booking']['year']=$this->input->post('year');
                $data['booking']['preliminary_remarks']='';
                $data['booking']['status']=$this->config->item('system_status_active');
                $data['booking']['preliminary_booking_date']=$time;

                $data['payment']['amount']='';
                $data['payment']['payment_method']='';
                $data['payment']['payment_number']='';
                $data['payment']['bank_name']='';
                $data['payment']['branch_name']='';
                $data['payment']['payment_date']=$time;
                $data['payment']['remarks']='';

                $data['booked_varieties']=array(array('date'=>$time,'variety_id'=>'','quantity'=>''));*/

            }

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
        if($id>0)
        {
            if(!(isset($this->permissions['edit'])&&($this->permissions['edit']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();
            }
        }
        else
        {
            if(!(isset($this->permissions['add'])&&($this->permissions['add']==1)))
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("YOU_DONT_HAVE_ACCESS");
                $this->jsonReturn($ajax);
                die();

            }
        }
        if(!$this->check_validation())
        {
            $ajax['status']=false;
            $ajax['system_message']=$this->message;
            $this->jsonReturn($ajax);
        }
        else
        {
            $payment_id=$this->input->post('payment_id');
            $payment_info = $this->input->post('payment');
            $time=time();

            $data['modified_by']=$user->user_id;
            $data['modification_date']=$time;
            $data['booking_status']=$this->config->item('booking_status_permanent');
            $data['permanent_booking_date']=System_helper::get_time($payment_info['payment_date']);
            $data['permanent_remarks']=$payment_info['remarks'];

            $payment_info['payment_date']=$data['permanent_booking_date'];
            if($payment_id>0)
            {
                $payment_info['modified_by']=$user->user_id;
                $payment_info['modification_date']=$time;
                Query_helper::update($this->config->item('table_booking_payments'),$payment_info,array("id = ".$payment_id));
            }
            else
            {

                $payment_info['created_by'] = $user->user_id;
                $payment_info['creation_date'] = $time;
                $payment_info['booking_id'] = $id;
                $payment_info['booking_status'] = $this->config->item('booking_status_permanent');
                Query_helper::add($this->config->item('table_booking_payments'),$payment_info);
            }

            $this->db->trans_start();  //DB Transaction Handle START
            Query_helper::update($this->config->item('table_bookings'),$data,array("id = ".$id));

            $this->db->trans_complete();   //DB Transaction Handle END

            if ($this->db->trans_status() === TRUE)
            {
                $this->message=$this->lang->line("MSG_SAVED_SUCCESS");
                $this->system_list();
            }
            else
            {
                $ajax['status']=false;
                $ajax['system_message']=$this->lang->line("MSG_SAVED_FAIL");
                $this->jsonReturn($ajax);

            }

        }
    }
    private function check_validation()
    {
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
        /*if(!($payment['payment_number']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_NUMBER_INVALID");
            return false;
        }*/
        /*if(!($payment['bank_name']))
        {
            $this->message=$this->lang->line("MSG_BOOKING_PAYMENT_BANK_NAME_INVALID");
            return false;
        }*/

        return true;
    }
    public function get_items()
    {
        //$crops=Query_helper::get_info($this->config->item('table_crops'),array('id','crop_name','remarks','status','ordering'),array('status !="'.$this->config->item('system_status_delete').'"'));
        $items=$this->booking_permanent_model->get_list();
        $this->jsonReturn($items);

    }

}
