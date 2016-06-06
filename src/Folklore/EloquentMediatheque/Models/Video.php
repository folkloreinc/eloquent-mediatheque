<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\PicturableTrait;
use Folklore\EloquentMediatheque\Traits\SizeableTrait;
use Folklore\EloquentMediatheque\Traits\TimeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Traits\ThumbnailableTrait;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;
use Folklore\EloquentMediatheque\Interfaces\ThumbnailableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use FFMpeg\FFProbe;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class Video extends Model implements SluggableInterface, TimeableInterface, SizeableInterface, ThumbnailableInterface {
    
    use WritableTrait, PicturableTrait, SizeableTrait, TimeableTrait, FileableTrait, UploadableTrait, LinkableTrait, SluggableTrait, ThumbnailableTrait;

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
    public static function getSizeFromFile($file)
    {
        try {
            $ffprobe = FFProbe::create(config('mediatheque.programs.ffmpeg'));
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
            $ffprobe = FFProbe::create(config('mediatheque.programs.ffmpeg'));
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
    
    public static function createThumbnailFromFile($file, $i, $count)
    {
        try {
            $ffmpeg = FFMpeg::create(config('mediatheque.programs.ffmpeg'));
            $video = $ffmpeg->open($file['tmp_path']);
        }
        catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
        
        $duration = array_get($file, 'duration', null);
        if($duration === null)
        {
            $duration = self::getDurationFromFile($file);
        }
        $durationSteps = $duration/$count;
        $durationMiddle = $durationSteps/2;
        $time = ($durationSteps * $i) + $durationMiddle;
        $path = tempnam(config('mediatheque.fileable.tmp_path', sys_get_temp_dir()), 'thumbnail');
        $video->frame(TimeCode::fromSeconds($time))
            ->save($path);
        
        return $path;
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
