<?php namespace Folklore\EloquentMediatheque\Traits;

trait TimeableTrait {
    
    public function getTimeableColumns()
    {
        return $this->timeable_columns ? $this->timeable_columns:[
            'duration' => 'duration'
        ];
    }
    
    public function setTimeableColumns($columns)
    {
        return $this->timeable_columns = $columns;
    }
    
    public function getDurationColumnName()
    {
        $columns = $this->getTimeableColumns();
        return $columns['duration'];
    }

    public function getDuration()
    {
        $columnName = $this->getDurationColumnName();
        return $this->{$columnName};
    }
}
