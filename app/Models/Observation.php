<?php

namespace App\Models;

/**
 * @property int $id
 * @property int participant_id
 * @property int $block_id
 * @property int $user_id
 * @property int $impression
 * @property string $content
 * @property Block $block
 * @property Participant $participant
 * @property User $user
 * @property Requirement[] $requirements
 * @property Category[] $categories
 */
class Observation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'observations';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'impression', 'content'];
    protected $fillable_relations = ['block'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo('App\Models\Block');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany('App\Models\Participant', 'observations_participants');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function requirements()
    {
        return $this->belongsToMany('App\Models\Requirement', 'observations_requirements', 'observation_id', 'requirement_id');
    }

}
