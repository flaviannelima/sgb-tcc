<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nome
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Editora extends Model
{
    protected $table = 'editoras';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['nome'];
    
    public function obras()
    {
        return $this->hasMany(Obra::class, 'editora','id');
    }
}
