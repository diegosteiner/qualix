<?php

namespace Tests\Feature\Observation;

use App\Models\HitobitoUser;
use Closure;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\UploadedFile;
use Tests\Feature\Auth\HitobitoOAuthTest;
use Tests\TestCaseWithBasicData;

class RestoreFormDataWhenSessionExpiredTest extends TestCaseWithBasicData {

    private $email = 'bari@example.com';
    private $formUrl;
    private $participantIds;
    private $blockIds;
    private $expectedRestoredFlashMessage = 'Deine eingegebenen Daten wurden wiederhergestellt. Speichern nicht vergessen!';
    private $payloadOverride = null;

    public function setUp(): void {
        parent::setUp();

        $this->formUrl = '/course/' . $this->courseId . '/observation/new';
        $this->participantIds = '' . $this->participantId;
        $this->blockIds = '' . $this->blockId;

        $this->createObservation('hat gut mitgemacht', 1, [], [], $this->blockId);
    }

    private function payload(): array {
        if ($this->payloadOverride !== null) {
            return $this->payloadOverride;
        }
        return ['participant_ids' => $this->participantIds, 'content' => 'this text will be restored', 'impression' => '1', 'block_id' => $this->blockIds, 'requirement_ids' => '', 'category_ids' => ''];
    }

    public function test_shouldRestoreSubmittedFormData_whenLoggingBackInNormally() {
        $this->checkRestorationOfFormData(function () {
            // the user logs back in
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        });
    }

    public function test_shouldRestoreSubmittedFormData_whenLoginFailsOnce() {
        $this->checkRestorationOfFormData(function () {
            // the user at first fails to log back in
            $response = $this->post('/login', ['email' => $this->email, 'password' => 'wrong-password'], ['referer' => '/login']);
            $response->assertStatus(302);
            $response->assertRedirect('/login');

            // then the user manages to log back in
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        });
    }

    public function test_shouldRestoreSubmittedFormData_whenLoggingInAsADifferentUser_inSameCourse() {
        $otherUser = $this->createUser(['name' => 'Lindo', 'password' => bcrypt('12345678'), 'email' => 'another@user.com'], false);
        $otherUser->courses()->attach($this->courseId);

        $this->checkRestorationOfFormData(function () {
            // log in as a different user
            return $this->post('/login', ['email' => 'another@user.com', 'password' => '12345678'], ['referer' => '/login']);
        });
    }

    public function test_shouldNotRestoreSubmittedFormData_whenLoggingInAsADifferentUser_whoIsNotPartOfTheCourse() {
        $this->createUser(['name' => 'Lindo', 'password' => bcrypt('12345678'), 'email' => 'another@user.com'], false);

        $this->checkRestorationOfFormData(function () {
            // log in as a different user
            return $this->post('/login', ['email' => 'another@user.com', 'password' => '12345678'], ['referer' => '/login']);
        }, false);
    }

    public function test_shouldRestoreSubmittedFormData_whenLoggingInViaHitobito() {
        $otherUser = factory(HitobitoUser::class)->create(['hitobito_id' => 123, 'name' => 'Cosinus', 'email' => 'cosinus@hitobito.com']);
        HitobitoOAuthTest::mockHitobitoResponses(123, 'cosinus@hitobito.com', 'Cosinus');
        $otherUser->courses()->attach($this->courseId);

        $this->checkRestorationOfFormData(function () {
            $state = HitobitoOAuthTest::extractRedirectQueryParams($this->get('/login/hitobito'))['state'];
            return $this->get('/login/hitobito/callback?code=1234&state=' . $state);
        });
    }

    public function test_shouldRestoreSubmittedFormData_whenChangingLanguageOnLoginScreen() {
        $this->checkRestorationOfFormData(function () {
            // the user switches the language
            $this->get('/locale/fr');
            $this->expectedRestoredFlashMessage = 'Tes données saisies ont étés restaurées. N&#039;oublie pas a sauvegarder!';

            // the user logs back in
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        }, true);
    }

    public function test_shouldRestoreEmptyParticipantField_evenWhenParticipantIsSpecifiedInURL() {
        // given
        // Participant is specified in URL
        $this->formUrl = '/course/' . $this->courseId . '/observation/new?participant=' . $this->participantId;
        // but not sent in payload, so the user deleted it from the form
        $this->participantIds = '';

        $this->checkRestorationOfFormData(function () {
            // the user logs back in normally
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        }, true, function (TestResponse $response) {
            // then
            // the participant selection field in the restored form should still be empty
            $response->assertDontSee(' value="' . $this->participantId . '"');
        });
    }

    public function test_shouldRestoreChangedBlockField_evenWhenBlockIsSpecifiedInURL() {
        // given
        // Block is specified in URL
        $this->formUrl = '/course/' . $this->courseId . '/observation/new?block=' . $this->blockId;
        // but a different one is sent in payload, so the user manually changed it
        $this->blockIds = '9999999';

        $this->checkRestorationOfFormData(function () {
            // the user logs back in normally
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        }, true, function (TestResponse $response) {
            // then
            // the participant selection field in the restored form should still be the changed value
            $response->assertDontSee(' value="' . $this->blockId . '"');
            $response->assertSee(' old-value="' . $this->blockIds . '"');
        });
    }

    public function test_shouldNotRestoreFileInput() {
        $this->formUrl = '/user';
        $this->payloadOverride = ['name' => 'this text will be restored', 'image' => new UploadedFile(__DIR__.'/../../resources/Blockuebersicht.xls', 'profilePicture.jpg')];
        $this->checkRestorationOfFormData(function () {
            // the user logs back in
            return $this->post('/login', ['email' => $this->email, 'password' => '87654321'], ['referer' => '/login']);
        });
    }

    public function checkRestorationOfFormData(Closure $logBackIn, bool $shouldRestore = true, Closure $afterRelogin = null) {
        // given
        $this->get($this->formUrl);

        // simulate the user session expiring
        auth()->logout();

        // when
        // simulate the user clicking the stale submit button
        $response = $this->post('/course/' . $this->courseId . '/observation/new', $this->payload());
        $response->assertStatus(302);
        $response->assertRedirect('/login');

        // then
        // check that the flash message is displayed
        /** @var TestResponse $response */
        $response = $response->followRedirects();
        $response->assertSeeText('Ups, du bist inzwischen nicht mehr eingeloggt. Bitte logge dich nochmals ein, deine Eingaben werden dann wiederhergestellt.');

        // when
        $response = $logBackIn();

        // then
        $response->assertStatus(302);
        $response->assertRedirect($this->formUrl);

        /** @var TestResponse $response */
        $response = $response->followRedirects();

        // check that restoration works as intended
        if ($shouldRestore) {
            $response->assertSeeText($this->expectedRestoredFlashMessage);
            $response->assertSee('this text will be restored');
        } else {
            $response->assertDontSeeText($this->expectedRestoredFlashMessage);
            $response->assertDontSee('this text will be restored');
        }

        if ($afterRelogin !== null) {
            // additional assertions
            $afterRelogin($response);
        }

        // when
        // Refresh the page
        $response = $this->get('/course/' . $this->courseId . '/observation/new');

        // then
        // data should not be restored a second time
        $response->assertDontSeeText($this->expectedRestoredFlashMessage);
        $response->assertDontSee('this text will be restored');
    }

}