<?php
/**
 * Schema.org openingHours
 *
 * @author  Justin Frydman
 * @version 2.4.0
 * @since   2.4.0
 * @see     wp-structuring-display.php
 * @link 	https://schema.org/openingHours
 * @link 	https://schema.org/LocalBusiness
 */
class Structuring_Markup_Opening_Hours {

	/**
	 * Multidimensional array of days and opening hours
	 *
	 * @since 2.4.0
	 * @var   array
	 */
	protected $opening_hours;

	/**
	 * List of days
	 *
	 * @since 2.4.0
	 * @var   array
	 */
	protected $days;

	/**
	 * Grouped, unique hour periods
	 *
	 * @since 2.4.0
	 * @var   array
	 */
	protected $periods;

	/**
	 * Days grouped with open-close hours
	 *
	 * @since 2.4.0
	 * @var   array
	 */
	protected $grouped_days;

	/**
	 * Open/close hours grouped by Mo-Su
	 *
	 * @since 2.4.0
	 * @var   array
	 */
	protected $weekly_hours;

	/**
	 * Constructor
	 *
	 * @since 2.4.0
	 * @param array $opening_hours
	 */
	public function __construct ( array $opening_hours ) {
		/** Default Value Set */
		if ( !empty( $opening_hours ) ) {
			$this->opening_hours = $opening_hours;
			$this->init();
		}
	}

	/**
	 * Initialize the class
	 *
	 * @since 2.4.0
	 */
	public function init () {
		$this->days         = array_keys( $this->opening_hours );
		$this->periods      = $this->group_periods();
		$this->grouped_days = $this->group_periods_with_days();
		$this->weekly_hours = $this->group_weekly_hours();
	}

	/**
	 * Groups unique open and closed hour periods
	 *
	 * @since 2.4.0
	 * @return array
	 */
	public function group_periods () {
		$periods = array();
		foreach( $this->opening_hours as $day ) {
			foreach( $day as $group ) {
				if( !in_array( $group, $periods ) ) {
					if( !empty( $group['open'] ) && !empty( $group['close'] ) ) {
						$periods[] = $group;
					}
				}
			}
		}

		return (array) $periods;
	}

	/**
	 * Groups day ranges with their opening hours
	 *
	 * @since 2.4.0
	 * @return array
	 */
	public function group_periods_with_days () {
		$periods = $this->periods;

		foreach( $periods as $key => $group ) {
			$days = array();

			foreach( $this->opening_hours as $day => $grouped_days ) {
				if( in_array( $group, $grouped_days) ) {
					$days[] = $day;
				}
			}

			$periods[$key]['days'] = $days;
		}

		return (array) $periods;
	}

	/**
	 * Group weekly group ranges with their opening hours
	 *
	 * @since 2.4.0
	 * @return array
	 */
	public function group_weekly_hours () {
		$grouped_days = $this->grouped_days;
		$days         = $this->days;
		$object       = $this;

		return (array) array_reduce( $grouped_days, function($result, $group) use ( $days, $object ) {
			return array_merge( $result, $object->group_periods_to_day_range( $group ) );
		}, array() );
	}

	/**
	 * Groups days of the week with their opening hours
	 *
	 * @since  2.4.0
	 * @param  array $group
	 * @return array
	 */
	public function group_periods_to_day_range ( array $group ) {
		$starting_day = null;
		$ending_day   = null;

		$consecutive_days = array();

		foreach( $this->days as $i => $day ) {
			$has_day = in_array( $day, $group['days'] );

			if( $has_day ) {
				$starting_day = $starting_day ? $starting_day : $day;
				$ending_day	  = $day;
			}

			if( $starting_day && (!$has_day || $i == count($this->days) - 1) ) {
				$consecutive_days[] = array(
					'start'	=> $starting_day,
					'end'	=> $ending_day,
					'open'	=> $group['open'],
					'close'	=> $group['close']
				);

				$starting_day = null;
			}
		}

		return (array) $this->sort_by_day_of_the_week( $consecutive_days );
	}

	/**
	 * Sorts our days in the proper weekly hour
	 *
	 * @since  2.4.0
	 * @param  array $consecutive_days
	 * @return array
	 */
	public function sort_by_day_of_the_week ( array $consecutive_days ) {
		$days = $this->days;

		arsort($consecutive_days);

		$sort_by_day_func = function( $a, $b ) use ( $days ) {
			$aKey = array_search( $a['start'], $days );
			$bKey = array_search( $b['start'], $days );
			return $aKey === $bKey ? 1 : $aKey < $bKey ? -1 : 1;
		};

		usort($consecutive_days, $sort_by_day_func);

		return (array) $consecutive_days;
	}

	/**
	 * Displays formatted opening hours
	 *
	 * @since  2.4.0
	 * @return array
	 */
	public function display () {
		$opening_hours = array();

		foreach( $this->weekly_hours as $key => $group ) {
			if( $group['start'] != $group['end'] ) {
				$hours = $group['start'].'-'.$group['end'];
			} else {
				$hours = $group['start'];
			}

			$hours .= ' ' . $group['open'] . '-' . $group['close'];

			$opening_hours[] = $hours;
		}

		return (array) $opening_hours;
	}

}