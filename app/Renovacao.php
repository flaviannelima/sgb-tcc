<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $emprestimo
 * @property Carbon $data_prevista_devolucao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Renovacao extends Model
{
    protected $table = 'renovacoes';
    protected $dates = ['created_at','updated_at','data_prevista_devolucao'];
    protected $fillable = ['emprestimo','data_prevista_devolucao','usuario_renovou'];

    public function usuarioRenovou()
    {
        return $this->belongsTo(User::class,'usuario_renovou','id');
    }

}
