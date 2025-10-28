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
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                        <i class="fas fa-sitemap me-2"></i>Category Hierarchy - Infinite Depth
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Drag and drop any category to any level. Create unlimited subcategory levels.
                    </p>
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
                    <h4 class="text-lg font-medium text-gray-800 dark:text-gray-200">
                        <i class="fas fa-tree me-2"></i>Category Structure
                    </h4>
                    <div>
                        <button class="btn btn-success btn-sm me-2" id="saveChangesBtn">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                        <button class="btn btn-secondary btn-sm me-2" id="expandAllBtn">
                            <i class="fas fa-expand-arrows-alt me-1"></i> Expand All
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" id="collapseAllBtn">
                            <i class="fas fa-compress-arrows-alt me-1"></i> Collapse All
                        </button>
                    </div>
                </div>

                <div id="rootDropZone" class="drop-zone-root" tabindex="0">
                    <div class="drop-zone-hint">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Drop here to make root category</span>
                    </div>
                </div>

                <ul class="category-tree sortable" id="categoryTree">
                    @foreach ($tree as $category)
                        @include('filament.resources.categories.pages.partials.category-node', ['category' => $category])
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- CSS & JS Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.3/sweetalert2.all.min.js"></script>

    <style>
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

        /* Level Colors */
        .level-0 .category-icon, .level-0 .category-level { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .level-0 .children-container::before { background: linear-gradient(180deg, #ef4444, #dc2626); }
        .level-1 .category-icon, .level-1 .category-level { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .level-1 .children-container::before { background: linear-gradient(180deg, #3b82f6, #2563eb); }
        .level-2 .category-icon, .level-2 .category-level { background: linear-gradient(135deg, #22c55e, #16a34a); }
        .level-2 .children-container::before { background: linear-gradient(180deg, #22c55e, #16a34a); }
        .level-3 .category-icon, .level-3 .category-level { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .level-3 .children-container::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
        .level-4 .category-icon, .level-4 .category-level { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .level-4 .children-container::before { background: linear-gradient(180deg, #8b5cf6, #7c3aed); }
        .level-5 .category-icon, .level-5 .category-level { background: linear-gradient(135deg, #ec4899, #db2777); }
        .level-5 .children-container::before { background: linear-gradient(180deg, #ec4899, #db2777); }
        .level-6 .category-icon, .level-6 .category-level { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .level-6 .children-container::before { background: linear-gradient(180deg, #06b6d4, #0891b2); }

        .status-badge {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .badge-success { background: #22c55e; color: #fff; }
        .badge-warning { background: #f59e0b; color: #fff; }

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

        .sortable-ghost { opacity: 0.5; }
        .sortable-chosen { background: #e6f3ff !important; }
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
                } else break;
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
                                    ID: ${category.id} | Slug: ${category.slug} | Children: ${hasChildren ? category.children.length : 0}
                                    <div class="path-indicator" title="Category Path">${categoryPath}</div>
                                </small>
                            </div>
                        </div>
                        <div class="category-actions">
                            <span class="status-badge badge ${statusClass}">${category.status}</span>
                            <button class="btn-action btn-success add-sub" data-id="${category.id}" data-bs-toggle="tooltip" title="Add subcategory">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button class="btn-action btn-primary toggle-children" data-id="${category.id}" data-bs-toggle="tooltip" title="Toggle children">
                                <i class="fas fa-${toggleIcon}"></i>
                            </button>
                            <button class="btn-action btn-info edit-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Edit category">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-action btn-warning move-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Quick move category">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                            <button class="btn-action btn-danger delete-cat" data-id="${category.id}" data-bs-toggle="tooltip" title="Delete category">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="children-container ${containerClass}" data-parent="${category.id}">
                        <ul class="children-tree sortable" data-parent="${category.id}">`;

            if (hasChildren) {
                category.children.forEach(child => {
                    html += buildCategoryHtml(child, allData);
                });
            }

            html += `</ul></div></li>`;
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
            $('.add-sub').off('click').on('click', e => addSubcategory($(e.currentTarget).data('id')));
            $('.edit-cat').off('click').on('click', e => editCategory($(e.currentTarget).data('id')));
            $('.move-cat').off('click').on('click', e => quickMoveCategory($(e.currentTarget).data('id')));
            $('.delete-cat').off('click').on('click', e => deleteCategory($(e.currentTarget).data('id')));
        }

        function getExpandedIds() {
            return $('.children-container:not(.d-none)').map(function() {
                return $(this).data('parent');
            }).get();
        }

        function setExpanded(ids) {
            ids.forEach(id => {
                $(`.children-container[data-parent="${id}"]`).removeClass('d-none');
                $(`.toggle-children[data-id="${id}"] i`).removeClass('fa-plus').addClass('fa-minus');
            });
        }

        function destroyAllSortables() {
            sortableInstances.forEach(instance => instance?.destroy());
            sortableInstances = [];
        }

        function initializeAllSortables() {
            destroyAllSortables();
            initializeRootDropZone();

            document.querySelectorAll('.sortable').forEach(container => {
                const instance = new Sortable(container, {
                    group: 'nested-categories',
                    animation: 200,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.category-item',
                    onStart: evt => evt.item.classList.add('dragging'),
                    onEnd: evt => {
                        evt.item.classList.remove('dragging');
                        document.querySelectorAll('.children-tree, #rootDropZone')
                            .forEach(z => z.classList.remove('drop-zone-active', 'drag-over-empty'));
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
                    onMove: evt => {
                        const draggedId = parseInt(evt.dragged.dataset.id);
                        const targetParentId = evt.to.dataset.parent ? parseInt(evt.to.dataset.parent) : null;
                        return !targetParentId || !isDescendantOf(targetParentId, draggedId);
                    }
                });
                sortableInstances.push(instance);
            });
        }

        function initializeRootDropZone() {
            const rootZone = document.getElementById('rootDropZone');
            const rootSortable = new Sortable(rootZone, {
                group: 'nested-categories',
                animation: 200,
                onAdd: () => {
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
            const cat = findCategoryById(categoryData, parentId);
            if (!cat || cat.id === childId) return cat?.id === childId;
            return cat.children?.some(c => isDescendantOf(c.id, childId));
        }

        function findCategoryById(cats, id) {
            for (const cat of cats) {
                if (cat.id === id) return cat;
                if (cat.children) {
                    const found = findCategoryById(cat.children, id);
                    if (found) return found;
                }
            }
            return null;
        }

        function toggleChildren(e) {
            const btn = $(e.target).closest('.toggle-children');
            const container = btn.closest('.category-item').find('> .children-container');
            const icon = btn.find('i');
            container.toggleClass('d-none');
            icon.toggleClass('fa-minus fa-plus');
        }

        function expandAll() {
            $('.children-container').each(function() {
                const $container = $(this);
                if ($container.find('.children-tree > li').length > 0) {
                    $container.removeClass('d-none');
                    $container.closest('.category-item').find('.toggle-children i')
                        .removeClass('fa-plus').addClass('fa-minus');
                }
            });
        }

        function collapseAll() {
            $('.children-container').addClass('d-none');
            $('.toggle-children i').removeClass('fa-minus').addClass('fa-plus');
        }

        function updateStats() {
            const countCategories = cats => cats.reduce((c, cat) => c + 1 + (cat.children ? countCategories(cat.children) : 0), 0);
            const calculateMaxDepth = (cats, depth = 0) => {
                if (!cats.length) return depth;
                return Math.max(...cats.map(cat => calculateMaxDepth(cat.children || [], (cat.level ?? depth) + 1)), depth);
            };
            $('#totalCategories').text(countCategories(categoryData));
            $('#maxDepth').text(calculateMaxDepth(categoryData));
        }

        function showSaveButton() {
            $('#saveChangesBtn')
                .removeClass('btn-success').addClass('btn-warning')
                .html('<i class="fas fa-exclamation-triangle me-1"></i> Save Changes');
        }

        function updateCategoryDataFromDOM() {
            categoryData = [];
            const processItem = (item, parentId = null, level = 0, order = 0) => {
                const id = parseInt(item.dataset.id);
                const original = findCategoryById(categoryDataOriginal, id);
                if (!original) return null;

                const newCat = {
                    id, parent_id: parentId, level, sort_order: order + 1,
                    title: original.title, slug: original.slug,
                    status: original.status, is_featured: original.is_featured,
                    children: [], children_count: 0
                };

                const childrenList = item.querySelector(':scope > .children-container > .children-tree');
                if (childrenList) {
                    [...childrenList.children].forEach((child, i) => {
                        const c = processItem(child, id, level + 1, i);
                        if (c) newCat.children.push(c);
                    });
                }
                newCat.children_count = newCat.children.length;
                return newCat;
            };

            $('#categoryTree > li').each((i, el) => {
                const cat = processItem(el, null, 0, i);
                if (cat) categoryData.push(cat);
            });
        }

        function saveChanges() {
            if (!hasChanges) return Swal.fire({ icon: 'info', title: 'No Changes', text: 'Nothing to save!' });

            const btn = $('#saveChangesBtn').html('Saving...').prop('disabled', true);
            const original = btn.html();

            const flatten = (cats, parent = null) => cats.flatMap((c, i) => [
                { id: c.id, parent_id: parent, sort_order: i + 1, level: c.level },
                ...(c.children ? flatten(c.children, c.id) : [])
            ]);

            $.post('{{ route("admin.categories.update-tree") }}', {
                _token: csrfToken,
                categories: flatten(categoryData)
            }).done(res => {
                if (res.success) {
                    hasChanges = false;
                    categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                    btn.html('Saved').addClass('btn-success').removeClass('btn-warning');
                    setTimeout(() => btn.html(original).prop('disabled', false), 2000);
                    Swal.fire('Success', 'Hierarchy saved!', 'success');
                } else {
                    Swal.fire('Error', res.message || 'Save failed', 'error');
                    btn.html(original).prop('disabled', false);
                }
            }).fail(() => {
                Swal.fire('Error', 'Network error', 'error');
                btn.html(original).prop('disabled', false);
            });
        }

        function quickMoveCategory(id) {
            const collect = (cats, level = 0, path = '') => {
                const res = [];
                cats.forEach(cat => {
                    if (cat.id !== id && !isDescendantOf(cat.id, id)) {
                        res.push({
                            id: cat.id,
                            html: `${'&nbsp;'.repeat(level * 4)} ${cat.title} <small class="text-muted">(L${cat.level})</small>`
                        });
                        if (cat.children) res.push(...collect(cat.children, level + 1, path ? `${path} → ${cat.title}` : cat.title));
                    }
                });
                return res;
            };

            const options = collect(categoryData);
            const current = findCategoryById(categoryData, id);
            const select = `<select id="parentSelect" class="form-control"><option value="">-- Root Level --</option>${options.map(o => `<option value="${o.id}">${o.html}</option>`).join('')}</select>`;

            Swal.fire({
                title: `Move "${current.title}"`,
                html: `<p class="text-muted mb-2">New parent:</p>${select}<div class="mt-2"><small class="text-info">Current: Level ${current.level}</small></div>`,
                showCancelButton: true,
                preConfirm: () => document.getElementById('parentSelect').value || null
            }).then(r => r.isConfirmed && moveCategory(id, r.value));
        }

        function moveCategory(id, newParentId) {
            hasChanges = true;
            let moved = null;

            const remove = cats => {
                for (let i = 0; i < cats.length; i++) {
                    if (cats[i].id === id) return !!(moved = cats.splice(i, 1)[0]);
                    if (cats[i].children && remove(cats[i].children)) {
                        cats[i].children_count = cats[i].children.length;
                        return true;
                    }
                }
                return false;
            };
            remove(categoryData);

            const updateLevel = (cat, lvl) => {
                cat.level = lvl;
                cat.children?.forEach(c => updateLevel(c, lvl + 1));
            };

            if (newParentId) {
                const parent = findCategoryById(categoryData, +newParentId);
                if (parent) {
                    parent.children = parent.children || [];
                    moved.parent_id = +newParentId;
                    updateLevel(moved, parent.level + 1);
                    parent.children.push(moved);
                    parent.children_count = parent.children.length;
                }
            } else {
                moved.parent_id = null;
                updateLevel(moved, 0);
                categoryData.push(moved);
            }

            const expanded = getExpandedIds();
            renderCategoryTree();
            setExpanded(expanded);
            initializeAllSortables();
            initializeTooltips();
            updateStats();
            showSaveButton();

            Swal.fire('Moved!', `"${moved.title}" moved successfully.`, 'success');
        }

        function addSubcategory(parentId) {
            Swal.fire({
                title: parentId ? 'Add Subcategory' : 'Add Root Category',
                html: `
                    <input id="swal-input1" class="swal2-input" placeholder="Title" required>
                    <input id="swal-input2" class="swal2-input" placeholder="Slug" required>
                    <select id="swal-input3" class="swal2-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="text-start mt-2">
                        <label><input type="checkbox" id="swal-input4" class="me-2"> Featured</label>
                    </div>
                `,
                preConfirm: () => {
                    const title = $('#swal-input1').val(), slug = $('#swal-input2').val();
                    if (!title || !slug) { Swal.showValidationMessage('Title & Slug required'); return false; }
                    return { title, slug, status: $('#swal-input3').val(), is_featured: $('#swal-input4').is(':checked') };
                }
            }).then(r => {
                if (!r.isConfirmed) return;
                const { title, slug, status, is_featured } = r.value;

                $.post('/admin/categories', {
                    _token: csrfToken, title, slug, status, is_featured: is_featured ? 1 : 0, parent_id: parentId || null
                }).done(res => {
                    if (res.success) {
                        const cat = res.data;
                        cat.children = []; cat.children_count = 0;
                        cat.level = parentId ? (findCategoryById(categoryData, parentId).level + 1) : 0;

                        if (parentId) {
                            const p = findCategoryById(categoryData, parentId);
                            p.children.push(cat); p.children_count++;
                        } else {
                            categoryData.push(cat);
                        }

                        categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                        const expanded = getExpandedIds();
                        renderCategoryTree();
                        setExpanded(expanded);
                        initializeAllSortables();
                        initializeTooltips();
                        updateStats();
                        Swal.fire('Added!', 'Category created.', 'success');
                    } else {
                        Swal.fire('Error', res.message || 'Failed', 'error');
                    }
                }).fail(() => Swal.fire('Error', 'Network error', 'error'));
            });
        }

        function editCategory(id) {
            window.location = `/admin/categories/${id}/edit`;
        }

        function deleteCategory(id) {
            const cat = findCategoryById(categoryData, id);
            if (!cat) return Swal.fire('Error', 'Not found', 'error');

            Swal.fire({
                title: 'Delete?',
                text: cat.children?.length ? `Delete "${cat.title}" and ${cat.children_count} subcategories?` : `Delete "${cat.title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete!'
            }).then(r => {
                if (!r.isConfirmed) return;

                $.ajax({
                    url: `/admin/categories/${id}`, method: 'DELETE', data: { _token: csrfToken }
                }).done(res => {
                    if (res.success) {
                        const remove = cats => {
                            for (let i = 0; i < cats.length; i++) {
                                if (cats[i].id === id) { cats.splice(i, 1); return true; }
                                if (cats[i].children && remove(cats[i].children)) {
                                    cats[i].children_count = cats[i].children.length;
                                    return true;
                                }
                            }
                            return false;
                        };
                        remove(categoryData);
                        categoryDataOriginal = JSON.parse(JSON.stringify(categoryData));
                        const expanded = getExpandedIds();
                        renderCategoryTree();
                        setExpanded(expanded);
                        initializeAllSortables();
                        initializeTooltips();
                        updateStats();
                        Swal.fire('Deleted!', 'Category removed.', 'success');
                    } else {
                        Swal.fire('Error', res.message || 'Failed', 'error');
                    }
                }).fail(() => Swal.fire('Error', 'Network error', 'error'));
            });
        }

        function initializeTooltips() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                bootstrap.Tooltip.getInstance(el)?.dispose();
                new bootstrap.Tooltip(el);
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });
            initializePage();

            $('#saveChangesBtn').on('click', saveChanges);
            $('#expandAllBtn').on('click', expandAll);
            $('#collapseAllBtn').on('click', collapseAll);

            window.addEventListener('beforeunload', e => {
                if (hasChanges) (e || window.event).returnValue = 'Unsaved changes!';
            });
        });
    </script>
</x-filament-panels::page>