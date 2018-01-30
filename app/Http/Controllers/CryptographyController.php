<?php

namespace App\Http\Controllers;

use App\Helpers\Encryption\AesSecurity;
use App\Helpers\Encryption\CaesarCipher;
use App\Helpers\Encryption\Custom;
use Golden\Http\Request;
use Golden\Http\Response;


/**
 * Class CryptographyController
 *
 * @package App\Http\Controllers
 */
class CryptographyController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 * @throws \Golden\Exception\ViewWasNotFound
	 */
	public function index()
	{
		return view('pages.cryptography.index');
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

			if(!$request->has('action') || strlen(trim($request->get('action'))) <= 0)
				throw new \Exception('Erro: Por favor, preencha o campo  action!', 1);

			switch ($request->get('action'))
			{
				case 'encrypt':
					$caesar = CaesarCipher::encrypt( $request->get('text'), 3 );
					$aes = AesSecurity::encrypt( '1b!0n#2h5j4$u8y4%g5b2n3&f1v0b*2g5h(3nr)8', $request->get('text') );
					$custom = Custom::encrypt( $request->get('text'), 10 );

					$final = CaesarCipher::encrypt( $request->get('text'), 3 );
					$final = AesSecurity::encrypt( '1b!0n#2h5j4$u8y4%g5b2n3&f1v0b*2g5h(3nr)8', $final );
					$final = Custom::encrypt( $final, 10 );

					$details = "Caesar: {$caesar}\nAes256: {$aes}\nCustom: {$custom}\n\nJuntos em sequencia: {$final}";

					return Response::create([ 'details' => $details, 'final' => $final ])->setAjax()->send();

				case 'decrypt':
					$caesar = Custom::decrypt( $request->get('text'), 10 );
					$aes = AesSecurity::decrypt( '1b!0n#2h5j4$u8y4%g5b2n3&f1v0b*2g5h(3nr)8', $caesar );
					$custom = CaesarCipher::decrypt( $aes, 3 );

					$final = Custom::decrypt( $request->get('text'), 10 );
					$final = AesSecurity::decrypt( '1b!0n#2h5j4$u8y4%g5b2n3&f1v0b*2g5h(3nr)8', $final );
					$final = CaesarCipher::decrypt( $final, 3 );

					$details = "Caesar: {$caesar}\nAes256: {$aes}\nCustom: {$custom}\n\nResultado: {$final}";

					return Response::create([ 'details' => $details, 'final' => $final, ])->setAjax()->send();
			}

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