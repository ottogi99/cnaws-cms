<div>
    <h1 class="text-2xl font-semibold text-gray-900">지출 관리</h1>

    <div class="py-4 space-y-4">
        <!-- Top Bar -->
        <div class="flex justify-between">
            <div class="w-2/4 flex space-x-8">
                <x-input.text wire:model.debounce.700ms="filters.search" id="search" leading-add-on="검색어" placeholder="지출항목을 입력하세요..." />
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
                        {{-- <x-input.group inline for="filter-city" label="시군">
                            <x-input.select wire:model="filters.city" id="filter-city">
                                <option value="" disabled>시/군을 선택하세요</option>
                                <option value="">전체</option>
                                @foreach (App\Models\City::$NAMES as $id => $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group> --}}

                        <x-input.group inline for="filter-nonghyup" label="농협" :error="$errors->first('filter.nonghyup')">
                            <x-input.select wire:model="filters.nonghyup" id="filter-nonghyup">
                                <option value="" disabled>농협을 선택하세요</option>
                                @foreach ($this->nonghyups as $nonghyup)
                                <option value="{{ $nonghyup->id }}">{{ $nonghyup->name }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        <x-input.group inline for="filter-expenditure_type" label="지출형태" :error="$errors->first('filter.expenditure_type')">
                            <x-input.select wire:model="filters.expenditure_type" id="filter-expenditure_type">
                                <option value="" disabled>지출 형태를 선택하세요.</span></option>
                                @foreach (\App\Models\Expenditure::$EXPENDITURE_TYPES as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        <x-input.group inline for="filter-expenditure-target" label="지급대상">
                            <x-input.text wire:model.debounce.500ms="filters.expenditure_target" id="filter-expenditure-target" />
                        </x-input.group>

                        <x-input.group inline for="filter-expenditure-details" label="지급내용">
                            <x-input.text wire:model.debounce.500ms="filters.expenditure_details" id="filter-expenditure-details" />
                        </x-input.group>

                        <x-input.group inline for="filter-amount-min" label="지급액(최소)">
                            <x-input.text wire:model.debounce.500ms="filters.amount_min" id="filter-amount-min" placeholder="금액을 입력하세요..." />
                        </x-input.group>

                        <x-input.group inline for="filter-amount-max" label="지급액(최대)">
                            <x-input.text wire:model.debounce.500ms="filters.amount_max" id="filter-amount-max" placeholder="금액을 입력하세요..." />
                        </x-input.group>

                        <x-input.group inline for="filter-payment-at-min" label="지급일자(시작)">
                            <x-input.date wire:model="filters.payment_at_min" id="filter-payment-at-min" placeholder="YYYY-MM-DD" />
                        </x-input.group>

                        <x-input.group inline for="filter-payment-at-max" label="지급일자(종료)">
                            <x-input.date wire:model="filters.payment_at_max" id="filter-payment-at-max" placeholder="YYYY-MM-DD" />
                        </x-input.group>


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
                    <x-table.header class="pr-0 w-8">
                        <x-input.checkbox wire:model="selectPage" />
                    </x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('nonghyup_name')" :direction="$sorts['nonghyup_name'] ?? null" >농협</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_type')" :direction="$sorts['expenditure_type'] ?? null">지출형태</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_item')" :direction="$sorts['expenditure_item'] ?? null">지출항목</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_target')" :direction="$sorts['expenditure_target'] ?? null">지급대상</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_staff_name')" :direction="$sorts['expenditure_staff_name'] ?? null">지급대상자</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_details')" :direction="$sorts['expenditure_details'] ?? null" >지급내용</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('expenditure_amount')" :direction="$sorts['expenditure_amount'] ?? null" >지급액</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('payment_at')" :direction="$sorts['payment_at'] ?? null" >지급일자</x-table.header>
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
                                    <p class="text-cool-gray-500 truncate">
                                        {{ $item->nonghyup_name }}
                                    </p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->expenditure_type }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->expenditure_item }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->expenditure_target }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->staff_name }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->expenditure_details }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->expenditure_amount }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->payment_at }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <x-button.link wire:click="edit({{ $item->id }})" key="{{ $item->id }}">Edit</x-button.link>
                            </x-table.cell>
                        </x-table.row>
                    @empty
                        <x-table.row>
                            <x-table.cell colspan="9">
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
            <x-slot name="title">지출 등록(수정)</x-slot>


            <x-slot name="content">
                {{-- <x-input.group for="editing_nonghyup" label="농협명" :error="$errors->first('editing.nonghyup')">
                    <x-input.text wire:model.defer="editing.nonghyup_name" id="editing_nonghyup" />
                </x-input.group> --}}

                <x-input.group inline for="editing_nonghyup" label="농협" :error="$errors->first('editing.nonghyup_id')">
                    <x-input.select wire:model="editing.nonghyup_id" id="editing_nonghyup">
                        <option value="" disabled>농협을 선택하세요...</option>
                        @foreach ($this->nonghyups as $nonghyup)
                        <option value="{{ $nonghyup->id }}">{{ $nonghyup->name }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group inline for="editing_type" label="지출형태" :error="$errors->first('editing.type')">
                    <x-input.select wire:model="editing.type" id="editing_type">
                        <option value="" disabled>지출형태를 선택하세요...</option>
                        @foreach (\App\Models\Expenditure::$EXPENDITURE_TYPES as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group for="editing_item" label="지출항목" :error="$errors->first('editing.item')">
                    <x-input.text wire:model.defer="editing.item" id="editing_item" />
                </x-input.group>

                @if ($editing->type == \App\Models\Expenditure::$LABOR_TYPE)
                <x-input.group inline for="editing_staff_id" label="지급대상자" :error="$errors->first('editing.staff_id')">
                    <x-input.select wire:model="editing.staff_id" id="editing_staff_id">
                        <option value="" disabled>직원을 선택하세요...</option>
                        @foreach ($this->staffInNonghyup as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                @else
                <x-input.group for="editing_target" label="지급대상" :error="$errors->first('editing.target')">
                    <x-input.text wire:model.defer="editing.target" id="editing_target" />
                </x-input.group>
                @endif

                <x-input.group for="editing_details" label="지급내용" :error="$errors->first('editing.details')">
                    <x-input.text wire:model.defer="editing.details" id="editing_details" />
                </x-input.group>

                <x-input.group for="editing_amount" label="지급액" :error="$errors->first('editing.amount')">
                    <x-input.text wire:model.defer="editing.amount" id="editing_amount" />
                </x-input.group>

                <x-input.group inline for="editing_payment_at" label="지급일">
                    <x-input.date wire:model="editing.payment_at" id="editing_payment_at" placeholder="YYYY-MM-DD" />
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
