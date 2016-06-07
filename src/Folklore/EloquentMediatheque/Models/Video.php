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
     * Sizeable
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
    
    /**
     * Timeable
     */
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
    
    /**
     * Thumbnailable
     */
    public static function createThumbnailFromFile($file, $i, $count)
    {
        try {
            $ffmpeg = FFMpeg::create(config('mediatheque.programs.ffmpeg'));
            $video = $ffmpeg->open($file['tmp_path']);
            
            $duration = array_get($file, 'duration', null);
            if($duration === null)
            {
                $duration = self::getDurationFromFile($file);
            }
            $durationSteps = $duration/$count;
            $durationMiddle = $durationSteps/2;
            $inMiddle = config('mediatheque.thumbnailable.video.in_middle', true);
            $time = ($durationSteps * $i) + ($inMiddle ? $durationMiddle:0);
            $path = tempnam(config('mediatheque.thumbnailable.tmp_path', sys_get_temp_dir()), 'thumbnail');
            $video->frame(TimeCode::fromSeconds($time))
                ->save($path);
                
            return $path;
        }
        catch(\Exception $e)
        {
            return null;
        }
    }
    
    public function shouldCreateThumbnail()
    {
        return config('mediatheque.thumbnailable.enable', config('mediatheque.thumbnailable.video.enable', true));
    }
    
    public function getThumbnailCount()
    {
        return isset($this->thumbnail_count) ? $this->thumbnail_count:config('mediatheque.thumbnailable.video.count', 1);
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
