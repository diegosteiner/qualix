<?php

namespace Tests\Feature\Admin\Course;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateCourseTest extends TestCase {

    private $payload;

    public function setUp(): void {
        parent::setUp();

        $this->payload = ['name' => 'Kursname', 'course_number' => 'CH 123-00'];
    }

    public function test_shouldRequireLogin() {
        // given
        auth()->logout();

        // when
        $response = $this->post('/newcourse', $this->payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function test_shouldCreateAndAutoselectCourse() {
        // given

        // when
        $response = $this->post('/newcourse', $this->payload);

        // then
        $response->assertStatus(302);
        $response->assertRedirect('/');
        /** @var TestResponse $response */
        $response = $response->followRedirects();
        $this->assertRegExp("%<b-form-select[^>]*id=\"global-course-select\"[^>]*value=\"([^\"]*)\"((?!</b-form-select>).)*<b-form-select-option value=\"\\1\">" . $this->payload['name'] . "</b-form-select-option>%s", $response->content());
    }

    public function test_shouldValidateNewCourseData_noName() {
        // given
        $payload = $this->payload;
        unset($payload['name']);

        // when
        $response = $this->post('/newcourse', $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Kursname muss ausgefüllt sein.', $exception->validator->errors()->first('name'));
    }

    public function test_shouldValidateNewCourseData_longName() {
        // given
        $payload = $this->payload;
        $payload['name'] = 'Extrem langer Kursname 1Extrem langer Kursname 2Extrem langer Kursname 3Extrem langer Kursname 4Extrem langer Kursname 5Extrem langer Kursname 6Extrem langer Kursname 7Extrem langer Kursname 8Extrem langer Kursname 9Extrem langer Kursname 10Extrem langer Kursname 11';

        // when
        $response = $this->post('/newcourse', $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        /** @var ValidationException $exception */
        $exception = $response->exception;
        $this->assertEquals('Kursname darf maximal 255 Zeichen haben.', $exception->validator->errors()->first('name'));
    }

    public function test_shouldValidateNewCourseData_longCourseNumber() {
        // given
        $payload = $this->payload;
        $payload['course_number'] = 'Extrem lange Kursnummer 1Extrem lange Kursnummer 2Extrem lange Kursnummer 3Extrem lange Kursnummer 4Extrem lange Kursnummer 5Extrem lange Kursnummer 6Extrem lange Kursnummer 7Extrem lange Kursnummer 8Extrem lange Kursnummer 9Extrem lange Kursnummer 10Extrem lange Kursnummer 11';

        // when
        $response = $this->post('/newcourse', $payload);

        // then
        $this->assertInstanceOf(ValidationException::class, $response->exception);
    }
}
