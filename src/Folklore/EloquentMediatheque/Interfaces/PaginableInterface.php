<?php namespace Folklore\EloquentMediatheque\Interfaces;

interface PaginableInterface {

    public function getPaginableColumns();

    public function setPaginableColumns($columns);
    
    public function getPagesColumnName();

    public function getPages();
}
