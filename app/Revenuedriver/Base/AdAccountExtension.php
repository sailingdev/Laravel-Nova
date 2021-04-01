<?php

/**
 * Extend and manipulate the default behavior of some functionalities in the Facebook AdAccount class
*/

namespace App\Revenuedriver\Base;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Values\CampaignDatePresetValues;
use FacebookAds\Object\Values\CampaignEffectiveStatusValues;
use FacebookAds\ApiRequest;
use FacebookAds\Http\RequestInterface;
use FacebookAds\Object\AdCreative;
use FacebookAds\TypeChecker;

class AdAccountExtension extends AdAccount
{
    
  public function getCampaigns(array $fields = array(), array $params = array(), $pending = false) {
    $this->assureId();

    $param_types = array(
      'date_preset' => 'date_preset_enum',
      'effective_status' => 'list<effective_status_enum>',
      'is_completed' => 'bool',
      'time_range' => 'Object',
    );
    $enums = array(
      'date_preset_enum' => CampaignDatePresetValues::getInstance()->getValues(),
      'effective_status_enum' => CampaignEffectiveStatusValues::getInstance()->getValues(),
    );
    
    $request = new ApiRequest(
      $this->api,
      $this->data['id'],
      RequestInterface::METHOD_GET,
      '/campaigns?limit=60000',
      new Campaign(),
      'EDGE',
      Campaign::getFieldsEnum()->getValues(),
      new TypeChecker($param_types, $enums)
    );
    $request->addParams($params);
    $request->addFields($fields);
    return $pending ? $request : $request->execute();
  }

   
}