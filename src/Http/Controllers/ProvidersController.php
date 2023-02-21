<?php

namespace Corals\Modules\SMS\Http\Controllers;

use Corals\Foundation\Http\Controllers\BaseController;
use Corals\Modules\SMS\DataTables\ProvidersDataTable;
use Corals\Modules\SMS\Http\Requests\ProviderRequest;
use Corals\Modules\SMS\Models\Provider;
use Corals\Modules\SMS\Services\ProviderService;
use Illuminate\Http\Request;

class ProvidersController extends BaseController
{
    protected $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;

        $this->resource_url = config('sms.models.provider.resource_url');

        $this->resource_model = new Provider();

        $this->title = trans('SMS::module.provider.title');
        $this->title_singular = trans('SMS::module.provider.title_singular');

        parent::__construct();
    }

    /**
     * @param ProviderRequest $request
     * @param ProvidersDataTable $dataTable
     * @return mixed
     */
    public function index(ProviderRequest $request, ProvidersDataTable $dataTable)
    {
        return $dataTable->render('SMS::providers.index');
    }

    /**
     * @param ProviderRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(ProviderRequest $request)
    {
        $provider = new Provider();

        $this->setViewSharedData(['title_singular' => trans('Corals::labels.create_title', ['title' => $this->title_singular])]);

        return view('SMS::providers.create_edit')->with(compact('provider'));
    }

    /**
     * @param ProviderRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ProviderRequest $request)
    {
        try {
            $provider = $this->providerService->store($request, Provider::class);

            flash(trans('Corals::messages.success.created', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Provider::class, 'store');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param ProviderRequest $request
     * @param Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ProviderRequest $request, Provider $provider)
    {
        $this->setViewSharedData([
            'title_singular' => trans('Corals::labels.show_title', ['title' => $provider->getIdentifier()]),
            'showModel' => $provider,
        ]);

        return view('SMS::providers.show')->with(compact('provider'));
    }

    /**
     * @param ProviderRequest $request
     * @param Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProviderRequest $request, Provider $provider)
    {
        $this->setViewSharedData(['title_singular' => trans('Corals::labels.update_title', ['title' => $provider->getIdentifier()])]);

        return view('SMS::providers.create_edit')->with(compact('provider'));
    }

    /**
     * @param ProviderRequest $request
     * @param Provider $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ProviderRequest $request, Provider $provider)
    {
        try {
            $this->providerService->update($request, $provider);

            flash(trans('Corals::messages.success.updated', ['item' => $this->title_singular]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, Provider::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    /**
     * @param ProviderRequest $request
     * @param Provider $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProviderRequest $request, Provider $provider)
    {
        try {
            $this->providerService->destroy($request, $provider);

            $message = ['level' => 'success', 'message' => trans('Corals::messages.success.deleted', ['item' => $this->title_singular])];
        } catch (\Exception $exception) {
            log_exception($exception, Provider::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }

        return response()->json($message);
    }

    /**
     * @param Request $request
     * @param Provider $provider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function renderProviderKeys(Request $request, Provider $provider)
    {
        abort_if(!$request->ajax(), 404);
        return $this->providerService->renderProviderKeys($provider, $request->get('provider_key'));
    }
}
