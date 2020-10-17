<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $codigo_barras
 * @property int $edicao
 * @property int $ano
 * @property string $observacao
 * @property int $obra
 * @property int $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Exemplar extends Model
{
    protected $table = 'exemplares';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['codigo_barras','edicao','ano','observacao','obra','ativo'];

    public function obra()
    {
        return $this->belongsTo(Obra::class,'obra','id');
    }

    public function emprestimos(){
        return $this->hasMany(Emprestimo::class,'exemplar','id');
    }
}
