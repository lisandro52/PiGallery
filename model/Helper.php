<?php

namespace piGallery\model;

use piGallery\Properties;

require_once __DIR__."/../db/entities/Photo.php";
require_once __DIR__."/../config.php";

class Helper {

    public static function require_REQUEST($str){
        if(isset($_REQUEST[$str]) && !empty($_REQUEST[$str])){
            return $_REQUEST[$str];
        }
        die("Error: " .$str . " is required");
    }
    public static function get_REQUEST($str, $default){
        if(isset($_REQUEST[$str]) && !empty($_REQUEST[$str])){
            return $_REQUEST[$str];
        }
        return $default;
    }

    public static function getAbsoluteThumbnailFolderPath(){
        $documentRoot = Helper::concatPath(Helper::toDirectoryPath($_SERVER['DOCUMENT_ROOT']), Properties::$documentRoot);
        return Helper::concatPath($documentRoot,Properties::$thumbnailFolder);
    }

    public static function getAbsoluteImageFolderPath(){
        $documentRoot = Helper::concatPath(Helper::toDirectoryPath($_SERVER['DOCUMENT_ROOT']), Properties::$documentRoot);
        return Helper::concatPath($documentRoot,Properties::$imageFolder);
    }

    public static function relativeToDocumentRoot($absolute){
        $absolute = Helper::toDirectoryPath($absolute);
        $baseDir = Helper::toDirectoryPath($_SERVER['DOCUMENT_ROOT']);
        if(Helper::isSubPath($absolute,$baseDir)){
            return ltrim(str_replace($baseDir,"",$absolute),DIRECTORY_SEPARATOR);
        }
        return $absolute;
    }

    public static function relativeToImageDirectory($absolute){

        $absolute = Helper::toDirectoryPath($absolute);
        $baseDir = Helper::concatPath(Helper::toDirectoryPath($_SERVER['DOCUMENT_ROOT']), Properties::$documentRoot);
        $baseDir = Helper::concatPath($baseDir, Properties::$imageFolder);
        if(Helper::isSubPath($absolute,$baseDir)){
            return ltrim(str_replace($baseDir,"",$absolute),DIRECTORY_SEPARATOR);
        }
        return $absolute;
    }

    public static function isSubPath($path, $subpath){
        $path = Helper::toDirectoryPath($path);
        $subpath = Helper::toDirectoryPath($subpath);

        $position1 = strpos($path, $subpath);
        $position2 = strpos($path, ".".DIRECTORY_SEPARATOR.$subpath);
        $position3 = strpos(".".DIRECTORY_SEPARATOR.$path,  $subpath);
        if($position1 === 0 || $position2 === 0 || $position3 === 0)
            return true;

        return false;
    }

    public static function toDirectoryPath($path){
        $path = str_replace("/", DIRECTORY_SEPARATOR,$path);
        $path = str_replace("\\", DIRECTORY_SEPARATOR,$path);
        $path = str_replace("..".DIRECTORY_SEPARATOR, "..\\".DIRECTORY_SEPARATOR,$path);
        $path = str_replace(".".DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,$path);
        $path = str_replace( "..\\".DIRECTORY_SEPARATOR ,"..".DIRECTORY_SEPARATOR,$path);
        $path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,$path); //clean duplicated separators
        return $path;
    }

    /**
     * Converts directory path to url path
     * @param $path string
     * @return string
     */
    public static function toURLPath($path){
        return str_replace([".\\","\\","./"],["","/",""],$path);
    }

    /**
     * Concatenate 2 paths and organises directory separators
     * @param $path1
     * @param $path2
     * @return string
     */
    public static function concatPath($path1, $path2){
        $path1 = Helper::toDirectoryPath($path1);
        $path2 = Helper::toDirectoryPath($path2);

        $path = $path1. DIRECTORY_SEPARATOR.$path2;

        $path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR,$path); //clean duplicated separators

        return $path;
    }

    public static function contentArrayToJSONable($array){


        $convertedPath = utf8_encode(Helper::toURLPath($array['currentPath']));
        $convertedDirectories =  $array['directories'];
        $convertedPhotos =  $array['photos'];

        foreach($convertedDirectories as $directory){
            $directory->setPath(Helper::toURLPath($directory->getPath()));
            $directory->setDirectoryName(Helper::toURLPath($directory->getDirectoryName()));
            $directory->setPath(utf8_encode($directory->getPath()));
            $directory->setDirectoryName(utf8_encode($directory->getDirectoryName()));

            if($directory->getSamplePhotos() != null){
                foreach($directory->getSamplePhotos() as $photo){
                    $photo->setPath(Helper::toURLPath($photo->getPath()));
                    $photo->setPath(utf8_encode($photo->getPath()));
                }
            }
        }

        foreach($convertedPhotos as $photo){
            $photo->setPath(Helper::toURLPath($photo->getPath()));
            $photo->setPath(utf8_encode($photo->getPath()));
        }

        $array = array("currentPath" => $convertedPath ,"directories" => $convertedDirectories , "photos" => $convertedPhotos);

        //convert to jsonable
         $JSON_array = array();

          foreach($array as $key => $value){
              if(is_array($value)){
                  $tmp_array = array();
                  foreach ($value as $row) {
                      if(is_object($row) && method_exists($row,'getJsonData')){
                          $row = $row->getJsonData();
                      }
                      $tmp_array[] =  $row;
                  }
                  $JSON_array[$key] = $tmp_array;
              }else{
                  $JSON_array[$key] = $value;
              }

          }

        return  $JSON_array;
    }


    public static function contentArrayToJSON($array){

        return  json_encode(Helper::contentArrayToJSONable($array));
    }

    public static function phpObjectArrayToJSON($array){

        $JSON_array = array();


        foreach ($array as $row) {
            if(is_object($row) && method_exists($row,'getJsonData')){
                $row = $row->getJsonData();
            }
            $JSON_array[] =  $row;
        }

        return  json_encode($JSON_array);
    }
} 