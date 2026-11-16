<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use UnitEnum;

class PublishSite extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRocketLaunch;

    protected static ?string $navigationLabel = 'Publish to site';

    protected static ?string $title = 'Publish to site';

    protected static ?string $slug = 'publish';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.publish-site';

    public ?string $lastOutput = null;

    public function publish(): void
    {
        $exitCode = Artisan::call('portfolio:export');
        $this->lastOutput = trim(Artisan::output());

        if ($exitCode === 0) {
            Notification::make()
                ->title('Portfolio data exported')
                ->body('Commit and push the updated src/data/portfolio.json and public/images/projects files to deploy.')
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Export failed')
            ->body($this->lastOutput ?: 'Check the Laravel log for details.')
            ->danger()
            ->send();
    }
}
