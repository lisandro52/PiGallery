<?php
/**
 * Created by IntelliJ IDEA.
 * User: VirtualPc-Win7x64
 * Date: 2014.04.24.
 * Time: 20:41
 */

namespace piGallery\db\entities;


class Content {

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $path;




    function getJsonData(){
        $var = get_object_vars($this);
        foreach($var as &$value){
            if(is_object($value) && method_exists($value,'getJsonData')){
                $value = $value->getJsonData();
            }
            if(is_array($value)){
                $JSON_array = array();
                foreach ($value as $row) {
                    if(is_object($row) && method_exists($row,'getJsonData')){
                        $row = $row->getJsonData();
                    }
                    $JSON_array[] =  $row;
                }
                $value = $JSON_array;
            }
        }
        return $var;
    }
} 