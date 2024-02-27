<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Os;
use App\Models\Produto;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Clientes', function () {
                return Cliente::count();
            }),
            Stat::make('Produtos', function () {
                return Produto::count();
            }),
            Stat::make('OS', function () {
                return Os::count();
            }),
        ];
    }
}
