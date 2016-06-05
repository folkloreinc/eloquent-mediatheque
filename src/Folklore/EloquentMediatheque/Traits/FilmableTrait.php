<?php namespace Folklore\EloquentMediatheque\Traits;

trait FilmableTrait {

    protected function getFilmableOrder()
    {
        return $this->filmable_order ? true:false;
    }
    
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

        if($this->getFilmableOrder())
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
