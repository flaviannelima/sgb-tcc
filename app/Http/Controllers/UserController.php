<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\AlterarSenhaRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDF;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(9);
        return view('users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect()->route('users.index')
            ->with('success', 'Usuário ' . $user->name . ' cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!$user->ativo) {
            return redirect()->route('users.index')->withErrors(['msg' => 'Este usuário não pode ser 
            editado, pois está desativado']);
        }

        if (count($user->coordenador()->get()) && (!count(Auth::user()->coordenador()->get()))) {

            return redirect()->route('users.index')
                ->withErrors(['msg' => 'Você não possui permissão para alterar esse usuário.']);
        }
        if (count($user->atendente()->get()) && (!count(Auth::user()->coordenador()->get()))) {

            return redirect()->route('users.index')
                ->withErrors(['msg' => 'Você não possui permissão para alterar esse usuário.']);
        }
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        if (!$user->ativo) {
            return redirect()->route('users.index')->withErrors(['msg' => 'Este usuário não pode ser 
            editado, pois está desativado']);
        }

        if (count($user->coordenador()->get()) && (!count($request->user()->coordenador()->get()))) {

            return redirect()->route('users.index')
                ->withErrors(['msg' => 'Você não possui permissão para alterar esse usuário.']);
        }
        if (count($user->atendente()->get()) && (!count($request->user()->coordenador()->get()))) {

            return redirect()->route('users.index')
                ->withErrors(['msg' => 'Você não possui permissão para alterar esse usuário.']);
        }
        if ($request->password) {

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
        } else
            $user->update($request->except(['password']));

        return redirect()->route('users.index')
            ->with('success', 'Usuário ' . $user->name . ' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $user->ativo = false;
        $user->save();
        return redirect()->route('users.index')
            ->with('success', 'Usuário ' . $user->name . ' desativado com sucesso!');
    }

    /**
     * Busca e mostra os usuários que contém os termos pesquisados.
     *
     * @return \Illuminate\Http\Response
     */
    public function busca(Request $request)
    {

        $busca = $this->pesquisa($request);

        $users = $busca->orderBy('name')->paginate(9);

        return view('users.index', ['users' => $users, 'request' => $request]);
    }

    private function pesquisa(Request $request)
    {
        $busca = User::query();
        if ($request->name)
            $busca->where('name', 'LIKE', '%' . $request->name . '%');
        if ($request->email)
            $busca->where('email', 'LIKE', '%' . $request->email . '%');
        if ($request->cadastro)
            $busca->whereHas($request->cadastro);
        if ($request->situacao != "" && $request->situacao != null)
            $busca->where('ativo', $request->situacao);
        return $busca;
    }

    public function ativa(User $user)
    {
        $user->ativo = true;
        $user->save();
        return redirect()->route('users.index')
            ->with('success', 'Usuário ' . $user->name . ' ativado com sucesso!');
    }

    public function pdf(Request $request)
    {
        $users = $this->pesquisa($request)->orderBy('name')->get();
        $pdf = PDF::loadView('users.pdf', ['users' => $users]);
        if ($request->cadastro != "leitor") {
            $pdf = PDF::loadView('users.pdf', ['users' => $users]);
            return $pdf->setPaper('a4')->download('usuarios.pdf');
        } else {
            $pdf = PDF::loadView('leitores.pdf', ['users'=>$users]);
            return $pdf->setPaper('a4', 'landscape')->download('leitores.pdf');
        }
    }

    public function alterarSenha(AlterarSenhaRequest $request)
    {
        if (!Hash::check($request->senha_atual, $request->user()->password)) {
            return redirect()->route('users.alterarSenha')
                ->withErrors(['msg' => 'Senha atual inválida.']);
        }
        $user = $request->user();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('users.alterarSenha')
            ->with(['success' => 'Senha alterada com sucesso!']);
    }
}
