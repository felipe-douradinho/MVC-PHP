<?php

namespace App\Http\Controllers;

use Golden\Http\Request;
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
		try
		{
			$request = collect(Request::all())->except(['uri']);

			if(!$request->has('text') || strlen(trim($request->get('text'))) <= 0)
				throw new \Exception('Erro: Por favor, preencha o campo de texto!', 1);

			// -- set must compare
			$must_compare = $request->has('hash') && strlen(trim($request->get('hash'))) > 0;
			$compare_sha512 = $compare_hmac = $compare_pbkdf2 = '';

			// ----- SHA512
			$sha512 = hash('sha512', $request->get('text'));
			if($must_compare)
			{
				$sha512_crypt = crypt($sha512, 'Fr6q5Pq^r_UCGJj5');
				$hash_crypt = crypt($request->get('hash'), 'Fr6q5Pq^r_UCGJj5');
				$compare_sha512 = (hash_equals($sha512_crypt, $hash_crypt) ? ' [ IGUAL ]' : '[ DIFERENTE ]');
			}
			// ------------

			// ----- HMAC
			$hash_hmac = hash_hmac('sha512', $request->get('text'), 'Rbh_ZYQnT!N6Z4%wC9TUXjYN%#jXj#Gm');
			if($must_compare)
			{
				$hmac_crypt = crypt($hash_hmac, 'Fr6q5Pq^r_UCGJj5');
				$hash_crypt = crypt($request->get('hash'), 'Fr6q5Pq^r_UCGJj5');
				$compare_hmac = (hash_equals($hmac_crypt, $hash_crypt) ? ' [ IGUAL ]' : '[ DIFERENTE ]');
			}
			// ------------

			// ----- PBKDF2
			$hash_pbkdf2 = hash_pbkdf2("sha512", $request->get('text'), 'STATIC-JUST-TO-MT4-TEST', 5000);
			if($must_compare)
			{
				$hash_pbkdf2_crypt = crypt($hash_pbkdf2, 'Fr6q5Pq^r_UCGJj5');
				$hash_crypt = crypt($request->get('hash'), 'Fr6q5Pq^r_UCGJj5');
				$compare_pbkdf2 = (hash_equals($hash_pbkdf2_crypt, $hash_crypt) ? ' [ IGUAL ]' : '[ DIFERENTE ]');
			}
			// ------------

			$output = "Sha512: {$sha512}{$compare_sha512}\n\nHMAC: {$hash_hmac}{$compare_hmac}\n\nPBKDF2: {$hash_pbkdf2}{$compare_pbkdf2}";

			return Response::create([ 'output' => $output, ])->setAjax()->send();
		}
		catch (\Exception $ex)
		{
			return Response::create([
				'error' => $ex->getMessage(),
				'output' => $ex->getMessage(),
			], 500)->setAjax()->send();
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