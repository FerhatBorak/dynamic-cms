<?php

namespace App\Filament\Resources\HomepageContentResource\Pages;

use App\Filament\Resources\HomepageContentResource;
use App\Filament\Resources\HomepageSectionResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListHomepageContents extends ListRecords
{
    protected static string $resource = HomepageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Yeni Anasayfa Bölümü')
                ->url(HomepageSectionResource::getUrl('create'))
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}
