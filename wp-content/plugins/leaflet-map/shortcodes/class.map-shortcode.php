<?php
/**
 * Map Shortcode
 *
 * Displays map with [leaflet-map ...atts] 
 *
 * JavaScript equivalent : L.map("id");
 * 
 * PHP Version 5.5
 * 
 * @category Shortcode
 * @author   Benjamin J DeLong <ben@bozdoz.com>
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
} 

require_once LEAFLET_MAP__PLUGIN_DIR . 'shortcodes/class.shortcode.php';

/**
 * Leaflet_Map_Shortcode Class
 */
class Leaflet_Map_Shortcode extends Leaflet_Shortcode
{
    /**
     * Unique ID for a map
     * 
     * @var int $map_id
     */
    protected $map_id = 0;

    /**
     * Instantiate class
     */
    public function __construct()
    {
        parent::__construct();
        $this->enqueue();
        $this->incrementMap();
    }

    /**
     * Increment the map count
     */
    protected function incrementMap()
    {
        $this->LM->map_count++;
        $this->map_id = $this->LM->map_count;
    }

    /**
     * Merge shortcode options with default options
     *
     * @param array|string $atts    key value pairs from shortcode 
     * 
     * @return array new atts, which is actually an array
     */
    protected function getAtts($atts='')
    {
        $atts = (array) $atts;
        extract($atts);

        $settings = Leaflet_Map_Plugin_Settings::init();

        $atts['zoom'] = array_key_exists('zoom', $atts) ? 
            $zoom : $settings->get('default_zoom');
        $atts['height'] = empty($height) ? 
            $settings->get('default_height') : $height;
        $atts['width'] = empty($width) ? $settings->get('default_width') : $width;
        $atts['zoomcontrol'] = array_key_exists('zoomcontrol', $atts) ?
            $zoomcontrol : $settings->get('show_zoom_controls');
        $atts['min_zoom'] = array_key_exists('min_zoom', $atts) ? 
            $min_zoom : $settings->get('default_min_zoom');
        $atts['max_zoom'] = empty($max_zoom) ? 
            $settings->get('default_max_zoom') : $max_zoom;
        $atts['scrollwheel'] = array_key_exists('scrollwheel', $atts) ? 
            $scrollwheel : $settings->get('scroll_wheel_zoom');
        $atts['doubleclickzoom'] = array_key_exists('doubleclickzoom', $atts) ? 
            $doubleclickzoom : $settings->get('double_click_zoom');
        $atts['fit_markers'] = array_key_exists('fit_markers', $atts) ? 
            $fit_markers : $settings->get('fit_markers');

        /* allow percent, but add px for ints */
        $atts['height'] .= is_numeric($atts['height']) ? 'px' : '';
        $atts['width'] .= is_numeric($atts['width']) ? 'px' : '';   

        // maxbounds as string: maxbounds="50, -114; 52, -112"
        $maxBounds = isset($maxbounds) ? $maxbounds : null;

        if ($maxBounds) {
            try {
                // explode by semi-colons and commas
                $maxBounds = preg_split("[;|,]", $maxBounds);
                print_r($maxBounds);
                $maxBounds = array(
                    array(
                        $maxBounds[0], $maxBounds[1]
                    ),
                    array(
                        $maxBounds[2], $maxBounds[3]
                    )
                );
                print_r($maxBounds);
            } catch (Exception $e) {
                $maxBounds = null;
            }
        }

        /* 
        need to allow 0 or empty for removal of attribution 
        */
        if (!array_key_exists('attribution', $atts)) {
            $atts['attribution'] = $settings->get('default_attribution');
        }

        /* allow a bunch of other options */
        // http://leafletjs.com/reference-1.0.3.html#map-closepopuponclick
        $more_options = array(
            'closePopupOnClick' => isset($closepopuponclick) ? 
                $closepopuponclick : null,
            'trackResize' => isset($trackresize) ? $trackresize : null,
            'boxZoom' => isset($boxzoom) ? $boxzoom : null,
            'dragging' => isset($dragging) ? $dragging : null,
            'keyboard' => isset($keyboard) ? $keyboard : null,
        );

        // filter out nulls
        $more_options = $this->LM->filter_null($more_options);
        
        // change string booleans to booleans
        $more_options = filter_var_array($more_options, FILTER_VALIDATE_BOOLEAN);

        if ($maxBounds) {
            $more_options['maxBounds'] = $maxBounds;
        }

        // wrap as JSON
        if ($more_options) {
            $more_options = json_encode($more_options);
        } else {
            $more_options = '{}';
        }

        $atts['more_options'] = $more_options;

        return $atts;
    }

    /**
     * Enqueue Scripts and Styles for Leaflet 
     * 
     * @return null
     */
    protected function enqueue()
    {
        wp_enqueue_style('leaflet_stylesheet');
        wp_enqueue_script('leaflet_js');
        wp_enqueue_script('leaflet_map_init');

        if (wp_script_is('leaflet_mapquest_plugin', 'registered')) {
            // mapquest doesn't accept direct tile access as of July 11, 2016
            wp_enqueue_script('leaflet_mapquest_plugin');
        }

        // enqueue user-defined scripts
        do_action('leaflet_map_enqueue');
    }

    /**
     * Get script for shortcode
     * 
     * @param array  $atts    sometimes this is null
     * @param string $content anything within a shortcode
     * 
     * @return string HTML
     */
    protected function getHTML($atts='', $content=null)
    {
        extract($this->getAtts($atts));

        if (!empty($address)) {
            /* try geocoding */
            include_once LEAFLET_MAP__PLUGIN_DIR . 'class.geocoder.php';
            $location = new Leaflet_Geocoder($address);
            $lat = $location->lat;
            $lng = $location->lng;
        }

        $settings = Leaflet_Map_Plugin_Settings::init();

        /*
        map uses lat/lng
        */
        $lat = empty($lat) ? $settings->get('default_lat') : $lat;
        $lng = empty($lng) ? $settings->get('default_lng') : $lng;

        /*
        mapquest doesn't need tile urls
        */
        if (wp_script_is('leaflet_mapquest_plugin', 'registered')) {
            $tileurl = '';
            $subdomains = '';
        } else {
            $tileurl = empty($tileurl) ? $settings->get('map_tile_url') : $tileurl;
            $subdomains = empty($subdomains) ? 
                $settings->get('map_tile_url_subdomains') : $subdomains;
        }

        /* should be iterated for multiple maps */
        ob_start(); ?>
        <div 
            id="leaflet-map-<?php echo $this->map_id; ?>" 
            class="leaflet-map" 
            style="height:<?php 
                echo $height; 
            ?>; width:<?php 
                echo $width; 
            ?>;"></div>
        <script>
		/* la siguiente función sirve para volver al zoom original*/
!function(){"use strict";L.Control.ZoomHome=L.Control.Zoom.extend({options:{position:"topleft",zoomInText:"+",zoomInTitle:"Zoom in",zoomOutText:"-",zoomOutTitle:"Zoom out",zoomHomeIcon:"home",zoomHomeTitle:"Inicio",homeCoordinates:null,homeZoom:null},onAdd:function(a){var b="leaflet-control-zoomhome",c=L.DomUtil.create("div",b+" leaflet-bar"),d=this.options;null===d.homeCoordinates&&(d.homeCoordinates=a.getCenter()),null===d.homeZoom&&(d.homeZoom=a.getZoom()),this._zoomInButton=this._createButton(d.zoomInText,d.zoomInTitle,b+"-in",c,this._zoomIn.bind(this));var e='<i class="fa fa-'+d.zoomHomeIcon+'" style="line-height:1.65;"></i>';return this._zoomHomeButton=this._createButton(e,d.zoomHomeTitle,b+"-home",c,this._zoomHome.bind(this)),this._zoomOutButton=this._createButton(d.zoomOutText,d.zoomOutTitle,b+"-out",c,this._zoomOut.bind(this)),this._updateDisabled(),a.on("zoomend zoomlevelschange",this._updateDisabled,this),c},setHomeBounds:function(a){void 0===a?a=this._map.getBounds():"function"!=typeof a.getCenter&&(a=L.latLngBounds(a)),this.options.homeZoom=this._map.getBoundsZoom(a),this.options.homeCoordinates=a.getCenter()},setHomeCoordinates:function(a){void 0===a&&(a=this._map.getCenter()),this.options.homeCoordinates=a},setHomeZoom:function(a){void 0===a&&(a=this._map.getZoom()),this.options.homeZoom=a},getHomeZoom:function(){return this.options.homeZoom},getHomeCoordinates:function(){return this.options.homeCoordinates},_zoomHome:function(a){this._map.setView(this.options.homeCoordinates,this.options.homeZoom)}}),L.Control.zoomHome=function(a){return new L.Control.ZoomHome(a)}}();
//aquí termina la función
		
        window.WPLeafletMapPlugin = window.WPLeafletMapPlugin || [];
        window.WPLeafletMapPlugin.push(function () {
            var baseUrl = '<?php echo $tileurl; ?>',
                base = (!baseUrl && window.MQ) ? 
                    MQ.mapLayer() : L.tileLayer(baseUrl, { 
                        subdomains: '<?php echo $subdomains; ?>'
                    }),
                options = L.Util.extend({}, {
                    maxZoom: <?php echo $max_zoom; ?>,
                    minZoom: <?php echo $min_zoom; ?>,
                    layers: [base],
                    zoomControl: <?php echo $zoomcontrol; ?>,
                    scrollWheelZoom: <?php echo $scrollwheel; ?>,
                    doubleClickZoom: <?php echo $doubleclickzoom; ?>,
                    attributionControl: false
                }, <?php echo $more_options; ?>),
                map = L.map('leaflet-map-<?php echo $this->map_id; ?>', options)
                    .setView([<?php 
                        echo $lat . ',' . $lng . '],' . $zoom; 
                    ?>);
					L.control.scale().addTo(map);  //AÑADE CONTROL DE ESCALA AL MAPA
					map.removeControl(map.zoomControl); // Quita el control de zoom por defecto
					var zoomHome = L.Control.zoomHome().addTo(map); //añade la función de volver al zoom original
            if (<?php echo $fit_markers; ?>) {
                map.fit_markers = true;
            }
            <?php
            if ($attribution) :
                /* add any attributions, semi-colon-separated */
                $attributions = explode(';', $attribution);
                ?>
                var attControl = L.control.attribution({prefix:false}).addTo(map);
                <?php
                foreach ($attributions as $a):
                ?>
                    attControl.addAttribution('<?php echo trim($a); ?>');
                <?php
                endforeach;
            endif;
            ?>
            window.WPLeafletMapPlugin.maps.push(map);
        }); // end add
        </script><?php

        return ob_get_clean();
    }
}