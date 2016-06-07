<?php namespace Folklore\EloquentMediatheque\Models;

use Folklore\EloquentMediatheque\Traits\WritableTrait;
use Folklore\EloquentMediatheque\Traits\PicturableTrait;
use Folklore\EloquentMediatheque\Traits\TimeableTrait;
use Folklore\EloquentMediatheque\Traits\FileableTrait;
use Folklore\EloquentMediatheque\Traits\UploadableTrait;
use Folklore\EloquentMediatheque\Traits\LinkableTrait;
use Folklore\EloquentMediatheque\Traits\ThumbnailableTrait;
use Folklore\EloquentMediatheque\Interfaces\FileableInterface;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Folklore\EloquentMediatheque\Interfaces\ThumbnailableInterface;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

use FFMpeg\FFProbe;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;

class Audio extends Model implements SluggableInterface, FileableInterface, TimeableInterface, ThumbnailableInterface {
    
    use WritableTrait, PicturableTrait, TimeableTrait, FileableTrait, UploadableTrait, LinkableTrait, SluggableTrait, ThumbnailableTrait;

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
        'build_from' => array('name', 'mediatheque_type'),
        'save_to'    => 'slug',
    );
    
    protected $appends = array(
        'link',
        'mediatheque_type'
    );
    
    /**
     * Timeable
     */
    public static function getDurationFromFile($file)
    {
        try {
            $ffprobe = FFProbe::create(config('mediatheque.programs.ffmpeg'));
            $stream = $ffprobe->streams($file['tmp_path'])
                        ->audios()
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
        $tmpPath = $file['tmp_path'];
        $audioPath = $tmpPath.'.'.$file['extension'];
        copy($tmpPath, $audioPath);
        
        $zoom = config('mediatheque.thumbnailable.audio.zoom', 600);
        $width = config('mediatheque.thumbnailable.audio.width', 1200);
        $height = config('mediatheque.thumbnailable.audio.height', 400);
        $backgroundColor = config('mediatheque.thumbnailable.audio.background_color', 'FFFFFF00');
        $color = config('mediatheque.thumbnailable.audio.color', '000000');
        $borderColor = config('mediatheque.thumbnailable.audio.border_color', null);
        $axisColor = config('mediatheque.thumbnailable.audio.axis_label_color', null);
        $axisLabel = config('mediatheque.thumbnailable.audio.axis_label', false);
        
        $waveformPath = tempnam(config('mediatheque.thumbnailable.tmp_path', sys_get_temp_dir()), 'thumbnail').'.png';
        $command = [];
        $command[] = config('mediatheque.programs.audiowaveform.bin', '/usr/local/bin/audiowaveform');
        $command[] = '-i '.escapeshellarg($audioPath);
        $command[] = '-o '.escapeshellarg($waveformPath);
        if(!empty($zoom))
        {
            $command[] = '-z '.$zoom;
        }
        if(!empty($width))
        {
            $command[] = '-w '.$width;
        }
        if(!empty($height))
        {
            $command[] = '-h '.$height;
        }
        $command[] = '--background-color '.$backgroundColor;
        $command[] = '--waveform-color '.$color;
        if(!empty($borderColor))
        {
            $command[] = '--border-color '.$borderColor;
        }
        if(!empty($axisColor))
        {
            $command[] = '--axis-label-color '.$axisColor;
        }
        $command[] = $axisLabel ? '--with-axis-labels':'--no-axis-labels';
        
        
        $output = [];
        $return = 0;
        exec(implode(' ', $command), $output, $return);
        
        unlink($audioPath);
        
        if($return !== 0)
        {
            return null;
        }
        
        return $waveformPath;
    }
    
    public function shouldCreateThumbnail()
    {
        return config('mediatheque.thumbnailable.enable', config('mediatheque.thumbnailable.audio.enable', true));
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
