<?php namespace Folklore\EloquentMediatheque\Traits;

trait PicturableTrait {

    protected function getPicturableOrder()
    {
        return $this->picturable_order ? true:false;
    }

    /*
     *
     * Relationships
     *
     */
    public function pictures()
    {
        $morphName = 'picturable';
        $key = 'picture_id';
        $model = config('mediatheque.models.Picture', 'Folklore\EloquentMediatheque\Models\Picture');
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->getPicturableOrder())
        {
            $query->orderBy('picturable_order','asc');
        }

        return $query;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncPictures($items = array())
    {
        $model = config('mediatheque.models.Picture', 'Folklore\EloquentMediatheque\Models\Picture');
        $this->syncMorph($model, 'picturable', 'pictures', $items);
    }

}
