<?php

/**
 * Timelines stuff
 *
 * @package	CLOSER-2022
 * @since	0.1
 */


/**
 * Timeline class
 *
 * @since	CLOSER 0.1
 */
class Closer_Timeline {

	/**
	 * Offset for start of timeline before start of first study
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_start_offset = 10;

	/**
	 * Interval for years axis
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $years_axis_interval = 10;

	/**
	 * Year at which timeline starts
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_start = null;

	/**
	 * Year at which timeline end
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_end = null;

	/**
	 * Timespan of timeline
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_span = null;

	/**
	 * Earliest year in studies
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_earliest = null;

	/**
	 * Latest year in studies
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $year_latest = null;

	/**
	 * Years axis intervals
	 *
	 * @since	CLOSER 0.1
	 * @var     array
	 */
	protected $years_axis_intervals = array();

	/**
	 * Holds data on studies
	 *
	 * @since	CLOSER 0.1
	 * @var     array
	 */
	protected $studies_data = array();

	/**
	 * Mode
	 *
	 * @since	CLOSER 0.1
	 * @var     string
	 */
	protected $mode = null;

	/**
	 * Single?
	 *
	 * @since	CLOSER 0.1
	 * @var     bool
	 */
	protected $single = null;

	/**
	 * Pixel height for bars when multiple
	 *
	 * @since	CLOSER 0.1
	 * @var     int
	 */
	protected $bar_height = 32;

	/**
	 * Initialize
	 *
	 * @since	CLOSER 0.1
	 * @param	string		$mode		'simple' | 'full'
	 * @param	int			$study_id	Supply ID for single-study timeline; if not supplied, draws all studies
	 */
	function __construct( $mode = 'simple', $study_id = null ) {
		global $pilau_custom_fields, $wpdb;
		$current_year = intval( date( 'Y' ) );

		// Pass mode etc.
		$this->mode = $mode;
		$this->single = (bool) $study_id;

		if ( $this->single ) {

			// Single study
			$this->studies_data[] = array(
				'post_id'				=> $study_id,
			);

		} else {

			// Get necessary data for all studies
			$studies = null;
			if ( function_exists( 'slt_cf_field_key' ) ) {
				$studies = $wpdb->get_results("
					SELECT	p.ID, p.post_title, pm1.meta_value AS time_period, pm2.meta_value AS overview_short, pm3.meta_value AS overview_long, pm4.meta_value AS total_years
					FROM	$wpdb->posts p
								LEFT JOIN $wpdb->postmeta pm1 ON
								(	p.ID			= pm1.post_id	AND
									pm1.meta_key	= '" . slt_cf_field_key( 'study-time-period' ) . "' )
								LEFT JOIN $wpdb->postmeta pm2 ON
								(	p.ID			= pm2.post_id	AND
									pm2.meta_key	= '" . slt_cf_field_key( 'study-overview-short' ) . "' )
								LEFT JOIN $wpdb->postmeta pm3 ON
								(	p.ID			= pm3.post_id	AND
									pm3.meta_key	= '" . slt_cf_field_key( 'study-overview-long' ) . "' )
								LEFT JOIN $wpdb->postmeta pm4 ON
								(	p.ID			= pm4.post_id	AND
									pm4.meta_key	= '" . slt_cf_field_key( 'study-total-years' ) . "' )
					WHERE	p.post_type		= 'study'
					AND 	p.post_status	= 'publish'
				");
			}
			//echo '<pre>'; print_r( $studies ); echo '</pre>'; exit;

			if ( $studies ) {
				foreach ( $studies as $study ) {
					// var_dump($study);
					$this->studies_data[] = array(
						'post-id'				=> $study->ID,
						'post-title'			=> $study->post_title,
						'time-period-string'	=> $study->time_period,
						'overview-short'		=> $study->overview_short,
						'overview-long'			=> $study->overview_long,
						'total-years'			=> $study->total_years,
					);
				}

				// Make sure studies data are sorted by time period
				usort(
					$this->studies_data,
					function( $a, $b ) {
						return strcmp( $a['time-period-string'], $b['time-period-string'] );
					}
				);

			}

		}

		// Go through all studies and gather / parse data
		foreach ( $this->studies_data as &$sd ) {
			// Parse time period
			if ( $sd['time-period-string'] ) {
				$sd['time-period-parsed'] = $this->parse_time_periods( $sd['time-period-string'] );
			}

			// Earliest and latest years
			$sd['year-earliest'] = $sd['time-period-parsed'][0][0];
			$sd['year-latest'] = $sd['time-period-parsed'][ count( $sd['time-period-parsed'] ) - 1 ][1];
			if ( ! $this->year_earliest || $this->year_earliest > $sd['year-earliest'] ) {
				$this->year_earliest = $sd['time-period-parsed'][0][0];
			}
			if ( ! $this->year_latest || $this->year_latest < $sd['year-latest'] ) {
				$this->year_latest = $sd['year-latest'];
			}

		}

		// For start of timeline, take offset off and round down to nearest start of decade
		$this->year_start = floor( ( intval( $this->year_earliest ) - $this->year_start_offset ) / 10 ) * 10;

		// End at current year unless the time period ends in the future
		$this->year_end = ( $this->year_latest > $current_year ) ? $this->year_latest : $current_year;

		// Span
		$this->year_span = $this->year_end - $this->year_start;

		// Need to change interval?
		if ( $this->year_span > 80 ) {
			$this->years_axis_interval = 20;
		} else if ( $this->year_span < 30 ) {
			$this->years_axis_interval = 5;
		}

		// Years axis
		$year_label = $this->year_start;
		while ( $year_label < $this->year_end ) {
			$this->years_axis_intervals[] = $year_label;
			$year_label += $this->years_axis_interval;
		}

	}

	/**
	 * Parse manually-entered time periods
	 *
	 * @since	CLOSER 0.1
	 * @param	string		$tps
	 * @return	array
	 */
	function parse_time_periods( $tps ) {

		// Remove all irrelevant characters
		$tps = preg_replace( '/[^0-9\-,]/', '', $tps );

		// Split by commas
		$time_periods = explode( ',', $tps );

		// Split each range by dash
		foreach ( $time_periods as &$time_period ) {
			$tp = explode( '-', $time_period );

			// Do we need to add in next year for "ongoing"?
			if ( count( $tp ) == 1 || empty( $tp[1] ) ) {
				$tp[1] = intval( date( 'Y' ) ) + 1;
				$tp[2] = 'ongoing';
			}

			$time_period = $tp;
		}

		return $time_periods;
	}


	/**
	 * Draw the timeline
	 *
	 * @since	CLOSER 0.1
	 * @return	void
	 */
	public function draw_timeline() {

		$classes = array( 'timeline', 'tl-' . $this->mode );
		if ( $this->single ) {
			$classes[] = 'tl-single';
		} else {
			$classes[] = 'tl-multiple';
		}
		if ( count( $this->years_axis_intervals ) > 10 ) {
			$classes[] = 'tl-intervals-many';
		} else if ( count( $this->years_axis_intervals ) > 6 ) {
			$classes[] = 'tl-intervals-medium';
		} else {
			$classes[] = 'tl-intervals-few';
		}

		echo '<div class="' . implode( ' ', $classes ) . '">';

		$this->draw_studies_bars();

		$this->draw_years_axis();

		echo '</div>';

	}


	/**
	 * Draw studies bars
	 *
	 * @since	CLOSER 0.1
	 * @return	void
	 */
	protected function draw_studies_bars() {
		global $pilau_custom_fields;
		echo '<div class="tl-studies">';

		$all_y_pos = 0; // For tracking vertical position if necessary
		$simple_study_height = 32;
		foreach ( $this->studies_data as $sd ) {

			// Width and position of all periods
			$pb_styles = array();
			$all_period_duration = $sd['year-latest'] - $sd['year-earliest'];
			$pb_styles[] = 'width: ' . ( ( $all_period_duration / $this->year_span ) * 100 ) . '%';
			$all_x_pos = ( ( $sd['year-earliest'] - $this->year_start ) / $this->year_span ) * 100;
			$pb_styles[] = 'left: ' . $all_x_pos . '%';

			// For simple multiple (with pop-up details), need to position stuff vertically
			if ( ! $this->single && $this->mode == 'simple' ) {
				$pb_styles[] = '';
			}
			
			// var_dump($sd);
			// var_dump($pilau_custom_fields);


			echo '<div class="study-timeline" id="study-' . $sd['post-id'] . '">';
			echo '<div class="image-wrap">';
			echo get_the_post_thumbnail($sd['post-id']);
			echo '</div>';
			echo '<div class="timeline-content">';
			echo '<div class="top">';
			echo '<h2><a href="' . get_permalink( $sd['post-id'] ) . '">' . apply_filters( 'the_title', $sd['post-title'] ) . '</a></h2>';
			echo '<div class="no-float-label black">';
			echo $sd['total-years'];
			echo ' years</div>';
			echo '</div>';
			echo '<div class="accordion-header">';
			echo '<div class="date-range-wrapper">';
			echo '<p class="date date-1">1931</p>';
			echo '<div class="date-range">';
			echo '<div title="' . $title . '" class="tl-periods-bar date-range-inner" style="' . implode( '; ', $pb_styles ) . '">';

			// Draw individual periods
			foreach ( $sd['time-period-parsed'] as $tp ) {

				$classes = array( 'tl-period' );
				if ( count( $tp ) > 2 && $tp[2] == 'ongoing' ) {
					$classes[] = 'ongoing';
				}

				// Width and position of this period
				$this_period_duration = $tp[1] - $tp[0];
				$this_width = ( $this_period_duration / $all_period_duration ) * 100;
				$this_x_pos = ( ( $tp[0] - $sd['year-earliest'] ) / $all_period_duration ) * 100;
				echo '<div title="' . $tooltip . '" class="' . implode( ' ', $classes ) . ' dr dr-'. count($tp) .'" style="width: ' . $this_width . '%; left: ' . $this_x_pos . '%;"></div>';
			}

			echo '</div>';
			echo '</div>';
			echo '<p class="date date-2">2022</p>';
			echo '</div>';
			echo '<button class="accordion-init" id="acc-init-' . $sd['post-id'] . '" aria-controls="acc-sect-' . $sd['post-id'] . '" aria-expanded="false">Expand study details</button>';
			echo '</div>';
			echo '<div class="accordion-content" id="acc-sect-' . $sd['post-id'] . '" aria-labelledby="acc-init-' . $sd['post-id'] . '" aria-hidden="true">';
			echo '<div class="flex col-2-1">';
			echo '<div class="accordion-left">';
			if ( function_exists( 'slt_cf_simple_formatting' ) ) {
				echo slt_cf_simple_formatting( $sd['overview-short'] );
			}
			echo '</div>';
			echo '<div class="accordion-right">';
			echo '<a href="' . get_permalink( $sd['post-id'] ) . '" class="button">Find out more</a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
			echo '</div>';


			// Track vertical position?
			if ( ! $this->single && $this->mode == 'simple' ) {
				$all_y_pos += $simple_study_height;
			}

		}

		echo '</div>';

	}


	/**
	 * Output the X-axis of years for a timeline
	 *
	 * This is purely visual - the rest of the timeline should include accessible information
	 *
	 * @since	CLOSER 0.1
	 * @param	mixed		$tps
	 * @return	array
	 */
	protected function draw_years_axis() {

		// Output
		$standard_width = ( 100 / $this->year_span ) * $this->years_axis_interval;
		$previous_interval_year = null;
		for ( $i = 0; $i < count( $this->years_axis_intervals ); $i++ ) {
			$width = $standard_width;
			$classes = array( 'tl-interval' );

			// Does the last interval need to be narrower?
			if ( $i == count( $this->years_axis_intervals ) - 1 ) {
				$classes[] = 'last';
				if ( $this->years_axis_intervals[ $i ] < $this->year_end ) {
					$width = ( 100 / $this->year_span ) * ( $this->year_end - $this->years_axis_intervals[ $i ] );
				}
			}

		}
	}

}