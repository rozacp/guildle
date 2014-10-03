<?php namespace Guildle\Http\Controllers;

use Illuminate\Routing\Controller;
use Guildle\Faq;
use Input;
use Redirect;

class FaqController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$faqs = Faq::all();

		return View('faq.index', compact('faqs'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View('faq.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$faq = new Faq();
		$faq->title = Input::get('title');
		$faq->body = Input::get('body');
		$faq->save();

		return Redirect::route('faq.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$faq = Faq::find($id);

		return View('faq.show', compact('faq'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$faq = Faq::find($id);

		return View('faq.edit', compact('faq'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$faq = Faq::find($id);

		$faq->title = Input::get('title');
		$faq->body = Input::get('body');
		$faq->save();

		return View('faq.show', compact('faq'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$faq = Faq::find($id);

		$faq->delete();

		return Redirect::route('faq.index');

	}

}
