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
    }

    public function access_menu_data($items, $args)
    {
        $hasChild = '';
        foreach ($items as $i => $item) {

            $this->id[$i] = $item->ID;
            $this->title[$i] = $item->title;
            $this->url[$i] = $item->url;

            $this->class_data = null;
            $hasChild = '';
            // access the menu classes and store all class in the "li_class" array
            foreach ($item->classes as $class) {
                $this->class_data .= " " . $class;
                $gsp = str_split($class);

                if (
                    $gsp[0] == 'g' || $gsp[0] == 'G' &&
                    $gsp[1] == 's' || $gsp[1] == 'S' &&
                    $gsp[2] == 'p' || $gsp[2] == 'P'
                ) {

                    $this->checkAcfValue = true;
                    array_push($this->icon_classes, [$this->id[$i], $class]);
                }

                if ($class == 'menu-item-has-children') {
                    $hasChild = 'hasChild';
                }
            }

            $this->li_class[$i] = $this->class_data;
            array_push($this->hasChild, $hasChild );
            
        }

        print_r($this->hasChild);
        return $items;
    }


    function mobify_menu($items, $args)
    {

        if (!$this->checkAcfValue) {
            return $items;
        }

        foreach ($this->id as $i => $id) {

            if( $this->hasChild[$i] == 'hasChild' ){
                $this->newMenu .= '<li id="' . 'menu-item-' . $id . '" class="' . $this->li_class[$i] . '"><i class="bi bi-android2"></i><a href="' . $this->url[$i] . '">' . $this->title[$i] . '</a></li>';
            }else{
                $this->newMenu .= '<li id="' . 'menu-item-' . $id . '" class="' . $this->li_class[$i] . '"><i class="bi bi-android2"></i><a href="' . $this->url[$i] . '">' . $this->title[$i] . '</a></li>';
            }
            
        }

        // if get one result from acf then will be returned new menu otherwise default will return
        return $this->newMenu;
    }
}
