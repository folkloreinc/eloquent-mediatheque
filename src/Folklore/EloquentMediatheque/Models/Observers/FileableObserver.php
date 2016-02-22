<?php namespace Folklore\EloquentMediatheque\Models\Observers;

class FileableObserver {
    
    protected $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    public function updating($model)
    {
        $fileableColumns = array_values($model->getFileableColumns());
        if(!$this->app['config']->get('mediatheque.fileable.delete_file_on_update'))
        {
            return;
        }
        else if(!$model->isDirty($fileableColumns))
        {
            return;
        }
        
        $original = $model->getOriginal();
        $model->deleteFileableFile($original['filename']);
    }
    
    public function deleting($model)
    {
        if(!$this->app['config']->get('mediatheque.fileable.delete_file_on_delete'))
        {
            return;
        }
        
        $original = $model->getOriginal();
        $model->deleteFileableFile($original['filename']);
    }
    
}
