<?php
class System_helper
{
    public static function pagination_config($base_url, $total_rows, $segment)
    {
        $CI =& get_instance();

        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_rows;
        $config["per_page"] = $CI->config->item('view_per_page');
        $config['num_links'] = $CI->config->item('links_per_page');
        $config['use_page_numbers'] = true;
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['uri_segment'] = $segment;
        return $config;
    }

    public static function get_rnd_code($variety,$display_style=0)
    {
        $CI = & get_instance();

        $rndCode = $variety['crop_code'].'-'.$variety['type_code'].'-'.str_pad($variety['variety_index'],3,'0',STR_PAD_LEFT);
        $varietyConfig = $CI->config->item('variety_type');

        if($variety['variety_type']==1)
        {
            if($display_style==0)
            {
                $rndCode = $rndCode.'-'.$variety['principal_code'];
            }
            else
            {
                $rndCode = $rndCode.'-XXX';
            }
        }
        else
        {
            $rndCode = $rndCode.'-'.$varietyConfig[$variety['variety_type']];
        }

        if($display_style==0)
        {
            $rndCode = $rndCode.'-'.$variety['year'];
            $rndCode = $rndCode.'-'.$variety['variety_no'];
        }

        if($variety['replica_status']==1)
        {
            $rndCode = $rndCode.'-R';
        }
        else
        {
            $rndCode = $rndCode.'-S';
        }

        return $rndCode;
    }
    public static function display_date($date)
    {
        return date('d-M-Y',$date);
    }
    public static function get_time($str)
    {
        $time=strtotime($str);
        if($time===false)
        {
            return 0;
        }
        else
        {
            return $time;
        }
    }
    
    public static function get_ordered_crops()
    {
        $CI = & get_instance();
        $CI->db->select('rnd_crop.id, rnd_crop.crop_name');
        $CI->db->from('rnd_crop');
        $CI->db->order_by('ordering','ASC');
        $CI->db->order_by('id','ASC');
        $CI->db->where('status != ',$CI->config->item('status_delete'));
        $result=$CI->db->get()->result_array();
        return $result;
    }

    public static function upload_file($save_dir="images")
    {
        $CI = & get_instance();
        $CI->load->library('upload');
        $config=array();
        $config['upload_path'] = FCPATH.$save_dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = $CI->config->item("max_file_size");
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;

        $uploaded_files=array();
        foreach ($_FILES as $key => $value)
        {
            if(strlen($value['name'])>0)
            {
                $CI->upload->initialize($config);
                if (!$CI->upload->do_upload($key))
                {
                    $uploaded_files[$key]=array("status"=>false,"message"=>$value['name'].': '.$CI->upload->display_errors());
                }
                else
                {
                    $uploaded_files[$key]=array("status"=>true,"info"=>$CI->upload->data());
                }

            }
        }

        return $uploaded_files;
    }
    public static function get_pdf($html)
    {
        include(FCPATH."mpdf60/mpdf.php");
        $mpdf=new mPDF();
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($html);
        $mpdf->Output();
        exit;

    }

}