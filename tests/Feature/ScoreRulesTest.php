<?php

namespace Tests\Feature;

use App\Domain\Services\ScoreRules\EmailScoreRule;
use App\Domain\Services\ScoreRules\NameScoreRule;
use App\Domain\Services\ScoreRules\PhoneScoreRule;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_corporate_email_receives_20_points(): void
    {
        $contact = Contact::factory()->make([
            'email' => 'maria@maria.com',
        ]);

        $rule = new EmailScoreRule();

        $this->assertEquals(20, $rule->calculate($contact));
    }

    public function test_not_corporate_and_not_br_email_receives_0_points(): void
    {
        $contact = Contact::factory()->make([
            'email' => 'maria@gmail.com',
        ]);

        $rule = new EmailScoreRule();

        $this->assertEquals(0, $rule->calculate($contact));
    }

    public function test_name_with_2_or_more_words_receives_10_points(): void
    {
        $contact = Contact::factory()->make([
            'name' => 'Styl Bandeira'
        ]);

        $rule = new NameScoreRule();

        $this->assertEquals(10, $rule->calculate($contact));
    }

    public function test_name_with_only_1_word_receives_0_points(): void
    {
        $contact = Contact::factory()->make([
            'name' => 'Styl'
        ]);

        $rule = new NameScoreRule();

        $this->assertEquals(0, $rule->calculate($contact));
    }

    public function test_name_with_only_1_word_and_space_still_receives_0_points(): void
    {
        $contact = Contact::factory()->make([
            'name' => 'Styl '
        ]);

        $rule = new NameScoreRule();

        $this->assertEquals(0, $rule->calculate($contact));
    }

    /**
     * @dataProvider validSaoPauloDdds
     *
     * @return void
     */
    public function test_area_code_from_sao_paulo_receives_20_points(string $ddd): void
    {
        $contact = Contact::factory()->make([
            'phone' => "{$ddd}996236447"
        ]);

        $rule = new PhoneScoreRule();

        $this->assertEquals(20, $rule->calculate($contact));
    }

    public function test_area_code_not_from_sao_paulo_receives_10_points_ddd_87(): void
    {
        $contact = Contact::factory()->make([
            'phone' => "87996236447"
        ]);

        $rule = new PhoneScoreRule();

        $this->assertEquals(10, $rule->calculate($contact));
    }

    public function test_area_code_not_from_sao_paulo_receives_10_points_ddd_10(): void
    {
        $contact = Contact::factory()->make([
            'phone' => "10996236447"
        ]);

        $rule = new PhoneScoreRule();

        $this->assertEquals(10, $rule->calculate($contact));
    }

    public function test_area_code_not_from_sao_paulo_receives_10_points_ddd_20(): void
    {
        $contact = Contact::factory()->make([
            'phone' => "20996236447"
        ]);

        $rule = new PhoneScoreRule();

        $this->assertEquals(10, $rule->calculate($contact));
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
