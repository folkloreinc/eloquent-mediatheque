<?php namespace Folklore\EloquentMediatheque\Traits;

trait PaginableTrait {

    public function getPaginableColumns()
    {
        return $this->paginable_columns ? $this->paginable_columns:[
            'pages' => 'pages'
        ];
    }
    
    public function setPaginableColumns($columns)
    {
        return $this->paginable_columns = $columns;
    }
    
    public function getPagesColumnName()
    {
        $columns = $this->paginable_columns;
        return $columns['pages'];
    }

    public function getPages()
    {
        $columnName = $this->getPagesColumnName();
        return $this->{$columnName};
    }
}
