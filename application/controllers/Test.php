<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function index()
    {
        echo md5(md5('Sp2004'));
    }

}
