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
    public function syncTexts($items = array()) {

        $ids = array();
        
        if(is_array($items) && sizeof($items))
        {
            $order = 0;
            foreach($items as $item)
            {
                $model = null;
                if(!is_array($item))
                {
                    $model = Text::find($item);
                    if(!$model)
                    {
                        continue;
                    }
                }
                else
                {
                    $model = isset($item['id']) && !empty($item['id']) ? Text::find($item['id']):null;
                    if(!$model)
                    {
                        $model = new Text();
                    }
                    $model->fill($item);
                    $model->save();
                    
                    $model->syncLocales(isset($item['locales']) ? $item['locales']:array());
                }

                $ids[$model->id] = array(
                    'writable_order' => $this->writable_order ? $order:0
                );
                $order++;
            }
        }
        
        $this->texts()->sync($ids);

    }

}
