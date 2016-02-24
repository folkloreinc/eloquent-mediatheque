<?php namespace Folklore\EloquentMediatheque\Interfaces;

interface FileableInterface {

    public function getFileableColumns();

    public function setFileableColumns($columns);
    
    public function getFilenameColumnName();
    
    public function getSizeColumnName();
    
    public function getMimeColumnName();
    
    public function getSize();
    
    public function getSizeInKB();
    
    public function getSizeInMB();
    
    public function getMime();
    
    public function setFile($path, $file = array());
    
    public function deleteFileableFile();
}
