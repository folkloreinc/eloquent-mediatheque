<?php namespace Folklore\EloquentMediatheque\Interfaces;

interface ThumbnailableInterface {
    
    public function updateThumbnails($file);
    
    public static function createThumbnailFromFile($file, $i, $count);
}
