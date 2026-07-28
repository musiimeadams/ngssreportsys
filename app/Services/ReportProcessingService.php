<?php

namespace App\Services;

class ReportProcessingService
{
    /**
     * Compute total score, assign Grade, Level of Achievement /3, and Identifier.
     * Scale:
     * - 90 to 100: A*
     * - 80 to 89: A
     * - 70 to 79: B
     * - 60 to 69: C
     * - 50 to 59: D
     * - 40 to 49: E
     * - 30 to 39: F
     * - Below 30: G
     *
     * Level of Achievement = (Total Score / 100) * 3, rounded to 1 decimal place.
     * Identifier:
     * - 3: 2.5 - 3.0
     * - 2: 1.5 - 2.4
     * - 1: 0.9 - 1.4
     * - 0: below 0.9
     */
    public static function processScore(?float $formative, ?float $summative): array
    {
        $formative = $formative ?? 0;
        $summative = $summative ?? 0;
        
        $total = $formative + $summative;
        if ($total > 100) {
            $total = 100;
        }

        // Grade
        if ($total >= 90) {
            $grade = 'A*';
        } elseif ($total >= 80) {
            $grade = 'A';
        } elseif ($total >= 70) {
            $grade = 'B';
        } elseif ($total >= 60) {
            $grade = 'C';
        } elseif ($total >= 50) {
            $grade = 'D';
        } elseif ($total >= 40) {
            $grade = 'E';
        } elseif ($total >= 30) {
            $grade = 'F';
        } else {
            $grade = 'G';
        }

        // Level of Achievement / 3
        $achievement = round(($total / 100) * 3, 1);

        // Identifier / Descriptor
        if ($achievement >= 2.5) {
            $identifier = 3;
            $descriptor = 'Most LOS achieved';
        } elseif ($achievement >= 1.5) {
            $identifier = 2;
            $descriptor = 'Some LOS achieved';
        } elseif ($achievement >= 0.9) {
            $identifier = 1;
            $descriptor = 'No learning outcomes';
        } else {
            $identifier = 1; // Default to 1 as per template lowest identifier or 0
            $descriptor = 'No learning outcomes';
        }

        return [
            'total_score' => $total,
            'grade' => $grade,
            'achievement' => $achievement,
            'identifier' => $identifier,
            'descriptor' => $descriptor,
        ];
    }
}
