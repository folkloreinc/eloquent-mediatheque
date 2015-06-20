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
    
    public $mediatheque_type = 'text';

    protected $guarded = array();
    protected $fillable = array(
        'name',
        'source',
        'url',
        'embed',
        'filename',
        'mime',
        'size',
        'width',
        'height',
        'duration'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to' => 'slug'
    );
    
    /**
     * Fileable
     */
    public static function getSizeFromFile($path)
    {
        return array(
            'width' => 0,
            'height' => 0
        );
    }
    
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
