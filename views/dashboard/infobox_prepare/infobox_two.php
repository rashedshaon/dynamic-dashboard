<?php
    $title_text = 'Total Salary';
    $background_color = 'red';
    $info_icon = 'fa fa-cog';
    $link_label = 'More Info';
    $link_url = '#';
    $value_icon = 'fa fa-percent';
    $value_icon_position = 'end';
    $format_number = '';

    if(isset($settings))
    {
        extract($settings);
    }
?>

<div class="small-box bg-<?= $background_color ?>">
    <div class="inner">
        <h3>
            <?= ($value_icon && $value_icon_position == 'start')? '<small style="color:#fff"><i class="'.$value_icon.'" aria-hidden="true"></i></small>' : '' ?>   
            
            <?php if($format_number == 'true'){ ?>
            <?= isset($info_summary)?  number_format($info_summary,2,".",",") :'999' ?>
            <?php }else{ ?>
            <?= isset($info_summary)? $info_summary :'999' ?>
            <?php } ?>

            <?= ($value_icon && $value_icon_position == 'end')? '<small style="color:#fff"><i class="'.$value_icon.'" aria-hidden="true"></i></small>' : '' ?>   
        </h3>
        <p>
            <?= ($title_text)?:'' ?>
        </p>
    </div>
    <div class="icon">
        <i class="<?= $info_icon ?>" aria-hidden="true"></i>
    </div>
    <?php if($link_url != ''){ ?>
    <a href="<?= $link_url ?>" class="small-box-footer"><?= $link_label ?> <i class="fa fa-arrow-circle-right"></i></a>
    <?php } ?>
</div>