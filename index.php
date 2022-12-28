<?php

if (!defined('gspkit')) {
    define('gspkit', 'gspkit');
}

if ( ! defined( 'gspkiturl' ) ) {
   define( 'gspkiturl', plugin_dir_url(__FILE__) );
}

class GSPMenuIcon
{

    protected $id = array();
    protected $li_class = array();
    protected $icon_classes = [];
    protected $url = array();
    protected $title = array();
    protected $class_data = '';
    protected $hasChild = [];

    protected $checkAcfValue = false;
    protected $newMenu = '';
    public $domain = 'ACF-For-Menu-GSP';

    function __construct()
    {
        // add_filter('wp_nav_menu_objects', array($this, 'access_menu_data'), 10, 2);
        
        // add_filter('wp_nav_menu_items', array($this, 'mobify_menu'), 10, 2);
        // add_action( 'wp_head' , array( $this, 'style' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
    }

    public function access_menu_data($items, $args)
    {
       
        foreach ( $items as $item ) {
            
            array_push( $this->id, $item->ID );
            
            foreach ($item->classes as $class) {
                
                if( empty($this->class_data) ){
                    $this->class_data = $class;
                }else{
                    $this->class_data .= ' ' . $class;
                }
                
                $gsp = str_split($class);

                if (
                    $gsp[0] == 'g' || $gsp[0] == 'G' &&
                    $gsp[1] == 's' || $gsp[1] == 'S' &&
                    $gsp[2] == 'p' || $gsp[2] == 'P'
                ) {

                    $this->checkAcfValue = true;
                    array_push($this->icon_classes, [$class, $item->ID]);
                }
            }

            // store all the the classess of a menu item in this array
            array_push( $this->li_class, $this->class_data );
        

            // identify, the menu item has sub-menu  or not.
            if ( in_array( 'menu-item-has-children' , $item->classes ) ) {
                $this->checkAcfValue = true;
                array_push($this->hasChild, [ "hasChild" , $item->ID ] );
            }else{
                array_push($this->hasChild, [ "noChild" , $item->ID ] );
            }
               
        }

        return $items;
    }


    function mobify_menu($items, $args)
    {
        

        if ( ! $this->checkAcfValue) { 
            return $items;
        }
        
        for( $i=0; $i < count($this->id); $i++ ) {

           
            if( $this->hasChild[$i][0] == 'hasChild' ){
                
                $this->newMenu .= '<li id="' . 'menu-item-' . $this->id[$i] . '" class="d-flex ' . $this->li_class[$i] . '"><i class="'.$this->icon_classes[$i].'"></i><a href="' . $this->url[$i] . '">' . $this->title[$i] . '</a><i class="has-child fa-solid fa-angle-down px-2"></i></li>';
            }else{
                $this->newMenu .= '<li id="' . 'menu-item-' . $this->id[$i] . '" class="' . $this->li_class[$i] . '"><i class="'.$this->icon_classes[$i].'"></i><a href="' . $this->url[$i] . '">' . $this->title[$i] . '</a></li>';
            }
            
        }

        // if get one result from acf then will be returned new menu otherwise default will return
        return $this->newMenu;
    }

    function style()
    {
        ?>
        <style>

            .down-arrow
            {
                font-size: 100px; font-weight: bold;
                position: relative;
            }

            .menu-item-has-children a
            {
                width: 100%;
            }
            .has-child{
            color: black;
            }
            .sub-menu
            {
                display: none;
            }
        </style>
        <?php

    }

    function scripts()
    {
        wp_enqueue_script( 'gspkitfun', gspkiturl . '/fun.js', array(), microtime(), true );
        
       
    }
}
