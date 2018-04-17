<?php
    $title_text = 'Total Salary';
    $sub_text = '%s%% employee salary paid.';
    $background_color = 'red';
    $info_icon = 'fa fa-cog';
    $value_icon = 'fa fa-percent';
    $value_icon_position = 'end';
    $format_number = '';

    if(isset($settings))
    {
        extract($settings);
    }
?>

<div class="info-box bg-<?= $background_color ?>">
    <span class="info-box-icon"><i class="<?= $info_icon ?>" aria-hidden="true"></i></span>

    <div class="info-box-content">
        <span class="info-box-text"><?= ($title_text)?:'' ?></span>
        <span class="info-box-number">
            <?= ($value_icon && $value_icon_position == 'start')? '<small><i class="'.$value_icon.'" aria-hidden="true"></i></small>' : '' ?>
            
            <?php if($format_number == 'true'){ ?>
            <?= isset($info_summary)?  number_format($info_summary,2,".",",") :'999' ?>
            <?php }else{ ?>
            <?= isset($info_summary)? $info_summary :'999' ?>
            <?php } ?>
            
            <?= ($value_icon && $value_icon_position == 'end')? '<small><i class="'.$value_icon.'" aria-hidden="true"></i></small>' : '' ?>
        </span>

        <div class="progress">
            <div class="progress-bar" style="width: <?= isset($info_percent) ? $info_percent : '0' ?>%"></div>
        </div>

        <span class="progress-description">
            <?php 
                $info_percent = isset($info_percent) ? $info_percent : '0';
                echo sprintf($sub_text, $info_percent); 
            ?>
        </span>
    </div>
</div>