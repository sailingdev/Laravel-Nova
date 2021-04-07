<?php 

namespace App\Services;

use App\Models\FbReporting\Website;

class WebsiteService
{
    /**
     * @param string $domain
     * 
     * @return Website|null
     */
    public function getRowByDomain(string $domain): ?Website
    {
        return Website::where('domain', $domain)->first();
    }
}