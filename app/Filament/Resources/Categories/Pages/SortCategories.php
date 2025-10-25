<?php


namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Models\Category;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SortCategories extends Page
{
    protected static string $resource = CategoryResource::class;

    protected string $view = 'filament.resources.categories.pages.sort-categories';
    protected static ?string $title = 'Sort Categories';

    public array $tree = [];
    public int $totalCategories = 0;
    public int $maxDepth = 0;
    public $parentId;
    public $createModalOpen = false;
    public $newCategoryData = [
        'title' => '',
        'slug' => '',
        'status' => 'active',
        'is_featured' => false,
    ];

    public function mount(): void
    {
        $this->loadTree();
    }

    protected function loadTree(): void
    {
        // Load all categories once - use 'is_featured' instead of 'featured'
        $all = Category::orderBy('sort_order')->orderBy('id')->get([
            'id',
            'title',
            'parent_id',
            'level',
            'slug',
            'status',
            'is_featured'
        ]);

        // Index them by id
        $byId = [];
        foreach ($all as $c) {
            $byId[$c->id] = [
                'id' => $c->id,
                'title' => $c->title,
                'parent_id' => $c->parent_id,
                'level' => (int) ($c->level ?? 0),
                'slug' => $c->slug,
                'status' => $c->status,
                'is_featured' => (bool) $c->is_featured,
                'children' => [],
            ];
        }

        // Attach children to parents
        foreach ($byId as $id => &$item) {
            if ($item['parent_id'] && isset($byId[$item['parent_id']])) {
                $byId[$item['parent_id']]['children'][] = &$item;
            }
        }
        unset($item);

        // Collect roots
        $roots = [];
        foreach ($byId as $id => $item) {
            if (empty($item['parent_id'])) {
                $roots[] = $item;
            }
        }

        // Assign levels recursively
        $this->tree = $this->assignLevels($roots, 0);

        // Stats
        $this->totalCategories = count($byId);
        $this->maxDepth = $this->computeMaxDepth($this->tree);
    }

    protected function assignLevels(array $nodes, int $level): array
    {
        $out = [];
        foreach ($nodes as $node) {
            $node['level'] = $level;
            if (!empty($node['children'])) {
                $node['children'] = $this->assignLevels($node['children'], $level + 1);
            }
            $out[] = $node;
        }
        return $out;
    }

    protected function computeMaxDepth(array $nodes, int $current = 0): int
    {
        $max = $current;
        foreach ($nodes as $node) {
            $max = max($max, $current);
            if (!empty($node['children'])) {
                $max = max($max, $this->computeMaxDepth($node['children'], $current + 1));
            }
        }
        return $max;
    }

    public function saveOrder($payload): void
    {
        if (!is_array($payload)) {
            Notification::make()
                ->title('Invalid payload')
                ->warning()
                ->send();
            return;
        }

        DB::transaction(function () use ($payload) {
            $this->rebuildFromNested($payload, null, 0);
        });

        $this->loadTree();

        Notification::make()
            ->title('Category order saved')
            ->success()
            ->send();
    }

    public function deleteCategory(int $id): void
    {
        $category = Category::find($id);

        if (!$category) {
            Notification::make()
                ->title('Category not found')
                ->danger()
                ->send();
            return;
        }

        $childrenCount = Category::where('parent_id', $category->id)->count();

        if ($childrenCount > 0) {
            Notification::make()
                ->title('Cannot delete category')
                ->body('This category has child categories. Delete all child categories first.')
                ->warning()
                ->send();
            return;
        }

        $category->delete();

        $this->loadTree();

        Notification::make()
            ->title('Category deleted')
            ->success()
            ->send();
    }

    protected function rebuildFromNested(array $nodes, $parentId = null, int $level = 0): void
    {
        $position = 0;
        foreach ($nodes as $node) {
            $position++;
            $category = Category::find($node['id']);
            if (!$category) {
                continue;
            }

            $category->parent_id = $parentId;
            $category->level = $level;
            $category->sort_order = $position;
            $category->path = $this->calcPathFor($category);
            $category->saveQuietly();

            if (!empty($node['children']) && is_array($node['children'])) {
                $this->rebuildFromNested($node['children'], $node['id'], $level + 1);
            }
        }
    }

    protected function calcPathFor(Category $category): string
    {
        $path = [];
        $parent = $category->parent_id ? Category::find($category->parent_id) : null;
        while ($parent) {
            array_unshift($path, (string) $parent->id);
            $parent = $parent->parent_id ? Category::find($parent->parent_id) : null;
        }
        $path[] = (string) $category->id;
        return implode('/', $path);
    }

    public function openCreateModal($parentId): void
    {
        $this->parentId = $parentId;
        $this->createModalOpen = true;
    }

    public function saveSubcategoryFromModal($payload): void
    {
        $parentId = $payload['parent_id'] ?? null;
        $title = trim($payload['title'] ?? '');
        $slug = trim($payload['slug'] ?? '');
        $status = $payload['status'] ?? 'active';
        $isFeatured = $payload['is_featured'] ?? false;

        if ($title === '' || $slug === '') {
            Notification::make()
                ->title('Validation Error')
                ->body('Title and Slug are required.')
                ->danger()
                ->send();
            return;
        }

        // Determine parent level
        $parentLevel = 0;
        if ($parentId) {
            $parentCategory = Category::find($parentId);
            if ($parentCategory) {
                $parentLevel = $parentCategory->level;
            }
        }

        // Create new subcategory
        $category = Category::create([
            'title' => $title,
            'slug' => $slug,
            'parent_id' => $parentId,
            'level' => $parentLevel + 1,
            'status' => $status,
            'is_featured' => $isFeatured,
            'sort_order' => (Category::where('parent_id', $parentId)->max('sort_order') ?? 0) + 1,
            'has_children' => 0,
            'children_count' => 0,
            'products_count' => 0,
        ]);

        // Reload tree for real-time UI update
        $this->loadTree();

        Notification::make()
            ->title('Subcategory Added')
            ->body("“{$category->title}” added successfully.")
            ->success()
            ->send();
    }
    public function updateTree(Request $request): JsonResponse
    {
        $payload = $request->input('payload', []);

        if (!is_array($payload) || empty($payload)) {
            Notification::make()->title('Invalid payload')->warning()->send();
            return response()->json(['success' => false, 'message' => 'Invalid payload']);
        }

        try {
            DB::transaction(function () use ($payload) {
                // Rebuild tree using helper that expects nested nodes (id + children)
                $this->rebuildFromNested($payload, null, 0);
                // After rebuild, fix paths and children_count in one pass
                $this->recalculateCountsAndPaths();
            });

            // reload tree
            $this->loadTree();

            Notification::make()
                ->title('Category hierarchy updated')
                ->success()
                ->send();

            return response()->json(['success' => true, 'message' => 'Category hierarchy updated']);
        } catch (\Throwable $e) {
            Log::error('Error updating category tree: ' . $e->getMessage());

            Notification::make()
                ->title('Failed to update category tree')
                ->danger()
                ->send();

            return response()->json(['success' => false, 'message' => 'Failed to update category tree']);
        }
    }

    /**
     * Recalculate path, children_count, has_children for all categories (fast single pass).
     */
    protected function recalculateCountsAndPaths(): void
    {
        // Recompute children_count for every category.
        // First reset counts
        Category::query()->update(['children_count' => 0, 'has_children' => 0]);

        // Compute children_count using aggregated query
        $counts = Category::select('parent_id', DB::raw('COUNT(*) as cnt'))
            ->whereNotNull('parent_id')
            ->groupBy('parent_id')
            ->pluck('cnt', 'parent_id')
            ->toArray();

        foreach ($counts as $parentId => $cnt) {
            Category::where('id', $parentId)->update([
                'children_count' => $cnt,
                'has_children' => $cnt > 0 ? 1 : 0,
            ]);
        }

        // Recompute paths for all categories. We can do BFS/recursive approach.
        $all = Category::select('id', 'parent_id')->get()->keyBy('id')->toArray();

        // Compute path for each category by walking up parents
        foreach ($all as $id => $row) {
            $pathParts = [];
            $parent = $row['parent_id'];
            while ($parent && isset($all[$parent])) {
                array_unshift($pathParts, (string) $parent);
                $parent = $all[$parent]['parent_id'];
            }
            $path = count($pathParts) ? implode('/', $pathParts) : null;
            Category::where('id', $id)->update(['path' => $path]);
        }
    }
}