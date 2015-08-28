<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\SizeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Interfaces\FileableInterface;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Metadata extends Model implements SluggableInterface {
    
    use SluggableTrait;

    protected $table = 'metadatas';
    
    public $mediatheque_type = 'metadata';

    protected $guarded = array();
    protected $fillable = array(
        'type',
        'name',
        'value',
        'value_text',
        'value_date',
        'value_time',
        'value_datetime'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to'    => 'slug',
    );
    
    /*protected $casts = [
        'value' => 'type_attribute'
    ];*/
    
    protected function setValueAttribute($value)
    {
        if($this->attributes['type'] === 'date')
        {
            $this->attributes['value_date'] = $value;
        }
        else if($this->attributes['type'] === 'time')
        {
            $this->attributes['value_time'] = $value;
        }
        else if($this->attributes['type'] === 'datetime')
        {
            $this->attributes['value_datetime'] = $value;
        }
        else if($this->attributes['type'] === 'text')
        {
            $this->attributes['value_text'] = $value;
        }
        else
        {
            $this->attributes['value'] = $value;
        }
    }
    
    protected function getValueAttribute()
    {
        if($this->attributes['type'] === 'date')
        {
            return $this->attributes['value_date'];
        }
        else if($this->attributes['type'] === 'time')
        {
            return $this->attributes['value_time'];
        }
        else if($this->attributes['type'] === 'datetime')
        {
            return $this->attributes['value_datetime'];
        }
        else if($this->attributes['type'] === 'text')
        {
            return $this->attributes['value_text'];
        }
        else
        {
            return $this->attributes['value'];
        }
    }
    
    protected function castAttribute($key, $value)
    {
        $type = $this->getCastType($key);
        if($type === 'type_attribute' && !empty($this->type))
        {
            $this->casts[$key] = $this->type;
        }
        
        return parent::castAttribute($key, $value);
    }
    
    /**
     * Query scopes
     */
    public function scopeSearch($query, $text)
    {
        $query->where(function($query) use ($text) {
			$query->where('slug', 'LIKE', '%'.$text.'%');
            $query->orWhere('name', 'LIKE', '%'.$text.'%');
            $query->orWhere('value', 'LIKE', '%'.$text.'%');
            $query->orWhere('value_text', 'LIKE', '%'.$text.'%');
		});
        
        return $query;
    }


}
