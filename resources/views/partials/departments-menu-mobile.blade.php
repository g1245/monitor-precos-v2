<!-- Mobile Departments Menu - Full Screen -->
<div id="mobileDepartmentsMenu" class="hidden fixed inset-0 bg-white z-50 md:hidden overflow-y-auto">
    <div class="flex flex-col h-full">
        <div class="bg-blue-800 text-white p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">Departamentos</h2>
            <button id="closeMobileMenu" class="text-white hover:text-blue-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="flex-1 p-4">
            @foreach($departmentMenu as $parentDepartment)
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">
                        {{ $parentDepartment->name }}
                    </h3>
                    <ul class="space-y-3">
                        @foreach($parentDepartment->children as $childDepartment)
                            <li>
                                <a href="{{ route('department.index', ['alias' => Str::of($childDepartment->name)->slug(), 'departmentId' => $childDepartment->id]) }}" 
                                class="text-gray-700 block py-1">
                                    {{ $childDepartment->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>