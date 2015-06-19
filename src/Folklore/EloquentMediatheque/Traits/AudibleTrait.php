<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Models\Audio;

trait AudibleTrait {

    protected $audible_order = true;

    /*
     *
     * Relationships
     *
     */
    public function audios()
    {
        $morphName = 'audible';
        $key = 'audio_id';
        $model = 'Folklore\EloquentMediatheque\Models\Audio';
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->audible_order)
        {
            $query->orderBy('audible_order','asc');
        }

        return $query;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncAudios($items = array())
    {
        $this->syncMorph('Folklore\EloquentMediatheque\Models\Audio', 'audible', 'audios', $items);
    }
}
