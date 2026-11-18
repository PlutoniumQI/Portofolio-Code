@if (app()->isLocal() && auth()->check())
    <div style="margin-bottom: 1.5rem;">
        <x-filament::callout
            color="warning"
            icon="heroicon-o-exclamation-triangle"
            heading="Local database — this does not update the live site"
            description="Saves here go to backend/database/database.sqlite. The live site reads the Neon Postgres database, so changes made on this machine will not appear on www.resky.web.id. Edit production content in the production admin."
        >
            <x-slot name="footer">
                <x-filament::link
                    href="https://api.resky.web.id/admin"
                    target="_blank"
                    icon="heroicon-o-arrow-top-right-on-square"
                >
                    Open the production admin
                </x-filament::link>
            </x-slot>
        </x-filament::callout>
    </div>
@endif
