<?php 
require_once 'Status.php';

class User extends Model{

    public $id, $username, $password, $salt, $location, $name, $joined, $group;

	public $table = 'user';

	public $dbFields = ['username',  'password', 'salt', 'name', 'group']; //remove


	public function __construct(){
		parent::__construct();
	}

	public function getUsername(){
		return $this->username;
	}

	public function getNameOrUsername(){
		return ($this->name) ? $this->first_name : $this->getUsername(); //first name
	}

    public function getAvatarUrl(){
        return "https://www.gravatar.com/avatar/". md5($this->email). "?d=mm&s=40";
    }

    public function statuses(){
        return $this->hasMany('Status', '', 'user_id', '')->get();
    }

    public function likeStatus($id){
        $this->morphMany('likeable')->create(array(
                                            'user_id' => $this->id,
                                            'likeable_id' => $id,
                                            'likeable_type' => 'status',
                                            )); //improve that
    }

	public function friendOf(){
		return $this->hasMany('User', 'friend', 'friend_id', 'user_id');
	}

	public function friendsOfMine(){
		return $this->hasMany('User', 'friend', 'user_id', 'friend_id');
	}

    public function friends(){
    	return $this->friendsOfMine()->wherePivot(array('accepted', '=', 'true'))->get()->merge()->friendOf()->wherePivot(array('accepted', '=', 'true'))->get();
    }

    public function friendRequests(){
        return $this->friendsOfMine()->wherePivot(array('accepted', '=', 'false'))->get();
    }

    public function friendRequestsPending(){
        return $this->friendOf()->wherePivot(array('accepted', '=', 'false'))->get();
    }

    public function hasFriendRequestReceived($user){
        return (bool)$this->friendRequests()->where(array('id', '=', $user->id))->count();
    }

    public function hasFriendRequestPending($user){
        return (bool)$this->friendRequestsPending()->where(array('id', '=', $user->id))->count();
    }

    public function addFriend($user){
		$this->hasMany('User', 'friend', 'friend_id', 'user_id')->attach($user->id); //correct that 
    }

    public function deleteFriend($user){
        $this->friendOf()->detach($user->id);
        $this->friendsOfMine()->detach($user->id);
    }

    public function acceptFriendRequest($user){
        $this->friendRequests()->where(array('id', '=', $user->id))->pivot()->update(array(
																					'accepted' => 'true'
																					));
    }

    public function isFriendsWith($user){
        return (bool)$this->friends()->where(array('id', '=', $user->id))->count();
    }

    public function hasLikedStatus($status){
        return (bool) $status->likes()->where(array('user_id', '=', $this->id))->count();
    }

}
?>

