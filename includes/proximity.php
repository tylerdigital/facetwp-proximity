<?php

class FacetWP_Facet_Proximity
{

    public $ordered_posts;


    function __construct() {
        $this->label = __( 'Proximity', 'fwp' );
    }


    /**
     * Generate the facet HTML
     */
    function render( $params ) {

        $output = '';
        $value = $params['selected_values'];
        $zipcode = empty( $value[0] ) ? '' : $value[0];
        $chosen_radius = empty( $value[1] ) ? '' : $value[1];
        $output .= '<input type="text" class="facetwp-zip" value="' . esc_attr( $zipcode ) . '" placeholder="' . __( 'Enter zip code', 'fwp' ) . '" />';
        $output .= '<select class="facetwp-radius">';
        foreach ( array( 5, 10, 25, 50, 100 ) as $radius ) {
            $selected = ( $chosen_radius == $radius ) ? ' selected' : '';
            $output .= "<option value=\"$radius\"$selected>$radius miles</option>";
        }
        $output .= '</select>';
        $output .= '<input type="button" class="facetwp-update" value="Apply" />';
        return $output;
    }


    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $selected_values = $params['selected_values'];

        if ( empty( $selected_values ) || empty( $selected_values[0] ) ) {
            return 'continue';
        }

        $zip = $selected_values[0];
        $radius = $selected_values[1];

        // Lookup the coordinates from an external service
        $response = wp_remote_get( 'http://api.zippopotam.us/us/' . $zip );

        try {
            $response = json_decode( $response['body'], true );
        }
        catch ( Exception $ex ) {
            return array();
        }

        $lat = $response['places'][0]['latitude'];
        $lng = $response['places'][0]['longitude'];

        // Lat = facet_value
        // Lng = facet_display_value
        // TODO: optimize
        $sql = "
        SELECT DISTINCT post_id,
        ( 3959 * acos( cos( radians( $lat ) ) * cos( radians( facet_value ) ) * cos( radians( facet_display_value ) - radians( $lng ) ) + sin( radians( $lat ) ) * sin( radians( facet_value ) ) ) ) AS distance
        FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}'
        HAVING distance < $radius
        ORDER BY distance";

        $this->ordered_posts = $wpdb->get_col( $sql );
        return $this->ordered_posts;
    }


    /**
     * Output any admin scripts
     */
    function admin_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/load/proximity', function($this, obj) {
        $this.find('.facet-source').val(obj.source);
    });

    wp.hooks.addFilter('facetwp/save/proximity', function($this, obj) {
        obj['source'] = $this.find('.facet-source').val();
        return obj;
    });

    wp.hooks.addAction('facetwp/change/proximity', function($this) {
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/refresh/proximity', function($this, facet_name) {
        var zip = $this.find('.facetwp-zip').val();
        var radius = $this.find('.facetwp-radius').val();
        FWP.facets[facet_name] = ('' != zip) ? [zip, radius] : [];
    });

    wp.hooks.addAction('facetwp/ready', function() {
        $(function() {
            $(document).on('click', '.facetwp-update', function() {
                FWP.refresh();
            });
        });
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
    }
}
