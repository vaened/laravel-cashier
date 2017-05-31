<?php
/**
 * Created by enea dhack - 30/05/2017 08:18 PM
 */

namespace Enea\Cashier\Contracts;


use Illuminate\Support\Collection;

interface HeaderSoldContract
{

    /**
     * Primary key that uniquely identifies the buyer
     *
     * @return integer:string
     */
    public function getHeaderKey( );

    /**
     * Gets the detail of the database
     *
     * @return Collection
     */
    public function elements( ): Collection;

    /**
     * Returns the series plus the generated document number
     *
     * @return string
     * */
    public function getDocumentAttribute( ): string;

    /**
     * Get tax percentage
     *
     * @return int
     */
    public function getTaxPercentageAttribute( ) : int;

}