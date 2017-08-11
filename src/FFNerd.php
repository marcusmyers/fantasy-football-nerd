<?php

namespace MarkMyers\FFNerd;

use MarkMyers\FFNerd\ApiRequest;
use MarkMyers\FFNerd\Exceptions\UndefinedKey;
use MarkMyers\FFNerd\Exceptions\OutOfRange;
use MarkMyers\FFNerd\Exceptions\NoPositionArgument;
use MarkMyers\FFNerd\Exceptions\NoWeekArgument;
use Illuminate\Support\Collection as Collection;
use Exception;

class FFNerd extends ApiRequest
{

  public static function apiKey() {
    if(getenv('FFNERD_API_KEY') === false) {
        throw UndefinedKey::api();
    }

    return getenv('FFNERD_API_KEY');
  }

  public function currentWeek() {
    return $this->requestService('schedule', self::apiKey())['currentWeek'];
  }

  public function collectionRequest($service_name, $json_key = '', $extra = []) {
      $data = $json_key != '' ? $this->requestService($service_name, self::apiKey(), $extra)[$json_key] : $this->requestService($service_name, self::apiKey(), $extra);
      return new Collection($data);
  }

  public function teams() {
      return $this->collectionRequest('nfl-teams', 'NFLTeams');
  }

  public function schedule() {
      return $this->collectionRequest('schedule', 'Schedule');
  }

  public function players() {
      return $this->collectionRequest('players', 'Players');
  }

  public function byes($week) {
      if (($week < 4) || ($week > 12)) {
          throw OutOfRange::create();
      }

      return $this->collectionRequest('byes', 'Bye Week '.$week);
  }

  public function weather() {
      return $this->requestService('weather', self::apiKey());
  }

  public function auctionValues($ppr = null) {
      return $this->collectionRequest('auction-enhanced', '', [$ppr]);
  }

  public function draftRankings($ppr = "") {
      return $this->collectionRequest('draft-rankings', 'DraftRankings', [$ppr]);
  }

  public function draftProjections($pos = "") {
      if ($pos == "") {
          throw NoPositionArgument::create();
      }

      return $this->collectionRequest('draft-projections', 'DraftProjections', [$pos]);
  }

  public function tiers() {
      return $this->collectionRequest('tiers', '');
  }

  public function idpDraftRankings() {
      return $this->collectionRequest('draft-idp', 'DraftIDP');
  }

  public function dynastyRankings() {
      return $this->collectionRequest('dynasty', 'Dynasty');
  }

  public function weeklyRankings($pos = "", $week = "", $ppr = 0) {
      if ($pos == "") {
          throw NoPositionArgument::create();
      }

      if ($week == "") {
          throw NoWeekArgument::create();
      }

      return $this->requestService('weekly-rankings', self::apiKey(), [ $pos, $week, $ppr ]);
  }

  public function weeklyProjections($pos = "", $week = "") {
      if ($pos == "") {
          throw NoPositionArgument::create();
      }

      if ($week == "") {
          throw noweekargument::create();
      }

      return $this->requestService('weekly-projections', self::apiKey(), [ $pos, $week ]);
  }

  public function weeklyIdpRankings() {
      return $this->collectionRequest('weekly-idp', 'rankings');
  }

  public function injuries($week = "") {
      if ($week == "") {
          throw noweekargument::create();
      }
      return $this->collectionRequest('injuries', 'Injuries', [$week]);
  }

  public function depthCharts() {
      return $this->collectionRequest('depth-charts', 'DepthCharts');
  }
}
