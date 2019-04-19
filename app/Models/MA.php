<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $kurs_id
 * @property string $anforderung
 * @property bool $killer
 * @property Kurs $kurs
 * @property Beobachtung[] $beobachtungen
 * @property Block[] $bloecke
 * @property MADetail[] $maDetails
 */
class MA extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ma';

    /**
     * @var array
     */
    protected $fillable = ['kurs_id', 'anforderung', 'killer'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['killer' => 'bool'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kurs()
    {
        return $this->belongsTo('App\Models\Kurs', 'kurs_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function beobachtungen()
    {
        return $this->belongsToMany('App\Models\Beobachtung', 'beobachtung_ma', 'ma_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bloecke()
    {
        return $this->belongsToMany('App\Models\Block');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function maDetails()
    {
        return $this->hasMany('App\Models\MADetail');
    }
}