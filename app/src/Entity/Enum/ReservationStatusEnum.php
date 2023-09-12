<?php
/**
 * ReservationStatusEnum enum.
 */

namespace App\Entity\Enum;

/**
 * Enum ReservationStatusEnum.
 */
enum ReservationStatusEnum: string
{
    case STATUS_NEW = 'STATUS_NEW';
    case STATUS_CONFIRMED = 'STATUS_CONFIRMED';
    case STATUS_CANCELLED = 'STATUS_CANCELLED';
    case STATUS_RETURNED = 'STATUS_RETURNED';

    /**
     * Get the status label.
     *
     * @return string Status label
     */
    public function label(): string
    {
        return match ($this) {
            ReservationStatusEnum::STATUS_NEW => 'label.status_new',
            ReservationStatusEnum::STATUS_CONFIRMED => 'label.status_confirmed',
            ReservationStatusEnum::STATUS_CANCELLED => 'label.status_cancelled',
            ReservationStatusEnum::STATUS_RETURNED => 'label.status_returned',
            default => '',
        };
    }

}
