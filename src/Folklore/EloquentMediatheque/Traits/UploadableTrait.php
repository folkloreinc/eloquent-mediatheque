<?php namespace Folklore\EloquentMediatheque\Traits;

trait UploadableTrait {
    
    public function setUploadedFile($uploadedFile) {

		//Get infos
        $file = array();
		$file['original'] = $uploadedFile->getClientOriginalName();
		$file['size'] = $uploadedFile->getSize();
		$file['mime'] = $uploadedFile->getMimeType();
        
        //Get temp folder and name
        $tmpPath = tempnam(config('mediatheque.uploadable.tmp_path'), 'MEDIATHEQUE');
        $tmpFilename = basename($tmpPath);
        $tmpFolder = dirname($tmpFilename);
        
        ///Move uploaded file
        $uploadedFile->move($tmpFolder, $tmpFilename);
        
        //Set file
        $this->setFile($tmpPath, $file);
        
        //Delete temp file
        if(file_exists($tmpPath))
        {
            unlink($tmpPath);
        }
	}
}
