<?php

namespace STS\Services\Notifications\Models;

use Illuminate\Database\Eloquent\Model;
use STS\User;

class DatabaseNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = ['user_id', 'type'];

    protected $hidden = [];

    protected $via = [];

    protected $_attributes = null;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plain_values()
    {
        return $this->hasMany(ValueNotification::class, 'notification_id')->with("value");
    }

    public function attributes()
    {
        // [MEJORA] $_attributes=new stdClass();
        if ($this->_attributes) {

            return $this->_attributes;
        }

        $this->_attributes = [];
        $plains_values = $this->plain_values;
        foreach ($plains_values as $plain) {
            if ($model = $plain->value) {
                $this->_attributes[$plain->key] = $model;
            } else {
                $this->_attributes[$plain->key] = $plain->value_text;
            }
        }

        return $this->_attributes;
    }
}
