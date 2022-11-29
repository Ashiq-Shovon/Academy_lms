<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ebook extends CI_Controller
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
    public function index(){
    
    }

    public function ebooks()
    {
        if (!$this->session->userdata('layout')) {
            $this->session->set_userdata('layout', 'list');
        }
        $layout = $this->session->userdata('layout');
        $selected_category_id = "all";
        $selected_price = "all";
        $selected_rating = "all";
        $search_text = "";
        // Get the category ids
        if (isset($_GET['category']) && !empty($_GET['category'] && $_GET['category'] != "all")) {
            $selected_category_id = $this->ebook_model->get_category_id($_GET['category']);
            
        }

        // Get the selected price
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $selected_price = $_GET['price'];
        }

       

        // Get the selected rating
        if (isset($_GET['rating']) && !empty($_GET['rating'])) {
            $selected_rating = $_GET['rating'];
        }
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search_text = $_GET['search'];
            $page_data['search_value'] = $search_text;
        }



        if ($selected_category_id == "all" && $selected_price == "all" && $selected_rating == 'all' && empty($_GET['search'])) {
            // if (!addon_status('scorm_course')) {
            //     $this->db->where('course_type', 'general');
            // }
            $this->db->where('is_active', 1);
            $total_rows = $this->db->get('ebook')->num_rows();
            $config = array();
            $config = pagintaion($total_rows, 6);
            $config['base_url']  = site_url('ebook');
            $this->pagination->initialize($config);
            // if (!addon_status('scorm_course')) {
            //     $this->db->where('course_type', 'general');
            // }
            $this->db->where('is_active', 1);
            $page_data['ebooks'] = $this->db->get('ebook', $config['per_page'], $this->uri->segment(3))->result_array();
            $page_data['total_result'] = $total_rows;
        } else {
            $ebooks = $this->ebook_model->filter_ebook($selected_category_id, $selected_price, $selected_rating, $search_text);
            $page_data['ebooks'] = $ebooks;
            $page_data['total_result'] = count($ebooks);
        }
         
        $page_data['page_name']  = "ebook_page";
        $page_data['page_title'] = site_phrase('ebooks');
        $page_data['layout']     = $layout;
        $page_data['selected_category_id']     = $selected_category_id;
        $page_data['selected_price']     = $selected_price;
        $page_data['selected_rating']     = $selected_rating;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    public function ebook_details($slug ="", $ebook_id = "")
    {
        $page_data['page_name'] = "ebook_details";
        $page_data['page_title'] = "ebook_details";
        $page_data['ebook_id'] = $ebook_id;
        $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    }

    // public function ebooks_filter()
    // {
    //     if (!$this->session->userdata('layout')) {
    //         $this->session->set_userdata('layout', 'list');
    //     }
    //     $layout = $this->session->userdata('layout');
    //     $selected_category_id = "all";
    //     $selected_price = "all";
    //     $selected_level = "all";
    //     $selected_language = "all";
    //     $selected_rating = "all";
    //     // Get the category ids
    //     if (isset($_GET['category']) && !empty($_GET['category'] && $_GET['category'] != "all")) {
    //         $selected_category_id = $this->ebook_model->get_category_id($_GET['category']);
    //     }

    //     // Get the selected price
    //     if (isset($_GET['price']) && !empty($_GET['price'])) {
    //         $selected_price = $_GET['price'];
    //     }

    //     // Get the selected level
    //     if (isset($_GET['level']) && !empty($_GET['level'])) {
    //         $selected_level = $_GET['level'];
    //     }

    //     // Get the selected language
    //     if (isset($_GET['language']) && !empty($_GET['language'])) {
    //         $selected_language = $_GET['language'];
    //     }

    //     // Get the selected rating
    //     if (isset($_GET['rating']) && !empty($_GET['rating'])) {
    //         $selected_rating = $_GET['rating'];
    //     }


    //     if ($selected_category_id == "all" && $selected_price == "all" && $selected_level == 'all' && $selected_language == 'all' && $selected_rating == 'all') {
    //         if (!addon_status('scorm_course')) {
    //             $this->db->where('course_type', 'general');
    //         }
    //         $this->db->where('status', 'active');
    //         $total_rows = $this->db->get('course')->num_rows();
    //         $config = array();
    //         $config = pagintaion($total_rows, 6);
    //         $config['base_url']  = site_url('home/courses/');
    //         $this->pagination->initialize($config);
    //         if (!addon_status('scorm_course')) {
    //             $this->db->where('course_type', 'general');
    //         }
    //         $this->db->where('status', 'active');
    //         $page_data['courses'] = $this->db->get('course', $config['per_page'], $this->uri->segment(3))->result_array();
    //         $page_data['total_result'] = $total_rows;
    //     } else {
    //         $courses = $this->crud_model->filter_course($selected_category_id, $selected_price, $selected_level, $selected_language, $selected_rating);
    //         $page_data['courses'] = $courses;
    //         $page_data['total_result'] = count($courses);
    //     }
       

    //     $page_data['page_name']  = "courses_page";
    //     $page_data['page_title'] = site_phrase('courses');
    //     $page_data['layout']     = $layout;
    //     $page_data['selected_category_id']     = $selected_category_id;
    //     $page_data['selected_price']     = $selected_price;
    //     $page_data['selected_level']     = $selected_level;
    //     $page_data['selected_language']     = $selected_language;
    //     $page_data['selected_rating']     = $selected_rating;
    //     $this->load->view('frontend/' . get_frontend_settings('theme') . '/index', $page_data);
    // }

    
}