<?php namespace Folklore\EloquentMediatheque\Traits;

trait WritableTrait {

    protected function getWritableOrder()
    {
        return $this->writable_order ? true:false;
    }

    /*
     *
     * Relationships
     *
     */
    public function texts()
    {
        $morphName = 'writable';
        $key = 'text_id';
        $model = config('mediatheque.models.Text', 'Folklore\EloquentMediatheque\Models\Text');
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->getWritableOrder())
        {
            $query->orderBy('writable_order','asc');
        }

        return $query;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncTexts($items = array())
    {
        $model = config('mediatheque.models.Text', 'Folklore\EloquentMediatheque\Models\Text');
        $this->syncMorph($model, 'writable', 'texts', $items);
    }

}
