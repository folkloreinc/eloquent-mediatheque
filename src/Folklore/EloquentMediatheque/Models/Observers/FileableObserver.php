<?php namespace Folklore\EloquentMediatheque\Models\Observers;

use Folklore\EloquentMediatheque\Models\Picture;

class FileableObserver {
    
    protected $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function updating($model)
    {
        $fileableColumns = array_values($model->getFileableColumns());
        if(!$this->app['config']->get('eloquent-mediatheque::fileable.delete_file_on_update'))
        {
            return;
        }
        else if(!$model->isDirty($fileableColumns))
        {
            return;
        }
        
        $model->deleteFile();
    }
    
    public function deleting($model)
    {
        if(!$this->app['config']->get('eloquent-mediatheque::fileable.delete_file_on_delete'))
        {
            return;
        }
        
        $model->deleteFile();
    }
    
}
