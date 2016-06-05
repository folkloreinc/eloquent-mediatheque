<?php namespace Folklore\EloquentMediatheque\Traits;

trait AudibleTrait {

    protected function getAudibleOrder()
    {
        return $this->audible_order ? true:false;
    }
    /*
     *
     * Relationships
     *
     */
    public function audios()
    {
        $morphName = 'audible';
        $key = 'audio_id';
        $model = config('mediatheque.models.Audio', 'Folklore\EloquentMediatheque\Models\Audio');
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->getAudibleOrder())
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
        $model = config('mediatheque.models.Audio', 'Folklore\EloquentMediatheque\Models\Audio');
        $this->syncMorph($model, 'audible', 'audios', $items);
    }
}
