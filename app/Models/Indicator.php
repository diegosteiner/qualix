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
    protected $fillable = ['course_id', 'requirement_id', 'content'];

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
    public function requirement()
    {
        return $this->belongsTo('App\Models\Requirement', 'requirement_id');
    }

}
