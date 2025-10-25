<x-filament-panels::page>
    <div class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
            Sort Categories
        </h2>

        <div class="rounded-lg bg-gray-50 dark:bg-gray-800 p-4 border border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Drag and drop categories to reorder them. Click <strong>Save Order</strong> once done.
            </p>
        </div>

        <div wire:ignore>
            <div id="category-nestable"
                class="dd bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <ol class="dd-list" id="nestable-list" style="padding-left: 0;">
                    @foreach ($tree as $category)
                        @include('filament.resources.categories.pages.partials.category-node', [
                            'category' => $category,
                        ])
                    @endforeach
                </ol>
            </div>
        </div>

        <div class="flex justify-end">
            <x-filament::button id="saveOrderBtn" color="primary">Save Order</x-filament::button>
        </div>

        <!-- Modal for adding subcategory -->
        <div id="addSubcategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div id="addSubcategoryOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <!-- Modal panel -->
            <div class="relative z-10 w-full max-w-md">
                <div class="modal bg-white rounded-lg p-8 shadow-xl relative">
                    <button type="button" id="modalCloseBtn"
                        class="modal-close absolute right-3 top-3 text-gray-500 hover:text-gray-700"
                        aria-label="Close">×</button>

                    <h2 id="modalTitle" class="text-center text-xl font-semibold mb-6">Add Subcategory</h2>

                    <form id="addSubcategoryForm" autocomplete="off" novalidate>
                        <input type="hidden" id="parentIdInput" name="parent_id" value="">

                        <div class="form-row mb-4">
                            <input type="text" id="categoryTitle" name="title" placeholder="Category Title"
                                class="w-full border rounded px-3 py-2" required />
                        </div>

                        <div class="form-row mb-4">
                            <input type="text" id="categorySlug" name="slug" placeholder="Category Slug"
                                class="w-full border rounded px-3 py-2" />
                        </div>

                        <div class="controls mb-4">
                            <select id="statusSelect" name="status" class="w-full border rounded px-3 py-2 mb-2">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                            <label class="checkbox inline-flex items-center gap-2 text-sm">
                                <input type="checkbox" id="featured" name="featured" class="w-4 h-4" />
                                Featured Category
                            </label>
                        </div>

                        <div class="actions text-center">
                            <button type="submit" id="submitBtn" class="btn-ok inline-block px-4 py-2 rounded"
                                style="background:linear-gradient(180deg,#7550ff,#5a33f2); color:#fff;">OK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>

        <style>
            #category-nestable .dd-list {
                padding-left: 0;
            }

            #category-nestable .dd-item {
                margin-bottom: 0.5rem;
            }

            .category-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
                padding: .5rem .75rem;
                border-radius: .5rem;
                background-color: var(--tw-bg-opacity, 1);
            }

            .category-left {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                min-width: 0;
                flex: 1 1 auto;
            }

            .category-left[class^="level-"]:not(.level-0) {
                padding-left: calc(16px * (1 + var(--level-offset, 0)));
            }

            .category-title {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-weight: 600;
                color: inherit;
            }

            .category-actions {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                flex: 0 0 auto;
            }

            .dd-handle.drag-handle {
                cursor: grab;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 34px;
                height: 34px;
                border-radius: 6px;
                background: transparent;
            }

            .icon-btn {
                width: 34px;
                height: 34px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                font-size: 14px;
            }

            .dd-list .dd-list {
                margin-left: 1.25rem;
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

            .level-0 .category-icon {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }

            .level-0 .category-level {
                background: linear-gradient(135deg, #ef4444, #dc2626);
            }

            .level-0 .children-container::before {
                background: linear-gradient(180deg, #ef4444, #dc2626);
            }

            .level-1 .category-icon {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
            }

            .level-1 .category-level {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
            }

            .level-1 .children-container::before {
                background: linear-gradient(180deg, #3b82f6, #2563eb);
            }

            .level-2 .category-icon {
                background: linear-gradient(135deg, #22c55e, #16a34a);
            }

            .level-2 .category-level {
                background: linear-gradient(135deg, #22c55e, #16a34a);
            }

            .level-2 .children-container::before {
                background: linear-gradient(180deg, #22c55e, #16a34a);
            }

            .level-3 .category-icon {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }

            .level-3 .category-level {
                background: linear-gradient(135deg, #f59e0b, #d97706);
            }

            .level-3 .children-container::before {
                background: linear-gradient(180deg, #f59e0b, #d97706);
            }

            .level-4 .category-icon {
                background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            }

            .level-4 .category-level {
                background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            }

            .level-4 .children-container::before {
                background: linear-gradient(180deg, #8b5cf6, #7c3aed);
            }

            .level-5 .category-icon {
                background: linear-gradient(135deg, #ec4899, #db2777);
            }

            .level-5 .category-level {
                background: linear-gradient(135deg, #ec4899, #db2777);
            }

            .level-5 .children-container::before {
                background: linear-gradient(180deg, #ec4899, #db2777);
            }

            .level-6 .category-icon {
                background: linear-gradient(135deg, #06b6d4, #0891b2);
            }

            .level-6 .category-level {
                background: linear-gradient(135deg, #06b6d4, #0891b2);
            }

            .level-6 .children-container::before {
                background: linear-gradient(180deg, #06b6d4, #0891b2);
            }

            /* .category-item[class*="level-"]:nth-child(7n+1) .category-icon {
                background: linear-gradient(135deg, #84cc16, #65a30d);
            }

            .category-item[class*="level-"]:nth-child(7n+1) .category-level {
                background: linear-gradient(135deg, #84cc16, #65a30d);
            } */
            .dd-list .dd-item {
                margin-left: 0;
            }

            .dd-list .dd-item.level-0 {
                margin-left: 0;
            }

            .dd-list .dd-item.level-1 {
                margin-left: 1.25rem;
            }

            .dd-list .dd-item.level-2 {
                margin-left: 2.5rem;
            }

            .dd-list .dd-item.level-3 {
                margin-left: 3.75rem;
            }

            .dd-list .dd-item.level-4 {
                margin-left: 5rem;
            }

            .dd-list .dd-item.level-5 {
                margin-left: 6.25rem;
            }

            .dd-list .dd-item.level-6 {
                margin-left: 7.5rem;
            }

            .open-btn {
                display: inline-block;
                background: #6b46ff;
                color: #fff;
                padding: 10px 16px;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                font-weight: 600;
                box-shadow: 0 6px 12px rgba(107, 70, 255, 0.12);
            }

            /* Overlay */
            .modal-overlay {
                position: fixed;
                inset: 0;
                /* top:0; right:0; bottom:0; left:0; */
                background: rgba(20, 24, 31, 0.55);
                display: none;
                /* hidden by default */
                align-items: center;
                justify-content: center;
                z-index: 1200;
                transition: opacity 180ms ease;
            }

            .modal-overlay.show {
                display: flex;
            }

            /* Modal card */
            .modal {
                width: 520px;
                max-width: calc(100% - 48px);
                background: #ffffff;
                border-radius: 8px;
                padding: 34px 36px 28px;
                box-shadow: 0 30px 60px rgba(12, 18, 30, 0.35);
                transform: translateY(-6px);
                position: relative;
            }

            /* Title */
            .modal h2 {
                margin: 0 0 20px 0;
                font-size: 22px;
                color: #444;
                text-align: center;
                font-weight: 600;
            }

            /* Form layout */
            .modal .form-row {
                margin-bottom: 16px;
            }

            .modal input[type="text"],
            .modal select {
                width: 100%;
                padding: 12px 14px;
                border-radius: 4px;
                border: 1px solid #e7e7ea;
                font-size: 14px;
                outline: none;
                background: #fbfbfc;
                color: #333;
            }

            .modal input[type="text"]::placeholder {
                color: #bfc4c9;
            }

            /* Slight inset effect on inputs */
            .modal input[type="text"]:focus,
            .modal select:focus {
                border-color: rgba(107, 70, 255, 0.9);
                box-shadow: 0 0 0 4px rgba(107, 70, 255, 0.06);
            }

            /* Row for select + checkbox */
            .controls {
                display: flex;
                flex-direction: column;
                gap: 10px;
                margin-bottom: 18px;
            }

            .checkbox {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
            }

            .checkbox input[type="checkbox"] {
                width: 16px;
                height: 16px;
                accent-color: #6b46ff;
                /* modern browsers */
            }

            /* Buttons area */
            .modal .actions {
                text-align: center;
                margin-top: 6px;
            }

            .btn-ok {
                background: linear-gradient(180deg, #7550ff, #5a33f2);
                color: #fff;
                padding: 10px 18px;
                border-radius: 6px;
                border: none;
                font-weight: 600;
                cursor: pointer;
                min-width: 78px;
                box-shadow: 0 8px 16px rgba(86, 56, 224, 0.18);
            }

            /* Close X in top-right (subtle) */
            .modal .close {
                position: absolute;
                right: 12px;
                top: 10px;
                width: 36px;
                height: 36px;
                border-radius: 6px;
                background: transparent;
                border: none;
                cursor: pointer;
                color: #9aa0a6;
                font-size: 18px;
            }

            @media (max-width:480px) {
                .modal {
                    padding: 20px;
                }

                .modal h2 {
                    font-size: 18px;
                }
            }

            #category-nestable .dd-list {
                padding-left: 0;
            }

            .modal {
                width: 520px;
                max-width: calc(100% - 48px);
            }

            .btn-ok {
                min-width: 78px;
            }

            #addSubcategoryModal {
                z-index: 9999 !important;
                position: fixed !important;
            }

            #addSubcategoryOverlay {
                position: fixed !important;
                inset: 0 !important;
                z-index: 9998 !important;
                width: 100vw !important;
                height: 100vh !important;
            }

            #addSubcategoryModal>div:last-child {
                z-index: 9999 !important;
                position: relative !important;
            }

            /* ================================ */
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // init nestable
                $('#category-nestable').nestable({
                    maxDepth: null
                });

                // Elements
                const modalRoot = document.getElementById('addSubcategoryModal');
                const overlay = document.getElementById('addSubcategoryOverlay');
                const closeBtn = document.getElementById('modalCloseBtn');
                const form = document.getElementById('addSubcategoryForm');
                const parentIdInput = document.getElementById('parentIdInput');
                const titleInput = document.getElementById('categoryTitle');
                const slugInput = document.getElementById('categorySlug');
                const statusSelect = document.getElementById('statusSelect');
                const featuredCheckbox = document.getElementById('featured');
                const saveOrderBtn = document.getElementById('saveOrderBtn');

                const updateTreeUrl = "{{ route('admin.categories.update-tree') }}";
                const csrfToken = "{{ csrf_token() }}";

                // Utility functions
                function openModal(parentId = null) {
                    try {
                        parentIdInput.value = parentId ?? '';
                    } catch (e) {}
                    modalRoot.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => titleInput && titleInput.focus(), 60);
                }

                function closeModal() {
                    modalRoot.style.display = 'none';
                    document.body.style.overflow = '';
                    try {
                        form.reset();
                    } catch (e) {}
                    try {
                        parentIdInput.value = '';
                    } catch (e) {}
                }

                // Open modal
                document.addEventListener('click', function(e) {
                    const openBtn = e.target.closest && e.target.closest('.open-modal-btn');
                    if (!openBtn) return;
                    const parentId = openBtn.dataset.parentId || null;
                    e.preventDefault();
                    openModal(parentId);
                });

                // Close modal
                if (closeBtn) closeBtn.addEventListener('click', e => {
                    e.preventDefault();
                    closeModal();
                });
                if (overlay) overlay.addEventListener('click', e => {
                    if (e.target === overlay) closeModal();
                });
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && modalRoot.style.display !== 'none') closeModal();
                });

                // Form submit
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const payload = {
                            parent_id: parentIdInput?.value || null,
                            title: titleInput?.value.trim() || '',
                            slug: slugInput?.value.trim() || '',
                            status: statusSelect?.value || 'active',
                            is_featured: featuredCheckbox?.checked ? 1 : 0
                        };

                        if (!payload.title || !payload.slug) {
                            alert('Title and Slug are required.');
                            titleInput?.focus();
                            return;
                        }

                        if (typeof Livewire !== 'undefined' && Livewire.emit) {
                            Livewire.emit('saveSubcategoryFromModal', payload);
                            closeModal();
                        } else {
                            console.warn('Livewire not found — implement AJAX fallback if needed.');
                        }
                    });
                }

                // Save Order button - CALL ROUTE
                if (saveOrderBtn) {
                    saveOrderBtn.addEventListener('click', function() {
                        const serialized = $('#category-nestable').nestable('serialize');

                        if (!serialized || serialized.length === 0) {
                            alert('No categories found to update.');
                            return;
                        }

                        // Make backend call
                        fetch(updateTreeUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    payload: serialized
                                })
                            })
                            .then(res => res.json().catch(() => ({})))
                            .then(data => {
                                if (data?.success) {
                                    alert('Category hierarchy updated successfully.');
                                } else {
                                    alert(data?.message || 'Failed to update category hierarchy.');
                                }
                            })
                            .catch(err => {
                                console.error('Error:', err);
                                alert('Something went wrong while updating.');
                            });
                    });
                }

                if (typeof Livewire !== 'undefined' && Livewire.on) {
                    Livewire.on('openAddSubcategoryModalClient', parentId => openModal(parentId));
                    Livewire.on('closeAddSubcategoryModalClient', () => closeModal());
                }

                // Ensure hidden initially
                if (!modalRoot.style.display || modalRoot.style.display === '') modalRoot.style.display = 'none';
            });

            const ADMIN_BASE = "{{ url('admin/categories') }}";
            window.getCategoryEditUrl = id => ADMIN_BASE + '/' + id + '/edit';
        </script>

</x-filament-panels::page>
