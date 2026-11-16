<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    This writes <code class="font-mono text-xs">src/data/portfolio.json</code> and copies local uploads to
                    <code class="font-mono text-xs">public/images/projects</code> in the Next.js app.
                    GitHub image URLs are passed through without copying.
                </p>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    The public site is a static build: after exporting, commit and push the changed files so Vercel can deploy them.
                </p>

                <x-filament::button
                    wire:click="publish"
                    wire:loading.attr="disabled"
                    wire:target="publish"
                    icon="heroicon-o-rocket-launch"
                    size="lg"
                >
                    Export portfolio data
                </x-filament::button>
            </div>
        </x-filament::section>

        @if ($lastOutput)
            <x-filament::section heading="Last export">
                <pre class="overflow-x-auto whitespace-pre-wrap text-xs text-gray-600 dark:text-gray-300">{{ $lastOutput }}</pre>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
