<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $nome
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Autor extends Model
{
    protected $table = 'autores';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['nome'];

    public function obras()
    {
        return $this->belongsToMany(Obra::class,'autores_obras','autor','obra');
    }
}
