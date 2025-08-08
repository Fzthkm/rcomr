<?php

namespace App\Http\Controllers;

use App\DTOs\Interfaces\BaseDtoInterface;
use App\Services\Interfaces\BaseServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class BaseController extends Controller
{
    public function __construct(protected BaseServiceInterface $service)
    {
    }

    public function index(Request $request): View
    {
        return view($this->getViewPath('index'), [
            'items' => $this->service->all()
        ]);
    }

    public function show(int $id): View
    {
        return view($this->getViewPath('show'), [
            'item' => $this->service->find($id)
        ]);
    }

    public function create(Request $request): View
    {
        return view($this->getViewPath('create'));
    }

//    public function store(Request $request): RedirectResponse
//    {
//        $dto = $this->buildDtoFromRequest($request);
//        $this->service->create($dto);
//
//        return redirect()->route($this->getRouteBase() . '.index');
//    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $dto = $this->buildDtoFromRequest($request);
        $this->service->update($id, $dto);

        return redirect()->route($this->getRouteBase() . '.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route($this->getRouteBase() . '.index');
    }

    abstract protected function getViewPath(string $action): string;

    abstract protected function getRouteBase(): string;

    abstract protected function buildDtoFromRequest(Request $request): BaseDtoInterface;
}
