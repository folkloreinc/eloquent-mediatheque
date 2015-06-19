<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Models\Picture;

trait PicturableTrait {

    protected $picturable_order = true;

    /*
     *
     * Relationships
     *
     */
    public function pictures()
    {
        $morphName = 'picturable';
        $key = 'picture_id';
        $model = 'Folklore\EloquentMediatheque\Models\Picture';
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->picturable_order)
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
         $this->syncMorph('Folklore\EloquentMediatheque\Models\Picture', 'picturable', 'pictures', $items);
     }

}
