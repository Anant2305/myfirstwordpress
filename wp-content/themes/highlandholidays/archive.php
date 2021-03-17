<?php get_header();?>
<section class="page-wrap">
<div class="container">

<section class="row">

            <div class="col-lg-3">
            <?php if( is_active_sidebar('blog-sidebar') ):?>
                            <?php dynamic_sidebar('blog-sidebar');?>
                    <?php endif;?>


            </div>

            <div class="col-lg-9">
    <h1><?php echo single_cat_title();?>
    <?php get_template_part('includes/section', 'archive');?>
    </div>

    </section>

<!-- WHen POSTS Setting->Reading->Blog show 
has been set to only show certain blogs on a page -->

<!-- SHOW Next Page and Previous page solution -->
<!-- <?php previous_posts_link();?>
<?php next_posts_link();?> -->

<!-- SHOW Prevous Next and page numbers  -->
<?php
global $wp_query;

$big = 9999999999; //unlikely integer number

echo paginate_links( array( 
    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big )) ),
    'format' => '?paged=%#%',
    'current' => max( 1, get_query_var('paged')),
    'total' => $wp_query->max_num_pages
));  
?>
</div>
<section>

<?php get_footer();?>