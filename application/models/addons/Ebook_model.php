<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ebook_model extends CI_Model
{


    public function get_category_id($slug = "")
    {
        $category_details = $this->db->get_where("ebook_category", array("slug" => $slug))->row_array();
        return $category_details['category_id'];
    }
    public function get_categories($param1 = "")
    {
        if($param1 != ""){
            $this->db->where($param1);
        }
        return $this->db->get('ebook_category');
    }
    public function get_active_addon_by_category_id($category_id = "", $category_id_type = "category_id"){
        $this->db->where($category_id_type, $category_id);
        $this->db->where('is_active', 1);
        return $this->db->get('ebook');
    }
    public function get_category_details_by_id($id)
    {
        return $this->db->get_where('ebook_category', array('category_id' => $id));
    }
    function filter_ebook($selected_category_id = "", $selected_price = "", $selected_rating = "", $search_text ="")
    {
        //echo $selected_category_id.' '.$selected_price.' '.$selected_level.' '.$selected_language.' '.$selected_rating;

        $ebook_ids = array();
        if ($selected_category_id != "all") {
            $category_id = $this->get_category_details_by_id($selected_category_id)->row('category_id');
        }

        if ($selected_rating != "all") {
            $this->db->where('is_active', 1);
            $ebooks = $this->db->get('ebook')->result_array();
            foreach ($ebooks as $key => $ebook) {
                $total_rating =  $this->get_ratings( $ebook['ebook_id'], true)->row()->rating;
                $number_of_ratings = $this->get_ratings($ebook['ebook_id'])->num_rows();
                if ($number_of_ratings > 0) {
                    $average_ceil_rating = ceil($total_rating / $number_of_ratings);
                    if ($average_ceil_rating == $selected_rating) {
                        array_push($ebook_ids, $ebook['ebook_id']);
                    }
                    
                }
                
            }
        }

        if($search_text != ""){
            $this->db->group_start();
            $this->db->like('title', $search_text);
            $this->db->or_like('description', $search_text)->group_end();
        }
        
        if ($selected_category_id != "all") {
            $this->db->where('category_id', $category_id);
        }
        
        if ($selected_price != "all") {
            if ($selected_price == "paid") {
                $this->db->where('is_free', 0);
            } elseif ($selected_price == "free") {
                $this->db->where('is_free', 1);
            }
        }

        if ($selected_rating != "all") {
            if(!empty($ebook_ids)){
                $this->db->where_in('ebook_id', $ebook_ids);

            }else{
                $this->db->where_in('ebook_id', "");
            }
        }
            
        return $this->db->get('ebook')->result_array();
        
           
        
    }

    public function get_ratings ($ratable_id = "", $is_sum = false)
    {
        if ($is_sum) {
            $this->db->select_sum('rating');
            return $this->db->get_where('ebook_reviews', array('review_id' => $ratable_id));
        } else {
            return $this->db->get_where('ebook_reviews', array('review_id' => $ratable_id));
        }
    }

    public function get_ebook_thumbnail_url($ebook_id)
    {

        if (file_exists('uploads/thumbnails/ebook_thumbnails/' . $ebook_id . '.jpg'))
            return base_url() . 'uploads/thumbnails/ebook_thumbnails/' . $ebook_id . '.jpg';
        else
            return base_url() . 'uploads/thumbnails/thumbnail.png';
    }

    public function get_ebook_by_id($ebook_id = "")
    {
       return $this->db->get_where("ebook", array("ebook_id" => 1));

    }
    public function get_ebooks($category_id = "",  $instructor_id = 0)
    {
        if ($category_id > 0 && $instructor_id > 0) {

            $multi_instructor_course_ids = $this->multi_instructor_course_ids_for_an_instructor($instructor_id);
            $this->db->where('category_id', $category_id);
            $this->db->where('user_id', $instructor_id);

            if ($multi_instructor_course_ids && count($multi_instructor_course_ids)) {
                $this->db->or_where_in('id', $multi_instructor_course_ids);
            }

            return $this->db->get('ebook');
        } elseif ($category_id > 0  && $instructor_id == 0) {
            return $this->db->get_where('ebook', array('category_id' => $category_id));
        } else {
            return $this->db->get('course');
        }
    }

    public function get_percentage_of_specific_rating($rating = "", $ratable_type = "", $ratable_id = "")
    {
        $number_of_user_rated = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id
        ))->num_rows();

        $number_of_user_rated_the_specific_rating = $this->db->get_where('rating', array(
            'ratable_type' => $ratable_type,
            'ratable_id'   => $ratable_id,
            'rating'       => $rating
        ))->num_rows();

        //return $number_of_user_rated.' '.$number_of_user_rated_the_specific_rating;
        if ($number_of_user_rated_the_specific_rating > 0) {
            $percentage = ($number_of_user_rated_the_specific_rating / $number_of_user_rated) * 100;
        } else {
            $percentage = 0;
        }
        return floor($percentage);
    }
    public function get_user($user_id = 0)
    {
        if ($user_id > 0) {
            $this->db->where('id', $user_id);
        }
        // $this->db->where('role_id', 2);
        return $this->db->get('users');
    }

    //backend

    public function get_all_ebooks()
    {
        $this->db->order_by('ebook_id', 'desc');
        return $this->db->get('ebook')->result_array();
    }
    function get_ebook_categories($ebook_category_id = ""){
        if($ebook_category_id > 0){
            $this->db->where('category_id', $ebook_category_id);
        }
        return $this->db->get('ebook_category');
    }
}