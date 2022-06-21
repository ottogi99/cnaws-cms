<div>
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>

    <div class="py-4">
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.header sortable>입력년도</x-table.header>
                    <x-table.header sortable>입력시작일</x-table.header>
                    <x-table.header sortable>입력종료일</x-table.header>
                </x-slot>

                <x-slot name="body">
                    @foreach ($management as $item)
                        <x-table.row>
                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <!-- Heroicon name: cash -->
                                    <svg></svg>
                                    {{-- <x-icon.cash class="text-cool-gray-500" /> --}}
                                    <p class="text-cool-gray-500 truncate">
                                        {{ $item->input_year }}
                                    </p>
                                </span>
                            </x-table.cell>

                            {{-- AMOUNT --}}
                            <x-table.cell>
                                <span class="text-cool-gray-900 font-medium">${{ $item->input_start_date }}</span> USD
                            </x-table.cell>

                            {{-- STATUS --}}
                            <x-table.cell>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-{{ $item->status_color }}-100 text-{{ $item->status_color }}-800 capitalize">
                                    success
                                </span>
                            </x-table.cell>



                            <x-table.cell>
                                {{-- {{ $item->input_end_date->format('M, d Y') }} --}}
                                {{ $item->date_for_humans }}
                            </x-table.cell>
                        </x-table.row>
                    @endforeach
                </x-slot>
            </x-table>

            <div>
                {{ $management->links() }}
            </div>
        </div>
    </div>
</div>
