<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ViewOrder extends ViewRecord
{
  protected static string $resource = OrderResource::class;

  // Make Livewire use this blade as the page view (like SortCategories)
  protected string $view = 'admin.orders.view';

}
