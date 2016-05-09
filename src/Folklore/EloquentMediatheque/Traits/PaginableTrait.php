<?php namespace Folklore\EloquentMediatheque\Traits;

trait PaginableTrait {

    protected $paginable_columns = array(
        'pages' => 'pages'
    );
    
    public function getPaginableColumns()
    {
        return $this->paginable_columns;
    }
    
    public function setPaginableColumns($columns)
    {
        return $this->paginable_columns = $columns;
    }
    
    public function getPagesColumnName()
    {
        return $this->paginable_columns['pages'];
    }

    public function getPages()
    {
        $columnName = $this->getPagesColumnName();
        return $this->{$columnName};
    }
}
