<?php

namespace App\Helpers;

class FlightPriceHelper
{
    const TAX_PER_PERSON = 120000;      // Thuế & Phí an ninh sân bay
    const SERVICE_FEE_PER_PERSON = 50000; // Phí xuất vé của Đại lý
    const INFANT_FIXED_FEE = 150000;     // Phí em bé cố định
    const CHILD_BASE_PERCENT = 0.8;      // Trẻ em hưởng 80% giá gốc người lớn
    const BUSINESS_MULTIPLIER = 1.5;     // Thương gia nhân 1.5
    const VAT_RATE = 0.1;                // Thuế giá trị gia tăng 10%

    /**
     * Calculate comprehensive price breakdown for a flight selection
     */
    public static function calculate($outbound, $return = null, $adults = 1, $children = 0, $infants = 0, $ticketClass = 'economy')
    {
        $multiplier = ($ticketClass === 'business') ? self::BUSINESS_MULTIPLIER : 1;
        $segmentsCount = $return ? 2 : 1;

        // 1. Base Fare Calculation (per person)
        $outboundPrice = $outbound->price * $multiplier;
        $returnPrice = $return ? ($return->price * $multiplier) : 0;
        
        $basePerAdult = $outboundPrice + $returnPrice;
        $basePerChild = $basePerAdult * self::CHILD_BASE_PERCENT;

        // 2. Aggregates
        $totalBaseAdults = $basePerAdult * $adults;
        $totalBaseChildren = $basePerChild * $children;
        $totalBaseFare = $totalBaseAdults + $totalBaseChildren;

        // 3. Service Charges (Applied per segment per person - Adults & Children)
        $travelersCount = $adults + $children;
        $totalService = self::SERVICE_FEE_PER_PERSON * $travelersCount * $segmentsCount;

        // 4. VAT Calculation (VAT = (Base Fare + Surcharges) * 10%)
        $taxableAmount = $totalBaseFare + $totalService;
        $totalVat = $taxableAmount * self::VAT_RATE;

        // 5. Infant Fees (Fixed per segment)
        $totalInfantFee = self::INFANT_FIXED_FEE * $infants * $segmentsCount;

        // 6. Final Total
        $grandTotal = $totalBaseFare + $totalService + $totalVat + $totalInfantFee;

        // 7. Return structured data for view/controller
        return [
            'base_adult_single' => $basePerAdult,
            'base_child_single' => $basePerChild,
            'total_base_fare' => $totalBaseFare,
            'total_service' => $totalService,
            'total_vat' => $totalVat,
            'total_infant' => $totalInfantFee,
            'grand_total' => $grandTotal,
            // Per person totals
            'segments' => $segmentsCount,
            'service_unit' => self::SERVICE_FEE_PER_PERSON,
            'vat_rate' => self::VAT_RATE
        ];
    }
}
