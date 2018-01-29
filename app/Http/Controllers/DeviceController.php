<?php

namespace App\Http\Controllers;

use App\Entities\Device;
use Golden\Database\Drivers\MySql;
use Golden\Http\Request;
use Golden\Http\Response;
use Golden\Paginator\Paginator;
use Golden\Session\Session;


/**
 * Class DeviceController
 *
 * @package App\Http\Controllers
 */
class DeviceController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 * @throws \Exception
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function index()
	{
		$devices = Device::paginate(
			10,
			Request::has('page') ? Request::get('page') : 1,
			route('devices.index') . '&page=(:num)'
		);

		return view('pages.device_control.index', compact('devices', 'paginator'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function create()
	{
		return view('pages.device_control.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function store()
	{
		$request = collect(Request::all())->except(['uri', 'active']);
		$fillable = (new Device())->getFillable();

		if($request->keys()->diff( $fillable )->count())
		{
			$errors = [ 'Os campos enviados são inválidos' ];
			return view('pages.device_control.create', compact('errors'));
		}

		// -- check if all fields filled
		if($request->filter()->count() < 5)
		{
			$errors = [ 'Por favor, preencha todos os campos!' ];
			return view('pages.device_control.create', compact('errors'));
		}

		try
		{
			MySql::beginTransaction();

			Device::create([
				'hostname'      => Request::get('hostname'),
				'ip_address'    => Request::get('ip_address'),
				'type'          => Request::get('type'),
				'manufacturer'  => Request::get('manufacturer'),
				'model'         => Request::get('model'),
				'active'        => Request::get('active'),
			]);

			MySql::commit();

			return $this->index();
		}
		catch (\Exception $ex)
		{
			MySql::rollBack();
			Session::set($request->toArray());

			$errors = [ 'Desculpe, ocorreu um erro deconhecido!' ];
			return view('pages.device_control.create', compact('errors'));
		}

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
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function edit($id)
	{
		$device = Device::find($id);
		return view('pages.device_control.edit', compact('device'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function update($id)
	{
		$request = collect(Request::all())->except(['uri', 'active']);
		$fillable = (new Device())->getFillable();

		if($request->keys()->diff( $fillable )->count())
		{
			Session::set(['errors' => [ 'Os campos enviados são inválidos' ]]);

			$device = Device::find($id);
			return view('pages.device_control.create', compact('device'));
		}

		// -- check if all fields filled
		if($request->filter()->count() < 5)
		{
			Session::set(['errors' => [ 'Por favor, preencha todos os campos!' ]]);

			$device = Device::find($id);
			return view('pages.device_control.edit', compact('device'));
		}

		try
		{
			MySql::beginTransaction();

			Device::update([
				'hostname'      => Request::get('hostname'),
				'ip_address'    => Request::get('ip_address'),
				'type'          => Request::get('type'),
				'manufacturer'  => Request::get('manufacturer'),
				'model'         => Request::get('model'),
				'active'        => Request::get('active'),
			], [ 'id' => $id ]);

			MySql::commit();

			return $this->index();
		}
		catch (\Exception $ex)
		{
			MySql::rollBack();
			Session::set($request->toArray());

			Session::set(['errors' => [ 'Desculpe, ocorreu um erro deconhecido!' ]]);

			$device = Device::find($id);
			return view('pages.device_control.edit', compact('device'));
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function destroy($id)
	{
		try
		{
			MySql::beginTransaction();
			Device::where(['id' => $id])->delete();
			MySql::commit();

			return $this->index();
		}
		catch (\Exception $ex)
		{
			MySql::rollBack();

			$errors = [ 'Desculpe, ocorreu um erro deconhecido!' ];
			return view('pages.device_control.index', compact('errors'));
		}

	}
}