<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $descricao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Assunto extends Model
{
    protected $table = 'assuntos';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['descricao'];

    public function obras()
    {
        return $this->belongsToMany(Obra::class,'assuntos_obras','assunto','obra');
    }
}
