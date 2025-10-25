<li class="dd-item mb-2" data-id="{{ $category['id'] }}">
    <div class="category-row bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 category-item {{ 'level-' . $category['level'] }}">
        <div class="category-left {{ 'level-' . $category['level'] }}">
            <span class="dd-handle drag-handle text-gray-500 dark:text-gray-300 category-icon" title="Drag">
                <i class="fas fa-arrows-alt"></i>
            </span>

            <div class="min-w-0">
                <div class="category-title text-gray-800 dark:text-gray-200 flex items-center gap-2">
                    {{ $category['title'] }}
                    @if($category['is_featured'])
                        <span class="text-yellow-500" title="Featured Category">★</span>
                    @endif
                    <span class="category-level text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md">Level {{ $category['level'] }}</span>
                </div>

                <div class="text-xs text-gray-400 truncate" style="max-width: 420px;">
                    ID: {{ $category['id'] }} &nbsp; | &nbsp; Slug: {{ $category['slug'] }} &nbsp; | &nbsp; Child: {{ count($category['children'] ?? []) }}
                </div>
            </div>
        </div>

        <div class="category-actions">
            <button type="button" title="Add subcategory"
                    class="open-modal-btn icon-btn bg-green-50 dark:bg-green-700 text-green-700 dark:text-white"
                    data-parent-id="{{ $category['id'] }}">
                <i class="fas fa-plus"></i>
            </button>

            <button type="button" title="Edit category"
                    class="icon-btn bg-blue-50 dark:bg-blue-700 text-blue-700 dark:text-white"
                    onclick="window.location.href = window.getCategoryEditUrl({{ $category['id'] }});">
                <i class="fas fa-edit"></i>
            </button>

            <button type="button" title="Delete category" class="icon-btn bg-red-600 text-white"
                    onclick="if (confirm('Delete this category and its children?')) { console.log('Delete category:', {{ $category['id'] }}); }">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    @if (!empty($category['children']))
        <ol class="dd-list mt-2">
            @foreach ($category['children'] as $child)
                @include('filament.resources.categories.pages.partials.category-node', ['category' => $child])
            @endforeach
        </ol>
    @endif
</li>
