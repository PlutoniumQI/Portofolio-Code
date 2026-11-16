<?php

namespace App\Filament\Pages;

use App\Services\FrontendRevalidator;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use UnitEnum;

class PublishSite extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $navigationLabel = 'Publish changes';

    protected static ?string $title = 'Publish changes';

    protected static ?string $slug = 'publish';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.publish-site';

    public ?string $lastOutput = null;

    public function publish(FrontendRevalidator $revalidator): void
    {
        $result = $revalidator->revalidate();

        Notification::make()
            ->title($result['ok'] ? 'Live site revalidated' : 'Could not revalidate the site')
            ->body($result['message'])
            ->{$result['ok'] ? 'success' : 'warning'}()
            ->send();
    }

    /**
     * Keep the legacy static export available during the transition. The
     * public frontend reads the API from phase 2 onward.
     */
    public function export(): void
    {
        $exitCode = Artisan::call('portfolio:export');
        $this->lastOutput = trim(Artisan::output());

        Notification::make()
            ->title($exitCode === 0 ? 'Static export written' : 'Export failed')
            ->body($exitCode === 0
                ? 'src/data/portfolio.json and local images were updated.'
                : ($this->lastOutput ?: 'Check the Laravel log for details.'))
            ->{$exitCode === 0 ? 'success' : 'danger'}()
            ->send();
    }
}
