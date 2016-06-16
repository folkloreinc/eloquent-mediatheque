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

use Log;

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
     * Pageable
     */
    public static function getPagesFromFile($file)
    {
        try {
            if(class_exists(\Imagick::class))
            {
                $image = new \Imagick();
                $image->pingImage($file['tmp_path']);
                return $image->getNumberImages();
            }
        }
        catch(\Exception $e)
        {
            Log::error($e);
        }
        
        return 0;
    }
    
    /**
     * Thumbnailable
     */
    public static function createThumbnailFromFile($file, $i, $count)
    {
        try {
            $resolution = config('mediatheque.thumbnailable.document.resolution', 150);
            $format = config('mediatheque.thumbnailable.document.format', 'jpeg');
            $quality = config('mediatheque.thumbnailable.document.quality', 100);
            $backgroundColor = config('mediatheque.thumbnailable.document.background', 'white');
            $font = config('mediatheque.thumbnailable.document.font', __DIR__.'/../../../resources/fonts/arial.ttf');
            
            $path = tempnam(config('mediatheque.thumbnailable.tmp_path', sys_get_temp_dir()), 'thumbnail');
            $image = new \Imagick();
            $image->setResolution($resolution, $resolution);
            $image->readImage($file['tmp_path'].'['.$i.']');
            $image->setImageFormat($format);
            $image->setImageCompressionQuality($quality);
            if(!empty($backgroundColor))
            {
                $image->setImageBackgroundColor($backgroundColor);
            }
            if(!empty($font))
            {
                $image->setFont($font);
            }
            $image->writeImage($path);
            $image->clear();
            $image->destroy();
            return $path;
        }
        catch(\Exception $e)
        {
            Log::error($e);
            return null;
        }
    }
    
    public function shouldCreateThumbnail()
    {
        return config('mediatheque.thumbnailable.enable', config('mediatheque.thumbnailable.document.enable', true));
    }
    
    public function getThumbnailCount()
    {
        $allPages = config('mediatheque.thumbnailable.document.all_pages', true);
        return $allPages && $this->pages ? $this->pages:1;
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
