<?php

use App\Models\SeoMeta;
use Illuminate\Support\Facades\Request;

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
