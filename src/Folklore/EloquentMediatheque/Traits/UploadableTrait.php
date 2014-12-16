<?php namespace Folklore\EloquentMediatheque\Traits;

trait UploadableTrait {
    
    public function setUploadedFile($uploadedFile) {

		//Get infos
        $file = array();
		$file['original'] = $uploadedFile->getClientOriginalName();
		$file['size'] = $uploadedFile->getSize();
		$file['mime'] = $uploadedFile->getMimeType();
        
        //Get temp folder and name
        $tempFolder = \Config::get('eloquent-mediatheque::uploadable.tmp_path');
        $tempFilename = uniqid();
        
        //Create directory if doesn't exist
		if(!file_exists($tempFolder)) {
			mkdir($tempFolder, 0755, true);
		}
        
        ///Move uploaded file
        $uploadedFile->move($tempFolder, $tempFilename);
        
        //Set file
        $this->setFile($tempFolder.'/'.$tempFilename, $file);

	}
    
    /*protected $uploadable_columns = array(
        'filename' => 'filename',
        'original' => 'original',
        'mime' => 'mime',
        'size' => 'size'
    );
    
    protected $uploadable_filename = '{type}/{date(Y-m-d)}/{id}-{date(his)}.{extension}';

	public function upload($uploadedFile) {

        //Get config
        $extensions = \Config::get('eloquent-mediatheque::mime_to_extension');
		$destinationPath = \Config::get('eloquent-mediatheque::upload_path');

		//Get infos
        $file = array();
        $file['tmp_path'] = $uploadedFile->getRealPath();
		$file['original'] = $uploadedFile->getClientOriginalName();
		$file['size'] = $uploadedFile->getSize();
		$file['mime'] = $uploadedFile->getMimeType();
		$file['type'] = explode('/',$file['mime'])[0];
        $file['extension'] = isset($extensions[$file['mime']]) ? $extensions[$file['mime']]:'';
        
        //Save to get id
        if(!$this->id) {
            $this->save();
        }

		//Get destination
        $file['filename'] = ltrim($this->uploadable_filename, '/');
        $replaces = array_merge($this->toArray(),$file);
        foreach($replaces as $key => $value)
        {
            if(preg_match('/\{'.strtolower($key).'\}/',$file['filename']))
            {
                $file['filename'] = str_replace('{'.strtolower($key).'}', $value, $file['filename']);
            }
        }
        if(preg_match_all('/\{date\(([^\)]+)\)\}/', $file['filename'], $matches))
        {
            if(sizeof($matches))
            {
                for($i = 0; $i < sizeof($matches[0]); $i++)
                {
                    $file['filename'] = str_replace($matches[0][$i], date($matches[1][$i]), $file['filename']);
                }
            }
        }
        $destinationFolder = dirname($destinationPath.'/'.$file['filename']);
        $destinationFilename = basename($destinationPath.'/'.$file['filename']);

		//Create directory if doesn't exist
		if(!file_exists($destinationFolder)) {
			mkdir($destinationFolder, 0755, true);
		}

		//Move uploaded file
		$uploadedFile->move($destinationFolder, $destinationFilename);

        //Model data
        $modelData = array();
        
        //Get size
        $sizeable = in_array('Folklore\EloquentMediatheque\SizeableTrait', class_uses($this));
        if($sizeable && preg_match('/^image\//', $file['mime']))
        {
            list($width, $height, $type, $attr) = getimagesize($destinationPath.'/'.$file['filename']);
            $modelData[$this->getWidthColumnName()] = $width;
            $modelData[$this->getHeightColumnName()] = $height;
        }

		//Get model data
        foreach($this->uploadable_columns as $key => $column)
        {
            if($column)
            {
                $modelData[$column] = $file[$key];
            }
        }
        
        //Fill model
        $this->fill($modelData);
        $this->save();

		return $this;

	}*/
}
