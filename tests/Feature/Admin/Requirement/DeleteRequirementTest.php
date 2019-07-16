<?php

namespace Tests\Feature\Admin\Requirement;

use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCaseWithCourse;

class DeleteRequirementTest extends TestCaseWithCourse {

    private $maId;

    public function setUp(): void {
        parent::setUp();

        $this->maId = $this->createRequirement('Mindestanforderung 1', true);
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->delete('/course/' . $this->courseId . '/admin/requirement/' . $this->maId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldDeleteMA() {
        // given

        // when
        $response = $this->delete('/course/' . $this->courseId . '/admin/requirement/' . $this->maId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/course/' . $this->courseId . '/admin/requirement');
        /** @var TestResponse $response */
        $response = $response->followRedirects();
        $response->assertDontSee('Mindestanforderung 1');
    }

    public function test_shouldValidateDeletedMAUrl_wrongId() {
        // given

        // when
        $response = $this->delete('/course/' . $this->courseId . '/admin/requirement/' . ($this->maId + 1));

        // then
        $response->assertStatus(404);
    }
}