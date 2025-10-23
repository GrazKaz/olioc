<x-filament-panels::page>
    <div class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
        <div class="p-4 sm:p-6">
            <div class="flex flex-col gap-4 items-center justify-center mb-8">
                <div class="info-logo">
                    <x-filament-panels::logo />
                </div>
                <div>
                    <div><span class="font-medium">{{ __('version') }}:</span> {{ env('APP_VERSION') }}</div>
                </div>
            </div>
            <div class="flex flex-col gap-1 items-center justify-center mb-8">
                <table>
                    <tr><td class="font-semibold pr-4 text-right">{{ __('main programmers') }}:</td><td>Grażyna Kaznowska</td></tr>
                    <tr><td class="pr-4"></td><td>Dawid Politowski</td></tr>
                    <tr><td class="font-semibold pr-4 text-right">{{ __('cooperation') }}:</td><td>??</td></tr>
                    <tr><td class="pr-4"></td><td>??</td></tr>
                </table>
            </div>
            <div class="flex flex-col gap-2 items-center justify-center">
                <div class="text-center"><span class="font-medium">{{ __('Helpdesk and bug report') }}:</span></div>
                <div class=""><span class="font-medium">{{ __('phone') }}:</span> 1357</div>
                <div><span class="font-medium">{{ __('email') }}:</span> dpolitowski@poznan.uw.gov.pl</div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
