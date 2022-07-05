<div>
    <x-loading-indicator />
    <h1 class="text-2xl font-semibold text-gray-900">농기계 관리</h1>

    <div class="py-4 space-y-4">
        <!-- Top Bar -->
        <div class="flex justify-between">
            <div class="w-2/4 flex space-x-8">
                <x-input.text wire:model.debounce.700ms="filters.search" id="search" leading-add-on="검색어" placeholder="검색어를 입력하세요..." />
            </div>

            <x-button.link wire:click="$toggle('showFilters')" class="mt-4">
                @if ($showFilters)
                    필터...  (숨기기)
                @else
                    필터...  (보이기)
                @endif
            </x-button.link>

            <div class="flex space-x-2 items-center">
                <x-input.group borderless paddingless for="perPage" label="per Page">
                    <x-input.select wire:model="perPage" id="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </x-input.select>
                </x-input.group>

                <x-dropdown label="Bulk Actions">
                    <x-dropdown.item type="button" wire:click="exprotSelected" class="flex items-center space-x-2">
                        <span>Export</span>
                    </x-dropdown.item>

                    <x-dropdown.item type="button" wire:click="$toggle('showDeleteModal')" class="flex items-center space-x-2">
                        <span>Delete</span>
                    </x-dropdown.item>
                </x-dropdown>

                <x-button.primary wire:click="create"><x-icon.plus/>추가</x-button.primary>
            </div>
        </div>

        {{-- 필터 --}}
        <div>
            @if ($showFilters)
            <div class="bg-gray-200">
                <div class="bg-gray-200 p-4 rounded shadow-inner flex relative">
                    <div class="flex w-full pr-2 space-x-2">
                        <x-input.group inline for="filter-type" label="농기계">
                            <x-input.select wire:model="filters.type" id="filter-type">
                                <option value="" disabled>농기계를 선택하세요...</option>
                                <option value="">전체</option>
                                @foreach ($this->Machineries as $machinery)
                                <option value="{{ $machinery->id }}">{{ $machinery->type }} @if($machinery->spec != '') ({{ $machinery->spec }}) @endif</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        {{-- <x-input.group inline for="filter-spec" label="세부정보">
                            <x-input.text wire:model.debounce.500ms="filters.spec" id="filter-spec" />
                        </x-input.group> --}}

                        <x-button.link wire:click="resetFilters" class="absoulte right-0 bottom-0 p-4">Reset Filters</x-button.link>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- 테이블 -->
        <div class="flex-col space-y-4">
            <x-table>
                <x-slot name="head">
                    <x-table.header class="pr-0 w-8"><x-input.checkbox wire:model="selectPage" /></x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('type')" :direction="$sorts['name'] ?? null">종류</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('spec')" :direction="$sorts['spec'] ?? null">사양</x-table.header>
                    <x-table.header ></x-table.header>
                </x-slot>

                <x-slot name="body">
                    @if ($selectPage)
                    <x-table.row class="bg-gray-200" wire:key="row-message">
                        <x-table.cell colspan="6">
                            @unless ($selectAll)
                                <span> 현재 페이지 항목 <strong>{{ $items->count() }}</strong>개가 선택되었습니다., 모든 항목 <strong>{{ $items->total() }}</strong> 을 선택하시겠습니까? </span>
                                <x-button.link wire:click="selectAll">전체선택</x-button.link>
                            @else
                                <span> 전체 항목(<strong>{{ $items->total() }}</strong>)이 선택되었습니다.</span>
                            @endunless
                        </x-table.cell>
                    </x-table.row>
                    @endif

                    @forelse ($items as $item)
                        <x-table.row wire:loading.class.delay="opacity-50" wire:key="row-{{ $item->id }}">
                            <x-table.cell class="pr-0">
                                <x-input.checkbox wire:model="selected" value="{{ $item->id }}" />
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->type }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->spec }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <x-button.link wire:click="edit({{ $item->id }})" key="{{ $item->id }}">Edit</x-button.link>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="13">
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
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- 저장 모달 -->
    <form wire:submit.prevent="save">
        <x-modal.dialog wire:model="showEditModal">
            <x-slot name="title">농기계 등록(수정)</x-slot>

            <x-slot name="content">
                <x-input.group for="editing_type" label="종류" :error="$errors->first('editing.type')">
                    <x-input.text wire:model.defer="editing.type" id="editing_type" />
                </x-input.group>

                <x-input.group for="editing_spec" label="사양" :error="$errors->first('editing.spec')">
                    <x-input.text wire:model.defer="editing.spec" id="editing_spec" />
                </x-input.group>

            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showEditModal', false)">취소</x-button.primary>
                <x-button.primary type="submit">저장</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>

    {{-- Delete Modal --}}
    <form wire:submit.prevent="deleteSelected">
        <x-modal.confirmation wire:model="showDeleteModal">
            <x-slot name="title">삭제</x-slot>
            <x-slot name="content">
                정말로 삭제하시겠습니까? 삭제 후에는 되돌릴 수 없습니다.
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$set('showDeleteModal', false)">취소</x-button.primary>
                <x-button.primary type="submit">삭제</x-button.primary>
            </x-slot>
        </x-modal.dialog>
    </form>
</div>
