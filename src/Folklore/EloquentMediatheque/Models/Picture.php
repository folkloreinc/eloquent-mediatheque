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

class Picture extends Model implements SluggableInterface, FileableInterface, SizeableInterface {
    
    use WritableTrait, SizeableTrait, FileableTrait, UploadableTrait, LinkableTrait, SluggableTrait
    {
        FileableTrait::deleteFileableFile as originalDeleteFileableFile;
    }

    protected $table = 'pictures';
    
    public $mediatheque_type = 'picture';

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
        'height'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to'    => 'slug',
    );
    
    protected $appends = array(
        'link',
        'mediatheque_type'
    );
    
    /**
     * Fileable
     */
    public function deleteFileableFile($filename = null)
    {
        if(!$filename)
        {
            $filename = $this->filename;
        }
        $this->originalDeleteFileableFile($filename);
        
        $path = config('mediatheque.fileable.path');
        $path = $path.'/'.$filename;
        app('image')->delete($path);
    }
    
    public static function getSizeFromFile($path)
    {
        list($width, $height, $type, $attr) = getimagesize($path);
        return array(
            'width' => $width,
            'height' => $height
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
    
    /**
     * Accessors and mutators
     */
    protected function getMediathequeTypeAttribute()
    {
        return $this->mediatheque_type;
    }


}
