<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $emprestimo
 * @property int $valor_multa
 * @property double $valor_pago
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Multa extends Model
{
    protected $table = 'multas';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['emprestimo','valor_multa','valor_pago'];

    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class,'emprestimo','id');
    }
}
