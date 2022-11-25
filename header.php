<?php

/**
 * Theme header
 *
 * @package	CLOSER-2022
 * @since	0.1
 */
global $post, $pilau_post_ancestors;
$closer_pages_with_icons = explode( ',', CLOSER_PAGES_WITH_ICONS );


/*
 * Conditional HTML classes for IE / JS targetting
 * @link http://paulirish.com/2008/conditional-stylesheets-vs-styles-hacks-answer-neither/
 */
?><!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js ie lt-ie10 lt-ie9 lt-ie8 lt-ie7 ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js ie lt-ie10 lt-ie9 lt-ie8 ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js ie lt-ie10 lt-ie9 ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]><html class="no-js ie lt-ie10 ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">

	<title><?php wp_title(); ?></title>

	<!-- Mobile meta -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">

 	<!-- Site icons -->
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
	<link rel="icon" type="image/png" href="/favicon-196x196.png" sizes="196x196">
	<link rel="icon" type="image/png" href="/favicon-160x160.png" sizes="160x160">
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<meta name="msapplication-TileColor" content="#2d89ef">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">

	<!-- Fonts -->
	<script type="text/javascript" src="//use.typekit.net/uru0apw.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<!-- Google Tag Manager -->
<!-- <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5WZTFNZ');</script> -->

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-M9Q9XC6');</script>
<!-- End Google Tag Manager -->





<!-- End Google Tag Manager (noscript) -->

	<?php

	/*
	 * For any HTML header code that requires PHP processing, hook to wp_head
	 * Check inc/header.php
	 */
	wp_head();

	/*
	 * Extra Fontello stuff
	 */
	?>
	<!--[if lt IE 8]>
	<link rel="stylesheet" media="all" type="text/css" href="<?php echo get_stylesheet_directory_uri() . '/styles/fontello-ie7.css?v=1'; ?>">
	<![endif]-->

	<?php

	/*
	 * ShareThis customisation
	 */
	?>
	<?php

	/*
	* Modernizr custom build - must be in the header
	*
	* For features in this build, and for customizing it further, check the build URL in modernizr.js
	* Remember to update the version appended here if you upgrade!
	*/
	?>
	<script src="<?php echo get_stylesheet_directory_uri() . '/js/modernizr.js?ver=2.8.3'; ?>"></script>
	<script src="<?php echo get_stylesheet_directory_uri() . '/www/js/all.min.js'; ?>"></script>
	<style>
		.menu-image-title {
			margin-bottom: .4em;
		    color: #85c655;
		    font-weight: 400;
		    line-height: 1.1;
		}
		    
	</style>

    <?php
    $noindex        = false;
    $noindex_meta = get_field('noindex_meta');
    if(isset($noindex_meta)&&$noindex_meta=='yes'){
        $noindex = true;
    }

    // no index
    if($noindex){
        ?><meta name="robots" content="noindex" /><?php
	}
	?>
</head>
<body <?php body_class(); ?> role="document">
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M9Q9XC6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<!-- <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5WZTFNZ"; height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript> -->

<?php /* Upgrade notice for IE 6 and below */ ?>
<!--[if lt IE 7]><p class="upgrade-browser">Please note that this site does not support Internet Explorer 6 and below. Neither does Microsoft! <a href="http://browsehappy.com/">Please upgrade to a modern browser</a> if possible, or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this and other sites in your current browser.</p><![endif]-->


<?php
/* Cookie notice */
if ( PILAU_USE_COOKIE_NOTICE && ! isset( $_COOKIE['pilau_cookie_notice'] ) ) { ?>
	<div id="cookie-notice">
		<div class="wrapper">
			<p>This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies. <a href="/privacy/">Find out more</a>.</p>
			<p class="close img-rep"><a href="?close-cookie-notice=1">Close</a></p>
		</div>
	</div>
<?php } ?>


<div id="main">

<!-- Start custom header -->

<a class="skip-to-content-link" href="#main">Skip to content</a>
<header class="header-main">
	<div class="inner inner-wide a-center" id="nenu">
		<div class="search-form"><?php get_search_form(); ?> </div>
		<a href="/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/www/images/closer-logo-full.svg" alt="Closer - The home of longitudinal research"></a>
		<nav class="side-nav">
			<?php
			wp_nav_menu( array( 
			    'theme_location' => 'header_nav', 
			    'container_class' => 'header-side-nav' ) ); 
			?>
		</nav>
	</div>
</header>

<?php

// Output colour class

$pickedcolour = $closer_filters['colours'] ? null : pilau_get_first_term( 'colours' );
$currentpost = get_post_type();

if(!is_null($pickedcolour)){
	echo '<div class="' . $pickedcolour->slug . '">';
}else{
	if($currentpost == 'event'){
		echo '<div class="green">';
	}elseif($currentpost == 'news'){
		echo '<div class="blue">';	
	}elseif($currentpost == 'blog'){
		echo '<div class="blue">';
	}elseif($currentpost == 'evidence'){
		echo '<div class="pink">';
	}elseif($currentpost == 'resource'){
		echo '<div class="purple">';
	}elseif($currentpost == 'contextual_data'){
		echo '<div class="purple">';
	}else{
		echo '<div class="yellow">';		
	}
}
?>
<?php get_header(); ?>

<div class="nav-wrap" id="nav-main">
	<div class="inner">
	<div class="mob-init" id="nav-init"><span><span></span></span></div>
	<nav role="navigation">
		
		<?php
		$menu_args = array(
			'theme_location' => 'header-menu',
			'container'       => FALSE,
	        'container_id'    => FALSE,
	        'menu_class'      => 'menu',
	        'menu_id'         => 'main-menu',
	        'add_li_class'    => 'manu-parent',
	        
		);

		echo wp_nav_menu($menu_args);

		add_filter( 'pre_get_posts', 'closer_pre_get_posts_filtering' );
			function closer_pre_get_posts_filtering( $query ) {
				global $closer_filters;
				if ( ! is_admin() ) {

					// Which query?
					$is_news_opinion = $query->is_home() && $query->is_main_query();
					//$is_explore_evidence = get_page_template_slug() == 'page_explore-evidence.php' && $query->get( 'post_type' ) == 'evidence';

					// Taxonomy filters
					$tax_query = array();
                    $builtin = get_taxonomies( array( '_builtin' => false ) );
                    if($builtin) {
	                    foreach ( $builtin as $taxonomy ) {
		                    if ( isset($closer_filters[ $taxonomy ]) && $closer_filters[ $taxonomy ] && count( $closer_filters[ $taxonomy ] ) ) {
			                    $tax_query[] = array(
				                    'taxonomy' => $taxonomy,
				                    'terms'    => $closer_filters[ $taxonomy ],
			                    );
		                    }
	                    }
                    }
					if ( count( $tax_query ) ) {
						$query->set( 'tax_query', $tax_query );
					}

					// Meta filters
					$meta_query = array();

					if ( $is_news_opinion ) {

						// Related study?
						if ( count( $closer_filters[ 'rel-study' ] ) && function_exists( 'slt_cf_field_key' ) ) {
							$meta_query[] = array(
								'key'		=> slt_cf_field_key( 'post-study' ),
								'value'		=> $closer_filters[ 'rel-study' ],
								'compare'	=> 'IN'
							);
						}

						// Filter out news or opinion?
						if ( $closer_filters['news-opinion'] != 'both' && function_exists( 'slt_cf_field_key' ) ) {
							$meta_query[] = array(
								'key'		=> slt_cf_field_key( 'opinion' ),
								'value'		=> 1,
								'compare'	=> ( $closer_filters['news-opinion'] == 'opinion' ) ? '=' : '!='
							);
						}

					}

					if ( count( $meta_query ) ) {
						$query->set( 'meta_query', $meta_query );
					}

					// Date filters
					$date_query = array();

					if ( $is_news_opinion ) {

						if ( ! empty( $closer_filters['mf'] ) ) {
							// Timestamp for first day of month
							$ts = mktime( 0, 0, 0, substr( $closer_filters['mf'], 4, 2 ), 1, substr( $closer_filters['mf'], 0, 4 ) );
							// Timestamp for last day of previous month
							$ts2 = strtotime( '-1 day', $ts );
							$date_query['after'] = array(
								'year'		=> date( 'Y', $ts2 ),
								'month'		=> date( 'n', $ts2 ),
								'day'		=> date( 'j', $ts2 ),
							);
						}
						if ( ! empty( $closer_filters['mt'] ) ) {
							// Timestamp for first day of month
							$ts = mktime( 0, 0, 0, substr( $closer_filters['mt'], 4, 2 ), 1, substr( $closer_filters['mt'], 0, 4 ) );
							// Timestamp for first day of next month
							$ts2 = strtotime( '+1 month', $ts );
							$date_query['before'] = array(
								'year'		=> date( 'Y', $ts2 ),
								'month'		=> date( 'n', $ts2 ),
								'day'		=> date( 'j', $ts2 ),
							);
						}

					}

					if ( count( $date_query ) ) {
						$query->set( 'date_query', $date_query );
					}

					//echo '<pre>'; print_r( $query ); echo '</pre>';

				}
			}
		?>

	</nav>
</div>
</div>
</div>