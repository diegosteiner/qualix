<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $content
 * @property Requirement $requirement
 */
class Indicator extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'indicators';

    /**
     * @var array
     */
    protected $fillable = ['course_id', 'content'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requirements()
    {
        return $this->belongsToMany('App\Models\Requirement', 'requirements_indicators', 'indicator_id', 'requirement_id');
    }

}
