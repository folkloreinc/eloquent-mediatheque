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
    
    public $mediatheque_type = 'audio';

    protected $guarded = array();
    protected $fillable = array(
        'name',
        'source',
        'url',
        'embed',
        'filename',
        'mime',
        'size',
        'duration'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to'    => 'slug',
    );
    
    /**
     * Query scopes
     */
    public function scopeSearch($query, $text)
    {
        $query->where(function($query) use ($text) {
			$query->where('slug', 'LIKE', '%'.$text.'%');
			$query->orWhere('filename', 'LIKE', '%'.$text.'%');
			$query->orWhere('original', 'LIKE', '%'.$text.'%');
		});
        return $query;
    }
}
