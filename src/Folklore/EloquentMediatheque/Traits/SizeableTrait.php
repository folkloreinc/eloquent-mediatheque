<?php namespace Folklore\EloquentMediatheque\Traits;

trait SizeableTrait {

    protected $sizeable_columns = array(
        'width' => 'width',
        'height' => 'height'
    );
    
    public function getSizeableColumns()
    {
        return $this->sizeable_columns;
    }
    
    public function setSizeableColumns($columns)
    {
        return $this->sizeable_columns = $columns;
    }
    
    public function getWidthColumnName()
    {
        return $this->sizeable_columns['width'];
    }
    
    public function getHeightColumnName()
    {
        return $this->sizeable_columns['height'];
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
