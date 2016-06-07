<?php namespace Folklore\EloquentMediatheque\Interfaces;

interface SizeableInterface {

    public function getSizeableColumns();

    public function setSizeableColumns($columns);
    
    public function getWidthColumnName();
    
    public function getHeightColumnName();

    public function getWidth();
    
    public function getHeight();
    
    public function getRatio();
    
    public function getWidthFromHeight($height);
    
    public function getHeightFromWidth($width);
    
    public static function getSizeFromFile($file);
}
