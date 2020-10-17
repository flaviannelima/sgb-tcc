<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property User $user
 * @property int $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Atendente extends Model
{
    protected $table = 'atendentes';
    protected $fillable = ['user','ativo'];
    protected $dates = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user','id');
    }
}
