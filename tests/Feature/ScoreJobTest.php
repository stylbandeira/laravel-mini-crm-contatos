<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScoreJobTest extends TestCase
{

    public function test_job_change_status_to_processing() {}
    public function test_not_corporative_email_earns_points() {}
    public function test_dot_br_email_earns_points() {}
    public function test_full_name_earns_points() {}
    public function test_area_code_earns_points() {}
    public function test_job_succeed() {}
    public function test_job_fails() {}
    public function test_processed_date_is_saved_after_score() {}
    public function test_contact_score_processed_event_is_dispatched() {}
}
