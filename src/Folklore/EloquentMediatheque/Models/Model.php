<?php namespace Folklore\EloquentMediatheque\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent
{
    public $mediatheque_type = 'media';

    public function __construct(array $attributes = array())
    {
        $this->table = config('mediatheque.table_prefix').$this->table;

        parent::__construct($attributes);
    }
    
    /**
     * Accessors and mutators
     */
    protected function getMediathequeTypeAttribute()
    {
        return $this->mediatheque_type;
    }
}
