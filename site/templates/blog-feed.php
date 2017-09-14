<?php
   $panels = $pages->find("has_parent=blog");
   $panels = $panels->reverse();
   foreach($panels as $panel) {
?>

    <div class="panels row">
        <div class="col-xs-12">
            <h2><a href="<?php echo $panel->url; ?>"><?php echo $panel->title; ?></a></h2>
        </div>
        <div class="col-md-3">
            <?php
                 if($panel->blog_image){
                    echo "<a href='{$panel->url}'><img class='img-responsive' src='{$panel->blog_image->height(500)->url}' alt=''></a>";

                 }
             ?>
        </div>
        <div class="col-md-6">
            <h4><?php echo $panel->blog_date; ?></h4>
            <?php echo $panel->blog_description; ?>
            <p class="readmore"><a href="<?php echo $panel->url; ?>">Read More</a></p>
        </div>
   </div>
   <hr>
<?php } ?>
