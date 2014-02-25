<?php

class FacetWP_Facet_Proximity
{

    function __construct() {
        $this->label = __( 'Proximity', 'fwp' );
    }


    /**
     * Generate the facet HTML
     */
    function render( $params ) {

        $output = '';
        $value = $params['selected_values'];
        $value = is_array( $value ) ? $value[0] : $value;
        $output .= '<input type="text" class="facetwp-zip" value="' . esc_attr( $value ) . '" placeholder="' . __( 'Enter zip code', 'fwp' ) . '" />';
        return $output;
    }


    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $selected_values = $params['selected_values'];
        $selected_values = is_array( $selected_values ) ? $selected_values[0] : $selected_values;
        $selected_values = like_escape( $selected_values );

        if ( empty( $selected_values ) ) {
            return 'continue';
        }

        $sql = "
        SELECT DISTINCT post_id FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}' AND facet_display_value LIKE '%$selected_values%'";
        return $wpdb->get_col( $sql );
    }


    /**
     * Output any admin scripts
     */
    function admin_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/load/proximity', function($this, obj) {
    });

    wp.hooks.addFilter('facetwp/save/proximity', function($this, obj) {
        return obj;
    });

    wp.hooks.addAction('facetwp/change/proximity', function($this) {
        //$this.closest('.facetwp-facet').find('.name-source').hide();
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
    }
}
