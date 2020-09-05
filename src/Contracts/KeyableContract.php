<?php
/**
 * Created by enea dhack - 21/07/2020 16:16.
 */

namespace Enea\Cashier\Contracts;

interface KeyableContract
{
    public function getUniqueIdentificationKey(): string;
}