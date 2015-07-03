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

class Metadata extends Model implements SluggableInterface {
    
    use SluggableTrait;

    protected $table = 'metadatas';
    
    public $mediatheque_type = 'metadata';

    protected $guarded = array();
    protected $fillable = array(
        'type',
        'name',
        'value'
    );
    
    protected $sluggable = array(
        'build_from' => 'mediatheque_type',
        'save_to'    => 'slug',
    );
    
    protected $casts = [
        'value' => 'type_attribute'
    ];
    
    protected function castAttribute($key, $value)
    {
        $type = $this->getCastType($key);
        if($type)
        {
            $type = $this->type;
        }
        
        return parent::castAttribute($key, $value);
    }


}
