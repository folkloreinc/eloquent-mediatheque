<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\SizeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Interfaces\FileableInterface;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;

class Metadata extends Model {

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
        'value_datetime',
        'value_boolean',
        'value_integer',
        'value_float'
    );
    
    protected $appends = array(
        'mediatheque_type'
    );
    
    protected $casts = [
        'value' => 'string',
        'value_text' => 'string',
        'value_date' => 'date',
        'value_datetime' => 'datetime',
        'value_boolean' => 'boolean',
        'value_integer' => 'integer',
        'value_float' => 'double'
    ];
    
    protected function setValueAttribute($value)
    {
        $type = array_get($this->attributes, 'type', '');
        
        if(in_array('value_'.$type, $this->fillable))
        {
            $this->attributes['value_'.$type] = $value;
        }
        else
        {
            $this->attributes['value'] = $value;
        }
    }
    
    protected function getValueAttribute()
    {
        $type = array_get($this->attributes, 'type', '');
        
        if(isset($this->attributes['value_'.$type]))
        {
            return $this->attributes['value_'.$type];
        }
        else
        {
            return $this->attributes['value'];
        }
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
