<?php

namespace MarkMyers\FFNerd\Test;

use MarkMyers\FFNerd\FFNerd;
use MarkMyers\FFNerd\Exceptions\UndefinedKey;
use MarkMyers\FFNerd\Exceptions\OutOfRange;
use MarkMyers\FFNerd\Exceptions\NoPositionArgument;
use MarkMyers\FFNerd\Exceptions\NoWeekArgument;
use PHPUnit\Framework\TestCase;

class FFNerdTest extends TestCase
{
  public function setUp() : void {
      $this->f = new FFNerd();
      putenv("FFNERD_API_KEY=test");
  }

  private function unsetKey() {
      putenv("FFNERD_API_KEY");
  }

  /** @test */
  public function it_should_extend_api_request() {
      $this->assertEquals('https://www.fantasyfootballnerd.com/service', $this->f->uri());
  }

  /** @test */
  public function it_should_have_an_api_set() {
      $this->assertEquals('test', FFNerd::apiKey());
  }

  /** @test */
  public function it_should_throw_undefined_key_exception_when_no_api_key_is_set() {
      $this->expectException(UndefinedKey::class);
      $this->unsetKey();

      $this->f->currentWeek();
  }

  /** @test */
  public function it_should_return_the_current_week() {
      $current_week = $this->f->currentWeek();

      $this->assertEquals('17', $current_week);
  }

  /** @test */
  public function it_should_return_collection_from_array() {
      $schedules = $this->f->collectionRequest('schedule', 'Schedule');

      $this->assertEquals('1', $schedules->first()['gameId']);
  }

  /** @test */
  public function it_should_return_collection_of_teams() {
      $teams = $this->f->teams();

      $this->assertCount(32, $teams);
  }

  /** @test */
  public function it_should_return_collection_of_games() {
      $games = $this->f->schedule();

      $this->assertCount(256, $games);
  }

  /** @test */
  public function it_should_return_collection_of_players() {
      $players = $this->f->players();

      $this->assertCount(1117, $players);
      $this->assertEquals('CAR', $players->first()['team']);
      $this->assertEquals('Derek Anderson', $players->first()['displayName']);
  }

  /** @test */
  public function it_should_return_collection_of_players_by_position() {
      $players = $this->f->players('QB');

      $this->assertCount(128, $players);
      $this->assertEquals('CAR', $players->first()['team']);
      $this->assertEquals('Derek Anderson', $players->first()['displayName']);
  }

  /** @test */
  public function it_should_return_collection_of_teams_with_byes_for_week_4() {
      $byeTeams = $this->f->byes(4);
      $this->assertCount(2, $byeTeams);
      $this->assertEquals('CAR', $byeTeams->first()['team']);
  }

  /** @test */
  public function it_should_throw_out_of_range_exception_when_a_bye_week_is_13() {
      $this->expectException(OutOfRange::class);

      $this->f->byes(13);
  }

  /** @test */
  public function it_should_return_all_weeks_and_teams_with_byes() {
      $byeTeams = $this->f->byes();
      $this->assertCount(9, $byeTeams);
      $this->assertEquals('CAR', $byeTeams->first()[0]['team']);
  }

  /** @test */
  public function it_should_return_a_collection_of_auction_values() {
      $auction = $this->f->auctionValues();
      $this->assertEquals('100', $auction->first()['SalaryCap']);
      $this->assertEquals('8', $auction->first()['Teams']);
  }

  /** @test */
  public function it_should_return_a_collection_of_auction_enhanced() {
      $auction = $this->f->auctionEnhanced();
      $this->assertEquals('100', $auction->first()['SalaryCap']);
      $this->assertEquals('8', $auction->first()['Teams']);
  }

  /** @test */
  public function it_should_return_a_collection_game_weather_data_for_current_week() {
      $weather = $this->f->weather();
      $this->assertEquals('2014-09-02', $weather['Today']);
      $this->assertEquals('1', $weather['Week']);
      $this->assertArrayHasKey('SEA', $weather['Games']);
  }

  /** @test */
  public function it_should_return_collection_of_draft_rankings() {
      $rankings = $this->f->draftRankings();
      $this->assertEquals('MIN', $rankings->first()['team']);
      $this->assertEquals('1', $rankings->first()['overallRank']);
      $this->assertEquals('Adrian Peterson', $rankings->first()['displayName']);
  }

  /** @test */
  public function it_should_return_collection_of_draft_rankings_for_ppr_league() {
      $rankings = $this->f->draftRankings(1);
      $this->assertEquals('MIN', $rankings->first()['team']);
      $this->assertEquals('1', $rankings->first()['overallRank']);
      $this->assertEquals('Adrian Peterson', $rankings->first()['displayName']);

  }

  /** @test */
  public function it_should_return_collection_of_draft_projections_by_qb_position() {
      $projections = $this->f->draftProjections('QB');
      $this->assertEquals('NO', $projections->first()['team']);
      $this->assertEquals('Drew Brees', $projections->first()['displayName']);
  }

  /** @test */
  public function it_should_throw_no_position_argument_exception_when_no_position_is_passed() {
      $this->expectException(NoPositionArgument::class);
      $this->f->draftProjections();
  }

  /** @test */
  public function it_should_return_a_collection_tier_based_player_groups() {
      $tiers = $this->f->tiers();
      $this->assertEquals('Antonio Brown', $tiers->first()['displayName']);
  }

  /** @test */
  public function it_should_retrun_a_collection_of_idp_draft_rankings() {
      $idp = $this->f->idpDraftRankings();
      $this->assertEquals('Luke Kuechly', $idp->first()['player']);
      $this->assertEquals('CAR', $idp->first()['team']);
  }

  /** @test */
  public function it_should_return_a_collection_of_dynasty_rankings() {
      $dynasty = $this->f->dynastyRankings();
      $this->assertEquals('Odell Beckham Jr.', $dynasty->first()['player']);
      $this->assertEquals('NYG', $dynasty->first()['team']);
  }

  /** @test */
  public function it_should_throw_no_position_argument_exception_on_weekly_rankings() {
      $this->expectException(NoPositionArgument::class);
      $this->f->weeklyRankings('', '2', '1');
  }

  /** @test */
  public function it_should_throw_no_week_argument_exception_on_weekly_rankings() {
      $this->expectException(NoWeekArgument::class);
      $this->f->weeklyRankings('QB', '');
  }

  /** @test */
  public function it_should_return_weekly_rankings_for_ppr_leagues() {
      $weekly = $this->f->weeklyRankings('QB', '2', '1');
      $this->assertEquals('2', $weekly['Week']);
      $this->assertEquals('QB', $weekly['Position']);
      $this->assertEquals('1', $weekly['PPR']);
      $this->assertArrayHasKey('ppr', $weekly['Rankings'][0]);
  }

  /** @test */
  public function it_should_return_weekly_rankings_for_non_ppr_leagues() {
      $weekly = $this->f->weeklyRankings('QB', '2');
      $this->assertEquals('2', $weekly['Week']);
      $this->assertEquals('QB', $weekly['Position']);
      $this->assertEquals('0', $weekly['PPR']);
      $this->assertArrayHasKey('ppr', $weekly['Rankings'][0]);
  }

  /** @test */
  public function it_should_throw_no_position_argument_exception_on_weekly_projections() {
      $this->expectException(NoPositionArgument::class);
      $this->f->weeklyProjections('', '1');
  }

  /** @test */
  public function it_should_throw_no_week_argument_exception_on_weekly_projections() {
      $this->expectException(NoWeekArgument::class);
      $this->f->weeklyProjections('QB', '');
  }

  /** @test */
  public function it_should_return_weekly_projections_for_players() {
      $weekly = $this->f->weeklyProjections('QB', '1');
      $this->assertEquals('1', $weekly['Week']);
      $this->assertEquals('QB', $weekly['Position']);
      $this->assertArrayHasKey('displayName', $weekly['Projections'][0]);
  }

  /** @test */
  public function it_should_return_weekly_idp_rankings() {
      $weekly = $this->f->weeklyIdpRankings();
      $this->assertEquals('J.J. Watt', $weekly->first()['player']);
      $this->assertEquals('DE', $weekly->first()['position']);
  }

  /** @test */
  public function it_should_throw_no_week_argument_exception_on_injuries() {
      $this->expectException(NoWeekArgument::class);
      $this->f->injuries();
  }

  /** @test */
  public function it_should_return_injured_players_based_on_week() {
      $injuries = $this->f->injuries(4);
      $this->assertCount(14, $injuries->first());
      $this->assertEquals('ARI', $injuries->first()[0]['team']);
  }

  /** @test */
  public function it_should_return_collection_depth_charts_by_teams() {
      $depth = $this->f->depthCharts();
      $this->assertArrayHasKey('RB', $depth->first());
      $this->assertArrayHasKey('QB', $depth->first());
      $this->assertArrayHasKey('WR2', $depth->first());
  }

  /** @test */
  public function it_should_return_collection_nfl_picks() {
      $picks = $this->f->nflPicks();
      $this->assertCount(15, $picks);
  }

  /** @test */
  public function it_should_return_collection_defensive_rankings() {
      $dranks = $this->f->defensiveRankings();
      $this->assertCount(32, $dranks);
  }
}
