<?php

/**
 * Standard page
 *
 * @package	CLOSER-2022
 * @since	0.1
 */

?>

<?php get_header(); ?>
<?php

// Output colour class

$pickedcolour = $closer_filters['colours'] ? null : pilau_get_first_term( 'colours' );

if(!is_null($pickedcolour)){
	echo '<div class="' . $pickedcolour->slug . '">';
}else{
	echo '<div class="yellow">';
}
?>
<div id="content" role="main">

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">
		<section class="page-title">
			<div class="inner">
				<h1><?php the_title(); ?></h1>
			</div>
		</section>
		<section>
			<div class="inner">
			<div class="underline">
				<div class="post-content indent main-content">
					<div class="wrapper limit-width-narrow">
<?php
$s=get_search_query();
$args = array(
                's' =>$s
            );
    // The Query
$the_query = new WP_Query( $args );
if ( $the_query->have_posts() ) {
        _e("<h2 style='font-weight:bold;color:#000'>Search Results for: ". $s ."</h2>");
        while ( $the_query->have_posts() ) {
           $the_query->the_post();
                 ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>
                 <?php
        }
    }else{
	?>
        <h2 style='font-weight:bold;color:#000'>Nothing Found</h2>
        <div class="alert alert-info">
          <p>Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
        </div>
<?php } ?>
					</div>
				</div>
			</div>
			</div>
		</section>
		</article><!-- #post-<?php the_ID(); ?> -->

	<?php endwhile; endif; ?>

</div>
</div>

<?php get_footer(); ?>