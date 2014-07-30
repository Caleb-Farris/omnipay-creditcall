<?php

namespace Omnipay\Creditcall;

class Constant
{
    const CARD_HOLDER_ENROLLED_YES_MPI                  = 'Y';
    const CARD_HOLDER_ENROLLED_NO_MPI                   = 'N';
    const CARD_HOLDER_ENROLLED_UNKNOWN_MPI              = 'U';
    const CARD_HOLDER_ENROLLED_NONE_MPI                 = '';

    const CARD_HOLDER_ENROLLED_YES_DIRECT               = 'Yes';
    const CARD_HOLDER_ENROLLED_NO_DIRECT                = 'No';
    const CARD_HOLDER_ENROLLED_UNKNOWN_DIRECT           = 'Unknown';
    const CARD_HOLDER_ENROLLED_NONE_DIRECT              = 'None';

    const TRANSACTION_STATUS_SUCCESSFUL_MPI             = 'Y';
    const TRANSACTION_STATUS_FAILED_MPI                 = 'N';
    const TRANSACTION_STATUS_UNKNOWN_MPI                = 'U';
    const TRANSACTION_STATUS_ATTEMPTED_MPI              = 'A';
    const TRANSACTION_STATUS_NONE_MPI                   = '';

    const TRANSACTION_STATUS_SUCCESSFUL_DIRECT          = 'Successful';
    const TRANSACTION_STATUS_FAILED_DIRECT              = 'Failed';
    const TRANSACTION_STATUS_UNKNOWN_DIRECT             = 'Unknown';
    const TRANSACTION_STATUS_ATTEMPTED_DIRECT           = 'Attempted';
    const TRANSACTION_STATUS_NONE_DIRECT                = 'None';

    const ACQUIRER_ALLIED_IRISH_BANK_DOMESTIC           = 'bank_allied_irish_bank_domestic';
    const ACQUIRER_ALLIED_IRISH_BANK_INTERNATIONAL      = 'bank_allied_irish_bank_international';
    const ACQUIRER_BARCLAYCARD                          = 'barclaycard';
    const ACQUIRER_ELAVON_FINANCIAL_SERVICES            = 'elavon_financial_services';
    const ACQUIRER_HALIFAX_BANK_OF_SCOTLAND             = 'halifax_bank_of_scotland';
    const ACQUIRER_HSBC_BANK                            = 'hsbc_bank';
    const ACQUIRER_LLOYDS_TSB                           = 'lloyds_tsb';
    const ACQUIRER_WORLDPAY                             = 'worldpay';
}
