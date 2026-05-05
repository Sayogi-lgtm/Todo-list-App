<?php

namespace App\Filament\Resources;


use App\Filament\Resources\TaskResource\Pages;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->label('Nama Tugas'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan'),
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Tenggat Waktu'),
                        Forms\Components\Toggle::make('is_important')
                            ->label('Penting?'),
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', \Illuminate\Support\Facades\Auth::id());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_important')
                    ->boolean()
                    ->label('Bintang'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->label('Tugas'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'completed' => 'success',
                    }),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->label('Tenggat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('Selesaikan')
                    ->icon('heroicon-s-check-circle')
                    ->color('success')
                    // Tombol ini hanya muncul jika statusnya masih 'pending'
                    ->visible(fn (Task $record) => $record->status === 'pending')
                    ->action(function (Task $record) {
                        $record->update(['status' => 'completed']);
                    }),
                Tables\Actions\Action::make('Batal Selesai')
                ->icon('heroicon-s-arrow-path')
                ->color('warning')
                 // Tombol ini hanya muncul jika statusnya 'completed'
                ->visible(fn (Task $record) => $record->status === 'completed')
                ->action(function (Task $record) {
                    $record->update(['status' => 'pending']);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}