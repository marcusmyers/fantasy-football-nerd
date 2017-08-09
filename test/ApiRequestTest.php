<?php

namespace MarkMyers\FFNerd\Test;

use MarkMyers\FFNerd\ApiRequest;
use PHPUnit\Framework\TestCase;

class ApiRequestTest extends TestCase
{
    public function setUp() {
        $this->r = new ApiRequest();
    }

    /** @test */
    public function it_should_return_the_ffnerd_api_url() {
        $this->assertEquals('https://www.fantasyfootballnerd.com/service', $this->r->uri());
    }

    /** @test */
    public function it_should_return_the_service_url_based_on_method_params() {
      $this->assertEquals('https://www.fantasyfootballnerd.com/service/schedule/json/test', $this->r->serviceUrl('schedule','test'));
    }

    /** @test */
    public function it_should_return_array() {
        $data = $this->r->requestService('nfl-teams', 'test');
        $this->assertCount(32, $data['NFLTeams']);
    }
}
