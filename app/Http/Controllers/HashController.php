<?php

namespace App\Http\Controllers;

use Golden\Http\Response;


/**
 * Class HashController
 *
 * @package App\Http\Controllers
 */
class HashController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function index()
	{
		return view('pages.hash.index');
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
}