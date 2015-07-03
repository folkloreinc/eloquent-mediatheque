<?php namespace Folklore\EloquentMediatheque\Traits;

use Folklore\EloquentMediatheque\Models\Metadata;

trait MetadatableTrait {

    /*
     *
     * Relationships
     *
     */
    public function metadatas()
    {
        $morphName = 'metadatable';
        $key = 'metadata_id';
        $model = 'Folklore\EloquentMediatheque\Models\Metadata';
        $table = config('mediatheque.table_prefix').$morphName.'s';
        $query = $this->morphToMany($model, $morphName, $table, null, $key)
                        ->withTimestamps()
                        ->withPivot($morphName.'_position', $morphName.'_order');

        return $query;
    }

    /*
     *
     * Sync methods
     *
     */
    public function syncMetadatas($items = array())
    {
        $this->syncMorph('Folklore\EloquentMediatheque\Models\Metadata', 'metadatable', 'metadatas', $items);
    }

}
