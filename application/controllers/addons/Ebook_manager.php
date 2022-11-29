<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ebook_manager extends CI_Controller
{ 
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->database();
        $this->load->library('session');
        // $this->load->library('stripe');
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');

        // CHECK CUSTOM SESSION DATA
        // $this->session_data();
    }

    public function all_ebooks(){
        $page_data['page_title'] = 'Ebook list';
        $page_data['ebooks'] = $this->ebook_model->get_all_ebooks();
        $page_data['page_name'] = 'all_ebooks';
        $this->load->view('backend/index',$page_data);
    }
    
    public function add_ebook()
    {
        $page_data['page_title'] = 'Add ebook';
        $page_data['page_name'] = 'add_ebook';
        $this->load->view('backend/index',$page_data);
    }

    public function payment_history()
    {
        $page_data['page_title'] = 'Ebook payment history';
        $page_data['page_name'] = 'payment_history';
        $this->load->view('backend/index',$page_data);
    }

    public function category()
    {
        $page_data['page_title'] = 'Ebook Category';
        $page_data['page_name'] = 'category';
        $this->load->view('backend/index',$page_data);
    }

    public function edit_ebook()
    {
        
    }
}