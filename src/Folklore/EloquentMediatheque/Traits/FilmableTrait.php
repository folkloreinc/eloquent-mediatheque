<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Models\Video;

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
        $model = 'Folklore\EloquentMediatheque\Models\Video';
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
        $this->syncMorph('Folklore\EloquentMediatheque\Models\Video', 'filmable', 'videos', $items);
    }

}
