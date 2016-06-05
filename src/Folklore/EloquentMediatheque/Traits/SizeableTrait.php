<?php namespace Folklore\EloquentMediatheque\Traits;

trait SizeableTrait {

    public function getSizeableColumns()
    {
        return $this->sizeable_columns ? $this->sizeable_columns:[
            'width' => 'width',
            'height' => 'height'
        ];
    }
    
    public function setSizeableColumns($columns)
    {
        return $this->sizeable_columns = $columns;
    }
    
    public function getWidthColumnName()
    {
        $columns = $this->getSizeableColumns();
        return $columns['width'];
    }
    
    public function getHeightColumnName()
    {
        $columns = $this->getSizeableColumns();
        return $columns['height'];
    }

    public function getWidth()
    {
        $columnName = $this->getWidthColumnName();
        return $this->{$columnName};
    }
    
    public function getHeight()
    {
        $columnName = $this->getHeightColumnName();
        return $this->{$columnName};
    }
    
    public function getRatio()
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        return $width/$height;
    }
    
    public function getWidthFromHeight($height)
    {
        $ratio = $this->getRatio();
        return $height*$ratio;
    }
    
    public function getHeightFromWidth($width)
    {
        $ratio = $this->getRatio();
        return $width/$ratio;
    }
}
