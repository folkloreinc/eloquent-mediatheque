<?php



return array(
	
	'table_prefix' => 'mediatheque_',
    
	'uploadable' => array(
		
		'tmp_path' => public_path().'/files/tmp',
		
	),
	
	'fileable' => array(
		
		'path' => public_path().'/files',
		
		'link' => '/files',
		
		'delete_file_on_delete' => false,
	    'delete_file_on_update' => false,
		
		'mime_to_extension' => array(
	        //Image
			'image/jpeg' => 'jpg',
			'image/jpg' => 'jpg',
			'image/png' => 'png',
			'image/x-png' => 'png',
			'image/gif' => 'gif',
			'image/x-gif' => 'gif',
	        
	        //Audio
			'audio/wave' => 'wav',
			'audio/x-wave' => 'wav',
			'audio/wav' => 'wav',
			'audio/x-wav' => 'wav',
			'audio/mpeg' => 'mp3',
			'audio/mp3' => 'mp3'
		)
		
	),
	
	'linkable' => array(
		
		'fileable_path' => '/files',
		
	)

);
