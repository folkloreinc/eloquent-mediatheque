<?php namespace Folklore\EloquentMediatheque\Traits;

trait UploadableTrait {
    
    public function setUploadedFile($uploadedFile) {

		//Get infos
        $file = array();
		$file['original'] = $uploadedFile->getClientOriginalName();
		$file['size'] = $uploadedFile->getSize();
		$file['mime'] = $uploadedFile->getMimeType();
        
        //Get temp folder and name
        $tmpPath = tempnam(config('mediatheque.uploadable.tmp_path', sys_get_temp_dir()), 'MEDIATHEQUE');
        $tmpFilename = basename($tmpPath);
        $tmpFolder = dirname($tmpPath);
        
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
