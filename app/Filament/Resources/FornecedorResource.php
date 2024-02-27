<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FornecedorResource\Pages;
use App\Filament\Resources\FornecedorResource\RelationManagers;
use App\Models\Fornecedor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Fornecedores';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(4)
                    ->schema([
                        Forms\Components\TextInput::make('nome')
                            ->columnSpan(2)
                            ->required(),
                        Document::make('cnpj')
                            ->label('CNPJ')
                            ->cnpj()
                            ->placeholder('99.999.999/9999-99')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        PhoneNumber::make('telefone_fixo')
                            ->format('(99) 9999-9999')
                            ->placeholder('(99) 9999-9999'),
                        PhoneNumber::make('telefone_celular')
                            ->format('(99) 99999-9999')
                            ->placeholder('(99) 99999-9999'),
                    ]),
                Forms\Components\Section::make()
                    ->columns(4)
                    ->relationship('endereco', 'enderecoable')
                    ->schema([
                        Cep::make('cep')
                            ->label('CEP')
                            ->viaCep(
                                errorMessage: 'CEP inválido.',
                                setFields: [
                                    'logradouro' => 'logradouro',
                                    'numero' => 'numero',
                                    'complemento' => 'complemento',
                                    'bairro' => 'bairro',
                                    'cidade' => 'localidade',
                                    'estado' => 'uf'
                                ]
                            ),
                        Forms\Components\TextInput::make('logradouro')
                            ->label('Rua')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('numero')
                            ->label('Número')
                            ->required(),
                        Forms\Components\TextInput::make('complemento')
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('bairro')
                            ->columnSpan(2)
                            ->required(),
                        Forms\Components\TextInput::make('cidade')
                            ->required(),
                        Forms\Components\TextInput::make('estado')
                            ->required(),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnpj')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefone_fixo')
                    ->searchable(),
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
            'index' => Pages\ListFornecedors::route('/'),
            'create' => Pages\CreateFornecedor::route('/create'),
            'edit' => Pages\EditFornecedor::route('/{record}/edit'),
        ];
    }
}
