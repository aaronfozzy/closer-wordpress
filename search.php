<?php
/**
 * The Search template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dosth
 */
get_header();
?>
<div class="content-container">    
    <section class="page-title">
        <div class="inner">                 
            <h1 role=”status”><?php _e( 'Search results for: ', 'nd_dosth' ); ?><?php echo get_search_query(); ?></h1>
        </div>
    </section>
    <section class="container">
        <div class="inner">
            <div class="row">
                <div class="search-results-container col-md-8">
                <?php if ( have_posts() ): ?>
                    <?php while( have_posts() ): ?>
                        <?php the_post(); ?>
                        <div class="search-result st1">
                            <h2>
                            <?php
                            $currentpost = get_post_type();
                            if($currentpost != 'page' && $currentpost != 'post'){
                                echo '<div class="label ' . $currentpost . '">' . $currentpost . '</div>';
                            }
                            ?><?php the_title(); ?></h2>
                            <?php the_excerpt(); ?>
                            <a href="<?php the_permalink(); ?>" class="read-more-link button button-strong">
                                <?php _e( 'Read More', 'nd_dosth' );  ?>
                            </a>
                        </div>
                    <?php endwhile; ?>
                    <?php the_posts_pagination(); ?>
                <?php else: ?>
                    <p><?php _e( 'No Search Results found', 'nd_dosth' ); ?></p>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>