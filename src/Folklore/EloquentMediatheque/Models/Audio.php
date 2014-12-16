<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\TimeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Interfaces\FileableInterface;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Audio extends Model implements SluggableInterface, FileableInterface, TimeableInterface {
    
    use WritableTrait, TimeableTrait, FileableTrait, UploadableTrait, SluggableTrait;

    protected $table = 'audios';

    protected $guarded = array();
    protected $fillable = array(
        'filename',
        'original',
        'mime',
        'size',
        'duration'
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
        return 'audio';
    }
}
