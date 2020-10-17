<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $descricao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TipoMaterial extends Model
{
    protected $table = 'tipos_material';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['descricao'];

    public function obras()
    {
        return $this->hasMany(Obra::class, 'tipo_material','id');
    }
}
