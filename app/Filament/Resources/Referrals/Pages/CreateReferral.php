<?php

namespace App\Filament\Resources\Referrals\Pages;

use App\Filament\Resources\Referrals\ReferralResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReferral extends CreateRecord
{
    protected static string $resource = ReferralResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Generate unique code if not provided
        if (empty($data['code'])) {
            $data['code'] = \Wave\Referral::generateUniqueCode();
        }

        return $data;
    }
}
