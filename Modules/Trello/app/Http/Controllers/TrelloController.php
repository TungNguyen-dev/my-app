<?php

namespace Modules\Trello\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Trello\Services\TrelloService;

class TrelloController extends Controller
{
    private TrelloService $trelloService;

    public function __construct(TrelloService $trelloService)
    {
        $this->trelloService = $trelloService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|\Illuminate\Foundation\Application|View|Application
    {
        return view('trello::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('trello::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('trello::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('trello::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function import(Request $request): RedirectResponse
    {
        $file = $request->file('file');
        $this->trelloService->importCards($file);

        return back()->with('success', 'All good!');
    }
}
