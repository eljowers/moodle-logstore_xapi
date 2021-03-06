<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace XREmitter\Tests;

defined('MOODLE_INTERNAL') || die();

use \XREmitter\Events\Attended as Event;

class attended_test extends event_test {
    protected static $recipename = 'training_session_attend';

    /**
     * Sets up the tests.
     * @override EventTest
     */
    public function setup() {
        $this->event = new Event();
    }

    protected function construct_input() {
        return array_merge(
            parent::construct_input(),
            $this->construct_object('course'),
            $this->construct_object('module'),
            $this->construct_object('session', 'http://activitystrea.ms/schema/1.0/event'),
            $this->construct_user('attendee'),
            [
                "attempt_duration" => "PT150S",
                "attempt_completion" => true
            ]
        );
    }

    protected function assert_output($input, $output) {
        $this->assert_object('app', $input, $output['context']['contextActivities']['grouping'][0]);
        $this->assert_object('source', $input, $output['context']['contextActivities']['category'][0]);
        $this->assert_log($input, $output);
        $this->assert_info(
            $input['context_info'],
            $output['context']['extensions']['http://lrs.learninglocker.net/define/extensions/info']
        );
        $this->assert_valid_xapi_statement($output);
        $this->assert_user($input, $output['actor'], 'attendee');
        $this->assert_user($input, $output['context']['instructor'], 'user');
        $this->assert_verb('http://adlnet.gov/expapi/verbs/attended', 'attended', $output['verb']);
        $this->assert_object('session', $input, $output['object']);
        $this->assert_object('module', $input, $output['context']['contextActivities']['parent'][0]);
        $this->assert_object('course', $input, $output['context']['contextActivities']['grouping'][1]);
        $this->assertEquals($input['attempt_duration'], $output['result']['duration']);
        $this->assertEquals($input['attempt_completion'], $output['result']['completion']);
        $this->assertEquals(
            'http://xapi.trainingevidencesystems.com/recipes/attendance/0_0_1#simple',
            $output['context']['contextActivities']['category'][1]['id']
            );
    }
}
