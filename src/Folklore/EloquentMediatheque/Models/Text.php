<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentLocalizable\LocalizableTrait;
use Folklore\EloquentMediatheque\Models\Collections\TextsCollection;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Text extends Model implements SluggableInterface {
    
    use LocalizableTrait, SluggableTrait;

    protected $table = 'texts';

    protected $fillable = array(
        'content',
        'fields',
        'is_json'
    );
    
    protected $appends = array(
        'mediatheque_type'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to'    => 'slug',
    );
    
    protected function getMediathequeTypeAttribute()
    {
        return 'text';
    }
    
    public function pictures()
    {
        $morphName = 'writable';
        $model = 'Folklore\EloquentMediatheque\Models\Picture';
        $table = \Config::get('eloquent-mediatheque::table_prefix').'writables';
        $query = $this->morphedByMany($model, $morphName, $table)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');
        return $query;
    }
    
    public function audios()
    {
        $morphName = 'writable';
        $model = 'Folklore\EloquentMediatheque\Models\Audio';
        $table = \Config::get('eloquent-mediatheque::table_prefix').'writables';
        $query = $this->morphedByMany($model, $morphName, $table)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');
        return $query;
    }
    
    public function videos()
    {
        $morphName = 'writable';
        $model = 'Folklore\EloquentMediatheque\Models\Video';
        $table = \Config::get('eloquent-mediatheque::table_prefix').'writables';
        $query = $this->morphedByMany($model, $morphName, $table)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');
        return $query;
    }
        
    public function newCollection(array $models = array())
    {
        return new TextsCollection($models);
    }
    
    protected function getLocaleModelClass()
    {
        return 'Folklore\EloquentMediatheque\Models\TextLocale';
    }
    
    public function getContent($locale = null)
    {
        return $locale && $this->locales->{$locale} && !empty($this->locales->{$locale}->content) ? $this->locales->{$locale}->content:$this->content;
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
