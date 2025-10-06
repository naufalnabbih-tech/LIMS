@props([
    'title', 
    'subtitle' => null,
    'headers' => [],
    'rows' => [],
    'emptyIcon' => null,
    'emptyTitle' => 'No data available',
    'emptyMessage' => 'Start by adding your first record',
    'emptyAction' => null,
    'pagination' => null
])

<div class="bg-white shadow-sm rounded-lg border border-gray-200" style="overflow: visible;">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="overflow-x-auto" style="overflow-y: visible; overflow-x: auto;">
        <table class="min-w-full divide-y divide-gray-200" style="position: relative;">
            @if(count($headers) > 0)
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($headers as $header)
                            <th {{ $header['attributes'] ?? '' }} 
                                class="{{ $header['class'] ?? 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider' }}">
                                {{ $header['label'] }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif
            <tbody class="bg-white divide-y divide-gray-200">
                @if(isset($slot) && !empty(trim($slot)))
                    {{ $slot }}
                @elseif(count($rows) > 0)
                    @foreach($rows as $row)
                        <tr class="hover:bg-gray-50">
                            @foreach($row as $cell)
                                <td class="{{ $cell['class'] ?? 'px-6 py-4 whitespace-nowrap' }}">
                                    {!! $cell['content'] !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($headers) ?: 6 }}" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    @if($emptyIcon)
                                        {!! $emptyIcon !!}
                                    @else
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $emptyTitle }}</h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $emptyMessage }}</p>
                                @if($emptyAction)
                                    {!! $emptyAction !!}
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($pagination)
        <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
            {{ $pagination }}
        </div>
    @endif
</div>