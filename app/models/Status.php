<?php 

class Status extends Model{

	public $table = 'status';

    public $id, $body, $parent_id, $created_at;

	public function __construct(){
		parent::__construct();
	}	

    public function user(){
        return $this->belongsTo('User', '', 'user_id', '')->get()->first();
    }

    public function replies(){
        return $this->hasMany('Status', '', 'parent_id', '')->get()->data();
    }  

   public function likes(){
        return $this->morphMany('likeable')->get();
    }    
}
?>