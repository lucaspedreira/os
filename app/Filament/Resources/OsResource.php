<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OsResource\Pages;
use App\Filament\Resources\OsResource\RelationManagers;
use App\Models\Os;
use App\Models\Produto;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\Money;

class OsResource extends Resource
{
    protected static ?string $model = Os::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $produtos = Produto::get();

        return $form
            ->schema([
                Forms\Components\Section::make('Order de Serviço')
                    ->description('Preencha os campos abaixo para criar uma nova ordem de serviço.')
                    ->collapsible()
                    ->columns(4)
                    ->persistCollapsed()
                    ->schema([
                        Forms\Components\Select::make('funcionario_id')
                            ->columnSpan(2)
                            ->label('Funcionário')
                            ->relationship('funcionario', 'nome')
                            ->placeholder('Selecione um funcionário')
                            ->loadingMessage('Carregando funcionários...')
                            ->searchPrompt('Buscar funcionário...')
                            ->preload()
                            ->searchable()
                            ->native(false)
                            ->required(),
                        Forms\Components\Select::make('cliente_id')
                            ->columnSpan(2)
                            ->relationship('cliente', 'nome')
                            ->placeholder('Selecione um cliente')
                            ->loadingMessage('Carregando clientes...')
                            ->searchPrompt('Buscar cliente...')
                            ->preload()
                            ->searchable()
                            ->native(false)
                            ->required(),
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->placeholder('Descreva o serviço')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Itens da Ordem de Serviço')
                    ->schema([
                        Repeater::make('itens')
                            ->columns(6)
                            ->hiddenLabel()
                            ->relationship()
                            ->defaultItems(0)
                            ->schema([
                                Forms\Components\Select::make('produto_id')
                                    ->relationship('produto', 'nome')
                                    ->options(
                                        $produtos->mapWithKeys(function (Produto $produto) {
                                            return [$produto->id => sprintf('%s - %s', $produto->nome, number_format($produto->valor, 2, ',', '.'))];
                                        })
                                    )
                                    ->label('Produto')
                                    ->placeholder('Selecione um produto')
                                    ->loadingMessage('Carregando produtos...')
                                    ->searchPrompt('Buscar produto...')
                                    ->noSearchResultsMessage('Produto encontrado')
                                    ->preload()
                                    ->columnSpan(4)
                                    ->searchable()
                                    ->required()
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        $produto = Produto::find($get('produto_id'));
                                        $set('subtotal', number_format($produto->valor, 2, ',', '.'));
                                    }),
                                Forms\Components\TextInput::make('quantidade')
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                        if ($get('produto_id') === null) return;

                                        $produto = Produto::find($get('produto_id'));
                                        if (!$produto) return;

                                        // Ajuste da quantidade baseada no estoque disponível
                                        $quantidadeAjustada = min($get('quantidade'), $produto->quantidade);
                                        $set('quantidade', $quantidadeAjustada);

                                        // Calcula o subtotal com a quantidade ajustada
                                        $subtotal = $produto->valor * $quantidadeAjustada;
                                        $set('subtotal', number_format($subtotal, 2, ',', '.'));
                                    })
                                    ->integer()
                                    ->minValue(1)
                                    // O maxValue não precisa de uma função, pois o ajuste é feito acima.
                                    ->default(1)
                                    ->required(),

                                Money::make('subtotal')
                                    ->readOnly(),
                            ])
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                self::updateTotal($get, $set);
                            }),
                    ]),
                Forms\Components\Section::make()
                    ->columns(6)
                    ->schema([
                        Forms\Components\DatePicker::make('data')
                            ->displayFormat('d/m/Y')
                            ->default(date(now()))
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->columnSpan(2)
                            ->default('aberto')
                            ->options([
                                'aberto' => 'Aberto',
                                'fechado' => 'Fechado',
                                'cancelado' => 'Cancelado',
                                'aguardando_peca' => 'Aguardando peças',
                                'aguardando_aprovacao' => 'Aguardando aprovação',
                                'aguardando_orcamento' => 'Aguardando orçamento',
                                'aguardando_retirada' => 'Aguardando retirada',
                                'aguardando_entrega' => 'Aguardando entrega',
                            ])
                            ->placeholder('Selecione um status')
                            ->required(),
                        Money::make('valor_total')
                            ->readOnly()
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nome')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'aberto' => 'Aberto',
                        'fechado' => 'Fechado',
                        'cancelado' => 'Cancelado',
                        'aguardando_peca' => 'Aguardando peças',
                        'aguardando_aprovacao' => 'Aguardando aprovação',
                        'aguardando_orcamento' => 'Aguardando orçamento',
                        'aguardando_retirada' => 'Aguardando retirada',
                        'aguardando_entrega' => 'Aguardando entrega',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'aberto' => 'success',
                        'fechado' => 'gray',
                        'cancelado' => 'danger',
                        'aguardando_peca', 'aguardando_orcamento', 'aguardando_retirada', 'aguardando_entrega' => 'warning',
                        'aguardando_aprovacao' => 'info',
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor_total')
                    ->label('Total R$')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.'
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('cliente_id')
                    ->relationship('cliente', 'nome')
                    ->label('Cliente')
                    ->placeholder('Todos os clientes'),
                // Filtrar por data
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->displayFormat('d/m/Y')
                            ->native(false)
                            ->label('Data de criação')
                            ->placeholder('Selecione uma data')
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\DatePicker::make('until')
                            ->displayFormat('d/m/Y')
                            ->format('d/m/Y')
                            ->native(false)
                            ->label('Até')
                            ->placeholder('Selecione uma data')
                            ->closeOnDateSelection()
                            ->required(),
                    ])->query(function (Builder $query, array $data) {
                        $query->when(filled($data['from'] && filled($data['until'])), function (Builder $q) use ($data) {
                            $dataFrom = \Illuminate\Support\Carbon::parse($data['from'])->startOfDay()->toDateTimeString();
                            $dataUntil = \Illuminate\Support\Carbon::parse($data['until'])->endOfDay()->toDateTimeString();

                            return $q->whereBetween('created_at', [
                                $dataFrom,
                                $dataUntil,
                            ]);

                        });
                    }),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aberto' => 'Aberto',
                        'fechado' => 'Fechado',
                        'cancelado' => 'Cancelado',
                        'aguardando_peca' => 'Aguardando peças',
                        'aguardando_aprovacao' => 'Aguardando aprovação',
                        'aguardando_orcamento' => 'Aguardando orçamento',
                        'aguardando_retirada' => 'Aguardando retirada',
                        'aguardando_entrega' => 'Aguardando entrega',
                    ])
                    ->label('Status')
                    ->placeholder('Todos os status'),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOs::route('/'),
            'create' => Pages\CreateOs::route('/create'),
            'edit' => Pages\EditOs::route('/{record}/edit'),
        ];
    }

    public static function updateTotal(Forms\Get $get, Forms\Set $set)
    {
        $produtosSelecionados = collect($get('itens'))->filter(fn($item) => !empty($item['produto_id']) && !empty($item['quantidade']));
        $valores = Produto::find($produtosSelecionados->pluck('produto_id'))->pluck('valor', 'id');

        $total = $produtosSelecionados->reduce(function ($total, $item) use ($valores) {
            return $total + ($valores[$item['produto_id']] * $item['quantidade']);
        }, 0);

        $set('valor_total', number_format($total, 2, ',', '.'));
    }
}
