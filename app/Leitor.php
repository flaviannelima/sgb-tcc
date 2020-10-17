<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property string $cpf
 * @property Carbon $data_nascimento
 * @property string $endereco
 * @property string $telefone_residencial
 * @property string $celular
 * @property int $user
 * @property int $ativo
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Leitor extends Model
{
    protected $table = 'leitores';
    protected $fillable = ['cpf','data_nascimento','endereco','telefone_residencial','celular',
    'user','ativo'];
    protected $dates = ['data_nascimento','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user','id');
    }

    public function emprestimos(){
        return $this->hasMany(Emprestimo::class,'leitor','id');
    }
}
