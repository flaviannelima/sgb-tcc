<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int $id
 * @property int $tipo_material
 * @property int $categoria
 * @property string $titulo
 * @property int $editora
 * @property int $volume
 * @property string $observacao
 * @property string $localizacao
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Obra extends Model
{
    protected $table = 'obras';
    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['tipo_material', 'categoria',
    'titulo','editora', 'volume', 'localizacao','observacao','ativo'];

    public function tipoMaterial()
    {
        return $this->belongsTo(TipoMaterial::class,'tipo_material','id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class,'categoria','id');
    }

    public function editora()
    {
        return $this->belongsTo(Editora::class,'editora','id');
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class,'autores_obras','obra','autor');
    }

    public function assuntos()
    {
        return $this->belongsToMany(Assunto::class,'assuntos_obras','obra','assunto');
    }

    public function exemplares(){
        return $this->hasMany(Exemplar::class,'obra','id');
    }

    public function exemplaresDisponiveis(){
        return $this->hasMany(Exemplar::class,'obra','id')->where('ativo',1)->whereDoesntHave('emprestimos', 
        function ( $query) {
            $query->whereNull('data_devolucao');
        });
    }
}
