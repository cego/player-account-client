<?php

namespace Cego\PlayerAccount\Enumerations;

class Incidents
{
    // Brugeren har en spilscannerscore på over 60, har en alder på 18 - 25 og og skal kontaktes via e-mail.
    const AGE_BETWEEN_18_AND_25 = 'AgeBetween18And25';

    // Kunden skal overvåges af support, modtage et supportopkald og må ikke modtage reklame via e-mail eller sms.
    const CUSTOMER_CARE = 'CustomerCare';

    // Brugeren har en spilscannerscore på over 70, samt et akkumuleret tab over 3 mdr. på 50.000 kr. - 99.999 kr og skal kontaktes via e-mail
    const LOSS_ACCUMOLATED_OVER_THREE_MONTHS = 'LossAccumulatedOverThreeMonths';

    // Kunden har øget den månedlige indbetalingsgrænse til 10.000 SEK eller derover og skal overvåges af support, samt modtage et supportopkald, når indbetalingsgrænse er nået 8.000 SEK.
    const PAYMENT_LIMIT_INCREASED_MONTHLY = 'PaymentLimitIncreasedMonthly';

    // Kunden har øget den månedlige indbetalingsgrænse til 10.000 SEK eller derover og skal overvåges af support, samt modtage et supportopkald, når indbetalingsgrænse er nået 8.000 SEK.
    const PAYMENT_LIMIT_INCREASED_SEVERAL_TIMES = 'PaymentLimitIncreasedSeveralTimes';

    // Brugeren har en spilscannerscore på over 60, som er steget med 20-25 over den sidste måned og skal kontaktes via e-mail.
    const SCORE_INCREASED_FROM_20_TO_25_LAST_MONTH = 'ScoreIncreasedFrom20To25LastMonth';
}
