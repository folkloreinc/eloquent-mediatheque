<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\PicturableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Traits\PaginableTrait;
use Folklore\EloquentMediatheque\Traits\ThumbnailableTrait;
use Folklore\EloquentMediatheque\Interfaces\PaginableInterface;
use Folklore\EloquentMediatheque\Interfaces\ThumbnailableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Document extends Model implements SluggableInterface, PaginableInterface, ThumbnailableInterface {
    
    use WritableTrait, PicturableTrait, PaginableTrait, FileableTrait, UploadableTrait, LinkableTrait, SluggableTrait, ThumbnailableTrait;

    protected $table = 'documents';
    
    public $mediatheque_type = 'document';

    protected $guarded = array();
    protected $fillable = array(
        'name',
        'source',
        'url',
        'embed',
        'filename',
        'mime',
        'size',
        'pages'
    );
    
    protected $sluggable = array(
        'build_from' => array('name', 'mediatheque_type'),
        'save_to' => 'slug'
    );
    
    protected $appends = array(
        'link',
        'mediatheque_type'
    );
    
    /**
     * Fileable
     */
    public static function getPagesFromFile($path)
    {
        if(class_exists(\Imagick::class))
        {
            $image = new \Imagick();
            $image->pingImage($path);
            return $image->getNumberImages();
        }
        
        return 0;
    }
    
    public function getThumbnailCount()
    {
        return $this->pages || 1;
    }
    
    public static function createThumbnailFromFile($file, $i, $count)
    {
        try {
            $path = tempnam(config('mediatheque.fileable.tmp_path', sys_get_temp_dir()), 'thumbnail');
            $image = new \Imagick($file['tmp_path'].'['.$i.']');
            $image->setImageFormat('jpg');
            $image->writeImage($path);
            return $path;
        }
        catch(\Exception $e)
        {
            return null;
        }
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
