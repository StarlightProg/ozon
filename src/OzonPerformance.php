<?php

namespace KFilippovk\Ozon;

class OzonPerformance extends OzonPerformanceClient
{
    public function config(array $keys): OzonPerformance
    {
        $this->validateKeys($keys);

        $this->config = $keys;

        return $this;
    }

    /**
     * Список кампаний
     * 
     * @return mixed
     */
    public function getCampaign(
        array $campaign_ids = null,
        string $adv_object_type = null,
        string $state = null
    ): mixed {
        return (new OzonData($this->getResponse(
            'api/client/campaign',
            array_merge(
                compact('campaign_ids'),
                array_diff(compact('adv_object_type', 'state'), [''])
            )
        )))->data;
    }
}
