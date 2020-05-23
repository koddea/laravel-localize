<?php

namespace Koddea\Localize;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Cache\Repository as CacheRepository;
use Modules\Translation\Setting as TranslationSetting;

class Localize
{
    /** @var \Illuminate\Contracts\Container\Container */
    protected $app;
    protected $request;
    protected $cache;

    protected $localeModel;
    protected $translationModel;

    private $cacheTime = 30;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->cache = $app->make('cache');

        $this->translationModel = $app->make($this->configTranslationModel());
        $this->localeModel = $app->make($this->configLocaleModel());
        $this->request = $app->make('request');

        $this->setCacheTime($this->configCacheTime());
    }

    public function getDefaultLocaleName()
    {

        $locale = $this->app->getLocale();
        if($this->hasLocale($locale)){
            return $this->getLocales()[$locale]->name;
        }
        return $this->getLocales()->first()->name;
    }

    public function getDefaultLocale(): string
    {

        $locale = \Request::getPreferredLanguage();
        if($this->hasLocale($locale)){
            return $locale;
        }

        $locale = $this->app->getLocale();
        if($this->hasLocale($locale)){
            return $locale;
        }
        return $this->getLocales()->first()->code;
    }

    public function setDefaultLocale(string $locale)
    {
        $this->app->setLocale($locale);
    }

    public function isDefaultLocale(string $locale): bool
    {
        return $locale === $this->getDefaultLocale();
    }

    public function getLocale(string $locale): Model
    {
        $this->checkValidLocale($locale);
        return $this->getLocales()->get($locale);
    }

    public function hasLocale(string $locale): bool
    {
        try{
            $this->checkValidLocale($locale);
        }catch(InvalidArgumentException $e){
            return false;
        }
        return true;
    }

    public function getLocales(): Collection
    {
        return $this->cache->remember("localize::locales", $minutes='60', function() {
            return $this->localeModel->orderBy($this->configLocaleColMap("is_default"), "desc")->get()->keyBy($this->configLocaleColMap("code"));
        });
    }

    public function hideLocaleInUrl()
    {
        return $this->configHideLocale();
    }

    public function getRoutePrefix()
    {
        $locale = $this->request->segment($this->configUrlLocaleSegment());
        $locales = $this->getLocales();
        if (strlen($locale) == 2 && $locales->has($locale)) {
            return $locale;
        }
    }

    protected function config(string $key, $default = null)
    {
        if($default != null){
            return config("localize.$key", $default);
        }
        return config("localize.$key");
    }

    protected function configHideLocale()
    {
        return $this->config('url.hide_locale_segment', false);
    }

    protected function configUrlLocaleSegment()
    {
        return $this->config('url.locale_segment_index', 1);
    }

    protected function configLocaleModel()
    {
        return $this->config('locale.model_name', Models\Locale::class);
    }

    protected function configLocaleColMap($columnName)
    {
        return $this->config('locale.column_mappings.' . $columnName , $columnName);
    }

    protected function configTranslationModel()
    {
        return $this->config('translation.model_name', Models\TextTranslation::class);
    }

    protected function configTranslationColMap($columnName)
    {
        return $this->config('translation.column_mappings.' . $columnName , $columnName);
    }

    protected function configCacheTime()
    {
        return $this->config('cache_time', $this->cacheTime);
    }

    protected function setCacheTime($time)
    {
        if (is_numeric($time)) {
            $this->cacheTime = $time;
        }
    }

    protected function checkValidLocale(string $locale){
        if (!$this->getLocales()->has($locale)) {
            $message = 'Invalid Argument. Requested locale is not available.';
            throw new InvalidArgumentException($message);
        }
    }

    protected function validateText($text)
    {
        if (!is_string($text)) {
            $message = 'Invalid Argument. You must supply a string to be translated.';
            throw new InvalidArgumentException($message);
        }
        return true;
    }

    public function translate($key, $localeCode = null, $placeholder = false, array $customConditions = null)
    {
        $locale = $this->getLocale($localeCode ?:$this->getDefaultLocale());

        $conditions = $this->createTranslationConditions($locale, $key, $customConditions);
        $translation = $this->translationModel->where($conditions)->first();

        return $translation ? $translation->value : $this->getPrettyTranslationKey($key);
    }

    public function getPrettyTranslationKey($key){
        $tmp = explode('.', $key);
        return end($tmp);
    }

    protected function createTranslationConditions(Model $locale, $key, array $customConditions = null){
        $conditions = ($customConditions != null ?$customConditions:array());
        $conditions[$this->localeModel->getForeignKey()] = $locale->getKey();
        $conditions[$this->configTranslationColMap('key')] = $key;
        return $conditions;
    }

    public function createOrUpdateTranslation($key, $value, $localeCode = null, array $customConditions = null)
    {

        if($localeCode == null){
            $localeCode = $this->getDefaultLocale();
        }
        $locale = $this->getLocale($localeCode);

        $conditions = ($customConditions != null ?$customConditions:array());


        $conditions[$this->localeModel->getForeignKey()] = $locale->getKey();
        $conditions[$this->configTranslationColMap('key')] = $key;

        $this->translationModel->updateOrCreate(
            $conditions,
            [$this->configTranslationColMap('value') => $value]
        );
    }

}






