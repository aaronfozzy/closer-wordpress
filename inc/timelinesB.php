<?php

/**
 * Timelines stuff
 *
 * @package	CLOSER
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
				'time-period-string'	=> $pilau_custom_fields['study-time-period'],
			);

		} else {

			// Get necessary data for all studies
			$studies = null;
			if ( function_exists( 'slt_cf_field_key' ) ) {
				$studies = $wpdb->get_results("
					SELECT	p.ID, p.post_title, pm1.meta_value AS time_period, pm2.meta_value AS overview_short, pm3.meta_value AS overview_long
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
					WHERE	p.post_type		= 'study'
					AND 	p.post_status	= 'publish'
				");
			}
			//echo '<pre>'; print_r( $studies ); echo '</pre>'; exit;

			if ( $studies ) {

				foreach ( $studies as $study ) {
					$this->studies_data[] = array(
						'post-id'				=> $study->ID,
						'post-title'			=> $study->post_title,
						'time-period-string'	=> $study->time_period,
						'overview-short'		=> $study->overview_short,
						'overview-long'			=> $study->overview_long,
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

		echo '<ul class="tl-studies">';

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
				$pb_styles[] = 'top: ' . ( $all_y_pos + 1 ) . 'px';
			}

			echo '<li class="tl-study" id="study-' . $sd['post-id'] . '">';

			if ( ! $this->single ) {
				echo '<a href="' . get_permalink( $sd['post-id'] ) . '" class="tl-main-link block-wrapper no-button-effect" title="' . __( 'Click for more details on this study' ) . '">';
				echo '<h2 class="tl-study-title"';
				if ( $this->mode == 'simple' ) {
					echo ' data-left="' . $all_x_pos . '" style="left: ' . $all_x_pos . '%; top: ' . ( $all_y_pos + 9 ) . 'px;"';
				}
				echo '>' . apply_filters( 'the_title', $sd['post-title'] ) . '</h2>';
			}

			$title = $this->single ? '[ ' . __( 'Study on hold' ) . ' ]' : '';
			echo '<div title="' . $title . '" class="tl-periods-bar" style="' . implode( '; ', $pb_styles ) . '">';

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

				// Tooltip
				$tooltip = '';
				if ( $this->single ) {
					$tooltip = $tp[0] . ' - ';
					if ( $tp[1] == intval( date( 'Y' ) ) + 1 ) {
						$tooltip .= __( 'ongoing' );
					} else {
						$tooltip .= $tp[1];
					}
				}

				echo '<div title="' . $tooltip . '" class="' . implode( ' ', $classes ) . '" style="width: ' . $this_width . '%; left: ' . $this_x_pos . '%;"></div>';
			}

			echo '</div>';

			if ( ! $this->single ) {

				// Study details
				if ( $this->mode == 'simple' ) {

					// Pop-up details
					echo '<div class="tl-study-details"><div class="tlsd-wrapper">';

					// Connector arrows
					$arrow_y_pos = $all_y_pos + ( $simple_study_height - 4 );
					echo '<div style="top: ' . $arrow_y_pos . 'px" class="arrow2"></div><div style="top: ' . $arrow_y_pos . 'px" class="arrow1"></div>';

					echo '<h2>' . apply_filters( 'the_title', $sd['post-title'] ) . '</h2>';
					echo '<p class="period-string hide-for-medium hide-for-large">' . $sd['time-period-string']  . '</p>';
					if ( function_exists( 'slt_cf_simple_formatting' ) ) {
						echo slt_cf_simple_formatting( $sd['overview-short'] );
					}
					echo '<p class="more button">' . __( 'More about this study' ) . '</p>';
					echo '</div></div>';

					// In simple mode, details are inside link
					echo '</a>';

				} else {

					// In full mode, details are outside link
					echo '</a>';

					echo '<div class="tl-study-details"><div class="tlsd-full-wrapper">';

					// Explore the sweeps
					echo '<div class="explore-sweeps">';

					$this->draw_years_axis();

					// Get the sweeps
					$sweeps = null;
					if ( function_exists( 'slt_cf_field_key' ) ) {
						$sweeps = get_posts( array(
							'posts_per_page'	=> -1,
							'post_type'			=> 'sweep',
							'post_status'		=> 'publish',
							'meta_key'			=> slt_cf_field_key( 'sweep-year' ),
							'orderby'			=> 'meta_value',
							'meta_query'		=> array(
								array(
									'key'		=> slt_cf_field_key( 'sweep-study' ),
									'value'		=> $sd['post-id'],
								)
							)
						));
					}

					if ( $sweeps ) {

						echo '<h2>' . __( 'Explore the data sweeps' ) . '</h2>';

						echo '<ul class="sweeps">';
						foreach ( $sweeps as $sweep ) {

							// Custom fields
							$sweep_custom_fields = array();
							if ( function_exists( 'slt_cf_all_field_values' ) ) {
								$sweep_custom_fields = slt_cf_all_field_values( 'post', $sweep->ID );
							}

							// Data attributes
							$sweep_data = array(
								'year'			=> $sweep_custom_fields['sweep-year'],
								'age-range'		=> $sweep_custom_fields['sweep-age-range'],
								'left'			=> ( intval( $sweep_custom_fields['sweep-year'] - $this->year_start ) / $this->year_span ) * 100
							);

							echo '<li class="sweep"';
							foreach ( $sweep_data as $sweep_datum_key => $sweep_datum_value ) {
								echo ' data-' . $sweep_datum_key . '="' . $sweep_datum_value . '"';
							}
							echo '>';

							echo '<div class="infos">';
							echo '<h3>' . get_the_title( $sweep ) . '</h3>';
							echo '<ul class="sweep-details">';
							echo '<li class="year"><strong>' . __( 'Year' ) . ': </strong>' . $sweep_custom_fields['sweep-year'] . '</li>';
							echo '<li class="age-range"><strong>' . __( 'Age range' ) . ': </strong>' . $sweep_custom_fields['sweep-age-range'] . '</li>';
							echo '</ul>';
							if ( $sweep_custom_fields['sweep-description'] ) {
								echo '<p class="description">' . $sweep_custom_fields['sweep-description'] . '</p>';
							}
							if ( $sweep_custom_fields['sweep-url'] ) {
								echo '<p class="url"><a href="' . esc_url( $sweep_custom_fields['sweep-url'] ) . '">' . __( 'Link' ) . '</a></p>';
							}
							echo '</div>'; // Close infos

							echo '</li>';
						}
						echo '</ul>';


					} else {

						echo '<p><em>' . __( 'We have yet to populate the sweeps data for this study.' ) . '</em></p>';

					}

					echo '</div>'; // Close explore sweeps

					// Study overview
					echo '<div class="overview wrapper limit-width-narrow clearfix">';

					echo '<p class="title">' . __( 'Overview' ) . '</p>';

					echo '<div class="text">';

					echo '<h2>' . apply_filters( 'the_title', $sd['post-title'] ) . '</h2>';
					echo '<p class="period-string hide-for-medium hide-for-large">' . $sd['time-period-string']  . '</p>';
					if ( function_exists( 'slt_cf_simple_formatting' ) ) {
						echo '<div class="post-content first-para-normal">';
						echo slt_cf_simple_formatting( $sd['overview-long'] );
						echo '</div>';
					}

					echo '<p class="more"><a class="button" href="' . get_permalink( $sd['post-id'] ) . '">' . __( 'More about this study' ) . '</a></p>';

					echo '</div>'; // Close text

					echo '</div>'; // Close overview

					echo '</div></div>'; // Close study details

				}

			}

			echo '</li>';

			// Track vertical position?
			if ( ! $this->single && $this->mode == 'simple' ) {
				$all_y_pos += $simple_study_height;
			}

		}

		echo '</ul>';

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
		echo '<div class="tl-years-axis">';
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

			echo '<div class="' . implode( ' ', $classes ) . '" data-label="' . $this->years_axis_intervals[ $i ] . '" style="width: ' . $width . '%;"></div>';

		}
		echo '</div>';

	}

}