<?php namespace Folklore\EloquentMediatheque\Models;

class TextTranslation extends Model {

    protected $table = 'texts_translations';
    
    protected $visible = array(
        'locale',
        'content'
    );
    
    protected $fillable = array(
        'locale',
        'content'
    );
    
    /**
     * Query scopes
     */
    public function scopeSearch($query, $text)
    {
        $query->where(function($query) use ($text) {
			$query->where('content', 'LIKE', '%'.$text.'%');
		});
        
        return $query;
    }
}
