<?php



return array(
	
	'table_prefix' => 'mediatheque_',
	
	'models' => array(
		
		'Picture' => \Folklore\EloquentMediatheque\Models\Picture::class,
		'Audio' => \Folklore\EloquentMediatheque\Models\Audio::class,
		'Video' => \Folklore\EloquentMediatheque\Models\Video::class,
		'Text' => \Folklore\EloquentMediatheque\Models\Text::class,
		'Metadata' => \Folklore\EloquentMediatheque\Models\Metadata::class,
		'Document' => \Folklore\EloquentMediatheque\Models\Document::class,
		
	),
	
	'programs' => [
		'ffmpeg' => [
			'ffmpeg.binaries'  => env('FFMPEG_BIN', '/usr/local/bin/ffmpeg'),
			'ffprobe.binaries' => env('FFPROBE_BIN', '/usr/local/bin/ffprobe')
		],
		'audiowaveform' => [
			'bin'  => env('AUDIOWAVEFORM_BIN', '/usr/local/bin/audiowaveform')
		]
	],
    
	'uploadable' => array(
		
		'tmp_path' => sys_get_temp_dir()
		
	),
	
	'fileable' => array(
		
		'destination' => '{type}/{date(Y-m-d)}/{id}-{date(his)}.{extension}',
		
		'filesystem' => null,
	
		'path' => public_path().'/files',
		
		'tmp_path' => sys_get_temp_dir(),
		
		'delete_original_file' => false,
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
			'image/svg+xml' => 'svg',
			'image/xml' => 'svg',
			'image/svg' => 'svg',
	        
	        //Audio
			'audio/wave' => 'wav',
			'audio/x-wave' => 'wav',
			'audio/wav' => 'wav',
			'audio/x-wav' => 'wav',
			'audio/mpeg' => 'mp3',
			'audio/mp3' => 'mp3',
	        
	        //Video
			'video/quicktime' => 'mov',
			'video/mp4' => 'mp4',
			'video/webm' => 'webm',
			'video/ogv' => 'ogv',
			'video/avi' => 'avi',
	        
	        //Document
			'application/pdf' => 'pdf'
		)
		
	),
	
	'thumbnailable' => [
		
		'enable' => true,
		
		'tmp_path' => sys_get_temp_dir(),
		
		'video' => [
			'enable' => true,
			'count' => 5,
			'in_middle' => true
		],
		
		'audio' => [
			'enable' => true,
			'zoom' => 600,
			'width' => 1200,
			'height' => 400,
			'axis_label' => false,
			'background_color' => 'FFFFFF00',
			'color' => '000000',
			'border_color' => null,
			'axis_label_color' => null
		],
		
		'document' => [
			'enable' => true,
			'resolution' => 150,
			'quality' => 100,
			'background' => 'white',
			'format' => 'jpeg',
			'font' => __DIR__.'/../fonts/arial.ttf',
			'all_pages' => true
		]
		
	],
	
	'linkable' => array(
		
		'fileable_path' => '/files',
		
	)

);
