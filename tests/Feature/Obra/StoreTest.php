<?php

namespace Tests\Feature\Obra;

use App\Editora;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Obra;
use App\TipoMaterial;
use Carbon\Carbon;

class StoreTest extends TestCase
{
   

    private function storeObra(Obra $obra = null)
    {
        $obra = $obra ?? collect([]);
        //$this->withoutExceptionHandling();
        return $this->post(route('obras.store'),$obra->toArray());
    }

    /**
     * @test
     */
    public function usuario_deve_estar_autenticado_ser_coordenador()
    {
        $obra = $this->obra()->make();
        
        $this->storeObra($obra)->assertRedirect('/login');
        

        $user = $this->user()->create();
        $this->actingAs($user)
        ->storeObra($obra)
        ->assertStatus(403);  

    }
   

    /**
     * @test 
     * */
    public function campo_tipo_material_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setTipoMaterial(null)->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['tipo_material' => trans('validation.required',['attribute' => 'tipo de material'])]);
    }

    /**
     * @test 
     * */
    public function campo_categoria_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setCategoria(null)->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors(['categoria' => trans('validation.required',['attribute' => 'categoria'])]);
    }

    /**
     * @test 
     * */
    public function campo_titulo_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setTitulo('')->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['titulo' => 
        trans('validation.required',['attribute' => 'titulo'])]);
    }

    /**
     * @test 
     * */
    public function campo_editora_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setEditora(NULL)->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['editora' => 
        trans('validation.required',['attribute' => 'editora'])]);
    }

     /**
     * @test 
     * */
    public function campo_localizacao_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setLocalizacao('')->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['localizacao' => 
        trans('validation.required',['attribute' => 'localizacao'])]);
    }


    /**
     * @test 
     * */
    public function campo_autores_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setAutores(null)->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['autores' => 
        trans('validation.required',['attribute' => 'autores'])]);
    }

    /**
     * @test 
     * */
    public function campo_assuntos_obrigatorio()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setAssuntos(null)->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['assuntos' => 
        trans('validation.required',['attribute' => 'assuntos'])]);
    }


    /**
     * @test 
     * */
    public function campo_localizaca_deve_ter_14_caracteres()
    {
       
        /**@var Obra $obra */
        $obra = $this->obra()->setLocalizacao('fa')->make();
        
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['localizacao' => 
        trans('validation.min.string',['attribute' => 'localizacao','min' => 14])]);

        $obra = $this->obra()->setLocalizacao('fafffffffffffffffffffffffffffjjjjjj')->make();
        $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['localizacao' => 
        trans('validation.max.string',['attribute' => 'localizacao','max' => 14])]);
    }

    /**
     * @test 
     * */
    public function campo_volume_nao_e_obrigatorio()
    {
        
        Carbon::setTestNow(now());

        /**@var Obra $obra */
        $obra = $this->obra()->setVolume(null)->make();
        $obra->autores = $this->autor()->create(3);
        $obra->assuntos = $this->assunto()->create(3);
        $autores = [];
        foreach($obra->autores as $autor){
            $autores[] = $autor->id;
        }
        $obra->autores = $autores;

        $assuntos = [];
        foreach($obra->assuntos as $assunto){
            $assuntos[] = $assunto->id;
        }
        $obra->assuntos = $assuntos;


        $user = $this->coordenador()->create()->user()->first();
          $this->actingAs($user)
        ->storeObra($obra)->assertSessionDoesntHaveErrors(['volume' => 
        trans('validation.required',['attribute' => 'volume'])]);
      
        $this->tem_no_banco($obra);
    }


    /**
     * @test 
     * */
    public function campo_observacao_nao_e_obrigatorio()
    {
        
        Carbon::setTestNow(now());

        /**@var Obra $obra */
        $obra = $this->obra()->setObservacao(null)->make();
        $obra->autores = $this->autor()->create(3);
        $obra->assuntos = $this->assunto()->create(3);
        $autores = [];
        foreach($obra->autores as $autor){
            $autores[] = $autor->id;
        }
        $obra->autores = $autores;

        $assuntos = [];
        foreach($obra->assuntos as $assunto){
            $assuntos[] = $assunto->id;
        }
        $obra->assuntos = $assuntos;


        $user = $this->coordenador()->create()->user()->first();
          $this->actingAs($user)
        ->storeObra($obra)->assertSessionDoesntHaveErrors(['observacao' => 
        trans('validation.required',['attribute' => 'observacao'])]);
      
        $this->tem_no_banco($obra);
    }


    /**
     * @test 
     * */
    public function tipo_material_deve_estar_cadastrado_previamente()
    {
       
       
        /**@var Obra $obra */
        $obra = $this->obra()->setTipoMaterial(-1)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)->assertSessionHasErrors(['tipo_material' => trans('validation.exists',['attribute' => 'tipo de material'])]);
    }

    /**
     * @test 
     * */
    public function categoria_deve_estar_cadastrado_previamente()
    {
       
       
        /**@var Obra $obra */
        $obra = $this->obra()->setCategoria(-1)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors(['categoria' => 
        trans('validation.exists',['attribute' => 'categoria'])]);
    }

    

    /**
     * @test 
     * */
    public function editora_deve_estar_cadastrada_previamente()
    {
       
       
        /**@var Obra $obra */
        $obra = $this->obra()->setEditora(-1)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors(['editora' => 
        trans('validation.exists',['attribute' => 'editora'])]);
    }

    
    /**
     * @test 
     * */
    public function autor_deve_estar_cadastrado_previamente()
    {
        $autores = $this->autor()->make(3);
        $autoresArray = [];
        $a = $this->autor()->create();
        $autoresArray[]= $a->id;
        foreach($autores as $autor){
            $autoresArray[] = $autor->id;
        }
       
        /**@var Obra $obra */
        $obra = $this->obra()->setAutores($autoresArray)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors(['autores' => 
        trans('validation.exists',['attribute' => 'autores'])]);
    }

    /**
     * @test 
     * */
    public function assunto_deve_estar_cadastrado_previamente()
    {
        $assuntos = $this->assunto()->make(3);
        $assuntosArray = [];
        $a = $this->assunto()->create();
        $assuntosArray[]= $a->id;
        foreach($assuntos as $assunto){
            $assuntosArray[] = $assunto->id;
        }
       
        /**@var Obra $obra */
        $obra = $this->obra()->setAssuntos($assuntosArray)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors(['assuntos' => 
        trans('validation.exists',['attribute' => 'assuntos'])]);
    }

    /**
     * @test 
     * */
    public function atores_devem_ser_distintos()
    {
        Carbon::setTestNow(now());
        $autores = $this->autor()->create(3);
        $autoresArray = [];
      
        foreach($autores as $autor){
            $autoresArray[] = $autor->id;
        }
        $autoresArray[] = $autoresArray[0];
      
        /**@var Obra $obra */
        $obra = $this->obra()->setAutores($autoresArray)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors();
         $this->assertDatabaseMissing('obras',[
            'tipo_material' => $obra->tipo_material,
            'titulo' => $obra->titulo,
            'editora' => $obra->editora,
            'volume' => $obra->volume,
            'observacao' => $obra->observacao,
            'localizacao' => $obra->localizacao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * @test 
     * */
    public function assuntos_devem_ser_distintos()
    {
        Carbon::setTestNow(now());
        $assuntos = $this->assunto()->create(3);
        $assuntosArray = [];
      
        foreach($assuntos as $assunto){
            $assuntosArray[] = $assunto->id;
        }
        $assuntosArray[] = $assuntosArray[0];
      
        /**@var Obra $obra */
        $obra = $this->obra()->setAssuntos($assuntosArray)->make();
       
        $user = $this->coordenador()->create()->user()->first();
         $this->actingAs($user)
        ->storeObra($obra)
        ->assertSessionHasErrors();
         $this->assertDatabaseMissing('obras',[
            'tipo_material' => $obra->tipo_material,
            'titulo' => $obra->titulo,
            'editora' => $obra->editora,
            'volume' => $obra->volume,
            'observacao' => $obra->observacao,
            'localizacao' => $obra->localizacao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * @test 
     * */
    public function deve_salvar_no_banco_e_redirecionar_para_pagina_index()
    {
        
        Carbon::setTestNow(now());
        $this->withoutExceptionHandling();
        /**@var Obra $obra */
        $obra = $this->obra()->make();
        $obra->autores = $this->autor()->create(3);
        $obra->assuntos = $this->assunto()->create(3);
        $autores = [];
        foreach($obra->autores as $autor){
            $autores[] = $autor->id;
        }
        $obra->autores = $autores;

        $assuntos = [];
        foreach($obra->assuntos as $assunto){
            $assuntos[] = $assunto->id;
        }
        $obra->assuntos = $assuntos;

    
        $user = $this->coordenador()->create()->user()->first();
          $this->actingAs($user)
        ->storeObra($obra)->assertRedirect(route('obras.index'));
      
       $this->tem_no_banco($obra);
    }

    private function tem_no_banco(Obra $obra){
        $this->assertDatabaseHas('obras',[
            'tipo_material' => $obra->tipo_material,
            'titulo' => $obra->titulo,
            'editora' => $obra->editora,
            'volume' => $obra->volume,
            'observacao' => $obra->observacao,
            'localizacao' => $obra->localizacao,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $o = Obra::first();
        foreach($obra->autores as $autor){
            $this->assertDatabaseHas('autores_obras',[
                'obra' => $o->id,
                'autor' => $autor
            ]);
        }

        foreach($obra->assuntos as $assunto){
            $this->assertDatabaseHas('assuntos_obras',[
                'obra' => $o->id,
                'assunto' => $assunto
            ]);
        }
    }
}
