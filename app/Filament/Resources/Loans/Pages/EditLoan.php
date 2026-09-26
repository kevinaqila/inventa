<?php

namespace App\Filament\Resources\Loans\Pages;

use App\Filament\Resources\Loans\LoanResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditLoan extends EditRecord
{
    protected static string $resource = LoanResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);

        if ($this->getRecord()->status !== 'pending') {
            Notification::make()
                ->title('Peminjaman tidak dapat diubah')
                ->body('Data peminjaman yang sudah disetujui, dikembalikan, atau ditolak tidak dapat diubah kembali.')
                ->warning()
                ->send();

            $this->redirect(LoanResource::getUrl('view', ['record' => $this->getRecord()]));
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data peminjaman berhasil diperbarui';
    }
}
