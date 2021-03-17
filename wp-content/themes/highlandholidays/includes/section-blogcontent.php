<?php if( have_posts() ): while( have_posts() ): the_post();?>

    <p><?php echo get_the_date('l d/m/Y')?></p>

    <?php the_content();?>

    <p>Created by <?php 
        $fname = get_the_author_meta('first_name');
        $lname = get_the_author_meta('last_name');
        echo $fname . ' ' . $lname;
    ?></p>

    <p>Tags : <?php
    $tags = get_the_tags();
    foreach($tags as $tag):?>
        <a href="<?php echo get_tag_link($tag->term_id);?>" class="badge bg-success">
            <?php echo $tag->name;?>
        </a>
    <?php endforeach ?></p>

    <p>Categories: <?php
    $categories = get_the_category();
    foreach($categories as $cat):?>
        <a href="<?php echo get_category_link($cat->term_id);?>" class="badge bg-success">
        <?php echo $cat->name;?>
        </a>
    <?php endforeach;?></p>
    

<?php endwhile; else: endif;?>