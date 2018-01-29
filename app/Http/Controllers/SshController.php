<?php

namespace App\Http\Controllers;

use App\Entities\Device;
use Golden\Foundation\Application;
use Golden\Http\Request;
use Golden\Http\Response;
use Golden\Session\Session;
use Net_SSH2;


/**
 * Class SshController
 *
 * @package App\Http\Controllers
 */
class SshController extends Controller
{

	/**
	 * SshController constructor.
	 */
	public function __construct()
	{
		set_include_path(Application::getInstance()->getBasePath() . '/vendor/phpseclib');
		include_once 'Net/SSH2.php';
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function index()
	{
//		if(Session::has('ssh'))
//			return view('pages.ssh.commands');

		$devices = Device::where(['active' => 1])->get();
		return view('pages.ssh.index', compact('devices'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Connect to the shell terminal
	 *
	 * @return Response
	 */
	public function shell()
	{
		try
		{
			$request = collect(Request::all())->except(['uri']);

			// -- check if all fields filled
			if($request->filter()->count() < 3)
				throw new \Exception('Erro: Por favor, preencha todos os campos!', 1);

			if( $device = Device::find( $request->get('device_id')) )
			{
				$ssh = new Net_SSH2($device->ip_address);

				if (!$ssh->login($request->get('username'), $request->get('password')))
					throw new \Exception('Erro: Usuário ou senha inválidos', 1);

				if($request->has('command') && strlen(trim($request->get('command'))))
				{
					$output = $ssh->exec( $request->get('command') );

					return [
						'status' => 'Comando executado com sucesso',
						'output' => $output,
					];
				}

				return [
					'status' => 'Conectado com sucesso',
					'output' => 'Conectado. Aguardando comando...',
				];
			}
		}
		catch (\Exception $ex)
		{
			return Response::create(['error' => $ex->getMessage(), 500])->setAjax()->send();
		}
	}

}