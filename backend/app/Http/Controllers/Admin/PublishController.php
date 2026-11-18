<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FrontendRevalidator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublishController extends Controller
{
    public function index(): View
    {
        return view('admin.publish', [
            'url' => config('portfolio.frontend.revalidate_url'),
            'configured' => filled(config('portfolio.frontend.revalidate_url'))
                && filled(config('portfolio.frontend.revalidate_secret')),
        ]);
    }

    public function publish(FrontendRevalidator $revalidator): RedirectResponse
    {
        $result = $revalidator->revalidate();

        return redirect()
            ->route('admin.publish.index')
            ->with($result['ok'] ? 'status' : 'error', $result['message']);
    }
}
