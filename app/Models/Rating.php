<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $course_id
 * @property string $name
 * @property Course $course
 * @property Ratingscale $ratingscale
 */
class Rating extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ratings';

    /**
     * @var array
     */
    protected $fillable = ['course_id', 'ratingscale_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ratingscale()
    {
        return $this->belongsTo('App\Models\Ratingscale', 'ratingscale_id');
    }
}
