<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\SizeableTrait;
use Folklore\EloquentMediatheque\Traits\TimeableTrait;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Video extends Model implements SluggableInterface, TimeableInterface, SizeableInterface {
    
    use WritableTrait, SizeableTrait, TimeableTrait, SluggableTrait;

    protected $table = 'videos';

    protected $guarded = array();
    protected $fillable = array(
        'filename',
        'original',
        'mime',
        'size',
        'width',
        'height',
        'duration'
    );
    
    protected $appends = array(
        'mediatheque_type'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to' => 'slug',
        'on_update' => true
    );
    
    protected function getMediathequeTypeAttribute()
    {
        return 'video';
    }
    
    public static function getSizeFromFile($path)
    {
        return array(
            'width' => 0,
            'height' => 0
        );
    }
}
