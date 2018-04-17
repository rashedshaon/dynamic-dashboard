<?php header_js(['https://code.highcharts.com/highcharts.js']); ?>
<style>
    .grid-elements {
        border: 1px solid gold;
    }
    #layout,
    #editable-layout,
    #edit-layout .lyrow .preview,
    #edit-layout .db-element-block .icon,
    #edit-layout .db-element-block .drag-o,
    .grid-elements .lyrow .control,
    .grid-elements .lyrow .view,
    .dashboard-elements .remove
    {
        display: none;
    }

    .grid-elements .lyrow{
        width: 32%;
        margin-bottom: 3px;
        margin-right: 3px;
        float: left;
    }

    .grid-elements .lyrow .preview {
        margin-bottom: 5px;
        border: 1px solid gray;
        border-radius: 5px;
    }

    .grid-elements .lyrow .preview input {
        border: none;
        background-color: transparent;
        color: black;
    }

    .grid-elements .lyrow .preview input:focus {
        outline: none;
    }

    .grid-elements .lyrow .preview>div:first-child{
        width: 72%;
    }
    .grid-elements .lyrow .preview>div {
        display: inline-block;
    }

    #edit-layout {
        padding-top: 26px;
        padding-bottom: 25px;
        border: 1px solid #DDDDDD;
        min-height: 200px;
        height: auto;
    }

    #edit-layout:after {
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
        content: "Container";
        font-size: 12px;
        font-weight: bold;
        left: -1px;
        padding: 3px 7px;
        position: absolute;
        top: -1px;
    }


    #edit-layout .lyrow .column {
        border: 1px dotted rgb(240, 35, 35);
        padding-top: 30px;
        padding-bottom: 12px;
        min-height: 80px;
        height: auto;
        margin-bottom: 5px;
    }

    #edit-layout .lyrow .column:after {
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
        content: "Column";
        font-size: 12px;
        font-weight: bold;
        left: 0px;
        padding: 3px 7px;
        position: absolute;
        top: 0px;
    }

    #edit-layout .lyrow .nav-tabs-custom .column {
        border: 1px dotted blue;
    }

    #edit-layout .lyrow .nav-tabs-custom .column:after {
        border: 1px solid #DDDDDD;
        border-radius: 4px 0 4px 0;
        color: #9DA0A4;
        content: "Tab";
        font-size: 12px;
        font-weight: bold;
        padding: 3px 7px;
        position: absolute;
        left: 26px;
        top: 100px;
    }

    .container-placeholder {
        border: 2px dashed #000000;
        background-color: lightgoldenrodyellow;
        height: 100px;
    }

    .column-placeholder {
        border: 1px dashed #000000;
        background-color: lightcyan;
        height: 100px;
    }


    .tab-name-input {
        width: 100%;
        border: none;
        border-bottom: 1px solid lightgray;
        background-color: transparent;
        color: black;
    }

    .tab-name-input:focus {
        outline: none;
    }

    .dashboard-elements .db-element-block {
        width: 209px;
        display: block;
        border: 1px solid lightblue;
        padding: 3px;
        margin-bottom: 3px;
        margin-left: 3px;
        float: left;
    }

    .dashboard-elements .db-element-block .db-element{
        overflow: hidden;
        width: 78%;
        white-space: nowrap;
        line-height: 9px;
    }

 

    .dashboard-elements .db-element-block > div{display: inline-block;}

    .expend-close {
        background-color: #dddddd;
        padding: 2px 0px 2px 5px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .search-block{
        width: 75%;
        text-align: right;
        float: right;
    }
    .search-element{

    }

    .highlight {
        font-weight: 800;
        background-color: #fff34d;
        padding: 0px 4px 0px 0px;
        margin: 0px -4px 0px 0px;
    }

    #save-layout {
        display: none;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="expend-close"><i class="fa fa-compress" aria-hidden="true"></i></div>
        <div class="box box-warning">
            <div class="box-body">
                <form id="dashboard_form" action="<?=base_url();?><?= isset($dashboard_manage_id)?'dashboard/manage/update/'.$dashboard_manage_id : 'dashboard/manage/save'; ?>" method="post">
                    <div class="form-group">
                        <label for="dashboard_manage_name">Dashboard Name:</label>
                        <input type="text" class="form-control" id="dashboard_manage_name" name="dashboard_manage_name" value="<?php echo isset($dashboard_manage_name)?$dashboard_manage_name:''; ?>" required>
                    </div>  
                
                    <div class="form-group">
                        <label for="description">Dashboard Description:</label>
                        <textarea class="form-control" rows="3" id="description" name="description"><?php echo isset($description)?$description:''; ?></textarea>
                    </div>

                    <textarea  class="form-control" rows="3" id="layout" name="layout"><?php echo isset($layout)?$layout:''; ?></textarea>
                    <textarea  class="form-control" rows="3" id="editable-layout" name="editable_layout"><?php echo isset($editable_layout)?$editable_layout:''; ?></textarea>
                    
                    <button id="save-layout-btn" type="submit" class="btn btn-warning btn-flat btn-block bg-yellow-gradient">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="expend-close"><i class="fa fa-compress" aria-hidden="true"></i></div>
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title"><span class="labelchange" id="master_entry_table">Dashboard Entry List</span></h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="dashboard_grid_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Dashboard Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($dashboard_data as $key => $data){  ?>
                            <tr>
                                <th><?= $data['dashboard_manage_id']; ?></th>
                                <th><?= $data['dashboard_manage_name']; ?></th>
                                <th><?= $data['description']; ?></th>
                                <th>
                                    <a href="<?php echo base_url().'dashboard/manage/view/'.$data['dashboard_manage_id']; ?>" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a href="<?php echo base_url().'dashboard/manage/edit/'.$data['dashboard_manage_id']; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
                                    <a href="<?php echo base_url().'dashboard/manage/delete/'.$data['dashboard_manage_id']; ?>"><i class="glyphicon glyphicon-remove text-red"></i></a>
                                </th>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <span class="labelchange" id="master_entry_table">Dashboard Grids</span>
                </h3>
            </div>
            <div class="box-body grid-elements">
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div class="grid-input">
                            <input type="text" value="12" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <div class="row">
                            <div class="col-md-12 column"></div>
                        </div>
                    </div>
                </div>
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div class="grid-input">
                            <input type="text" value="6 6" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <div class="row">
                            <div class="col-md-6 column"></div>
                            <div class="col-md-6 column"></div>
                        </div>
                    </div>
                </div>
    
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div class="grid-input">
                            <input type="text" value="4 4 4" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <div class="row">
                            <div class="col-md-4 column"></div>
                            <div class="col-md-4 column"></div>
                            <div class="col-md-4 column"></div>
                        </div>
                    </div>
                </div>
    
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div class="grid-input">
                            <input type="text" value="3 3 3 3" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <div class="row">
                            <div class="col-md-3 column"></div>
                            <div class="col-md-3 column"></div>
                            <div class="col-md-3 column"></div>
                            <div class="col-md-3 column"></div>
                        </div>
                    </div>
                </div>
    
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div class="grid-input">
                            <input type="text" value="8 4" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <div class="row">
                            <div class="col-md-8 column"></div>
                            <div class="col-md-4 column"></div>
                        </div>
                    </div>
                </div>
    
    
                <div class="lyrow">
                    <div class="control">
                        <a class="remove btn btn-danger btn-xs">
                            <i class="fa fa-remove"></i>
                        </a>
                        <a class="clone btn btn-info btn-xs">
                            <i class="fa fa-clone"></i>
                        </a>
                        <a class="drag btn btn-default btn-xs">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                    <div class="preview">
                        <div>
                            <input type="text" value="Tab" class="form-control">
                        </div>
                        <div>
                            <a class="drag btn btn-default btn-xs">
                                <i class="fa fa-arrows"></i>
                            </a>
                        </div>
                    </div>
                    <div class="view">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">Tab 1</a>
                                </li>
    
                                <li class="pull-right">
                                    <a class="add-tab-btn" class="text-muted">
                                        <i class="fa fa-plus-circle"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane column active" id="tab_1">
    
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <span class="labelchange" id="master_entry_table">Dashboard Elements</span>
                </h3>
                <div class="search-block"><input class="search-element" type="text" placeholder="Search Element"/></div>
            </div>
            <div class="box-body dashboard-elements">
                <?php if(count($chart_data) > 0) {?>
                <?php foreach($chart_data as $data) { ?>
                <div class="db-element-block">
                    <a class="remove btn btn-danger btn-xs" title="<?=$data['chart_blocks_name']?>">
                        <i class="fa fa-remove"></i>
                    </a>
                    <div class="icon"><i class="fa fa-line-chart"></i></div> 
                    <div class="db-element" id="<?=$data['type'].'-'.$data['chart_blocks_id']?>" element-type="chart" element-id="<?=$data['type'].'-'.$data['chart_blocks_id']?>"><?=$data['chart_blocks_name']?></div>
                    <div class="drag-o">
                        <a class="drag btn btn-default btn-xs" title="<?=$data['chart_blocks_name']?>">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
    
                <?php if(count($infobox_data) > 0) {?>
                <?php foreach($infobox_data as $data) { ?>
                <div class="db-element-block">
                    <a class="remove btn btn-danger btn-xs" title="<?=$data['info_blocks_name']?>">
                        <i class="fa fa-remove"></i>
                    </a>
                    <div class="icon"><i class="fa fa-square-o"></i></div> 
                    <div class="db-element" id="<?=$data['type'].'-'.$data['info_blocks_id']?>" element-type="infobox" element-id="<?=$data['type'].'-'.$data['info_blocks_id']?>"><?=$data['info_blocks_name']?></div>
                    <div class="drag-o">
                        <a class="drag btn btn-default btn-xs" title="<?=$data['info_blocks_name']?>">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
    
                <?php if(count($table_data) > 0) {?>
                <?php foreach($table_data as $data) { ?>
                <div class="db-element-block">
                    <a class="remove btn btn-danger btn-xs" title="<?=$data['table_blocks_name']?>">
                        <i class="fa fa-remove"></i>
                    </a>
                    <div class="icon"><i class="fa fa-table"></i></div> 
                    <div class="db-element" id="<?=$data['type'].'-'.$data['table_blocks_id']?>" element-type="table" element-id="<?=$data['type'].'-'.$data['table_blocks_id']?>"><?=$data['table_blocks_name']?></div>
                    <div class="drag-o">
                        <a class="drag btn btn-default btn-xs" title="<?=$data['table_blocks_name']?>">
                            <i class="fa fa-arrows"></i>
                        </a>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div id="edit-layout" class="col-md-12">
        <?php 
            if(isset($editable_layout))
            {
                echo $editable_layout;
            }
        ?>
    </div>
</div>

<div id="save-layout">
    <div class="container-fluid"></div>
</div>


<!-- Modal -->
<div id="contentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> LAYOUT TO SAVE</h4>
            </div>
            <div class="modal-body">
                <div>
                    <textarea id="save-content"></textarea>
                </div>
                <hr>
                <div>
                    <textarea id="edit-content"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        
        //remove close buttons on edit mode
        $('.close-tab-btn').remove();
        $('.nav-tabs li a').append(' <a class="close-tab-btn"><i class="fa fa-times-circle" aria-hidden="true"></i></a>');
        $('.nav-tabs li:first-child .close-tab-btn').remove();
        $('.nav-tabs li:last-child .close-tab-btn').remove();

        setTimeout(function(){
            $('.expend-close').parent().parent().find('.box').slideUp("slow");
            $('.expend-close > i').removeClass('fa-compress').addClass('fa-expand');
        }, 3000);

        $(".expend-close" ).click(function() {
            var expend_close = $(this);
            expend_close.parent().parent().find('.box').slideToggle( "slow", function() {
                if ($(this).is(':visible')) {
                   $('.expend-close > i').removeClass('fa-expand').addClass('fa-compress');
                } else {
                   $('.expend-close > i').removeClass('fa-compress').addClass('fa-expand');
                }
            });
        });

        $('.search-element').bind('keyup change', function(ev) {
            // pull in the new value
            var searchTerm = $(this).val();
    
            // remove any old highlighted terms
            $('.db-element-block .db-element').removeHighlight();
    
            // disable highlighting if empty
            if ( searchTerm ) {
                // highlight the new term
                $('.db-element-block .db-element').highlight( searchTerm );
            }
        });

        $('#dashboard_form').validator();
        $('#dashboard_grid_table').DataTable();

        //Remove already loaded elements from Dashboard Elements Panel
        $('#edit-layout [element-id]').each(function(){
            var element_id = $(this).attr('element-id');
            $('.dashboard-elements #'+element_id).parent().remove();
        });

        $("#edit-layout, #edit-layout .column").sortable({
            connectWith: ".column",
            placeholder: "container-placeholder",
            opacity: 1,
            // scroll: true,
            handle: ".drag"
        });

        $(".grid-elements .lyrow").draggable({
            connectToSortable: "#edit-layout",
            helper: "clone",
            handle: ".drag",
            // scroll: true,
            drag: function (e, t) {
                t.helper.width('100%');
                t.helper.height('auto');
            }, stop: function (e, t) {
                //console.log(t);
                var random_id = get_random_id();
                t.helper.find('.nav-tabs a').attr('href', '#' + random_id);
                t.helper.find('.tab-content .tab-pane').prop('id', random_id);

                refresh_to_sortable();
            }
        });


        make_elements_draggable();

        //make grid as input
        $('.grid-input input').change(function(){
            var input = $(this).val();
            var column = '';
            $.each(input.split(' '), function(i, v){
                column += '<div class="col-md-' + v + ' column"></div>';
            });
            $(this).closest('.lyrow').find('.row').html(column);
        });


        $("#save-layout-btn").click(function () {
            downloadLayoutSrc();
            //$('#contentModal').modal();
        });
    });

    $(document).on('click', '.lyrow .control .remove', function () {
    
        restore_element($(this).closest('.lyrow').find('.db-element-block .remove'));
        
        $(this).closest('.lyrow').remove();
    });

    //Remove element
    $(document).on('click', '.db-element-block .remove', function () {

        restore_element($(this));

    });

    //Click to add new tab
    $(document).on('click', '.add-tab-btn', function ($e) {
        $e.preventDefault();

        var id_name = get_random_id();
        var $tab = $(this).parent().parent().find('a[data-toggle="tab"]:last').parent();
        var $clone_tab = $tab.clone();
        $clone_tab.removeClass('active');
        $tab.after($clone_tab.children().attr('href', '#' + id_name).html('New Tab <a class="close-tab-btn"><i class="fa fa-times-circle" aria-hidden="true"></i></a>').parent());


        var $tab_pan = $(this).parent().parent().parent().find('.tab-content .tab-pane:last');
        var $clone_tab_pan = $tab_pan.clone();
        $clone_tab_pan.removeClass('active');
        $tab_pan.after($clone_tab_pan.prop('id', id_name).html(''));

        refresh_to_sortable();
    });

    //double click to edit tab name.
    $(document).on('dblclick', 'a[data-toggle="tab"]', function () {
        $tab_name = $(this).text();
        $(this).html('<input class="tab-name-input" type="text" value="' + $tab_name + '" autofocus/>');
        $('.tab-name-input').focus();
        //alert('ss');
    });

    //on blur input change tab name.
    $(document).on('blur', '.tab-name-input', function () {
        $tab_name = $(this).val();
        $(this).parent().html($tab_name + ' <a class="close-tab-btn"><i class="fa fa-times-circle" aria-hidden="true"></i></a>');
    });

    //remove tab
    $(document).on('click', '.close-tab-btn', function (event) {
        event.preventDefault();
        $remove_id = $(this).parent().attr('href');
        //console.log('')
        $(this).parent().parent().remove();
        $($remove_id).remove();
    });

    //make dashboard elements draggable
    function make_elements_draggable()
    {
        $(".dashboard-elements .db-element-block").draggable({
            connectToSortable: ".column",
            helper: "clone",
            handle: ".drag",
            // scroll: true,
            drag: function (e, t) {
                var w_height = $(window).height();
                var d_height = $(document).height();
                var top = t.offset.top;
                var buttom = t.offset.buttom;
                console.log(top, buttom, 'top buttom');
                console.log(w_height, d_height, 'w_height d_height');

            }, stop: function (e, t) {

                t.helper.width('100%');
                t.helper.height('auto');

                var element_id = t.helper.find('.db-element').attr('element-id');
                var element_type = t.helper.find('.db-element').attr('element-type');
                var inColumn = $('.column [element-id="'+ element_id +'"]');

                if(typeof(inColumn[0]) !== "undefined"){
                    console.log(inColumn[0], 'rrrr');
                    $('.dashboard-elements [element-id="'+ element_id +'"]').parent().remove();
                    load_dashboard_element(element_type, element_id);
                }
            }
        });
    }    

    function downloadLayoutSrc() 
    {
        $("#save-layout").children().html($("#edit-layout").html());

        //refine dashboard elements
        $("#save-layout").children('.container-fluid').find('.db-element').each(function (i) {
            var db_element = $(this);
            db_element.parent().parent().html(db_element.removeAttr('data-highcharts-chart').html(''));
        });

        //refine dashboard row, column
        $("#save-layout").children('.container-fluid').each(function (i) {
            var container = $(this);
            container.children('.lyrow').each(function (i) {
                var row = $(this);
                cleanRow(row);
            });
        });

        $('#editable-layout').val($("#edit-layout").html());
        $('#layout').val(style_html($("#save-layout").html()));

        // $('#edit-content').val($("#edit-layout").html());
        // $('#save-content').val(style_html($("#save-layout").html()));

        // $('#download').modal('show');
    }

    function refresh_to_sortable(){
        $("#edit-layout .column").sortable({
            opacity: 1,
            connectWith: ".column",
            // scroll: true,
            placeholder: "column-placeholder"
        });
    }

    function restore_element(element)
    {
        element.each(function(){
            var title = $(this).attr('title');
            var parent_element = $(this).parent();
                parent_element.removeAttr('style');
                parent_element.find('.db-element').html(title);

            var new_element = jQuery.extend(true, {}, parent_element);
            $(".dashboard-elements").append(new_element);
        });

        // var title = element.attr('title');
        // var parent_element = element.parent();
        //     parent_element.removeAttr('style');
        //     parent_element.find('.db-element').html(title);
        // var new_element = jQuery.extend(true, {}, parent_element);
        // $(".dashboard-elements").append(new_element);
        make_elements_draggable();
    }

    //Clean row colunm unnecessary class and elements
    function cleanRow(row) {

        row.children('.control').remove();
        row.find('.close-tab-btn').remove();
        row.find('.nav-tabs .pull-right').remove();
        row.find('div.ui-sortable').removeClass('ui-sortable');

        row.children('.view').find('br').remove();

        row.children('.view').children('.row').children('.column').each(function () {
            // if there were to be rows in the column then
            var col = $(this);

            col.removeClass('column');
            col.children('.lyrow').each(function () {
                cleanRow($(this));
            });

            // col.children('.box-element').each(function () {
            //     var element = $(this);
            //     element.children('.remove , .drag, .configuration, .preview').remove();
            //     element.parent().append(element.children('.view').html());
            //     element.remove();
            // });
        });
        row.children('.view').find('.column').each(function () {
            // if there were to be rows in the column then
            var col = $(this);

            col.removeClass('column');
            col.children('.lyrow').each(function () {
                cleanRow($(this));
            });
        });
        row.parent().append(row.children('.view').html());
        row.remove();
    }

    function style_html(html) {
        html = html.trim();
        var result = '',
            indentLevel = 0,
            tokens = html.split(/</);
        for (var i = 0, l = tokens.length; i < l; i++) {
            var parts = tokens[i].split(/>/);
            if (parts.length === 2) {
                if (tokens[i][0] === '/') {
                    indentLevel--;
                }
                result += getIndent(indentLevel);
                if (tokens[i][0] !== '/') {
                    indentLevel++;
                }

                if (i > 0) {
                    result += '<';
                }

                result += parts[0].trim() + ">\n";
                if (parts[1].trim() !== '') {
                    result += getIndent(indentLevel) + parts[1].trim().replace(/\s+/g, ' ') + "\n";
                }

                if (parts[0].match(/^(img|hr|br)/)) {
                    indentLevel--;
                }
            } else {
                result += getIndent(indentLevel) + parts[0] + "\n";
            }
        }
        return result;
    }

    function getIndent(level) {
        var result = '',
            i = level * 4;
        if (level < 0) {
            throw "Level is below 0";
        }
        while (i--) {
            result += ' ';
        }
        return result;
    }

    function load_dashboard_element(element_type, element_id){
        var part = element_id.split('-');
        var type = part[0];
        var id = part[1];
        console.log('eeee');
        if(element_type == 'chart'){
            $.ajax({
                url:'<?php echo base_url('dashboard/chart/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    eval(data);
                    Highcharts.chart(element_id, chart_data);
                }
            });
        }else if(element_type == 'infobox'){
            $.ajax({
                url:'<?php echo base_url('dashboard/infobox/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    
                    $('#' + element_id).html(data);

                }
            });
        
        }else if(element_type == 'table'){
            $.ajax({
                url:'<?php echo base_url('dashboard/table/load'); ?>',
                type:'post',
                data:{type: type, id: id},
                success: function(data){
                    
                    $('#' + element_id).html(data);

                }
            });
        }
    }

    function get_random_id() {
        var randnumber = Math.floor((Math.random() * 9999) + 1);
        var id_name = 'random_id_' + randnumber;
        if (document.getElementById(id_name)) {
            get_random_id();
        } else {
            return id_name;
        }
    }



    jQuery.fn.highlight = function(pat) {
    function innerHighlight(node, pat) {
    var skip = 0;
    if (node.nodeType == 3) {
    var pos = node.data.toUpperCase().indexOf(pat);
    if (pos >= 0) {
        var spannode = document.createElement('span');
        spannode.className = 'highlight';
        var middlebit = node.splitText(pos);
        var endbit = middlebit.splitText(pat.length);
        var middleclone = middlebit.cloneNode(true);
        spannode.appendChild(middleclone);
        middlebit.parentNode.replaceChild(spannode, middlebit);
        skip = 1;
    }
    }
    else if (node.nodeType == 1 && node.childNodes && !/(script|style)/i.test(node.tagName)) {
    for (var i = 0; i < node.childNodes.length; ++i) {
        i += innerHighlight(node.childNodes[i], pat);
    }
    }
    return skip;
    }
    return this.each(function() {
    innerHighlight(this, pat.toUpperCase());
    });
    };

    jQuery.fn.removeHighlight = function() {
    function newNormalize(node) {
        for (var i = 0, children = node.childNodes, nodeCount = children.length; i < nodeCount; i++) {
            var child = children[i];
            if (child.nodeType == 1) {
                newNormalize(child);
                continue;
            }
            if (child.nodeType != 3) { continue; }
            var next = child.nextSibling;
            if (next == null || next.nodeType != 3) { continue; }
            var combined_text = child.nodeValue + next.nodeValue;
            new_node = node.ownerDocument.createTextNode(combined_text);
            node.insertBefore(new_node, child);
            node.removeChild(child);
            node.removeChild(next);
            i--;
            nodeCount--;
        }
    }

    return this.find("span.highlight").each(function() {
        var thisParent = this.parentNode;
        thisParent.replaceChild(this.firstChild, this);
        newNormalize(thisParent);
    }).end();
    };
</script>