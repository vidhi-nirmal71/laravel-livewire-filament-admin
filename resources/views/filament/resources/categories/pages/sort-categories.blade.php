<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
            Category Tree Manager - Infinite Levels
        </h2>

        <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Drag and drop any category to any level. Create unlimited subcategory levels. Click <strong>Save Changes</strong> once done.
            </p>
        </div>

        <div class="controls-panel mb-4 bg-white dark:bg-gray-900 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200"><i class="fas fa-sitemap me-2"></i>Category Hierarchy - Infinite Depth</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Drag and drop any category to any level. Create unlimited subcategory levels.</p>
                </div>
                <div class="stats-card text-center p-3 rounded-lg text-white" style="background: linear-gradient(135deg, #6366f1, #a855f7);">
                    <div class="stats-number text-2xl font-bold" id="totalCategories">{{ $totalCategories ?? 0 }}</div>
                    <div>Total Categories</div>
                    <div class="mt-2 text-sm">Max Depth: <span id="maxDepth">{{ $maxDepth ?? 0 }}</span></div>
                </div>
            </div>
        </div>

        <div wire:ignore>
            <div class="tree-container bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-lg font-medium text-gray-800 dark:text-gray-200"><i class="fas fa-tree me-2"></i>Category Structure</h4>
                    <div>
                        <button class="btn btn-success btn-sm me-2" id="saveChangesBtn"><i class="fas fa-save me-1"></i> Save Changes</button>
                        <button class="btn btn-secondary btn-sm me-2" id="expandAllBtn"><i class="fas fa-expand-arrows-alt me-1"></i> Expand All</button>
                        <button class="btn btn-outline-secondary btn-sm" id="collapseAllBtn"><i class="fas fa-compress-arrows-alt me-1"></i> Collapse All</button>
                    </div>
                </div>
                <div id="rootDropZone" class="drop-zone-root" tabindex="0">
                    <div class="drop-zone-hint"><i class="fas fa-cloud-upload-alt"></i><span>Drop here to make root category</span></div>
                </div>
                <ul class="category-tree sortable" id="categoryTree">
                    @foreach ($tree as $category)
                        @include('filament.resources.categories.pages.partials.category-node', ['category' => $category])
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Modal for adding subcategory -->
        {{-- <div id="addSubcategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="addSubcategoryOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative z-10 w-full max-w-md">
                <div class="bg-white rounded-lg p-6 shadow-xl">
                    <button type="button" id="modalCloseBtn" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700" aria-label="Close">×</button>
                    <h2 id="modalTitle" class="text-center text-xl font-semibold mb-6">Add Subcategory</h2>
                    <form id="addSubcategoryForm" autocomplete="off" novalidate>
                        <input type="hidden" id="parentIdInput" name="parent_id" value="">
                        <div class="mb-4">
                            <input type="text" id="categoryTitle" name="title" placeholder="Category Title" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div class="mb-4">
                            <input type="text" id="categorySlug" name="slug" placeholder="Category Slug" class="w-full border rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <select id="statusSelect" name="status" class="w-full border rounded px-3 py-2 mb-2">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <label class="inline-flex items-center gap-2 text-sm">
                                <input type="checkbox" id="featured" name="featured" class="w-4 h-4">
                                Featured Category
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="submitBtn" class="px-4 py-2 rounded text-white" style="background: linear-gradient(135deg, #6366f1, #a855f7);">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.all.min.js"></script>

    <style>
        /* All the styles from the reference code here */
        .tree-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .category-tree {
            list-style: none;
            padding: 0;
            margin: 0;
            min-height: 100px;
        }

        .category-item {
            background: #f8f9fa;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            margin: 10px 0;
            padding: 15px;
            position: relative;
            cursor: move;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .category-item:hover {
            border-color: #3b82f6;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .category-item.dragging {
            opacity: 0.8;
            transform: scale(1.02);
            background: #e6f3ff;
            border-color: #3b82f6;
            z-index: 1000;
        }

        .category-item.drag-over {
            border-color: #22c55e !important;
            background: #f0fdf4 !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2) !important;
        }

        .category-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .category-info {
            display: flex;
            align-items: center;
            flex: 1;
            gap: 15px;
        }

        .category-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            flex-shrink: 0;
            position: relative;
        }

        .category-details h6 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .category-details small {
            color: #64748b;
            font-size: 0.85rem;
            display: block;
            margin-top: 4px;
        }

        .category-level {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 12px;
            margin-left: 10px;
            color: #ffffff;
            font-weight: 500;
            display: inline-block;
        }

        .category-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-action:hover {
            transform: scale(1.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .children-container {
            margin-left: 20px;
            margin-top: 15px;
            border-left: 3px solid #e2e8f0;
            padding-left: 20px;
            position: relative;
        }

        .children-container::before {
            content: '';
            position: absolute;
            left: -3px;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 3px;
            opacity: 0.7;
        }

        .children-tree {
            list-style: none;
            padding: 0;
            margin: 0;
            min-height: 60px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .children-tree:empty {
            border: 2px dashed #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            font-size: 0.9rem;
            background: #f9fafb;
        }

        .children-tree:empty::after {
            content: 'Drop subcategories here or click + to add';
        }

        .level-0 .category-icon { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .level-0 .category-level { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .level-0 .children-container::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
        .level-1 .category-icon { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .level-1 .category-level { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .level-1 .children-container::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
        .level-2 .category-icon { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .level-2 .category-level { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .level-2 .children-container::before { background: linear-gradient(180deg, #22c55e, #16a34a); }
        .level-3 .category-icon { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .level-3 .category-level { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .level-3 .children-container::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
        .level-4 .category-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .level-4 .category-level { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .level-4 .children-container::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
        .level-5 .category-icon { background: linear-gradient(135deg, #ec4899, #db2777); }
        .level-5 .category-level { background: linear-gradient(135deg, #ec4899, #db2777); }
        .level-5 .children-container::before { background: linear-gradient(180deg, #ec4899, #db2777); }
        .level-6 .category-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .level-6 .category-level { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .level-6 .children-container::before { background: linear-gradient(180deg, #06b6d4, #0891b2); }
        .category-item[class*="level-"]:nth-child(7n+1) .category-icon { background: linear-gradient(135deg, #84cc16, #65a30d); }
        .category-item[class*="level-"]:nth-child(7n+1) .category-level { background: linear-gradient(135deg, #84cc16, #65a30d); }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #22c55e;
            color: #ffffff;
        }

        .badge-warning {
            background: #f59e0b;
            color: #ffffff;
        }

        .drop-zone-root {
            min-height: 60px;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .drop-zone-root.drag-over {
            border-color: #22c55e !important;
            background: #f0fdf4 !important;
        }

        .path-indicator {
            font-size: 0.75rem;
            color: #64748b;
            background: #f1f5f9;
            padding: 2px 8px;
            border-radius: 8px;
            margin-left: 8px;
        }

        .depth-indicator {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }

        .sortable-ghost {
            opacity: 0.5;
        }

        .sortable-chosen {
            background: #e6f3ff !important;
        }

        .sortable-drag {
            background: #ffffff !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2) !important;
            transform: rotate(5deg) !important;
        }
    </style>

    <script>
        let categoryData = @json($tree);
        let categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
        let hasChanges = false;
        let sortableInstances = [];
        const csrfToken = '{{ csrf_token() }}';

        function initializePage() {
            renderCategoryTree();
            updateStats();
            initializeAllSortables();
            initializeTooltips();
        }

        function buildCategoryPath(category, allData) {
            let path = [];
            let current = category;
            while (current && current.parent_id) {
                let parent = findCategoryById(allData, current.parent_id);
                if (parent) {
                    path.unshift(parent.title);
                    current = parent;
                } else {
                    break;
                }
            }
            return path.length > 0 ? path.join(' → ') : 'Root Level';
        }

        function buildCategoryHtml(category, allData = null) {
            allData = allData || categoryData;
            const hasChildren = category.children && category.children.length > 0;
            const statusClass = category.status === 'active' ? 'badge-success' : 'badge-warning';
            const featuredIcon = category.is_featured ? '<i class="fas fa-star text-warning" title="Featured"></i>' : '';
            const toggleIcon = hasChildren ? 'minus' : 'plus';
            const containerClass = hasChildren ? '' : 'd-none';
            const categoryPath = buildCategoryPath(category, allData);
            const maxLevelClass = category.level > 6 ? `level-${category.level % 7}` : '';

            let html = `
                <li class="category-item level-${category.level} ${maxLevelClass}" data-id="${category.id}" data-level="${category.level}" tabindex="0">
                    <div class="depth-indicator">L${category.level}</div>
                    <div class="category-content">
                        <div class="category-info">
                            <div class="category-icon">
                                <i class="fas fa-${category.level === 0 ? 'home' : 'folder'}"></i>
                            </div>
                            <div class="category-details">
                                <h6>
                                    ${category.title}
                                    ${featuredIcon}
                                    <span class="category-level">Level ${category.level}</span>
                                </h6>
                                <small>
                                    ID: ${category.id} | Slug: ${category.slug} | Children: ${category.children ? category.children.length : 0}
                                    <div class="path-indicator" title="Category Path">${categoryPath}</div>
                                </small>
                            </div>
                        </div>
                        <div class="category-actions">
                            <span class="status-badge badge ${statusClass}">${category.status}</span>
                            <button class="btn-action btn-success add-sub" data-id="${category.id}" data-bs-toggle="tooltip" title="Add subcategory" tabindex="0">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn-action btn-primary toggle-children" data-id="${category.id}" data-bs-toggle="tooltip" title="Toggle children" tabindex="0">
                                <i class="fas fa-${toggleIcon}"></i>
                            </button>
                            <button class="btn-action btn-info edit-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Edit category" tabindex="0">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-warning move-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Quick move category" tabindex="0">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                            <button class="btn-action btn-danger delete-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Delete category" tabindex="0">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="children-container ${containerClass}" data-parent="${category.id}">
                        <ul class="children-tree sortable" data-parent="${category.id}">
            `;

            if (hasChildren) {
                category.children.forEach(child => {
                    html += buildCategoryHtml(child, allData);
                });
            }

            html += `
                        </ul>
                    </div>
                </li>
            `;

            return html;
        }

        function renderCategoryTree() {
            let treeHtml = '';
            categoryData.forEach(category => {
                treeHtml += buildCategoryHtml(category);
            });
            $('#categoryTree').html(treeHtml);
            bindCategoryEvents();
        }

        function bindCategoryEvents() {
            $('.toggle-children').off('click').on('click', toggleChildren);
            $('.add-sub').off('click').on('click', function() {
                addSubcategory($(this).data('id'));
            });
            $('.edit-cat').off('click').on('click', function() {
                editCategory($(this).data('id'));
            });
            $('.move-cat').off('click').on('click', function() {
                quickMoveCategory($(this).data('id'));
            });
            $('.delete-cat').off('click').on('click', function() {
                deleteCategory($(this).data('id'));
            });
        }

        function getExpandedIds() {
            return $('.children-container:not(.d-none)').map(function() {
                return $(this).data('parent');
            }).get();
        }

        function setExpanded(ids) {
            ids.forEach(id => {
                const btn = $(`.toggle-children[data-id="${id}"]`);
                btn.find('i').removeClass('fa-plus').addClass('fa-minus');
                $(`.children-container[data-parent="${id}"]`).removeClass('d-none');
            });
        }

        function destroyAllSortables() {
            sortableInstances.forEach(instance => {
                if (instance && typeof instance.destroy === 'function') {
                    instance.destroy();
                }
            });
            sortableInstances = [];
        }

        function initializeAllSortables() {
            destroyAllSortables();
            initializeRootDropZone();
            const sortableContainers = document.querySelectorAll('.sortable');
            sortableContainers.forEach(container => {
                const sortableInstance = new Sortable(container, {
                    group: 'nested-categories',
                    animation: 200,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.category-item',
                    onStart: function(evt) {
                        evt.item.classList.add('dragging');
                        document.querySelectorAll('.children-tree').forEach(zone => {
                            if (!zone.contains(evt.item)) {
                                zone.classList.add('drop-zone-active');
                            }
                        });
                        document.getElementById('rootDropZone').classList.add('drop-zone-active');
                    },
                    onEnd: function(evt) {
                        evt.item.classList.remove('dragging');
                        document.querySelectorAll('.children-tree, #rootDropZone').forEach(zone => {
                            zone.classList.remove('drop-zone-active', 'drag-over-empty');
                        });
                        hasChanges = true;
                        const expanded = getExpandedIds();
                        updateCategoryDataFromDOM();
                        renderCategoryTree();
                        setExpanded(expanded);
                        initializeAllSortables();
                        initializeTooltips();
                        updateStats();
                        showSaveButton();
                    },
                    onMove: function(evt) {
                        const draggedId = parseInt(evt.dragged.dataset.id);
                        const targetContainer = evt.to;
                        if (targetContainer.dataset.parent) {
                            const targetParentId = parseInt(targetContainer.dataset.parent);
                            if (isDescendantOf(targetParentId, draggedId)) {
                                return false;
                            }
                        }
                        return true;
                    }
                });
                sortableInstances.push(sortableInstance);
            });
        }

        function initializeRootDropZone() {
            const rootDropZone = document.getElementById('rootDropZone');
            const rootSortable = new Sortable(rootDropZone, {
                group: 'nested-categories',
                animation: 200,
                onAdd: function(evt) {
                    document.getElementById('categoryTree').appendChild(evt.item);
                    hasChanges = true;
                    const expanded = getExpandedIds();
                    updateCategoryDataFromDOM();
                    renderCategoryTree();
                    setExpanded(expanded);
                    initializeAllSortables();
                    initializeTooltips();
                    updateStats();
                    showSaveButton();
                }
            });
            sortableInstances.push(rootSortable);
        }

        function isDescendantOf(parentId, childId) {
            const category = findCategoryById(categoryData, parentId);
            if (!category) return false;
            if (category.id === childId) return true;
            if (category.children) {
                for (let child of category.children) {
                    if (isDescendantOf(child.id, childId)) {
                        return true;
                    }
                }
            }
            return false;
        }

        function findCategoryById(categories, id) {
            for (const cat of categories) {
                if (cat.id === id) return cat;
                if (cat.children) {
                    const found = findCategoryById(cat.children, id);
                    if (found) return found;
                }
            }
            return null;
        }

        function toggleChildren(evt) {
            const btn = $(evt.target).closest('.toggle-children');
            const childrenContainer = btn.closest('.category-item').find('> .children-container');
            const icon = btn.find('i');
            childrenContainer.toggleClass('d-none');
            icon.toggleClass('fa-minus fa-plus');
        }

        function expandAll() {
            $('.children-container').removeClass('d-none');
            $('.toggle-children i').removeClass('fa-plus').addClass('fa-minus');
        }

        function collapseAll() {
            $('.children-container').addClass('d-none');
            $('.toggle-children i').removeClass('fa-minus').addClass('fa-plus');
        }

        function updateStats() {
            function countCategories(cats) {
                let count = cats.length;
                cats.forEach(cat => {
                    if (cat.children) {
                        count += countCategories(cat.children);
                    }
                });
                return count;
            }
            
            function calculateMaxDepth(cats, currentDepth = 0) {
                if (!cats || cats.length === 0) return currentDepth;
                let maxDepth = currentDepth;
                cats.forEach(cat => {
                    const categoryDepth = cat.level || currentDepth;
                    maxDepth = Math.max(maxDepth, categoryDepth);
                    if (cat.children) {
                        maxDepth = Math.max(maxDepth, calculateMaxDepth(cat.children, categoryDepth + 1));
                    }
                });
                return maxDepth;
            }
            
            const totalCount = countCategories(categoryData);
            const maxDepth = calculateMaxDepth(categoryData);
            $('#totalCategories').text(totalCount);
            $('#maxDepth').text(maxDepth);
        }

        function showSaveButton() {
            const saveBtn = $('#saveChangesBtn');
            saveBtn.addClass('btn-warning').removeClass('btn-success');
            saveBtn.html('<i class="fas fa-exclamation-triangle me-1"></i>Save Changes');
        }

        function updateCategoryDataFromDOM() {
            categoryData = [];
            const rootItems = document.querySelectorAll('#categoryTree > li');

            function processItem(item, parentId = null, level = 0, order = 0) {
                const id = parseInt(item.dataset.id);
                const original = findCategoryById(categoryDataOriginal, id);

                if (!original) return null;

                const newCategory = {
                    id: id,
                    parent_id: parentId,
                    level: level,
                    sort_order: order + 1,
                    title: original.title,
                    slug: original.slug,
                    status: original.status,
                    is_featured: original.is_featured,
                    children: [],
                    children_count: 0
                };

                const childrenContainer = item.querySelector(':scope > .children-container > .children-tree');
                if (childrenContainer) {
                    const childItems = childrenContainer.querySelectorAll(':scope > li');
                    childItems.forEach((childItem, childOrder) => {
                        const child = processItem(childItem, id, level + 1, childOrder);
                        if (child) {
                            newCategory.children.push(child);
                        }
                    });
                }

                newCategory.children_count = newCategory.children.length;
                return newCategory;
            }

            rootItems.forEach((item, order) => {
                const processed = processItem(item, null, 0, order);
                if (processed) {
                    categoryData.push(processed);
                }
            });
        }

        function saveChanges() {
            if (!hasChanges) {
                Swal.fire({ icon: 'info', title: 'No Changes', text: 'No changes to save!' });
                return;
            }

            const saveBtn = $('#saveChangesBtn');
            const originalHtml = saveBtn.html();
            saveBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Saving...').prop('disabled', true);

            const flattened = [];

            function flattenTree(cats, parentId = null) {
                cats.forEach((cat, index) => {
                    flattened.push({
                        id: cat.id,
                        parent_id: parentId,
                        sort_order: index + 1,
                        level: cat.level
                    });
                    if (cat.children && cat.children.length > 0) {
                        flattenTree(cat.children, cat.id);
                    }
                });
            }

            flattenTree(categoryData);

            $.ajax({
                url: '{{ route("admin.categories.update-tree") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    categories: flattened
                },
                success: function(response) {
                    if (response.success) {
                        hasChanges = false;
                        categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                        saveBtn.html('<i class="fas fa-check me-1"></i>Saved').addClass('btn-success').removeClass('btn-warning');
                        setTimeout(() => {
                            saveBtn.html(originalHtml).prop('disabled', false);
                        }, 2000);
                        Swal.fire({ icon: 'success', title: 'Success', text: 'Category hierarchy saved successfully!' });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.message || 'Failed to save changes' });
                        saveBtn.html(originalHtml).prop('disabled', false);
                    }
                },
                error: function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to save changes' });
                    saveBtn.html(originalHtml).prop('disabled', false);
                }
            });
        }

        function quickMoveCategory(categoryId) {
            const allCategories = [];
            
            function collectCategories(cats, level = 0, path = '') {
                cats.forEach(cat => {
                    if (cat.id !== categoryId && !isDescendantOf(cat.id, categoryId)) {
                        const indent = '&nbsp;'.repeat(level * 4);
                        const currentPath = path ? `${path} → ${cat.title}` : cat.title;
                        allCategories.push({
                            id: cat.id,
                            title: cat.title,
                            level: level,
                            path: currentPath,
                            html: `${indent}<i class="fas fa-folder"></i> ${cat.title} <small class="text-muted">(Level ${cat.level})</small>`
                        });
                        if (cat.children) {
                            collectCategories(cat.children, level + 1, currentPath);
                        }
                    }
                });
            }
            
            collectCategories(categoryData);
            
            let optionsHtml = '<option value="">-- Root Level --</option>';
            allCategories.forEach(cat => {
                optionsHtml += `<option value="${cat.id}">${cat.html}</option>`;
            });
            
            const currentCategory = findCategoryById(categoryData, categoryId);
            
            Swal.fire({
                title: `Move "${currentCategory.title}"`,
                html: `
                    <p class="text-muted mb-3">Select new parent category:</p>
                    <select id="parentSelect" class="form-control">
                        ${optionsHtml}
                    </select>
                    <div class="mt-3">
                        <small class="text-info">
                            <i class="fas fa-info-circle"></i> 
                            Current: Level ${currentCategory.level} 
                            ${currentCategory.parent_id ? '(Has Parent)' : '(Root Level)'}
                        </small>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Move Category',
                preConfirm: () => document.getElementById('parentSelect').value || null
            }).then((result) => {
                if (result.isConfirmed) {
                    moveCategory(categoryId, result.value);
                }
            });
        }

        function moveCategory(categoryId, newParentId) {
            hasChanges = true;
            let categoryToMove = null;
            
            function removeFromTree(cats) {
                for (let i = 0; i < cats.length; i++) {
                    if (cats[i].id === categoryId) {
                        categoryToMove = cats.splice(i, 1)[0];
                        return true;
                    }
                    if (cats[i].children && removeFromTree(cats[i].children)) {
                        cats[i].children_count = cats[i].children.length;
                        return true;
                    }
                }
                return false;
            }
            
            removeFromTree(categoryData);
            
            if (!categoryToMove) {
                Swal.fire('Error', 'Category not found!', 'error');
                return;
            }
            
            function updateLevels(cat, newLevel) {
                cat.level = newLevel;
                if (cat.children) {
                    cat.children.forEach(child => updateLevels(child, newLevel + 1));
                }
            }
            
            if (newParentId) {
                const newParent = findCategoryById(categoryData, parseInt(newParentId));
                if (newParent) {
                    if (!newParent.children) newParent.children = [];
                    categoryToMove.parent_id = parseInt(newParentId);
                    updateLevels(categoryToMove, newParent.level + 1);
                    newParent.children.push(categoryToMove);
                    newParent.children_count = newParent.children.length;
                }
            } else {
                categoryToMove.parent_id = null;
                updateLevels(categoryToMove, 0);
                categoryData.push(categoryToMove);
            }
            
            const expanded = getExpandedIds();
            renderCategoryTree();
            setExpanded(expanded);
            initializeAllSortables();
            initializeTooltips();
            updateStats();
            showSaveButton();
            
            Swal.fire({
                icon: 'success',
                title: 'Category Moved',
                text: `"${categoryToMove.title}" has been moved successfully!`,
                timer: 2000,
                showConfirmButton: false
            });
        }

        function addSubcategory(parentId) {
            Swal.fire({
                title: parentId ? 'Add Subcategory' : 'Add Root Category',
                html: `
                    <input id="swal-input1" class="swal2-input" placeholder="Category Title" required>
                    <input id="swal-input2" class="swal2-input" placeholder="Category Slug" required>
                    <select id="swal-input3" class="swal2-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="form-check mt-2">
                        <input type="checkbox" id="swal-input4" class="form-check-input">
                        <label for="swal-input4" class="form-check-label">Featured Category</label>
                    </div>
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const title = document.getElementById('swal-input1').value;
                    const slug = document.getElementById('swal-input2').value;
                    const status = document.getElementById('swal-input3').value;
                    const is_featured = document.getElementById('swal-input4').checked;
                    if (!title || !slug) {
                        Swal.showValidationMessage('Please enter both title and slug');
                        return false;
                    }
                    return { title, slug, status, is_featured };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { title, slug, status, is_featured } = result.value;
                    $.ajax({
                        url: '/admin/categories/create',
                        type: 'POST',
                        data: {
                            _token: csrfToken,
                            title: title,
                            slug: slug,
                            status: status,
                            is_featured: is_featured ? 1 : 0,
                            parent_id: parentId || null
                        },
                        success: function(response) {
                            if (response.success) {
                                const newCategory = response.data; // Assume response.data has the new category with id, etc.
                                if (parentId) {
                                    const parent = findCategoryById(categoryData, parentId);
                                    if (parent) {
                                        if (!parent.children) parent.children = [];
                                        newCategory.children = [];
                                        newCategory.children_count = 0;
                                        newCategory.level = parent.level + 1;
                                        parent.children.push(newCategory);
                                        parent.children_count = parent.children.length;
                                    }
                                } else {
                                    newCategory.children = [];
                                    newCategory.children_count = 0;
                                    newCategory.level = 0;
                                    categoryData.push(newCategory);
                                }
                                categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                                const expanded = getExpandedIds();
                                renderCategoryTree();
                                setExpanded(expanded);
                                initializeAllSortables();
                                initializeTooltips();
                                updateStats();
                                Swal.fire({ icon: 'success', title: 'Success', text: 'Category added successfully!', timer: 2000 });
                            } else {
                                Swal.fire('Error', response.message || 'Failed to add category', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to add category', 'error');
                        }
                    });
                }
            });
        }

        function editCategory(id) {
            window.location.href = '/admin/categories/edit';
        }

        function deleteCategory(id) {
            const category = findCategoryById(categoryData, id);
            if (!category) {
                Swal.fire('Error', 'Category not found!', 'error');
                return;
            }
            
            const hasChildren = category.children && category.children.length > 0;
            const warningText = hasChildren 
                ? `This will delete "${category.title}" and all its ${category.children_count} subcategories!`
                : `This will delete "${category.title}".`;
            
            Swal.fire({
                title: 'Are you sure?',
                text: warningText,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/categories/destroy',
                        method: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove from categoryData
                                function removeFromTree(cats) {
                                    for (let i = 0; i < cats.length; i++) {
                                        if (cats[i].id === id) {
                                            cats.splice(i, 1);
                                            return true;
                                        }
                                        if (cats[i].children && removeFromTree(cats[i].children)) {
                                            cats[i].children_count = cats[i].children.length;
                                            return true;
                                        }
                                    }
                                    return false;
                                }
                                removeFromTree(categoryData);
                                categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                                const expanded = getExpandedIds();
                                renderCategoryTree();
                                setExpanded(expanded);
                                initializeAllSortables();
                                initializeTooltips();
                                updateStats();
                                Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Category has been deleted successfully.', timer: 2000 });
                            } else {
                                Swal.fire('Error', response.message || 'Failed to delete category', 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to delete category', 'error');
                        }
                    });
                }
            });
        }

        function initializeTooltips() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                const tooltip = bootstrap.Tooltip.getInstance(el);
                if (tooltip) tooltip.dispose();
            });
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(tooltipTriggerEl => {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            initializePage();
            $('#saveChangesBtn').on('click', saveChanges);
            $('#expandAllBtn').on('click', expandAll);
            $('#collapseAllBtn').on('click', collapseAll);

            window.addEventListener('beforeunload', (e) => {
                if (hasChanges) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                }
            });
        });
    </script>
</x-filament-panels::page>