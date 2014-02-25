<?php
/*
Plugin Name: FacetWP - Proximity
Plugin URI: https://facetwp.com/
Description: A FacetWP facet to filter posts by proximity
Version: 1.0.0
Author: Matt Gibbs
Author URI: https://facetwp.com/
GitHub Plugin URI: https://github.com/mgibbs189/facetwp-proximity
GitHub Branch: 1.0.0

Copyright 2014 Matt Gibbs

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

// exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


class FWP_Proximity
{
    public $lang;


    function __construct() {
        add_action( 'init' , array( $this, 'init' ) );
    }


    /**
     * Intialize
     */
    function init() {
        add_filter( 'facetwp_facet_types', array( $this, 'register_facet_type' ) );
    }


    function register_facet_type( $facet_types ) {

        include( dirname( __FILE__ ) . '/includes/proximity.php' );
        $facet_types['proximity'] = new FacetWP_Facet_Proximity();
        return $facet_types;
    }
}


$fwp_proximity = new FWP_Proximity();
