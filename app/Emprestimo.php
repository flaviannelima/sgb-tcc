<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $exemplar
 * @property int $leitor
 * @property int $usuario_emprestou
 * @property int $usuario_devolveu
 * @property Carbon $data_prevista_devolucao
 * @property Carbon $data_devolucao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Emprestimo extends Model
{
    protected $table = 'emprestimos';
    protected $dates = ['created_at','updated_at','data_prevista_devolucao','data_devolucao'];
    protected $fillable = ['exemplar','leitor','usuario_emprestou','usuario_devolveu',
    'data_prevista_devolucao','data_devolucao'];

    public function exemplar()
    {
        return $this->belongsTo(Exemplar::class,'exemplar','id');
    }

    public function leitor()
    {
        return $this->belongsTo(Leitor::class,'leitor','id');
    }

    public function usuarioEmprestou()
    {
        return $this->belongsTo(User::class,'usuario_emprestou','id');
    }

    public function usuarioDevolveu()
    {
        return $this->belongsTo(User::class,'usuario_devolveu','id');
    }


    public function renovacoes(){
        return $this->hasMany(Renovacao::class,'emprestimo','id');
    }

    public function multa()
    {
        return $this->belongsTo(Multa::class,'id','emprestimo');
    }
}
