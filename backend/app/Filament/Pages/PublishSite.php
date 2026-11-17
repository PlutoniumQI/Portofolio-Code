<?php

namespace App\Filament\Pages;

use App\Services\FrontendRevalidator;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
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

    public function publish(FrontendRevalidator $revalidator): void
    {
        $result = $revalidator->revalidate();

        Notification::make()
            ->title($result['ok'] ? 'Live site revalidated' : 'Could not revalidate the site')
            ->body($result['message'])
            ->{$result['ok'] ? 'success' : 'warning'}()
            ->send();
    }
}
