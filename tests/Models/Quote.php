<?php
/**
 * Created by enea dhack - 06/08/2020 18:17.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\{BuyerContract, QuoteContract};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Collection;

/**
 * Class Quote
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * @property int id
 * @property int client_id
 * @property Client client
 * @property Collection quotedProducts
 */
class Quote extends Model implements QuoteContract
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function quotedProducts(): HasMany
    {
        return $this->hasMany(QuotedProduct::class)->with('product');
    }

    public function getUniqueIdentificationKey(): string
    {
        return $this->getKey();
    }

    public function getBuyer(): BuyerContract
    {
        return $this->client;
    }

    public function getQuotedProducts(): Collection
    {
        return $this->quotedProducts;
    }

    public function getProperties(): array
    {
        return [];
    }
}
