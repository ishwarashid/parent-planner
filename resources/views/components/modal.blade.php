@props(['name', 'title' => ''])

<div
    x-data="{ show: false, name: '{{ $name }}' }"
    x-show="show"
    x-on:open-modal.window="show = ($event.detail === name)"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50"
>
    <div @click.away="show = false" class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">{{ $title }}</h3>
            <button @click="show = false" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>