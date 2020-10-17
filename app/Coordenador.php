<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
/**
 * @property User $user
 * @property int $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Coordenador extends Model
{
    protected $table = 'coordenadores';
    protected $fillable = ['user','ativo'];
    protected $dates = ['created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user','id');
    }
}
