<?php

namespace App\Filament\Widgets;

use App\Models\Os;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OsChart extends ChartWidget
{
    protected static ?string $heading = 'Serviços por mês';

    protected function getData(): array
    {
        // Obtém o primeiro e último dia do mês atual
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Busca as OS criadas no mês atual
        $monthlyOrders = Os::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function ($date) {
                // Agrupa as OS por dia
                return Carbon::parse($date->created_at)->format('d'); // Agrupar por dia
            });

        // Prepara os dados para o gráfico
        $labels = [];
        $data = [];
        foreach (range(1, $startOfMonth->daysInMonth) as $day) {
            $labels[] = $day;
            $data[] = isset($monthlyOrders[$day]) ? count($monthlyOrders[$day]) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'OS criadas',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
