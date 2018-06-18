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

namespace LogExpander\Tests;

defined('MOODLE_INTERNAL') || die();

use \LogExpander\Events\AssignmentSubmitted as Event;

class assignment_submitted_test extends event_test {
    /**
     * Sets up the tests.
     * @override TestCase
     */
    public function setup() {
        parent::setup();
        $this->event = new Event($this->repo);
    }

    protected function construct_input() {
        return array_merge(parent::construct_input(), [
            'objecttable' => 'assign_submission',
            'objectid' => 1,
            'eventname' => '\mod_assign\event\assessable_submitted',
        ]);
    }

    protected function assert_output($input, $output) {
        parent::assert_output($input, $output);
        $this->assert_module(1, $output['module'], 'assign');
        $this->assert_record($input['objectid'], $output['submission']);
    }
}
