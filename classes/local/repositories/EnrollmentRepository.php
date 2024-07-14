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

/**
 * Version
 *
 * @package local_manage_enrollments
 * @copyright 2024 4Linux {@link https://www.lucasmendesdev.com.br}
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_manage_enrollments\local\repositories;

class EnrollmentRepository extends RepositoryBase
{

    public function __construct() {
        parent::__construct('user_enrolments');
    }


    public function getUserEnrollmentByCourseIdAndUserId(int $courseId, int $userId)
    {
        $sql = "SELECT ue.*
            FROM {{$this->getTable()}} ue
            JOIN {enrol} e ON e.id = ue.enrolid
            JOIN {user} u ON u.id = ue.userid
        WHERE e.courseid = :courseid AND ue.userid = :userid";

        return $this->db->get_record_sql($sql, [
            'courseid' => $courseId,
            'userid' => $userId
        ]);
    }

}