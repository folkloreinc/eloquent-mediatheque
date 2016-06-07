<?php namespace Folklore\EloquentMediatheque\Traits;

trait ThumbnailableTrait {
    
    public function shouldCreateThumbnail()
    {
        return config('mediatheque.thumbnailable.enable', true);
    }
    
    public function getThumbnailCount()
    {
        return isset($this->thumbnail_count) ? $this->thumbnail_count:1;
    }
    
    public function updateThumbnails($file)
    {
        if(!$this->shouldCreateThumbnail())
        {
            return;
        }
        
        $count = $this->getThumbnailCount();
        
        $thumbnails = [];
        for($i = 0; $i < $count; $i++)
        {
            $picture = $this->createThumbnail($file, $i, $count);
            if($picture)
            {
                $thumbnails[] = [
                    'model' => $picture,
                    'pivot' => [
                        'picturable_order' => $i,
                        'picturable_position' => 'thumbnail'
                    ]
                ];
            }
        }
        
        $thumbnailsToDelete = $this->pictures()
            ->where('picturable_position', 'thumbnail')
            ->get();
        foreach($thumbnailsToDelete as $thumbnail)
        {
            $thumbnail->delete();
        }
        
        foreach($thumbnails as $thumbnail)
        {
            $this->pictures()->save($thumbnail['model'], $thumbnail['pivot']);
        }
        
        $this->load('pictures');
    }
    
    public function createThumbnail($file, $i, $count)
    {
        $file = static::createThumbnailFromFile($file, $i, $count);
        $modelClass = config('mediatheque.models.Picture', \Folklore\EloquentMediatheque\Models\Picture::class);
        if(!$file)
        {
            return null;
        }
        else if(is_a($file, $modelClass))
        {
            return $file;
        }
        
        $picture = new $modelClass();
        $picture->setFile($file);
        
        return $picture;
    }
}
