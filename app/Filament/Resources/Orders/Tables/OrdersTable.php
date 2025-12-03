<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Orders\OrderResource;


class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // eager-load user to avoid N+1
            ->query(Order::with('user'))
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Name')
                    ->formatStateUsing(function ($state, $record) {
                        if (!empty($state)) {
                            return $state;
                        }
                        return trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? ''));
                    })
                    ->searchable(), 

                TextColumn::make('user.email')
                    ->label('Email')
                    ->formatStateUsing(function ($state, $record) {
                        return $state ?? $record->email;
                    })
                    ->searchable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),

                TextColumn::make('sub_total')
                    ->label('Charge')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float) $state, 2))
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->formatStateUsing(fn ($state) => '$' . number_format((float) $state, 2))
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'primary' => Order::STATUS_NEW,
                        'warning' => Order::STATUS_PROCESS,
                        'success' => Order::STATUS_DELIVERED,
                        'danger' => Order::STATUS_CANCELLED,
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
                    
            ])
//             ->actions([
//     ViewAction::make('view')
//         ->label('View')
//         ->icon('heroicon-o-eye')
//         ->action(function (Order $record, array $data) {
//             // This action will not be executed because we will show a modal
//         })
//         ->modalHeading('Order Details')
//         ->modalContent(function (Order $record) {
//             return view('admin.orders.view', compact('record'))->render();
//         })
//         ->modalWidth('7xl') // wider modal to resemble bill layout
// ])
        //     ->actions([
        //         ViewAction::make()
        //          ->modalContent(function (Order $record) {
        //     return view('admin.orders.view', compact('record'))->render();
        // }),
        //         EditAction::make(),
        //         DeleteAction::make(),
        //     ])
        // ... (keep the top of your file unchanged)
                ->actions([
                ViewAction::make()
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record])),

                    EditAction::make(),
                    DeleteAction::make(),
                ])

            ->filters([])
            ->bulkActions([]);
    }
}
