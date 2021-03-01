<?php

namespace Cego\PlayerAccount\Enumerations;

class Incidents
{
    // Game scanner score > 60, age 18-25. Contact via email.
    const AGE_BETWEEN_18_AND_25 = 'AgeBetween18And25';

    // Must be monitored by support, receive support call. May not receive email or SMS advertising.
    const CUSTOMER_CARE = 'CustomerCare';

    // Game scanner score > 70, acc. loss of 50.000 - 99.999 DKK through 3 months. Contact via email.
    const LOSS_ACCUMULATED_OVER_THREE_MONTHS = 'LossAccumulatedOverThreeMonths';

    // Customer has increased their monthly deposit limit to >= 10.000 SEK. Must be monitored and receive support call, when 8.000 SEK has been deposited.
    const PAYMENT_LIMIT_INCREASED_MONTHLY = 'PaymentLimitIncreasedMonthly';

    // Payment limit increased several times. Must be monitored by support, receive support call.
    const PAYMENT_LIMIT_INCREASED_SEVERAL_TIMES = 'PaymentLimitIncreasedSeveralTimes';

    // Game scanner score > 60. Score increased by 20-25 points through the last month. Contact via email.
    const SCORE_INCREASED_FROM_20_TO_25_LAST_MONTH = 'ScoreIncreasedFrom20To25LastMonth';
}
