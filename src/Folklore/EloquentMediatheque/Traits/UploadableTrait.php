<?php namespace Folklore\EloquentMediatheque\Traits;

trait UploadableTrait {
    
    public function setUploadedFile($uploadedFile) {

		//Get infos
        $file = array();
		$file['original'] = $uploadedFile->getClientOriginalName();
		$file['size'] = $uploadedFile->getSize();
		$file['mime'] = $uploadedFile->getMimeType();
        
        //Get temp folder and name
        $tempFolder = config('mediatheque.uploadable.tmp_path');
        $tempFilename = uniqid();
        
        //Create directory if doesn't exist
		if(!file_exists($tempFolder)) {
			mkdir($tempFolder, 0755, true);
		}
        
        ///Move uploaded file
        $uploadedFile->move($tempFolder, $tempFilename);
        
        //Set file
        $this->setFile($tempFolder.'/'.$tempFilename, $file);
        
        //Delete temp file
        $tmpPath = $tempFolder.'/'.$tempFilename;
        if(file_exists($tmpPath))
        {
            unlink($tmpPath);
        }
	}
}
