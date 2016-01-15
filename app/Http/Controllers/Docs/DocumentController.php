<?php  namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;

class DocumentController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth',  ['only' => ['getIndex']]);
		$this->middleware('content.available',  ['only' => ['getIndex']]);
		$this->middleware('docs.oauth',  ['only' => ['getIndex']]);
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('documents.index');
	}

	public function getDocument()
	{
		return view('documents.document');
	}

}
