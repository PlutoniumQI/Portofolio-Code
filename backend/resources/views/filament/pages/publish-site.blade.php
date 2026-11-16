<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    The frontend reads content from <code class="font-mono text-xs">GET /api/portfolio</code>. After editing
                    content, trigger a revalidation so the cached pages refresh. In production this calls the Vercel
                    revalidation webhook.
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Leave <code class="font-mono text-xs">FRONTEND_REVALIDATE_URL</code> and
                    <code class="font-mono text-xs">FRONTEND_REVALIDATE_SECRET</code> empty during local development; ISR
                    refreshes on its own schedule.
                </p>

                <x-filament::button
                    wire:click="publish"
                    wire:loading.attr="disabled"
                    wire:target="publish"
                    icon="heroicon-o-arrow-path"
                    size="lg"
                >
                    Revalidate live site
                </x-filament::button>
            </div>
        </x-filament::section>

        <x-filament::section heading="Legacy static export" collapsible collapsed>
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    This writes <code class="font-mono text-xs">src/data/portfolio.json</code> for the old one-page
                    frontend. It is no longer required once the API-backed pages are deployed.
                </p>

                <x-filament::button
                    wire:click="export"
                    wire:loading.attr="disabled"
                    wire:target="export"
                    color="gray"
                    icon="heroicon-o-arrow-down-tray"
                >
                    Export static JSON
                </x-filament::button>

                @if ($lastOutput)
                    <pre class="overflow-x-auto whitespace-pre-wrap text-xs text-gray-600 dark:text-gray-300">{{ $lastOutput }}</pre>
                @endif
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
