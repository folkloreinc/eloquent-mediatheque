<?php namespace Folklore\EloquentMediatheque\Traits;

trait SyncableTrait {

    /*
     *
     * Sync methods
     *
     */
    public function syncMorph($className, $morphName, $relationName, $items = array()) {

        $ids = array();
        
        if(is_array($items) && sizeof($items))
        {
            $order = 0;
            foreach($items as $item)
            {
                $model = null;
                if(!is_array($item))
                {
                    $model = $className::find($item);
                    if(!$model)
                    {
                        continue;
                    }
                }
                else
                {
                    $model = null;
                    if(isset($item['id']) && !empty($item['id']))
                    {
                        $model = $className::find($item['id']);
                    }
                    else if(isset($item['slug']) && !empty($item['slug']))
                    {
                        $model = $className::where('slug',$item['slug'])
                                            ->first();
                    }
                    
                    if(!$model)
                    {
                        $model = new $className();
                    }
                    $model->fill($item);
                    $model->save();
                }
                
                $pivotData = array();
                $pivotData[$morphName.'_order'] = $this->{$morphName.'_order'} ? $order:0;
                if(is_array($item))
                {
                    foreach($item as $key => $value)
                    {
                        if(preg_match('/^'.$morphName.'\_/',$key))
                        {
                            $pivotData[$key] = $value;
                        }
                    }
                }    
                $ids[$model->id] = $pivotData;
                $order++;
            }
        }
        
        $this->{$relationName}()->sync($ids);

    }
}
