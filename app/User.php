<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use App\Followable;
use App\Tweet;
Use App\User;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tweets()
    {
            return $this->hasMany('App\Tweet')->latest();
    }

    
    public function getAvatarAttribute()
    {
            // return "https://i.pravatar.cc/150?u" . $this->email;
            return "https://i.pravatar.cc/200?u=" . $this->email;
    }
    public function timeline()
    {
            
        // $ids=User::find($this->id)->follows->pluck('id');
        // $ids->push($this->id);
        // return Tweet::where('user_id', $ids)->latest()->get();

        
        $friends=$this->follows->pluck('id');//get the ids of his friends
    
        return Tweet::whereIn('user_id', $friends)
        ->orWhere('user_id', $this->id)
        ->latest()->get();//get the tweets of user as well as his friends
    
    }
    

    public function toggleFollow(User $user)
    {
        // Tip: You can also use the toggle() method.
        //      We'll cover this in the next episode.
        //      $this->follows()->toggle($user);

        if ($this->following($user)) {
            return $this->unfollow($user);
        }else{
            return $this->follow($user);
        }
    }
    
    public function follow(User $user)
    {
        return $this->follows()->save($user);
    }

    

    public function unfollow(User $user)
    {
        return $this->follows()->detach($user);
    }
    
    public function following(User $user)
    {
        return $this->follows()
            ->where('following_user_id', $user->id)
            ->exists();
    }

    public function follows()
    {
        return $this->belongsToMany('App\User', 'follows','user_id', 'following_user_id');
        // 

    }
    public function getRouteKeyName(){
        return 'name';
    }

    public function path($append='')
    {
        $path=route('profile', $this->name);

        return $append ? "{$path}/{$append}" : $path;
    }
}
