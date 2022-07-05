<div>
    <x-loading-indicator />
    <h1 class="text-2xl font-semibold text-gray-900">지원반 관리</h1>

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
                        <x-input.group inline for="filter-city" label="시군">
                            <x-input.select wire:model="filters.city" id="filter-city">
                                <option value="" disabled>시/군을 선택하세요</option>
                                <option value="">전체</option>
                                @foreach (App\Models\City::$NAMES as $id => $city)
                                <option value="{{ $city }}">{{ $city }}</option>
                                @endforeach
                            </x-input.select>
                        </x-input.group>

                        <x-input.group inline for="filter-total-min" label="은행명">
                            <x-input.text wire:model.debounce.500ms="filters.account-name" id="account-name" />
                        </x-input.group>

                        <x-input.group inline for="filter-total-min" label="계좌번호">
                            <x-input.text wire:model.debounce.500ms="filters.account-number" id="account-number" />
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
                    <x-table.header class="pr-0 w-8"><x-input.checkbox wire:model="selectPage" /></x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('nonghyup_name')" :direction="$sorts['nonghyup_name'] ?? null">년도</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('city')" :direction="$sorts['city'] ?? null">시군</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('nonghyup')" :direction="$sorts['nonghyup'] ?? null">농협</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('size')" :direction="$sorts['size'] ?? null">농가규모</x-table.header>

                    <x-table.header sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null">이름</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('birthday')" :direction="$sorts['birthday'] ?? null">생년월일</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('gender')" :direction="$sorts['gender'] ?? null">성별</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('address')" :direction="$sorts['address'] ?? null">주소</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('contact')" :direction="$sorts['contact'] ?? null">연락처</x-table.header>

                    <x-table.header sortable multi-column wire:click="sortBy('account-name')" :direction="$sorts['account-name'] ?? null">은행명</x-table.header>
                    <x-table.header sortable multi-column wire:click="sortBy('account-number')" :direction="$sorts['account-number'] ?? null">계좌번호</x-table.header>
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
                                    <p class="text-cool-gray-500 truncate">{{ $item->year }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->city_name }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->nonghyup_name }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">
                                        {{App\Models\Farmhouse::$FARMSIZES[$item->size] }}
                                    </p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->support_team_name }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->birthday }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">
                                        {{App\Models\Farmhouse::$GENDERS[$item->gender] }}
                                    </p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->support_team_address }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->support_team_contact }}</p>
                                </span>
                            </x-table.cell>

                            @if (true)
                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->account_name }}</p>
                                </span>
                            </x-table.cell>

                            <x-table.cell>
                                <span href="#" class="inline-flex space-x-2 truncate text-sm leading-5">
                                    <x-icon.cash class="text-cool-gray-500" />
                                    <p class="text-cool-gray-500 truncate">{{ $item->account_number }}</p>
                                </span>
                            </x-table.cell>
                            @endif

                            <x-table.cell>
                                <x-button.link wire:click="edit({{ $item->id }}, {{ $item->year }})" key="{{ $item->id }}-{{ $item->year }}">Edit</x-button.link>
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
            <x-slot name="title">농가 등록(수정)</x-slot>

            <x-slot name="content">
                {{-- @if (empty($yearForEditing)) --}}
                <x-input.group inline for="editing-year" label="년도">
                    <x-input.select wire:model.defer="yearForEditing" id="editing-year">
                        <option value="" disabled>년도를 선택하세요</option>
                        @foreach (App\Models\Management::yearList(1975) as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>
                {{-- @endif --}}

                <x-input.group inline for="editing_nonghyup" label="농협" :error="$errors->first('editing.nonghyup')">
                    <x-input.select wire:model.defer="editing.nonghyup_id" id="editing_nonghyup">
                        <option value="" disabled>농협을 선택하세요</option>
                        @foreach ($this->nonghyups as $nonghyup)
                        <option value="{{ $nonghyup->id }}">{{ $nonghyup->name }}</option>
                        @endforeach
                    </x-input.select>
                </x-input.group>

                <x-input.group for="editing_size" label="농가규모" :error="$errors->first('editing.size')">
                    <x-input.select wire:model="editing.size" id="size">
                        <option value="" disabled>농가규모를 선택하세요</option>
                        <option value="S">소규모/영세농</option>
                        <option value="L">대규모/전업농</option>
                    </x-input.select>
                </x-input.group>

                <x-input.group for="editing_name" label="이름" :error="$errors->first('editing.name')">
                    <x-input.text wire:model.defer="editing.name" id="editing_name" />
                </x-input.group>

                <x-input.group for="editing_birthday" label="생년월일" :error="$errors->first('editing.birthday')">
                    <x-input.date wire:model.defer="editing.birthday" id="editing_birthday" placeholder="YYYY-MM-DD" />
                </x-input.group>

                <x-input.group for="editing_gender" label="성별" :error="$errors->first('editing.gender')">
                    <x-input.select wire:model="editing.gender" id="gender">
                        <option value="" disabled>성별을 선택하세요</option>
                        <option value="M">남</option>
                        <option value="F">여</option>
                    </x-input.select>
                </x-input.group>

                <x-input.group for="editing_address" label="주소" :error="$errors->first('editing.address')">
                    <x-input.text wire:model.defer="editing.address" id="editing_address" />
                </x-input.group>

                <x-input.group for="editing_contact" label="연락처" :error="$errors->first('editing.contact')">
                    <x-input.text wire:model.defer="editing.contact" id="editing_contact" />
                </x-input.group>

                @if ($editing->size === 'S')
                    <x-input.group inline for="editing-machineries" label="농기계" :error="$errors->first('machineriesForEditing')">
                        <x-input.select wire:model="machineriesForEditing" id="editing-machineries">
                            <option value="" disabled>농기계를 선택하세요...</option>
                            @foreach (App\Models\Machinery::getMachineries() as $machinery)
                            <option value="{{ $machinery->id }}">{{ $machinery->type }} @if($machinery->spec != '') ({{ $machinery->spec }}) @endif</option>
                            @endforeach
                        </x-input.select>
                    </x-input.group>

                @elseif ($editing->size === 'L')
                    <x-input.group for="editing_insurance" label="상해보험 가입여부" :error="$errors->first('editing.insurance')">
                        <x-input.select wire:model="editing.insurance" id="insurance">
                            <option value="" disabled>상해보험 가입여부를 선택하세요</option>
                            <option value="0">미가입</option>
                            <option value="1">가입</option>
                        </x-input.select>
                    </x-input.group>

                    {{-- Pikaday --}}
                    <div wire:ignore>
                        <x-input.group label="교육 시작일" for="inputStartDate" :error="$errors->first('inputStartDate')">
                            <x-input.date wire:model.lazy="inputStartDate" id="inputStartDate" placeholder="YYYY-MM-DD" />
                        </x-input.group>

                        <x-input.group label="교육 종료일" for="inputEndDate" :error="$errors->first('inputEndDate')">
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
                    </div>
                @endif

                <x-input.group for="editing_bank_name" label="은행명" :error="$errors->first('editingAccount.name')">
                    <x-input.text wire:model.defer="editingAccount.name" id="editing_bank_name" />
                </x-input.group>

                <x-input.group for="editingAccount_bank_number" label="계좌번호" :error="$errors->first('editingAccount.number')">
                    <x-input.text wire:model.defer="editingAccount.number" id="editingAccount_bank_number" />
                </x-input.group>

                {{-- <x-input.group for="editing_accountable_type" label="계좌구분" :error="$errors->first('editingAccount.accountable_type')"> --}}
                    <x-input.hidden wire:model.defer="editingAccount.accountable_type" id="editingAccount_accountable_type" />
                {{-- </x-input.group> --}}

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
