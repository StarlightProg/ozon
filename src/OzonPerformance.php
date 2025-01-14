<?php

namespace Filippi4\Ozon;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OzonPerformance extends OzonPerformanceClient
{
    private const DT_FORMAT_DATE_TIME_TZ = 'Y-m-d\TH:i:s.v\Z';
    private const DT_FORMAT_DATE = 'Y-m-d';

    /**
     * @throws ValidationException
     */
    public function config(array $keys): OzonPerformance
    {
        $this->validateKeys($keys);

        $this->config = $keys;

        return $this;
    }

    private function formatDate(?Carbon $dateTime, string $format = self::DT_FORMAT_DATE_TIME_TZ): ?string
    {
        return $dateTime ? $dateTime->format($format) : null;
    }

    /**
     * Список кампаний
     *
     * @param array|null $campaign_ids
     * @param string|null $adv_object_type
     * @param string|null $state
     * @return mixed
     */
    public function getCampaign(
        array  $campaign_ids = null,
        string $adv_object_type = null,
        string $state = null
    ): mixed
    {
        $params = $this->getNotNullParams(compact('campaign_ids', 'adv_object_type', 'state'));

        return (new OzonData($this->getResponse('api/client/campaign', $params)))->data;
    }

    /**
     * Список рекламируемых объектов в кампании
     *
     * @param int $campaign_id Идентификатор кампании
     * @return mixed
     */
    public function getCampaignObjects(int $campaign_id): mixed
    {
        return (new OzonData($this->getResponse(
            'api/client/campaign/' . $campaign_id . '/objects'
        )))->data;
    }

    /**
     * Статистика по расходу кампаний
     *
     * @param int|null $campaigns Идентификаторы кампаний
     * @param Carbon|null $dateFrom
     * @param Carbon|null $dateTo
     * @return mixed
     */
    public function getStatisticsExpense(
        int    $campaigns = null,
        Carbon $dateFrom = null,
        Carbon $dateTo = null
    ): mixed
    {
        $dateFrom = $this->formatDate($dateFrom, self::DT_FORMAT_DATE);
        $dateTo = $this->formatDate($dateTo, self::DT_FORMAT_DATE);

        $params = $this->getNotNullParams(compact('campaigns', 'dateFrom', 'dateTo'));

        return (new OzonData($this->getResponse('api/client/statistics/expense', $params, false)))->data;
    }

    /**
     * Дневная статистика по кампаниям
     *
     * @param array|null $campaignIds Список идентификаторов кампаний
     * @param Carbon|null $dateFrom Начальная дата периода отчёта
     * @param Carbon|null $dateTo Конечная дата периода отчёта
     * @return mixed
     */
    public function getStatisticsDaily(
        array  $campaignIds = null,
        Carbon $dateFrom = null,
        Carbon $dateTo = null,
    ): mixed
    {
        $dateFrom = $this->formatDate($dateFrom, self::DT_FORMAT_DATE);
        $dateTo = $this->formatDate($dateTo, self::DT_FORMAT_DATE);

        $params = $this->getNotNullParams(compact('campaignIds', 'dateFrom', 'dateTo'));

        return (new OzonData($this->getResponse('api/client/statistics/daily', $params, false)))->data;
    }

    private function getNotNullParams(array $params): array
    {
        $notNullParams = [];
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $notNullParams[$key] = $value;
            }
        }
        return $notNullParams;
    }
}
