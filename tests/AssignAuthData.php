<?php

namespace Tests;

use App\Models\User;
use App\Services\FbPageService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

trait AssignAuthData
{
    protected $markets;

    /**
     * @var
     */
    protected $defaultUser;

    /**
     * @return void
     */
    public function setDefaultUser()
    {
        $this->defaultUser = User::factory()->create([
            'email' => 'unit-tester@revenuedriver.com'
        ]);
    }

    public function getDefaultUser()
    {
        return $this->defaultUser;
    }

    public function getMarkets(array $markets)
    {
        return $this->markets;
    }

    public function getPageGroups()
    {
        $fbPageService = new FbPageService;
        return $fbPageService->groupPage();
    }
}
