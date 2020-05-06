<?php

namespace Tests\Feature\Observation;

use App\Models\Block;
use App\Models\Course;
use App\Models\Observation;
use App\Models\Participant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Tests\TestCaseWithBasicData;

class ReadObservationTest extends TestCaseWithBasicData {

    private $observationId;

    public function setUp(): void {
        parent::setUp();

        $this->observationId = $this->createObservation('hat gut mitgemacht');
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->get('/course/' . $this->courseId . '/observation/' . $this->observationId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldRequireNonArchivedCourse() {
        // given
        Course::find($this->courseId)->update(['archived' => true]);

        // when
        $response = $this->get('/course/' . $this->courseId . '/observation/' . $this->observationId);

        // then
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.course', ['course' => $this->courseId]));
    }

    public function test_shouldDisplayObservation() {
        // given

        // when
        $response = $this->get('/course/' . $this->courseId . '/observation/' . $this->observationId);

        // then
        $response->assertOk();
        $response->assertSee('hat gut mitgemacht');
    }

    public function test_shouldNotDisplayObservation_fromOtherCourseOfSameUser() {
        // given
        $otherKursId = $this->createCourse('Zweiter Kurs', '');

        // when
        $response = $this->get('/course/' . $otherKursId . '/observation/' . $this->observationId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }

    public function test_shouldNotDisplayObservation_fromOtherUser() {
        // given
        $otherCourseId = $this->createCourse('Zweiter Kurs', '', false);
        $otherParticipantId = Participant::create(['course_id' => $otherCourseId, 'scout_name' => 'Pflock'])->id;
        $otherBlockId = Block::create(['course_id' => $otherCourseId, 'full_block_number' => '1.1', 'name' => 'Block 1', 'block_date' => '01.01.2019', 'requirements' => null])->id;
        $otherUserId = $this->createUser(['name' => 'Lindo'])->id;
        $otherObservationId = $this->createObservation('hat gut mitgemacht', '1', [], [], $otherBlockId, $otherParticipantId, $otherUserId);

        // when
        $response = $this->get('/course/' . $otherCourseId . '/observation/' . $otherObservationId);

        // then
        $this->assertInstanceOf(ModelNotFoundException::class, $response->exception);
    }

    public function test_shouldRenderNewlinesInObservation() {
        // given
        $this->createObservation("Mehrzeilige Beobachtungen\n- nützlich\n- wichtig\n- erlauben Strukturierung");

        // when
        $response = $this->get('/course/' . $this->courseId . '/participants/' . $this->participantId);

        // then
        $response->assertOk();
        $response->assertSee("Mehrzeilige Beobachtungen\n- nützlich\n- wichtig\n- erlauben Strukturierung");
    }

    public function test_shouldRenderParticipants_whenMultipleAreAssigned() {
        // given
        $otherParticipantId = $this->createParticipant('Zweiter TN<em>yay!</em>');
        $this->createObservation("Wird auf allen TN angezeigt", 1, [], [], null, [$this->participantId, $otherParticipantId]);

        // when
        $response = $this->get('/course/' . $this->courseId . '/participants/' . $this->participantId);

        // then
        $response->assertOk();
        $response->assertSee(">Zweiter TN&lt;em&gt;yay!&lt;/em&gt;</a></div>Wird auf allen TN angezeigt");

        // when
        $response = $this->get('/course/' . $this->courseId . '/participants/' . $otherParticipantId);

        // then
        $response->assertOk();
        $response->assertSee(">Zweiter TN&lt;em&gt;yay!&lt;/em&gt;</a></div>Wird auf allen TN angezeigt");
    }

    public function test_shouldOrderObservationsByBlockOrder() {
        // given
        $this->createBlock('later date', '1.1', '02.01.2019');
        $this->createBlock('earlier date', '1.1', '31.12.2018');
        $this->createBlock('later day number', '2.1', '01.01.2019');
        $this->createBlock('earlier day number', '0.1', '01.01.2019');
        $this->createBlock('later block number', '1.2', '01.01.2019');
        $this->createBlock('earlier block number', '1.0', '01.01.2019');
        $this->createBlock('Block 2 later block name', '1.1', '01.01.2019');
        $this->createBlock('Block 0 earlier block name', '1.1', '01.01.2019');
        /** @var Collection $blockIds */
        $blockIds = $this->user()->lastAccessedCourse->blocks->map(function (Block $block) { return $block->id; });
        $blockIdsToCreateObservations = $blockIds->sort();
        $blockIdsToCreateObservations->shift();
        foreach ($blockIdsToCreateObservations as $blockId) {
            $this->createObservation(Block::find($blockId)->name, 1, [], [], $blockId);
        }

        // when
        $response = $this->get('/course/' . $this->courseId . '/participants/' . $this->participantId);

        // then
        $response->assertOk();
        $this->assertSeeAllInOrder('table.table-responsive-cards td[data-label="Beobachtung"]', [
          'earlier date',
          'earlier day number',
          'earlier block number',
          'Block 0 earlier block name',
          'hat gut mitgemacht',
          'Block 2 later block name',
          'later block number',
          'later day number',
          'later date',
        ]);
    }
}
