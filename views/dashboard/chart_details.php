<?php header_js(['https://code.highcharts.com/highcharts.js']); ?>
<style>
    #settings-block{
        //border: 2px solid black;
        display:none;
        overflow:hidden;
        height: 79px;
    }
    .label-block span {
        display: inline-block;
        width: 48%;
    }
    .label-block span:last-child {
        text-align: right;
        cursor: pointer;
        color: gray;
    }

    table.input-block {
        font-size: 13px;
        border-bottom: none;
    }

    table.input-block td{
        padding-left: 2px;
        border: 1px solid lightgrey;
    }

    table.input-block input{
        width: 100%;
        border: none;
        background-color: transparent;
        color: black;
    }

    table.input-block input:focus{
        outline: none;
    }

    table.input-block td:first-child{
        width: 50%;
    }



</style>
<div class="col-md-4 no-padding">
    <div class="box box-warning">
        <div class="box-body">
            <form id="chart_form" action="<?=base_url();?><?= isset($chart_blocks_id)?'dashboard/chart/update/'.$chart_blocks_id : 'dashboard/chart/save'; ?>" method="post">
                <div class="form-group">
                    <label for="chart_blocks_name">Chart Block Name:</label>
                    <input type="text" class="form-control" id="chart_blocks_name" name="chart_blocks_name" value="<?php echo isset($chart_blocks_name)?$chart_blocks_name:''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="type">Chart Type:</label>
                    <select class="form-control" id="type" name="type" required>
                    <option value="">Select Settings</option>
                    <?php foreach($type_list as $key => $type_value){  ?>
                        <option value="<?php echo $key; ?>" <?php if(isset($type)){ echo ($type == $key)?'selected':''; } ?> >
                            <?php echo $type_value; ?>
                        </option>
                    <?php } ?>
                    </select>
                </div>

                <div id="settings-block">
                    <div class="label-block"><span><b>Chart Settings:</b></span> <span class="more-btn"><i class="fa fa-cog" aria-hidden="true"></i> more settings</span></div>    
                    <table class="input-block"></table>
                </div>    
         
                <div class="form-group">
                    <label for="sql">Chart SQL/Own Function Name:</label>
                    <textarea class="form-control" rows="3" id="sql" name="sql" required><?php echo isset($sql)?$sql:''; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="description">Chart Description:</label>
                    <textarea class="form-control" rows="3" id="description" name="description"><?php echo isset($description)?$description:''; ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-warning btn-flat btn-block bg-yellow-gradient">Save</button>
            </form>
        </div>
    </div>
</div>
<div class="col-md-8">
    
    <div class="box box-warning">
        <div class="box-header with-border">
            <!--            <h3 class="box-title">--><!--</h3>-->
            <h3 class="box-title"><span class="labelchange" id="master_entry_table">Chart Entry List</span></h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="chart_grid_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Chart Name</th>
                            <th>Chart Type</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($table_data as $key => $data){  ?>
                        <tr>
                            <th><?= $data['chart_blocks_id']; ?></th>
                            <th><?= $data['chart_blocks_name']; ?></th>
                            <th><?= $data['type']; ?></th>
                            <th><?= $data['description']; ?></th>
                            <th>
                                <a class="modal-load-btn" data-type="<?= $data['type'] ?>" data-id="<?= $data['chart_blocks_id'] ?>" ><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="<?php echo base_url().'dashboard/chart/edit/'.$data['chart_blocks_id']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                                <a href="<?php echo base_url().'dashboard/chart/delete/'.$data['chart_blocks_id']; ?>"><i class="glyphicon glyphicon-remove text-red"></i></a>
                            </th>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>



<!-- Modal -->
<div id="chartModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
            <div>
                <div id="chartContainer">Something is wrong. Check console response.</div>
            </div>
            <hr>
            <div>
                <h4><i class="fa fa-code" aria-hidden="true"></i> Copy Code to use anywhere in Application.</h4>
                <code>&lt;?php __chart("<span class="chart-slug">SLUG_NAME</span>"); ?&gt;</code>
                <h5>or, pass the data.</h5>
                <code>&lt;?php __chart("<span class="chart-slug">SLUG_NAME</span>", $data_array); ?&gt;</code>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



<script>
    $(document).ready(function(){

        <?php if(isset($chart_blocks_id)){ ?>
                load_settings('<?= $settings; ?>');
        <?php } ?>
       
        $('#chart_form').validator();
        $('#chart_grid_table').DataTable();
        $('#type').change(function(){
            if($(this).val() != ''){
                
                $('#settings').val('');
                $.ajax({
                    url:'<?php echo base_url('dashboard/chart/get_settings'); ?>',
                    type:'post',
                    data:{file_name: $(this).val()},
                    success: function(data){
                        load_settings(data);
                    }
                });
            }else{
                $('#settings-block').hide();
            }
        });  

        

        $('.modal-load-btn').click(function(){
            var type = $(this).attr('data-type');
            var id = $(this).attr('data-id');
            $('.chart-slug').text(type + '-' + id);
            $('#chartModal').modal(); 
            $.ajax({
                url:'<?php echo base_url('dashboard/chart/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    eval(data);
                    Highcharts.chart('chartContainer', chart_data);
                }
            });
        });
    });

    //expend settings
    $(document).on('click', '.more-btn', function(){
        var settings_block = $('#settings-block');
        //console.log(settings_block.height(), 'hh');
        if(settings_block.height() > '79')
        {
            settings_block.height('79px');
        }else{
            settings_block.height('auto');
        }
    });

    function load_settings(settings)
    {
        $('#settings-block').show();
        $('.input-block').html(''); 
        var settings = JSON.parse(settings);
        $.each(settings, function(k,v){
            
            var input = '<tr><td>' + v.label + ':</td><td><input type="text" placeholder="' + v.label + '" id="' + v.key + '" name="settings[' + v.key + ']" /></td></tr>';
            $('.input-block').append(input);
            var value = $('<textarea />').html(v.value).text();
            $('#' + v.key).val(value);
        });
    }
</script>
