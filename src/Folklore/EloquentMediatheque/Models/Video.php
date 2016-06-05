<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\PicturableTrait;
use Folklore\EloquentMediatheque\Traits\SizeableTrait;
use Folklore\EloquentMediatheque\Traits\TimeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use FFMpeg\FFProbe;

class Video extends Model implements SluggableInterface, TimeableInterface, SizeableInterface {
    
    use WritableTrait, PicturableTrait, SizeableTrait, TimeableTrait, FileableTrait, UploadableTrait, LinkableTrait, SluggableTrait;

    protected $table = 'videos';
    
    public $mediatheque_type = 'video';

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
    
    protected $appends = array(
        'link',
        'mediatheque_type'
    );
    
    /**
     * Fileable
     */
    public static function getSizeFromFile($file)
    {
        try {
            $ffprobe = FFProbe::create(config('mediatheque.ffmpeg'));
            $stream = $ffprobe->streams($file['tmp_path'])
                                ->videos()
                                ->first();
            $width = $stream->get('width');
            $height = $stream->get('height');
        }
        catch(\Exception $e)
        {
            $width = 0;
            $height = 0;
        }
        
        return array(
            'width' => $width,
            'height' => $height
        );
    }
    
    public static function getDurationFromFile($file)
    {
        try {
            $ffprobe = FFProbe::create(config('mediatheque.ffmpeg'));
            $stream = $ffprobe->streams($file['tmp_path'])
                        ->videos()
                        ->first();
            $duration = $stream->get('duration');
        }
        catch(\Exception $e)
        {
            $duration = 0;
        }

        return $duration;
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
