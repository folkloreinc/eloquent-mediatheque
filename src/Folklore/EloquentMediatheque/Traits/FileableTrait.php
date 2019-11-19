<?php namespace Folklore\EloquentMediatheque\Traits;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;
use GuzzleHttp\Client as GuzzleClient;
use Folklore\EloquentMediatheque\Interfaces\SizeableInterface;
use Folklore\EloquentMediatheque\Interfaces\TimeableInterface;
use Folklore\EloquentMediatheque\Interfaces\ThumbnailableInterface;
use Folklore\EloquentMediatheque\Interfaces\PaginableInterface;

trait FileableTrait {

    public function getFileableColumns()
    {
        return $this->fileable_columns ? $this->fileable_columns:[
            'filename' => 'filename',
            'original' => 'name',
            'mime' => 'mime',
            'size' => 'size'
        ];
    }

    public function getFileableDestination()
    {
        return config('mediatheque.fileable.destination', '{mediatheque_type}/{date(Y-m-d)}/{id}-{date(his)}.{extension}');
    }

    public function getFileableDestinationReplaces($file)
    {
        return array_merge([
            'mediatheque_type' => $this->mediatheque_type
        ], $this->toArray(), $file);
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

    protected function isRemoteFile($path)
    {
        return preg_match('/^(https?\:)?\/\/([^\/]+)/i', $path) !== 0;
    }

    public function setFile($path, $file = array())
    {
        //Get config
        $filesystem = config('mediatheque.fileable.filesystem');
        $extensions = config('mediatheque.fileable.mime_to_extension');
		$destinationPath = config('mediatheque.fileable.path');
        $extension = pathinfo(array_get($file, 'original', $path), PATHINFO_EXTENSION);

        //Get original file name
        $deleteOriginalFile = config('mediatheque.fileable.delete_original_file', false);
        $original =  basename($path);

        //If it's a remote file, download it
        if($this->isRemoteFile($path))
        {
            $path = $this->downloadFile($path);
        }

        //Get file info
        $defaultFile = array();
        $defaultFile['tmp_path'] = $path;
        $defaultFile['original'] = $original;
        $defaultFile['size'] = filesize($path);
        $defaultFile['mime'] = MimeTypeGuesser::getInstance()->guess($path);
        $defaultFile['type'] = explode('/',$defaultFile['mime'])[0];
        $defaultFile['extension'] = isset($extensions[$defaultFile['mime']]) ? $extensions[$defaultFile['mime']]:$extension;
        $file = array_merge($defaultFile, $file);

        //Save to get id
        if(!$this->id)
        {
            $this->save();
        }

		//Get destination
        $replaces = $this->getFileableDestinationReplaces($file);
        $destination = $this->getFileableDestination();
        $file['filename'] = $this->parseFileableDestination($destination, $replaces);
        $file['folder'] = dirname($destinationPath.'/'.$file['filename']);
        $file['basename'] = basename($destinationPath.'/'.$file['filename']);

        //Get size
        if($this instanceof SizeableInterface)
        {
            $size = static::getSizeFromFile($file);
            $file['width'] = $size['width'];
            $file['height'] = $size['height'];
        }

        //Get duration
        if($this instanceof TimeableInterface)
        {
            $duration = static::getDurationFromFile($file);
            $file['duration'] = $duration;
        }

        //Get pages
        if($this instanceof PaginableInterface)
        {
            $pages = static::getPagesFromFile($file);
            $file['pages'] = $pages;
        }

        //Save file
        $file = $this->saveFile($path, $file);

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

        //Update model data from interfaces
        if($this instanceof SizeableInterface)
        {
            $modelData[$this->getWidthColumnName()] = array_get($file, 'width', 0);
            $modelData[$this->getHeightColumnName()] = array_get($file, 'height', 0);
        }
        if($this instanceof TimeableInterface)
        {
            $modelData[$this->getDurationColumnName()] = array_get($file, 'duration', 0);
        }
        if($this instanceof PaginableInterface)
        {
            $modelData[$this->getPagesColumnName()] = array_get($file, 'pages', 0);
        }

        //Fill model
        foreach($modelData as $key => $value)
        {
            $this->{$key} = $value;
        }
        $this->save();

        //Update thumbnails
        if($this instanceof ThumbnailableInterface)
        {
            $this->updateThumbnails($file);
        }

        //Delete original file
        if($deleteOriginalFile && file_exists($path))
        {
            unlink($path);
        }

		return $this;
    }

    public function deleteFileableFile($filename = null)
    {
        if(!$filename) {
            $filename = $this->filename;
        }
        $disk = $this->getFileableDisk();
        if($disk && $disk->exists($filename))
        {
            $disk->delete($filename);
        }
        elseif(method_exists($this, 'deleteFileableFile'))
        {
            $this->deleteFileableFile();
        }
        else
        {
            $path = config('mediatheque.fileable.path');
            $path = $path.'/'.$filename;
            if(file_exists($path))
            {
                unlink($path);
            }
        }

    }

    protected function downloadFile($path)
    {
        $deleteOriginalFile = true;
        $tmpPath = tempnam(config('mediatheque.fileable.tmp_path', sys_get_temp_dir()), 'MEDIATHEQUE');
        $client = new GuzzleClient();
        $response = $client->request('GET', $path, [
            'sink' => $tmpPath
        ]);
        $path = $tmpPath;

        return $tmpPath;
    }

    protected function getFileableDisk()
    {
        $filesystem = config('mediatheque.fileable.filesystem');
        if(!$filesystem)
        {
            return null;
        }

        return $filesystem === 'cloud' ? app('filesystem')->cloud():app('filesystem')->disk($filesystem);
    }

    protected function saveFile($path, $file)
    {
        $disk = $this->getFileableDisk();
        if($disk)
        {
            $resource = fopen($path, 'r');
            $disk->getDriver()
                ->put($file['filename'], $resource, [
                    'visibility' => 'public',
                    'ContentType' => array_get($file, 'mime', 'image/jpeg'),
                    'CacheControl' => 'max-age='.(3600 * 24)
                ]);
            fclose($resource);
        }
        elseif(method_exists($this, 'saveFileableFile'))
        {
            $this->saveFileableFile($file);
        }
        else
        {
            //Create directory if doesn't exist
    		if(!file_exists($file['folder']))
            {
    			mkdir($file['folder'], 0755, true);
    		}

    		//Move file
            copy($path, $file['folder'].'/'.$file['basename']);
        }

        return $file;
    }

    protected function parseFileableDestination($path, $replaces)
    {
        $destination = ltrim($path, '/');
        foreach($replaces as $key => $value)
        {
            if(preg_match_all('/\{\s*'.strtolower($key).'\s*\}/',$destination, $matches))
            {
                if(sizeof($matches))
                {
                    for($i = 0; $i < sizeof($matches[0]); $i++)
                    {
                        $destination = str_replace($matches[0][$i], $value, $destination);
                    }
                }
            }
        }
        if(preg_match_all('/\{\s*date\(([^\)]+)\)\s*\}/', $destination, $matches))
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
