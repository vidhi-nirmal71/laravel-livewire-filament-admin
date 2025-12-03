<x-filament-panels::page>
    {{-- Header Stats: 4 in one row --}}
    <x-filament-panels::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="4"
    />

    {{-- Charts: Side by side --}}
    <x-filament-panels::widgets
        :widgets="$this->getWidgets()"
        :columns="$this->getColumns()"
    />
</x-filament-panels::page>