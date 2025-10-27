<li class="dd-item mb-2" data-id="{{ $category['id'] }}" data-level="{{ $category['level'] }}">
    <div class="category-item level-{{ $category['level'] }} {{ $category['level'] > 6 ? 'level-' . ($category['level'] % 7) : '' }}">
        <div class="depth-indicator text-xs font-bold text-white bg-gray-700 rounded-full px-2 absolute top-2 right-2">L{{ $category['level'] }}</div>
        <div class="category-content">
            <div class="category-info">
                <span class="category-icon">
                    <i class="fas fa-{{ $category['level'] === 0 ? 'home' : 'folder' }}"></i>
                </span>
                <div class="category-details">
                    <h6>
                        {{ $category['title'] }}
                        @if($category['is_featured'])
                            <i class="fas fa-star text-yellow-500" title="Featured"></i>
                        @endif
                        <span class="category-level">Level {{ $category['level'] }}</span>
                    </h6>
                    <small>ID: {{ $category['id'] }} | Slug: {{ $category['slug'] }} | Children: {{ count($category['children'] ?? []) }}</small>
                </div>
            </div>
            <div class="category-actions">
                <span class="status-badge {{ $category['status'] === 'active' ? 'bg-green-500' : 'bg-yellow-500' }} text-white text-xs px-2 py-1 rounded-full">{{ $category['status'] }}</span>
                <button type="button" title="Add subcategory" class="btn-action bg-green-500 text-white open-modal-btn" data-parent-id="{{ $category['id'] }}">
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" title="Toggle children" class="btn-action bg-blue-500 text-white toggle-children" data-id="{{ $category['id'] }}">
                    <i class="fas fa-{{ !empty($category['children']) ? 'minus' : 'plus' }}"></i>
                </button>
                <button type="button" title="Edit category" class="btn-action bg-indigo-500 text-white" onclick="window.location.href = window.getCategoryEditUrl({{ $category['id'] }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" title="Delete category" class="btn-action bg-red-500 text-white" onclick="if(confirm('Delete {{ $category['title'] }} and its children?')) { Livewire.emit('deleteCategory', {{ $category['id'] }}); }">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @if (!empty($category['children']))
            <div class="children-container {{ !empty($category['children']) ? '' : 'd-none' }}" data-parent="{{ $category['id'] }}">
                <ol class="children-tree dd-list">
                    @foreach ($category['children'] as $child)
                        @include('filament.resources.categories.pages.partials.category-node', ['category' => $child])
                    @endforeach
                </ol>
            </div>
        @endif
    </li>