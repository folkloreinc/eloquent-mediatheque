<?php namespace Folklore\EloquentMediatheque\Interfaces;

interface TimeableInterface {

    public function getTimeableColumns();

    public function setTimeableColumns($columns);
    
    public function getDurationColumnName();

    public function getDuration();
}
