<?php 

namespace App\Traits;

use App\Labs\FileManager;
use App\Labs\StringManipulator;
use App\Revenuedriver\FacebookAdAccount;
use FacebookAds\Object\AdImage;

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
           
            $adImageDetails = $this->facebookAdAccount->getAdImages($sourceAccount, [
                'name', 'permalink_url', 'status', 'url',
            ], [
                'hashes' => [$existingAdImage['hash']]
            ]);
             
            if ($adImageDetails[0] === true && isset($adImageDetails[1][0]->url)) {

                $destinationPath = storage_path('app/public/ad_images/');
                $fileName = $this->cleanAdImageName($adImageDetails[1][0]->name);
                
                if (copy($adImageDetails[1][0]->url, $destinationPath . $fileName)) {
                    $targetEnv == 'rd' ? $this->facebookCampaign->initRD() : $this->facebookCampaign->initTT();
                    $newAdImage = $this->facebookAdImage->create($targetAccount, [
                        'filename' => $destinationPath . $fileName
                    ]);
                    return $newAdImage;
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
            return $dotNot[0] . '.' . $ext;
        }
        else {
            return $imageName . '.' . $ext; 
        }
    }
}