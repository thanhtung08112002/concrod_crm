<div class="p-3">
    @if ($memoryLimitMB !== -1 && $memoryLimitMB !== 0 && $memoryLimitMB <= 64)
        <div class="rounded border border-warning-100 bg-warning-50 px-4 py-3">
            <h3 class="mb-3 text-warning-800">Low PHP Memory Limit</h3>

            <p class="text-sm text-warning-800">
                The installer detected that the server <span class="font-medium">PHP memory limit</span> is set to
                <span class="font-medium">{{ $memoryLimitRaw }}</span>. It's
                <span class="font-medium">strongly recommended</span> to increase the memory limit to at least
                <span class="font-medium">128M</span> to avoid any failures during installation or while using the CRM.
            </p>

            <p class="mt-2 text-sm text-warning-800">
                When logged-in to the server control panel, perform a search for e.q. <span class="font-medium">"PHP
                    settings</span>", <span class="font-medium">"Select PHP
                    Version</span>", <span class="font-medium">"PHP INI Editor</span>", or <span
                    class="font-medium">"PHP Options</span>" or any other related options for your control panel in
                order to increase the memory limit, or consult with your hosting provider to do this for you.
            </p>
        </div>
    @endif


    <h4 class="my-5 text-lg font-semibold text-neutral-800">PHP Version</h4>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border border-neutral-200 shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Required PHP Version
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Current
                            </th>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white">
                            <td class="whitespace-nowrap px-4 py-2 text-sm font-medium text-neutral-900">
                                >= {{ $php['minimum'] }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-2 text-sm text-neutral-900">
                                <span
                                    class="{{ $php['supported'] ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                    @if ($php['supported'])
                                        @include('installer.passes-icon')
                                    @endif
                                    {{ $php['current'] }}
                                </span>
                            </td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-5 mt-10 text-lg font-semibold text-neutral-800">Required PHP Extensions</h4>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border border-neutral-200 shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Extension
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Enabled
                            </th>

                        </thead>
                        <tbody>
                            @foreach ($requirements['results']['php'] as $requirement => $enabled)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm font-medium text-neutral-900">
                                        {{ $requirement }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm text-neutral-900">
                                        <span
                                            class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                            @if ($enabled)
                                                @include('installer.passes-icon')
                                            @endif
                                            {{ $enabled ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-5 mt-10 text-lg font-semibold text-neutral-800">Required PHP Functions</h4>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border border-neutral-200 shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Function
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Enabled
                            </th>

                        </thead>
                        <tbody>
                            @foreach ($requirements['results']['functions'] as $function => $enabled)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm font-medium text-neutral-900">
                                        {{ $function }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm text-neutral-900">
                                        <span
                                            class="{{ $enabled ? 'text-success-500' : 'text-danger-500' }} inline-flex">
                                            @if ($enabled)
                                                @include('installer.passes-icon')
                                            @endif
                                            {{ $enabled ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-5 mt-10 text-lg font-semibold text-neutral-800">Recommended PHP Extensions/Functions</h4>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border border-neutral-200 shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50">
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Requirement
                            </th>
                            <th scope="col"
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">
                                Enabled
                            </th>

                        </thead>
                        <tbody>
                            @foreach ($requirements['recommended']['php'] as $requirement => $enabled)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm font-medium text-neutral-900">
                                        {{ $requirement }} <span class="text-xs text-neutral-400">(ext)</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm text-neutral-900">
                                        <span
                                            class="{{ $enabled ? 'text-success-500' : 'text-warning-500' }} inline-flex">
                                            @if ($enabled)
                                                @include('installer.passes-icon')
                                            @endif
                                            {{ $enabled ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($requirements['recommended']['functions'] as $function => $enabled)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm font-medium text-neutral-900">
                                        {{ $function }} <span class="text-xs text-neutral-400">(func)</span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 text-sm text-neutral-900">
                                        <span
                                            class="{{ $enabled ? 'text-success-500' : 'text-warning-500' }} inline-flex">
                                            @if ($enabled)
                                                @include('installer.passes-icon')
                                            @endif
                                            {{ $enabled ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
