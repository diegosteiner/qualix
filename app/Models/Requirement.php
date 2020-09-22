<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $course_id
 * @property string $content
 * @property bool $mandatory
 * @property Course $course
 * @property Observation[] $observations
 * @property Category[] $categories
 */
class Requirement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requirements';

    /**
     * @var array
     */
    protected $fillable = ['course_id', 'content', 'mandatory'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['mandatory' => 'bool'];

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
    public function observations()
    {
        return $this->belongsToMany('App\Models\Observation', 'observations_requirements', 'requirement_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'requirements_categories', 'requirement_id', 'category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function indicators()
    {
        return $this->hasMany('App\Models\Indicator', 'requirement_id');
    }
}
