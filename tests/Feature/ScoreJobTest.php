<?php

namespace Tests\Feature;

use App\Domain\Services\ContactScoreCalculatorService;
use App\Events\ContactScoreProcessedEvent;
use App\Jobs\ProcessContactScoreJob;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery;
use ReflectionMethod;
use Tests\TestCase;

class ScoreJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_change_status_to_processing()
    {
        Queue::fake();

        $contact = Contact::factory()->create();

        $job = new ProcessContactScoreJob($contact->id);

        $reflection = new ReflectionMethod(
            $job,
            'markAsProcessing'
        );

        $reflection->invoke($job, $contact);

        $this->assertDatabaseHas('contact', [
            'id' => $contact->id,
            'status' => 'processing'
        ]);
    }

    // public function test_not_corporative_email_earns_points()
    // {
    //     Queue::fake();

    //     $contact = Contact::factory()->create([
    //         'email' => 'stylbandeira@gmail.com',
    //         'name' => 'Styl',
    //         'phone' => '87996236447'
    //     ]);

    //     $job = new ProcessContactScoreJob($contact->id);

    //     $calculator = Mockery::mock(ContactScoreCalculatorService::class);

    //     $job->handle(
    //         app(ContactRepository::class),
    //         $calculator
    //     );

    //     $this->assertDatabaseHas('contact', [
    //         'id' => $contact->id,
    //         'score' => 30
    //     ]);
    // }

    // public function test_dot_br_email_earns_points()
    // {
    //     Queue::fake();

    //     $contact = Contact::factory()->create([
    //         'email' => 'stylbandeira@gmail.com.br',
    //         'name' => 'Styl',
    //         'phone' => '87996236447'
    //     ]);

    //     $job = new ProcessContactScoreJob($contact->id);

    //     $calculator = Mockery::mock(ContactScoreCalculatorService::class);

    //     $job->handle(
    //         app(ContactRepository::class),
    //         $calculator
    //     );

    //     $this->assertDatabaseHas('contact', [
    //         'id' => $contact->id,
    //         'score' => 40
    //     ]);
    // }

    // public function test_full_name_earns_points()
    // {
    //     Queue::fake();

    //     $contact = Contact::factory()->create([
    //         'email' => 'stylbandeira@styl.br',
    //         'name' => 'Styl Bandeira',
    //         'phone' => '87996236447'
    //     ]);

    //     $job = new ProcessContactScoreJob($contact->id);

    //     $calculator = Mockery::mock(ContactScoreCalculatorService::class);

    //     $job->handle(
    //         app(ContactRepository::class),
    //         $calculator
    //     );

    //     $this->assertDatabaseHas('contact', [
    //         'id' => $contact->id,
    //         'score' => 30
    //     ]);
    // }

    // /**
    //  * @dataProvider validSaoPauloDdds
    //  *
    //  * @return void
    //  */
    // public function test_area_code_earns_points(string $ddd)
    // {
    //     Queue::fake();

    //     $contact = Contact::factory()->create([
    //         "email" => "stylbandeira@styl.ai",
    //         "name" => "Styl Bandeira",
    //         "phone" => "{$ddd}996236447"
    //     ]);

    //     $job = new ProcessContactScoreJob($contact->id);

    //     $calculator = Mockery::mock(ContactScoreCalculatorService::class);

    //     $job->handle(
    //         app(ContactRepository::class),
    //         $calculator
    //     );

    //     $this->assertDatabaseHas('contact', [
    //         'id' => $contact->id,
    //         'score' => 30
    //     ]);
    // }

    public function test_job_succeed()
    {
        Queue::fake();

        $contact = Contact::factory()->create([
            "email" => "stylbandeira@styl.ai",
            "name" => "Styl Bandeira",
            "phone" => "87996236447"
        ]);

        $job = new ProcessContactScoreJob($contact->id);

        $calculator = Mockery::mock(ContactScoreCalculatorService::class);

        $calculator
            ->shouldReceive('calculate')
            ->once()
            ->andReturn(50);

        $job->handle(
            app(ContactRepository::class),
            $calculator
        );

        $this->assertDatabaseHas('contact', [
            'id' => $contact->id,
            'status' => 'active'
        ]);
    }

    public function test_job_fails()
    {
        $contact = Contact::factory()->create([
            'status' => 'pending',
        ]);

        $calculator = Mockery::mock(ContactScoreCalculatorService::class);

        $calculator
            ->shouldReceive('calculate')
            ->once()
            ->andThrow(new Exception('Erro no cálculo'));

        $job = new ProcessContactScoreJob($contact->id);

        try {
            $job->handle(
                app(ContactRepository::class),
                $calculator
            );
        } catch (Exception $e) {
            //
        }

        $this->assertDatabaseHas('contact', [
            'id' => $contact->id,
            'status' => 'failed',
        ]);
    }

    public function test_processed_date_is_saved_after_score()
    {
        Queue::fake();

        $contact = Contact::factory()->create([
            "email" => "stylbandeira@styl.ai",
            "name" => "Styl Bandeira",
            "phone" => "87996236447"
        ]);

        $job = new ProcessContactScoreJob($contact->id);

        $calculator = Mockery::mock(ContactScoreCalculatorService::class);

        $calculator
            ->shouldReceive('calculate')
            ->once()
            ->andReturn(50);

        $job->handle(
            app(ContactRepository::class),
            $calculator
        );

        $this->assertDatabaseHas('contact', [
            'id' => $contact->id,
            'status' => 'active',
            'processed_at' => now()
        ]);
    }

    public function test_contact_score_processed_event_is_dispatched()
    {
        Event::fake();

        $contact = Contact::factory()->create();

        $calculator = Mockery::mock(ContactScoreCalculatorService::class);

        $calculator
            ->shouldReceive('calculate')
            ->once()
            ->andReturn(50);

        $job = new ProcessContactScoreJob($contact->id);

        $job->handle(
            app(ContactRepository::class),
            $calculator
        );

        Event::assertDispatched(
            ContactScoreProcessedEvent::class,
            function ($event) use ($contact) {
                return $event->contact->id === $contact->id;
            }
        );
    }

    /**
     * Returns valid Sao Paulo ddd's
     *
     * @return array
     */
    public static function validSaoPauloDdds(): array
    {
        return [
            ['11'],
            ['12'],
            ['13'],
            ['14'],
            ['15'],
            ['16'],
            ['17'],
            ['18'],
            ['19'],
        ];
    }
}
