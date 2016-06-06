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
     * Fileable
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
    
    public static function createThumbnailFromFile($file, $i, $count)
    {
        $audioPath = $file['tmp_path'];
        $waveformPath = $audioPath.'.png';
        $command = [];
        $command[] = config('mediatheque.programs.audiowaveform.bin', '/usr/local/bin/audiowaveworm');
        $command[] = '-i '.escapeshellarg($audioPath);
        $command[] = '-o '.escapeshellarg($waveformPath);
        $command[] = '-z 600';
        $command[] = '-w 1200';
        $command[] = '-h 400';
        $command[] = '--background-color FFFFFF00';
        $command[] = '--waveform-color 000000';
        $command[] = '--no-axis-labels';
        
        $output = [];
        $return = 0;
        exec(implode(' ', $command), $output, $return);
        
        if($return !== 0)
        {
            return null;
        }
        
        return $waveformPath;
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
