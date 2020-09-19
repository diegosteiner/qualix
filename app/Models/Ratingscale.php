<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $course_id
 * @property string $name
 * @property Course $course
 * @property Rating[] $ratings
 */
class Ratingscale extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratingscales';

    /**
     * @var array
     */
    protected $fillable = ['course_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function ratings()
    {
        return $this->hasMany('App\Models\Rating');
    }
}
