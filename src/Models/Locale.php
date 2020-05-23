<?php
namespace Koddea\Localize\Models;
use Illuminate\Database\Eloquent\Model;
use Koddea\Localize\Traits\LocaleTrait;

class Locale extends Model
{
    use LocaleTrait;

    protected $table = 'locales';

    public function translations()
    {
        return $this->hasMany(TextTranslation::class);
    }
}