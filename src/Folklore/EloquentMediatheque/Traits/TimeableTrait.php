<?php namespace Folklore\EloquentMediatheque\Traits;

trait TimeableTrait {

    protected $timeable_columns = array(
        'duration' => 'duration'
    );
    
    public function getTimeableColumns()
    {
        return $this->timeable_columns;
    }
    
    public function setTimeableColumns($columns)
    {
        return $this->timeable_columns = $columns;
    }
    
    public function getDurationColumnName()
    {
        return $this->timeable_columns['duration'];
    }

    public function getDuration()
    {
        $columnName = $this->getDurationColumnName();
        return $this->{$columnName};
    }
}
