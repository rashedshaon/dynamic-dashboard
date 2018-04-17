<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: MD RASHED IBNA ANOWAR
 * Date: 8/19/2017
 * Time: 1:20 PM
 */
class Chart_model extends Custom_model {

    public $table_name = "dashboard_chart_blocks";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Process settings string data line by line.
     * 
     * 
     */
    public function process_settings_data($settings_array)
    {
        $settings = [];
        foreach($settings_array as $key => $value)
        {

            $settings[] = [
                "label" => ucwords(str_replace("_"," ", $key)),
                "key" => $key,
                "value" => htmlspecialchars ($value, ENT_QUOTES, 'UTF-8'),
            ];
        }

        return $settings;
    }

    public function ini_encode($array)
    {
        $ini = '';

        foreach($array as $key => $value)
        {
            $ini .= $key." = '".$value."'". PHP_EOL;
        }

        return $ini;
    }


    /**
     * Retrive chart settings as type.
     * 
     */
    public function settings($file_name)
    {
        $path = APPPATH.'views/dashboard/chart_settings/';
        $settings = parse_ini_file($path.$file_name.'.ini', true);

        $settings = $this->process_settings_data($settings);

        return json_encode($settings);
    }

    /**
     * Retrive chart type list.
     * 
     */
    public function type_list()
    {
        $data = [];
        $path = APPPATH.'views/dashboard/chart_settings/';
        $filenames = get_filenames($path);
        foreach($filenames as $filename)
        {
            $key = strstr($filename, '.', true);
            $data[$key] = ucwords(str_replace("_"," ", $key));
        }
        return $data;
    }

    /**
     * Consider a first colunm as bar chart category.
     * After first colunm all selected column data will be Bar's data source and column name will be Bar's name.
     * 
     */
    public function bar_chart($id, $perpared_data = '')
    {  
        $data = $this->findById(['chart_blocks_id' => $id]);

        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            return $this->process_by_column($data, $perpared_data);
        }

        $sql = $data['sql'];
     
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            $query_data = $query->result();
            
            return $this->process_by_column($data, $query_data);

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Consider a first colunm as line chart category.
     * After first colunm all selected column data will be Line's data source and column name will be Line's name.
     * 
     */
    public function line_chart_with_category($id, $perpared_data = '')
    {  
        $data = $this->findById(['chart_blocks_id' => $id]);
        
        /**
         * Return Data after process.
         */
        if(is_array($perpared_data))
        {
            return $this->process_by_column($data, $query_data);
        }

        $sql = $data['sql'];
        
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);
            $query_data = $query->result();
            
            return $this->process_by_column($data, $query_data);

        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Column data will be Line data source and column name will be Line name.
     */
    public function line_chart($id, $perpared_data = '')
    {
        $data = $this->findById(['chart_blocks_id' => $id]);

        /**
         * Return Data From perpared_data.
         */
        if(is_array($perpared_data))
        {
            $data['series'] = json_encode($perpared_data['series']);
            return $data;
        }

        $sql = $data['sql'];
        
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        $process = [];
        $series  = [];

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);

            foreach($query->result() as $row)
            {
                foreach($row as $key => $value)
                {
                    $process[$key][] = (float) $value;
                }
            }
            
            foreach($process as $key => $value)
            {
                $series[] = [
                    'name' => ucwords(str_replace("_"," ", $key)),
                    'data' => $value
                ];
            }

            $data['series'] = json_encode($series);
        }else{
            $data['error'] = implode(' - ', $this->db->error());
        }
        

        return $data;
    }

    /**
     * Process single row by multi column.
     * Process two column data (ex. name, value) by multi row.
     * 
     */
    public function pie_chart($id, $perpared_data = '')
    {
        $data = $this->findById(['chart_blocks_id' => $id]);
        
        /**
         * Return Data From perpared_data.
         */
        if(is_array($perpared_data))
        {
            $data['series'] = json_encode($perpared_data['series']);
            return $data;
        }

        $sql = $data['sql'];
        
        /**
         * Return Data From Custom Function.
         */
        if (stripos($sql, "select") === false) 
        {
            return $this->load_class_method($sql, $data);
        }
        
        $process = [];
        $series  = [];

        if($this->db->simple_query($sql))
        {
            $query = $this->db->query($sql);

            //Process single row by multi column
            if(count($query->result()) == 1)
            {
                foreach($query->result() as $row)
                {
                    foreach($row as $key => $value)
                    {
                        $series[] = [
                            'name' => ucwords(str_replace("_"," ", $key)),
                            'y' => (float) $value
                        ];
                    }
                }
            }
            
            //Process two column data (ex. name, value) by multi row
            if(count($query->result()) > 1)
            {
                foreach($query->result() as $key => $row)
                {
                    $flag = true;
                    foreach($row as  $value)
                    {
                        if($flag)
                        {
                            $flag = false;
                            $series[$key]['name'] = $value;
                        }else{
                            $series[$key]['y'] = (float) $value;
                        }
                    }
                }
            }

            $data['series'] = json_encode($series);

        }else{

            $data['error'] = implode(' - ', $this->db->error());
        }
        
        return $data;
    }

    /**
     * Consider a first colunm as chart category.
     * After first colunm all selected column data will be chart data source and column name will be chart bar/line name.
     * 
     */
    public function process_by_column($chart_data, $query_data)
    {
        $process    = [];
        $series     = [];
        $categories = [];

        foreach($query_data as $row)
        {
            $flag = true;
            foreach($row as $key => $value)
            {
                if($flag)
                {
                    $flag = false;
                    $categories[] = $value;
                }else{

                    $process[$key][] = (float) $value;
                }
            }
        }
        
        foreach($process as $key => $value)
        {
            $series[] = [
                'name' => ucwords(str_replace("_"," ", $key)),
                'data' => $value
            ];
        }

        $chart_data['categories'] = json_encode($categories);
        $chart_data['series'] = json_encode($series);

        return $chart_data;
    }
    

    /**
     * Custom function template.
     * 
     */
    public function custom_data_function($data)
    {
        $process    = [];
        $series     = [];
        $categories = [];

        $query = $this->db->query('SELECT region, SUM(cost) as cost, sum(amount) as amount FROM bar_chart2 GROUP BY region;');

        foreach($query->result() as $row)
        {
            $flag = true;
            foreach($row as $key => $value)
            {
                if($flag)
                {
                    $flag = false;
                    $categories[] = $value;
                }else{

                    $process[$key][] = (float) $value;
                }
            }
        }
        
        foreach($process as $key => $value)
        {
            $series[] = [
                'name' => ucwords(str_replace("_"," ", $key)),
                'data' => $value
            ];
        }

        // Prepare data as follow.

        // $data['categories'] = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        // $data['series'] = [{
        //                     name: 'Tokyo',
        //                     data: [7.0, 6.9, 9.5, 14.5, 18.4, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        //                 }, {
        //                     name: 'London',
        //                     data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        //                 }];

        $data['categories'] = json_encode($categories);
        $data['series'] = json_encode($series);

        return $data;
    }
}