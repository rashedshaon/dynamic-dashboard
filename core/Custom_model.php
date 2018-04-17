<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Nirjhar
 * Date: 8/20/2017
 * Time: 12:54 PM
 */
class Custom_model extends CI_Model {

    public $table_name;
    public $posted_data;
    public $timestamp;
    public $current_date;

    function __construct(){
        parent::__construct();

        date_default_timezone_set('Asia/Dhaka');
        $this->timestamp = date('Y-m-d H:i:s');
        $this->current_date = date('Y-m-d') ;
        
        //if ($this->input->server('REQUEST_METHOD') == 'POST')
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $this->posted_data = $this->input->post();
        }
        if ($this->input->server('REQUEST_METHOD') == 'GET')
        {
            $this->posted_data = $this->input->get();
        }
    }

    public function insert()
    {
        if(isset($this->user_id))
        {
            $this->posted_data['created_by'] = $this->user_id;
        }
        $this->db->insert($this->table_name, $this->posted_data);
        return $this->db->insert_id();
    }

    public function update($where)
    {
        if(isset($this->user_id))
        {
            $this->posted_data['updated_by'] = $this->user_id;
        }
        $this->db->update($this->table_name, $this->posted_data, $where);
        return true;
    }

    public function deleteItem($where)
    {
        $this->db->delete($this->table_name, $where);
        return true;
    }

    public function find($where, $select = '')
    {
        if($select)
        {
            $this->db->select($select);
        }
        $this->db->where($where);
        $Q = $this->db->get($this->table_name);
        $this->posted_data = $Q->result_array();
        return $this->posted_data;
    
    }
    
    public function findById($where)
    {
        $this->db->where($where);
        $Q = $this->db->get($this->table_name);
        $this->posted_data = $Q->result_array()[0];
        return $this->posted_data;
    }

    public function findAll($select = '')
    {
        if($select)
        {
            $this->db->select($select);
        }
        $Q = $this->db->get($this->table_name);
        $this->posted_data = $Q->result_array();
        return $this->posted_data;
    }


    /**
     * Dynamically laod a specific method.
     */
    public function load_class_method($string, $param)
    {
        $class_method = explode('.', $string);
        if(count($class_method) > 1)
        {
            $class_name = $class_method[0];
            $method_name = $class_method[1];

            if(class_exists($class_name))
            {
                return $this->$class_name->$method_name($param);
            }else{
                $this->load->model($class_name,'','TRUE');
                return $this->$class_name->$method_name($param);
            }
        }else{
            $method_name = $class_method[0];
            return $this->$method_name($param);
        }
    }
}