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
     * NEW FORMULA:
     * Adult = Base + Tax (120k) + Fee (50k)
     * Child = 90% * Adult
     * Infant = 10% * Adult + Tax (120k) + Fee (50k)
     */
    public static function calculate($outbound, $return = null, $adults = 1, $children = 0, $infants = 0, $ticketClass = 'economy')
    {
        $multiplier = ($ticketClass === 'business') ? self::BUSINESS_MULTIPLIER : 1;
        $segmentsCount = $return ? 2 : 1;
        $fixedFees = self::TAX_PER_PERSON + self::SERVICE_FEE_PER_PERSON;

        // --- 1. Outbound Leg Pricing ---
        $outboundBase = $outbound->price * $multiplier;
        $outboundAdult = $outboundBase + $fixedFees;
        $outboundChild = $outboundAdult * 0.9;
        $outboundInfant = ($outboundAdult * 0.1) + $fixedFees;

        // --- 2. Return Leg Pricing (if applicable) ---
        $returnAdult = 0;
        $returnChild = 0;
        $returnInfant = 0;
        $returnBase = 0;

        if ($return) {
            $returnBase = $return->price * $multiplier;
            $returnAdult = $returnBase + $fixedFees;
            $returnChild = $returnAdult * 0.9;
            $returnInfant = ($returnAdult * 0.1) + $fixedFees;
        }

        // --- 3. Combined Totals per Person Type ---
        $totalAdultPerPerson = $outboundAdult + $returnAdult;
        $totalChildPerPerson = $outboundChild + $returnChild;
        $totalInfantPerPerson = $outboundInfant + $returnInfant;

        // --- 4. Final Aggregates ---
        $totalAdultsFull = $totalAdultPerPerson * $adults;
        $totalChildrenFull = $totalChildPerPerson * $children;
        $totalInfantFull = $totalInfantPerPerson * $infants;

        $grandTotal = $totalAdultsFull + $totalChildrenFull + $totalInfantFull;

        // 7. Return structured data for view/controller
        return [
            'total_adults_full' => $totalAdultsFull,
            'total_children_full' => $totalChildrenFull,
            'total_infant_full' => $totalInfantFull,
            'grand_total' => $grandTotal,

            // Summary keys for easier rendering in Review/Checkout pages
            'total_base' => (($outboundBase + $returnBase) * $adults) + 
                            (($totalAdultPerPerson * 0.9 - $fixedFees) * $children) + 
                            (($totalAdultPerPerson * 0.1) * $infants),
            
            'total_service' => self::SERVICE_FEE_PER_PERSON * ($adults + $children + $infants) * $segmentsCount,
            
            'total_tax_fees' => self::TAX_PER_PERSON * ($adults + $children + $infants) * $segmentsCount,

            'total_adults_base' => ($outboundBase + $returnBase) * $adults,
            'total_adults_service' => self::SERVICE_FEE_PER_PERSON * $adults * $segmentsCount,
            'total_adults_vat' => self::TAX_PER_PERSON * $adults * $segmentsCount,

            'total_children_base' => ($totalAdultPerPerson * 0.9 - $fixedFees) * $children, 
            'total_children_service' => self::SERVICE_FEE_PER_PERSON * $children * $segmentsCount,
            'total_children_vat' => self::TAX_PER_PERSON * $children * $segmentsCount,

            'total_infants_base' => ($totalAdultPerPerson * 0.1) * $infants,
            'total_infants_service' => self::SERVICE_FEE_PER_PERSON * $infants * $segmentsCount,
            'total_infants_vat' => self::TAX_PER_PERSON * $infants * $segmentsCount,

            'segments' => $segmentsCount,
        ];
    }
}
