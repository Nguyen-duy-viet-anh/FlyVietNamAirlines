<?php

namespace App\Helpers;
/**
 * FlightPriceHelper
 * Giúp tính toán bảng bóc tách giá vé cho từng loại hành khách và loại vé.
 * Trả về mảng chứa các tổng và breakdown để hiển thị trên trang review/checkout.
 */
class FlightPriceHelper
{
    const SERVICE_FEE_PER_PERSON = 50000;  // Phí xuất vé của Đại lý (mỗi chặng)
    const INFANT_FIXED_FEE = 150000;      // Phí em bé cố định (nếu không dùng % giá vé)
    const CHILD_BASE_PERCENT = 0.9;       // Trẻ em hưởng 90% giá gốc người lớn
    const BUSINESS_MULTIPLIER = 1.5;      // Thương gia nhân 1.5
    const VAT_RATE = 0.1;                 // Thuế giá trị gia tăng 10%

    /**
     * Calculate comprehensive price breakdown for a flight selection
     * FORMULA (Removed Airport Fee):
     * Total = BaseFare + VAT (10% Base) + ServiceFee (50k)
     * Child = (90% Base) + VAT (10% ChildBase) + ServiceFee (50k)
     * Infant = (10% Base) + VAT (10% InfantBase) + ServiceFee (50k)
     */
    public static function calculate($outbound, $return = null, $adults = 1, $children = 0, $infants = 0, $ticketClass = 'economy', $returnTicketClass = null)
    {
        $outboundMultiplier = ($ticketClass === 'business') ? self::BUSINESS_MULTIPLIER : 1;
        $returnMultiplier = (($returnTicketClass ?? $ticketClass) === 'business') ? self::BUSINESS_MULTIPLIER : 1;

        $segmentsCount = $return ? 2 : 1;
        $serviceFee = self::SERVICE_FEE_PER_PERSON;

        // --- 1. Outbound Leg Pricing ---
        $outBase = $outbound->price * $outboundMultiplier;
        $outVAT = $outBase * self::VAT_RATE;
        $outAdult = $outBase + $outVAT + $serviceFee;

        $outChildBase = $outBase * self::CHILD_BASE_PERCENT;
        $outChildVAT = $outChildBase * self::VAT_RATE;
        $outChild = $outChildBase + $outChildVAT + $serviceFee;

        $outInfantBase = $outBase * 0.1;
        $outInfantVAT = $outInfantBase * self::VAT_RATE;
        $outInfant = $outInfantBase + $outInfantVAT + $serviceFee;

        // --- 2. Return Leg Pricing (if applicable) ---
        $retAdult = 0; $retChild = 0; $retInfant = 0;
        $retBase = 0; $retVAT = 0;
        $retChildBase = 0; $retChildVAT = 0;
        $retInfantBase = 0; $retInfantVAT = 0;

        if ($return) {
            $retBase = $return->price * $returnMultiplier;
            $retVAT = $retBase * self::VAT_RATE;
            $retAdult = $retBase + $retVAT + $serviceFee;

            $retChildBase = $retBase * self::CHILD_BASE_PERCENT;
            $retChildVAT = $retChildBase * self::VAT_RATE;
            $retChild = $retChildBase + $retChildVAT + $serviceFee;

            $retInfantBase = $retBase * 0.1;
            $retInfantVAT = $retInfantBase * self::VAT_RATE;
            $retInfant = $retInfantBase + $retInfantVAT + $serviceFee;
        }

        // --- 3. Totals per type ---
        $totalAdultsBase = ($outBase + $retBase) * $adults;
        $totalAdultsVAT = ($outVAT + $retVAT) * $adults;
        $totalAdultsService = $serviceFee * $segmentsCount * $adults;
        $totalAdultsFull = $totalAdultsBase + $totalAdultsVAT + $totalAdultsService;

        $totalChildrenBase = ($outChildBase + $retChildBase) * $children;
        $totalChildrenVAT = ($outChildVAT + $retChildVAT) * $children;
        $totalChildrenService = $serviceFee * $segmentsCount * $children;
        $totalChildrenFull = $totalChildrenBase + $totalChildrenVAT + $totalChildrenService;

        $totalInfantsBase = ($outInfantBase + $retInfantBase) * $infants;
        $totalInfantsVAT = ($outInfantVAT + $retInfantVAT) * $infants;
        $totalInfantsService = $serviceFee * $segmentsCount * $infants;
        $totalInfantsFull = $totalInfantsBase + $totalInfantsVAT + $totalInfantsService;

        $grandTotal = $totalAdultsFull + $totalChildrenFull + $totalInfantsFull;

        return [
            'total_adults_full' => $totalAdultsFull,
            'total_children_full' => $totalChildrenFull,
            'total_infant_full' => $totalInfantsFull,
            'grand_total' => $grandTotal,

            'total_base' => $totalAdultsBase + $totalChildrenBase + $totalInfantsBase,
            'total_vat' => $totalAdultsVAT + $totalChildrenVAT + $totalInfantsVAT,
            'total_airport_fees' => 0, // Removed per request
            'total_service' => $totalAdultsService + $totalChildrenService + $totalInfantsService,

            'total_adults_base' => $totalAdultsBase,
            'total_adults_vat' => $totalAdultsVAT,
            'total_adults_airport' => 0,
            'total_adults_service' => $totalAdultsService,

            'total_children_base' => $totalChildrenBase,
            'total_children_vat' => $totalChildrenVAT,
            'total_children_airport' => 0,
            'total_children_service' => $totalChildrenService,

            'total_infants_base' => $totalInfantsBase,
            'total_infants_vat' => $totalInfantsVAT,
            'total_infants_service' => $totalInfantsService,

            'segments' => $segmentsCount,
        ];
    }
}
