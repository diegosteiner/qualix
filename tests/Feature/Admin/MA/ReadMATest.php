<?php

namespace Tests\Feature\Admin\MA;

use App\Models\Requirement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCaseWithKurs;

class ReadMATest extends TestCaseWithKurs {

    private $maId;

    public function setUp(): void {
        parent::setUp();

        $this->maId = $this->createMA('Mindestanforderung 1', true);
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->get('/kurs/' . $this->courseId . '/admin/ma/' . $this->maId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldDisplayMA() {
        // given

        // when
        $response = $this->get('/kurs/' . $this->courseId . '/admin/ma/' . $this->maId);

        // then
        $response->assertOk();
        $response->assertSee('Mindestanforderung 1');
    }

    public function test_shouldNotDisplayMA_fromOtherCourseOfSameUser() {
        // given
        $otherKursId = $this->createKurs('Zweiter Kurs', '');

        // when
        $response = $this->get('/kurs/' . $otherKursId . '/admin/ma/' . $this->maId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }

    public function test_shouldNotDisplayMA_fromOtherUser() {
        // given
        $otherKursId = $this->createKurs('Zweiter Kurs', '', false);
        $otherMAId = Requirement::create(['course_id' => $otherKursId, 'content' => 'Mindestanforderung 1', 'mandatory' => '1'])->id;

        // when
        $response = $this->get('/kurs/' . $otherKursId . '/admin/ma/' . $otherMAId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }
}
