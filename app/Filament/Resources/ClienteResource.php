<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClienteResource\Pages;
use App\Filament\Resources\ClienteResource\RelationManagers;
use App\Models\Cliente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Cep;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Clientes';

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
                        Document::make('cpf')
                            ->label('CPF')
                            ->cpf()
                            ->placeholder('999.999.999-99')
                            ->required(),
                        Forms\Components\Select::make('sexo')
                            ->options([
                                'M' => 'Masculino',
                                'F' => 'Feminino',
                            ])->placeholder('Selecione o sexo')
                            ->native(false)
                            ->required(),
                        Forms\Components\DatePicker::make('data_nascimento')
                            ->placeholder('dd/mm/aaaa')
                            ->label('Data de Nascimento'),
                        Forms\Components\TextInput::make('email')
                            ->label('E-mail')
                            ->email(),
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
                Tables\Columns\TextColumn::make('email')
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
            'index' => Pages\ListClientes::route('/'),
            'create' => Pages\CreateCliente::route('/create'),
            'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
