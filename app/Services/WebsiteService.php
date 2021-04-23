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

    /**
     * @param string $feed
     * 
     * @return Website|null
     */
    public function getRowByFeed(string $feed): ?Website
    {
        return Website::where('feed', $feed)->first();
    }
}