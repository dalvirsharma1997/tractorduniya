<?php

use App\Models\SeoMeta;
use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

if (! function_exists('seo_meta_override')) {
    /**
     * Resolve an admin-configured SEO override: by routable record first
     * (when $type/$id are given), falling back to a static path match.
     */
    function seo_meta_override(?string $type = null, ?int $id = null): ?SeoMeta
    {
        if ($type && $id) {
            $byRecord = SeoMeta::where('routable_type', $type)->where('routable_id', $id)->first();
            if ($byRecord) {
                return $byRecord;
            }
        }

        $path = '/'.ltrim(Request::path(), '/');

        return SeoMeta::whereNull('routable_type')->where('path', $path)->latest('id')->first();
    }
}

if (! function_exists('localized_route')) {
    /**
     * Build a route name for the current (or given) locale, so controllers
     * can redirect back into whichever locale the request came from without
     * hardcoding the English route name.
     */
    function localized_route(string $name, mixed $parameters = [], ?string $locale = null): string
    {
        $locale ??= App::getLocale();
        $localizedName = $locale === 'en' ? $name : "{$locale}.{$name}";

        return Route::has($localizedName) ? route($localizedName, $parameters) : route($name, $parameters);
    }
}

if (! function_exists('trans_field')) {
    /**
     * A content field's translated value for the current locale, falling
     * back to the model's own (English) column when no override exists
     * or the current locale is English.
     */
    function trans_field(string $entityType, Model $model, string $field): mixed
    {
        $locale = App::getLocale();

        if ($locale === 'en') {
            return $model->{$field};
        }

        return Translation::valueFor($entityType, $model->id, $locale, $field) ?? $model->{$field};
    }
}

if (! function_exists('hreflang_alternates')) {
    /**
     * EN/HI alternate URLs for the current route, keyed by hreflang code.
     * Returns [] for routes that have no bilingual counterpart (sitemaps, etc).
     */
    function hreflang_alternates(): array
    {
        $route = Request::route();
        if (! $route || ! $route->getName()) {
            return [];
        }

        $name = $route->getName();
        $baseName = str_starts_with($name, 'hi.') ? substr($name, 3) : $name;
        $params = $route->parameters();

        $alternates = [];

        if (Route::has($baseName)) {
            $alternates['en'] = route($baseName, $params);
        }

        if (Route::has("hi.{$baseName}")) {
            $alternates['hi'] = route("hi.{$baseName}", $params);
        }

        return $alternates;
    }
}
