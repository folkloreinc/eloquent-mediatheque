<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentLocalizable\LocalesCollection;

class TextLocale extends Model {

    protected $table = 'texts_locales';
    
    protected $visible = array(
        'locale',
        'fields',
        'content'
    );
    
    protected $fillable = array(
        'locale',
        'content',
        'fields',
        'is_json'
    );
        
    public function newCollection(array $models = array())
    {
        return new LocalesCollection($models);
    }
    
    protected function getFieldsAttribute($value)
    {
        if(empty($value))
        {
            return array();
        }
        return is_string($value) ? json_decode($value,true):$value;
    }
    
    protected function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = is_array($value) ? json_encode($value):$value;
    }
    
    protected function getContentAttribute($value)
    {
        return (int)$this->is_json === 1 && is_string($value) ? json_decode($value):$value;
    }
    
    protected function setContentAttribute($value)
    {
        if(is_array($value))
        {
            $this->attributes['content'] = json_encode($value);
            $this->is_json = 1;
            if(!$this->fields || empty($this->fields)) {
                $this->fields = array_keys($value);
            }
        }
    }
}
