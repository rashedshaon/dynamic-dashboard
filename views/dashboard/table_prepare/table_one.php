<?php
    $number_format_columns = '';
    $stacked_number_format_columns = [];
    
    if(isset($settings))
    {
        extract($settings);
    }

    if($status_value_color)
    {
        $stacked_status_color = [];

        foreach(explode(',', $status_value_color) as $data)
        {
            $key_value = explode(':', $data);
            $stacked_status_color[$key_value[0]] = $key_value[1];
        }
    }

    if($progress_value_color)
    {
        $stacked_progress_color = [];

        foreach(explode(',', $progress_value_color) as $data)
        {
            $key_value = explode(':', $data);
            $stacked_progress_color[$key_value[0]] = $key_value[1];
        }
    }

    if($number_format_columns)
    {
        $stacked_number_format_columns = explode(',', $number_format_columns);
        array_unshift($stacked_number_format_columns,'');
        unset($stacked_number_format_columns[0]);
    }

    if($progress_active_values)
    {
        $stacked_progress_active_values = explode(',', $progress_active_values);
        array_unshift($stacked_progress_active_values,'');
        unset($stacked_progress_active_values[0]);
    }
?>


<div class="box box-info">
    <?php if($title_text){ ?>   
    <div class="box-header with-border">
        <h3 class="box-title"><?=$title_text?></h3>
    </div>
    <?php } ?>

    <div class="box-body">

        <div class="table-responsive">
            <table class="table no-margin <?=($data_hover == 'true')?'table-hover':''?> <?=($table_border == 'true')?'table-bordered':''?> <?=($data_table == 'true')?'dataTable':''?> <?=$background_color?>">
                <thead class="bg-<?=$head_color?>">
                    <tr>
                    <?php
                        if($show_serial == 'true'){
                    ?>
                        <th>#</th>
                    <?php
                        }
                    ?>
                    <?php 
                        foreach($table_data as $row)
                        {
                            foreach($row as $key => $value)
                            {
                                ?>
                                <th class="<?=(array_search($key, $stacked_number_format_columns))?'text-right':''?>"><?=ucwords(str_replace("_"," ", $key))?></th>
                                <?php
                            }
                            break;
                        }
                    ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $serial = 1; 
                        foreach($table_data as $row)
                        {
                            ?>
                            <tr>
                            <?php
                                //Increment Serial
                                if($show_serial == 'true'){
                            ?>
                                <td><?=$serial++?></td>
                            <?php
                                }
                            ?>
                            <?php
                            foreach($row as $key => $value)
                            {
                                ?>

                                <td class="<?=(array_search($key, $stacked_number_format_columns))?'text-right':''?>">
                                    <?php 
                                        //Check Status table for badge type show
                                        if($key == $status_column){
                                            echo '<span class="badge bg-'.array_search($value, $stacked_status_color).'">'.$value.'</span>';
                                        }

                                        //Check table row for progress
                                        elseif($key == $progress_column){
                                            ?>
                                            <!-- logic for <div class="progress progress-xs progress-striped active"> -->
                                            <div class="progress progress-xs <?=($progress_striped == 'true')?'progress-striped':''?> <?=(array_search($row->$progress_active_column, $stacked_progress_active_values))?'active':''?>">
                                                <div class="progress-bar bg-<?=array_search($row->$progress_active_column, $stacked_progress_color)?>" style="width: <?=$value?>%"></div>
                                            </div>
                                            <?php
                                        }

                                        else{
                                            if(array_search($key, $stacked_number_format_columns))
                                            {
                                                echo number_format($value,2,".",",");
                                            }else{
                                                echo $value;
                                            }
                                        }
                                        
                                    ?>
                                </td>

                                <?php
                            }
                            ?>
                            </tr>
                            <?php
                        }
                    ?>

                </tbody>

            </table>
        </div>

    </div>
    
    <?php if($link_label){ ?>
    <div class="box-footer clearfix">
        <a href="<?=$link_url?>" class="btn btn-sm btn-default btn-flat pull-right"><?=$link_label?></a>
    </div>
    <?php } ?>

</div>


