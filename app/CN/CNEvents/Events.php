<?php namespace App\CN\CNEvents;

use Illuminate\Database\Eloquent\Model;

class Events extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * primary key override
     * @var string
     */
    protected $primaryKey ='eventId';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['eventTitle','eventDesc','eventDate','eventTime'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = ['msg_read'];

}
