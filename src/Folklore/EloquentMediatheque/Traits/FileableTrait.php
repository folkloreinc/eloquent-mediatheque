<?php namespace Folklore\EloquentMediatheque\Traits;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use GuzzleHttp\Client as GuzzleClient;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;

trait FileableTrait {
    
    protected $fileable_columns = [
        'filename' => 'filename',
        'original' => 'name',
        'mime' => 'mime',
        'size' => 'size'
    ];

    protected $fileable_destination = '{type}/{date(Y-m-d)}/{id}-{date(his)}.{extension}';
    
    public function getFileableColumns()
    {
        return $this->fileable_columns;
    }
    
    public function getFileableDestination()
    {
        return $this->fileable_destination;
    }
    
    public function setFileableColumns($columns)
    {
        return $this->fileable_columns = $columns;
    }
    
    public function getFilenameColumnName()
    {
        $columns = $this->getFileableColumns();
        return $columns['filename'];
    }
    
    public function getSizeColumnName()
    {
        $columns = $this->getFileableColumns();
        return $columns['size'];
    }
    
    public function getMimeColumnName()
    {
        $columns = $this->getFileableColumns();
        return $columns['mime'];
    }
    
    public function getSize()
    {
        $columnName = $this->getSizeColumnName();
        return $this->{$columnName};
    }
    
    public function getSizeInKB()
    {
        $size = $this->getSize();
        return $size/1024;
    }
    
    public function getSizeInMB()
    {
        $size = $this->getSize();
        return $size/1024/1024;
    }
    
    public function getMime()
    {
        $columnName = $this->getMimeColumnName();
        return $this->{$columnName};
    }
    
    public function setFile($path, $file = array())
    {
        //Get config
        $extensions = config('mediatheque.fileable.mime_to_extension');
		$destinationPath = config('mediatheque.fileable.path');
        
        //Get original file name
        $original =  basename($path);
        
        //If it's a remote file, download it
        if(filter_var($path, FILTER_VALIDATE_URL))
        {
            $tmpPath =  tempnam('/tmp', 'MEDIATHEQUE');
            $client = new GuzzleClient();
            $response = $client->request('GET', $path, [
                'sink' => $tmpPath
            ]);
            $path = $tmpPath;
        }
        
        //Get file info
        $defaultFile = array();
        $defaultFile['tmp_path'] = $path;
        $defaultFile['original'] = $original;
        $defaultFile['size'] = filesize($path);
        $defaultFile['mime'] = MimeTypeGuesser::getInstance()->guess($path);
        $defaultFile['type'] = explode('/',$defaultFile['mime'])[0];
        $defaultFile['extension'] = isset($extensions[$defaultFile['mime']]) ? $extensions[$defaultFile['mime']]:'';
        $file = array_merge($defaultFile, $file);
        
        //Get size
        if($this instanceof SizeableInterface)
        {
            $size = static::getSizeFromFile($path);
            $file['width'] = $size['width'];
            $file['height'] = $size['height'];
        }
        
        //Save to get id
        if(!$this->id)
        {
            $this->save();
        }

		//Get destination
        $replaces = array_merge($this->toArray(),$file);
        $destination = $this->getFileableDestination();
        $file['filename'] = $this->parseFileableDestination($destination, $replaces);
        $file['folder'] = dirname($destinationPath.'/'.$file['filename']);
        $file['basename'] = basename($destinationPath.'/'.$file['filename']);

		//Create directory if doesn't exist
		if(!file_exists($file['folder']))
        {
			mkdir($file['folder'], 0755, true);
		}

		//Move file
        copy($path, $file['folder'].'/'.$file['basename']);

        //Model data
        $modelData = array();
        $columns = $this->getFileableColumns();
        foreach($columns as $key => $column)
        {
            if($column)
            {
                $modelData[$column] = $file[$key];
            }
        }
        if($this instanceof SizeableInterface)
        {
            if(isset($file['width']))
            {
                $modelData[$this->getWidthColumnName()] = $file['width'];
            }
            if(isset($file['height']))
            {
                $modelData[$this->getHeightColumnName()] = $file['height'];
            }
        }
        
        //Fill model
        foreach($modelData as $key => $value)
        {
            $this->{$key} = $value;
        }
        $this->save();

		return $this;
    }
    
    public function deleteFile()
    {
        $path = config('mediatheque.fileable.path');
        $path = $path.'/'.$model->filename;
        if(file_exists($path))
        {
            unlink($path);
        }
    }
    
    protected function parseFileableDestination($path, $replaces)
    {
        $destination = ltrim($path, '/');
        foreach($replaces as $key => $value)
        {
            if(preg_match('/\{'.strtolower($key).'\}/',$destination))
            {
                $destination = str_replace('{'.strtolower($key).'}', $value, $destination);
            }
        }
        if(preg_match_all('/\{date\(([^\)]+)\)\}/', $destination, $matches))
        {
            if(sizeof($matches))
            {
                for($i = 0; $i < sizeof($matches[0]); $i++)
                {
                    $destination = str_replace($matches[0][$i], date($matches[1][$i]), $destination);
                }
            }
        }
        return $destination;
    }
}
