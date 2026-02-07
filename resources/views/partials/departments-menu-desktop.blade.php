<div id="departmentsMenu" class="hidden absolute top-full left-0 w-full bg-white text-gray-800 shadow-xl z-50 border-t-2 border-blue-600">
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
            @foreach($departmentMenu as $parentDepartment)
                <div>
                    <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">
                        <a href="{{ route('department.index', ['alias' => Str::of($parentDepartment->name)->slug(), 'departmentId' => $parentDepartment->id]) }}" 
                        class="hover:text-blue-600 transition-colors">
                            {{ $parentDepartment->name }}
                        </a>
                    </h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($parentDepartment->children as $childDepartment)
                            <li>
                                <a href="{{ route('department.index', ['alias' => Str::of($childDepartment->name)->slug(), 'departmentId' => $childDepartment->id]) }}" 
                                class="text-gray-600 hover:text-blue-600 transition-colors">
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