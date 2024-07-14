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

namespace local_manage_enrollments\local\services;

use local_manage_enrollments\local\repositories\EnrollmentRepository;
use local_manage_enrollments\local\repositories\UserRepository;
use RuntimeException;

class EnrollmentService {

    /** @var EnrollmentRepository */
    private $enrollmentRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var EnrollmentService */
    private static $enrollmentService;

    public function __construct() {
        $this->enrollmentRepository = new EnrollmentRepository();
        $this->userRepository = new UserRepository();
    }

    public function updateEnrollmentDates(int $courseId, int $userId, int $endDate, int $startDate = null)
    {

        $course = get_course($courseId);

        $user = $this->userRepository->findById($userId);

        if(empty($user)) {
            throw new RuntimeException('User not found');
        }

        $userEnrollment = $this->enrollmentRepository->getUserEnrollmentByCourseIdAndUserId($course->id, $user->id);
        if (empty($userEnrollment)) {
            throw new RuntimeException('The specified user does not have an enrollment');
        }

        if(!empty($startDate)) {
            $userEnrollment->timestart = $startDate;
        }

        $userEnrollment->timeend = $endDate;

        $this->enrollmentRepository->save($userEnrollment);

        return true;
    }

    public static function getEnrollmentService(): EnrollmentService
    {
        if (self::$enrollmentService === null) {
            self::$enrollmentService = new self();
        }

        return self::$enrollmentService;
    }
}