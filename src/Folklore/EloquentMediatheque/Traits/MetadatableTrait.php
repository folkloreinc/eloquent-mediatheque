<?php namespace Folklore\EloquentMediatheque\Traits;

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
        $model = config('mediatheque.models.Metadata', 'Folklore\EloquentMediatheque\Models\Metadata');
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
        $model = config('mediatheque.models.Metadata', 'Folklore\EloquentMediatheque\Models\Metadata');
        $this->syncMorph($model, 'metadatable', 'metadatas', $items);
    }

}
