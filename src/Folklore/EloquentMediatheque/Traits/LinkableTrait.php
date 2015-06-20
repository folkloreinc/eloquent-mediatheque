<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Interfaces\FileableInterface;

trait LinkableTrait {
    
    public function getLink()
    {
        if($this->source === 'embed')
        {
            return $this->embed;
        }
        else if($this->source === 'url')
        {
            return $this->url;
        }
        else if($this instanceof FileableInterface)
        {
            return rtrim(config('mediatheque.linkable.fileable_path'),'/').'/'.ltrim($this->filename,'/');
        }
        
        return null;
    }
    
    protected function getLinkAttribute()
    {
        return $this->getLink();
    }
    
}
