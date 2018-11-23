<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Client extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'client_id';
    //public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];


    /**
     * Get the messages for the client.
     */
    public function messages(){
        return $this->hasMany('App\Models\Message', 'client_id', 'client_id');
    }
    
    /**
     * 
     * @return type
     */
    public function PaginateMessage(){
        return $this->messages()->paginate(10);
    }

}