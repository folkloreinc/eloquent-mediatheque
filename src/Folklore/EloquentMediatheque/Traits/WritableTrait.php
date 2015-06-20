<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Models\Text;

trait WritableTrait {

    protected $writable_order = true;

    /*
     *
     * Relationships
     *
     */
    public function texts()
    {
        $morphName = 'writable';
        $key = 'text_id';
        $model = 'Folklore\EloquentMediatheque\Models\Text';
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        if($this->writable_order)
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
        $this->syncMorph('Folklore\EloquentMediatheque\Models\Text', 'writable', 'texts', $items);
    }

}
