<div>
    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>

    <div class="py-4 space-y-4">
        <div class="flex justify-between">
            <div class="w-2/4 flex space-x-8">
                <x-input.text wire:model.debounce.500ms="filters.search" id="search" leading-add-on="검색어" placeholder="검색어를 입력하세요..." />

                <x-button.link wire:click="$toggle('showFilters')" class="mt-4">
                    @if ($showFilters)
                        필터...  (숨기기)
                    @else
                        필터...  (보이기)
                    @endif
                </x-button.link>
            </div>

            <div class="flex space-x-2">
                {{-- <x-input.select>
                    <option value="" disabled>Export</option>
                    <option value="" disabled>Delete</option>
                </x-input.select> --}}
                <x-dropdown label="Bulk Actions">
                    {{-- <x-dropdown.item class="flex items-center space-x-2"><x-icon.download class="text-gray-400"/>Export</x-dropdown.item>
                    <x-dropdown.item class="flex items-center space-x-2"><x-icon.trash class="text-gray-400"/>Delete</x-dropdown.item> --}}
                    <x-dropdown.item type="button" wire:click="exprotSelected" class="flex items-center space-x-2">
                        <span>Export</span>
                    </x-dropdown.item>

                    <x-dropdown.item type="button" wire:click="deleteSelected" class="flex items-center space-x-2">
                        <span>Delete</span>
                    </x-dropdown.item>
                </x-dropdown>
                <x-button.primary wire:click="create"><x-icon.plus/>New</x-button.primary>
            </div>
        </div>

        {{-- 필터 --}}
        <div>
            @if ($showFilters)
            <div class="bg-gray-200">
                <div class="bg-gray-200 p-4 rounded shadow-inner flex relative">
                    <div class="flex w-full pr-2 space-x-2">
                        <x-input.group inline for="filter-status" label="Status">
                            <x-input.select id="filter-status">
                                <option value="" disabled>Select Status...</option>

                                @foreach (App\Models\Management::STATUSES as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        <x-input.group inline for="filter-year" label="년도">
                            <x-input.select wire:model="filters.year" id="filter-year">
                                <option value="" disabled>년도를 선택하세요...</option>

                                @foreach (App\Models\Management::yearList() as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        {{-- <x-input.group inline for="filter-amount-min" label="Minimum Amount">
                            <x-input.text id="filter-amount-min" />
                        </x-input.group> --}}

                        <x-input.group inline for="filter-initiate" label="개시일">
                            <x-input.date wire:model="filters.initiate" id="filter-initiate" placeholder="YYYY-MM-DD" />
                        </x-input.group>

                        <x-input.group inline for="filter-deadline" label="마감일">
                            <x-input.date wire:model="filters.deadline" id="filter-deadline" placeholder="YYYY-MM-DD" />
                        </x-input.group>

                        <x-button.link wire:click="resetFilters" class="absoulte right-0 bottom-0 p-4">Reset Filters</x-button.link>
                    </div>
                </div>
            </div>
            @endif
        </div>

@json($selected)

        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.header class="pr-0 w-8">
                        <x-input.checkbox />
                    </x-table.header>
                    <x-table.header sortable wire:click="sortBy('year')" :direction="$sortField === 'year' ? $sortDirection : null" >년도</x-table.header>
                    <x-table.header sortable wire:click="sortBy('initiate')" :direction="$sortField === 'initiate' ? $sortDirection : null">개시일</x-table.header>
                    <x-table.header sortable wire:click="sortBy('deadline')" :direction="$sortField === 'deadline' ? $sortDirection : null">마감일</x-table.header>
                    <x-table.header sortable wire:click="sortBy('year')" :direction="$sortField === 'year' ? $sortDirection : null">상태</x-table.header>
                    <x-table.header ></x-table.header>
                </x-slot>

                <x-slot name="body">
                    @forelse ($management as $item)
                        <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $item->id }}">
                            <x-table.cell class="pr-0">
                                <x-input.checkbox wire:model="selected" value="{{ $item->id }}" />
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <!-- Heroicon name: cash -->
                                    {{-- <svg></svg> --}}
                                    {{-- <x-icon.cash class="text-cool-gray-500" /> --}}
                                    <p class="text-cool-gray-500 truncate">
                                        {{ $item->year }}
                                    </p>
                                </span>
                            </x-table.cell>

                            {{-- AMOUNT --}}
                            <x-table.cell>
                                <span class="text-cool-gray-900 font-medium">{{ $item->initiate_for_humans }}</span>
                            </x-table.cell>
                            <x-table.cell>
                                {{-- {{ $item->input_end_date->format('M, d Y') }} --}}
                                {{ $item->deadline_for_humans }}
                            </x-table.cell>
                            {{-- STATUS --}}
                            <x-table.cell>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-{{ $item->status_color }}-100 text-{{ $item->status_color }}-800 capitalize">
                                    success
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <x-button.link wire:click="edit({{ $item->id }})" key="{{ $item->id }}">Edit</x-button.link>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="4">
                                <div class="flex justify-center items-center space-x-2">
                                    {{-- <x-icon.inbox class="h-8 w-8 text-cool-gray-400" /> --}}
                                    <span class="font-medium py-8 text-gray-400 text-xl">검색된 데이터가 없습니다...</span>
                                </div>
                            </x-table.cell>
                        </x-table.row>
                    @endforelse
                </x-slot>
            </x-table>

            <div>
                {{ $management->links() }}
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model="showEditModal">
            <x-slot name="title">입력관리</x-slot>
            <x-slot name="content">
                <x-input.group for="year" label="년도" :error="$errors->first('editing.year')">
                    <x-input.text
                        wire:model.defer="editing.year" id="year"
                        isDisabled="{{ isset($editing->year) ? 'true' : 'false' }}"
                    />
                </x-input.group>
                <x-input.group for="initiate_for_editing" label="개시일" :error="$errors->first('editing.initiate_for_editing')">
                    <x-input.date wire:model.defer="editing.initiate_for_editing" id="initiate_for_editing" placeholder="YYYY-MM-DD" />
                </x-input.group>
                <x-input.group for="deadline_for_editing" label="마감일" :error="$errors->first('editing.deadline_for_editing')">
                    <x-input.date wire:model.defer="editing.deadline_for_editing" id="deadline_for_editing" placeholder="YYYY-MM-DD" />
                </x-input.group>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">Cancel</x-button.primary>
                <x-button.primary type="submit">Save</x-button.primary>
            </x-slot>
        </x-modal.dialog>

                {{-- SelectBox --}}
                <x-input.group label="Search" for="search" :error="$errors->first('search')">
                    {{-- <x-input.select wire:model="editing.status" id="status"> --}}
                    <select>
                        @foreach ( App\Models\Management::STATUSES as $value => $label )
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    {{-- </x-input.select> --}}
                </x-input.group>
    </form>
</div>


        {{-- Pikaday --}}
        {{-- <x-input.group label="입력 시작일" for="inputStartDate" :error="$errors->first('inputStartDate')">
            <x-input.date wire:model.lazy="inputStartDate" id="inputStartDate" placeholder="YYYY-MM-DD" />
        </x-input.group>
        입력 시작일 : {{ $inputStartDate }} --}}

        {{-- <div wire:ignore>
        <x-input.group label="입력 종료일" for="inputEndDate" :error="$errors->first('inputEndDate')">
            <x-input.date wire:model="inputEndDate" id="inputEndDate" placeholder="YYYY-MM-DD">
                <x-slot name="leadingAddOn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-check" viewBox="0 0 16 16">
                        <path d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>
                        <path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z"/>
                    </svg>
                </x-slot>
            </x-input.date>
        </x-input.group>
        </div> --}}


        {{-- TEXT AREA 구현 --}}
        {{-- <x-input.group label="About" for="about" :error="$errors->first('about')" help-text="Write a few sentences about yourself.">
            <x-input.textarea wire:model="about" id="about" />
        </x-input.group> --}}

        {{-- <div class="flex">
            <div class="w-1/4">
                <x-input.text wire:model="search" id="search" leading-add-on="localhost/" placeholder="검색어를 입력하세요..." />
            </div>
        </div> --}}
