<?php

if (!defined('gspkit')) {
    define('gspkit', 'gspkit');
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
        add_filter('wp_nav_menu_objects', array($this, 'access_menu_data'), 10, 2);
        add_filter('wp_nav_menu_items', array($this, 'mobify_menu'), 10, 2);
        add_action( 'wp_head' , array( $this, 'style' ) );
    }

    public function access_menu_data($items, $args)
    {
       
        foreach ( $items as $item ) {

            array_push( $this->id, $item->ID );
            array_push( $this->title, $item->title );
            array_push( $this->url, $item->url );
            
          
            $this->class_data = null;
            // access the menu classes and store all class in the "li_class" array
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
           
                $this->newMenu .= '<li id="' . 'menu-item-' . $this->id[$i] . '" class="d-flex ' . $this->li_class[$i] . '"><i class="'.$this->icon_classes[$i].'"></i><a href="' . $this->url[$i] . '">' . $this->title[$i] . '</a><i class="fa-solid fa-angle-down"></i></li>';
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
                .menu-item
                {
                    z-index: 100;
                }
                .menu-item i
                {
                    z-index: 150;
                }
                .menu-item a
            {
                width:100%;
            }
            </style>
        <?php
    }
}
