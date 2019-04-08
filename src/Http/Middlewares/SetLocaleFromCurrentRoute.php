<?php

namespace Koddea\Localize\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Koddea\Localize\Localize;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromCurrentRoute
{
    /** @var \Koddea\Localize\Localize */
    protected $localize;

    public function __construct(Localize $localize)
    {
        $this->localize = $localize;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1, '');
        
        if (!$this->localize->isSupportedLocale($locale)) {
            $locale = $this->localize->getDefaultLocale();
        }

        $this->localize->setLocale($locale);

        return $next($request);
    }
}
