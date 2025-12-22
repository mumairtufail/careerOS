@props(['title', 'description'])

<div {{ $attributes->merge(['class' => 'grid grid-cols-1 lg:grid-cols-3 gap-8']) }}>
    <div class="lg:col-span-1">
        <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $description }}
        </p>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
            {{ $slot }}
        </div>
    </div>
</div>
