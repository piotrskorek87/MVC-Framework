 <?php 

class Likeable extends Model{

	public $table = 'likeable';


	public function __construct(){
		parent::__construct();
	}	

    public function user(){
        return $this->belongsTo('User', '', 'user_id', '')->get()->first();
    }  

}