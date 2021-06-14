<?php 

namespace App\Traits;

use App\Labs\FileManager;
use App\Labs\StringManipulator;
use App\Revenuedriver\FacebookAdAccount;
use FacebookAds\Object\AdImage;
use Illuminate\Support\Facades\Log;

trait CampaignDuplicatorTrait
{
    public function transportAdImages(string $sourceAccount, $existingAdImage, string $sourceEnv, string $targetAccount, string $targetEnv)
    {
      
        if ($targetEnv === $sourceEnv) {
            // copy from old account to new account
            $copyFrom = new \stdclass;
            $copyFrom->source_account_id = preg_replace("#[^0-9]#i", "", $sourceAccount);
            $copyFrom->hash = $existingAdImage['hash'];

            $targetEnv == 'rd' ? $this->facebookCampaign->initRD() : $this->facebookCampaign->initTT();
            
            $newAdImage = $this->facebookAdImage->create($targetAccount, [
                'copy_from' => $copyFrom
            ]);
            return $newAdImage;
        } else {
           
            $sourceEnv == 'rd' ? $this->facebookCampaign->initRD() : $this->facebookCampaign->initTT();
            $sourceAccount = strpos($sourceAccount, 'act_') === false ? 'act_' . $sourceAccount : $sourceAccount;
            $adImageDetails = $this->facebookAdAccount->getAdImages($sourceAccount, [
                'name', 'permalink_url', 'status', 'url',
            ], [
                'hashes' => [$existingAdImage['hash']]
            ]);
             
            if ($adImageDetails[0] === true && isset($adImageDetails[1][0]->url)) {

                $destinationPath = storage_path('app/public/ad_images/');
                $fileName = $this->cleanAdImageName($adImageDetails[1][0]->name);
                try {
                    $copy = copy($adImageDetails[1][0]->url, $destinationPath . $fileName);
                    if ($copy === true) {
                        
                        $targetEnv == 'rd' ? $this->facebookCampaign->initRD() : $this->facebookCampaign->initTT();
                        $newAdImage = $this->facebookAdImage->create($targetAccount, [
                            'filename' => $destinationPath . $fileName
                        ]);
                        if (file_exists($destinationPath . $fileName)) {
                            // unlink($destinationPath . $fileName);
                        }
                        return $newAdImage;
                    }
                } catch (\Throwable $e) {
                    Log::info('Ad image Not Copied for', [$adImageDetails[1][0]->name, $fileName]);
                }
               
            }
            return [false];
        }
    }


    protected function cleanAdImageName(string $imageName)
    {
        $sm = new StringManipulator;
        $ext = 'jpg';
        $dotNot = $sm->generateArrayFromString($imageName, '.');
        if (count($dotNot) >= 2) {
            $mixedExt = $dotNot[1];
            $mixedExtArr = $sm->generateArrayFromString($mixedExt, '_');
            if (count($mixedExtArr) >= 2) {
                $ext = $mixedExtArr[0];
            }
            return preg_replace("#[^a-z0-9]#i", "_", $dotNot[0]) . '.' . $ext;
        }
        else {
            return preg_replace("#[^a-z0-9]#i", "_", $imageName) . '.' . $ext; 
        }
    }
}
