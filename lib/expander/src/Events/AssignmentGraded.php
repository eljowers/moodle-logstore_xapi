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

namespace LogExpander\Events;

defined('MOODLE_INTERNAL') || die();

class AssignmentGraded extends Event {
    /**
     * Reads data for an event.
     * @param [String => Mixed] $opts
     * @return [String => Mixed]
     * @override Event
     */
    public function read(array $opts) {
        $grade = $this->repo->read_object($opts['objectid'], $opts['objecttable']);
        echo "<script type='text/javascript'> console.debug('GRADE: ','".json_encode(print_r($grade, true))."');</script>";
        $gradecomment = $this->repo->read_grade_comment($grade->id, $grade->assignment)->commenttext;
        echo "<script type='text/javascript'> console.debug('GRADE COMMENT: ','".json_encode(print_r($gradecomment, true))."');</script>";
        $gradeitems = $this->repo->read_grade_items($grade->assignment, 'assign');
        echo "<script type='text/javascript'> console.debug('GRADE ITEMS: ','".json_encode(print_r($gradeitems, true))."');</script>";

        return array_merge(parent::read($opts), [
            'grade' => $grade,
        ]);
        // return array_merge(parent::read($opts), [
        //     'grade' => $grade,
        //     'grade_comment' => $gradecomment,
        //     'grade_items' => $gradeitems,
        //     'graded_user' => $this->repo->read_user($grade->userid),
        //     'module' => $this->repo->read_module($grade->assignment, 'assign'),
        // ]);
    }
}
