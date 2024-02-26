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
use Leandrocfe\FilamentPtbrFormFields\Money;

class OsResource extends Resource
{
    protected static ?string $model = Os::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
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
                            ->schema([
                                Forms\Components\Select::make('produto_id')
                                    ->relationship('produtos', 'nome')
                                    ->placeholder('Selecione um produto')
                                    ->loadingMessage('Carregando produtos...')
                                    ->searchPrompt('Buscar produto...')
                                    ->noSearchResultsMessage('Produto encontrado')
                                    ->preload()
                                    ->columnSpan(4)
                                    ->searchable(),
                                Forms\Components\TextInput::make('quantidade')
                                    ->integer()
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
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->columnSpan(2)
                            ->default('Aberto')
                            ->options([
                                'Aberto' => 'Aberto',
                                'Fechado' => 'Fechado',
                                'Cancelado' => 'Cancelado',
                                'Aguardando peças' => 'Aguardando peças',
                                'Aguardando aprovação' => 'Aguardando aprovação',
                                'Aguardando orçamento' => 'Aguardando orçamento',
                                'Aguardando retirada' => 'Aguardando retirada',
                                'Aguardando entrega' => 'Aguardando entrega',
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
                Tables\Columns\TextColumn::make('funcionario.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cliente.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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

        $set('valor_total', $total);
    }

    public static function updateSubtotal(Forms\Get $get, Forms\Set $set)
    {
        $produtosSelecionados = collect($get('itens'))->filter(fn($item) => !empty($item['produto_id']) && !empty($item['quantidade']));
        $valores = Produto::find($produtosSelecionados->pluck('produto_id'))->pluck('valor', 'id');

        $subtotal = $produtosSelecionados->reduce(function ($subtotal, $item) use ($valores) {
            return $subtotal + ($valores[$item['produto_id']] * $item['quantidade']);
        }, 0);

        $set('subtotal', $subtotal);
    }
}
