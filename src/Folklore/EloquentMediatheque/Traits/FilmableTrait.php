<?php namespace Folklore\EloquentMediatheque\Traits;

trait FilmableTrait {

    protected $filmable_order = true;

    /*
     *
     * Relationships
     *
     */
    public function videos()
    {
        $morphName = 'filmable';
        $key = 'video_id';
        $model = config('mediatheque.models.Video', 'Folklore\EloquentMediatheque\Models\Video');
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->filmable_order)
        {
            $query->orderBy('filmable_order','asc');
        }

        return $query;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncVideos($items = array())
    {
        $model = config('mediatheque.models.Video', 'Folklore\EloquentMediatheque\Models\Video');
        $this->syncMorph($model, 'filmable', 'videos', $items);
    }

}
