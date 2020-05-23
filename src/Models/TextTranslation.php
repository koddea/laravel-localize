<?php

namespace Koddea\Localize\Models;

use Illuminate\Database\Eloquent\Model;
use Koddea\Localize\Traits\TextTranslationTrait;

class TextTranslation extends Model
{
    use TextTranslationTrait;

    protected $guarded = [];
    protected $table = 'translations';

    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

}