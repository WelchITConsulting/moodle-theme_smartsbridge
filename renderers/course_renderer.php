<?php
/*
 * Copyright (C) 2015 Welch IT Consulting
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Filename : course_renderer
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 02 Mar 2015
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/renderer.php');

class theme_smartsbridge_core_course_renderer extends core_course_renderer
{
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course)
    {
        global $CFG;

        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            return '';
        }
        if ($course instanceof stdClass) {
            require_once($CFG->libdir . '/coursecatlib.php');
            $course = new course_in_list($course);
        }
        // Display cousrse overview files
        $contentimages = $contentfiles = '';
        foreach($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
//            $url = new moodle_url
        }
        $content = $contentimages
                 . $contentfiles
                 . ($course->has_summary() ? $chelper->get_course_formatted_summary($course)
                                           : '');

        if ($course->has_course_contacts()) {
            // Display course group links in a tag cloud
            $managers = array();
            $roles    = array();
            foreach($course->get_course_contacts() as $userid => $contact) {
                if (strtolower($contact['rolename']) == 'manager') {
                    $managers[] = html_writer::link(new moodle_url('/user/view.php',
                                                                   array('id'     => $userid,
                                                                         'course' => SITEID)),
                                                    $contact['username']);
                    continue;
                }
                $groups = groups_get_user_groups($course->id, $userid);
                if (!isset($groups[0][0])) {
                    continue;
                }
                if (!array_key_exists($groups[0][0], $roles)) {
                    $roles[$groups[0][0]] = 0;
                }
                $roles[$groups[0][0]]++;
            }
            if (!empty($roles)) {
                $content .= html_writer::div($this->show_tag_cloud($roles, $course->id), 'course-roles');
            }

            // Display admin contacts
            if (!empty($managers)) {
                $content .= html_writer::tag('h4', get_string('managerlinks', 'theme_smartsbridge'))
                          . html_writer::start_div('support-contacts');
                foreach($managers as $manager) {
                    $content .= html_writer::span($manager, 'contact');
                }
                $content .= html_writer::end_div();
            }
        }
        // Display course category if necessary
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT) {
            require_once($CFG->libdir . '/coursecatlib.php');
            if ($cat = coursecat::get($$course->category, IGNORE_MISSING)) {
                $content .= html_writer::start_div('coursecat')
                          . get_string('category')
                          . ': '
                          . html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                                              $cat->get_formatted_name(),
                                              array('class' => ($cat->visible ? '' : 'dimmed')))
                          . html_writer::end_div();
            }
        }
        return $content;
    }

    private function show_tag_cloud($tags, $courseid)
    {
        $smallest   = 11;
        $largest    = 22;
        $maxcounts  = max($tags);
        $mincounts  = min($tags);
        $spread     = $maxcounts - $mincounts;
        if ($spread <= 0) {
            $spread = 1;
        }
        $fontspread = $largest - $smallest;
        if ($fontspread <= 0) {
            $fontspread = 1;
        }
        $fontstep   = $fontspread / $spread;
        $a = array();
        $ctx = context_course::instance($courseid);
        foreach($tags as $groupid => $count) {
            $group = groups_get_group($groupid);
            $groupurl = new moodle_url('/user/index.php', array('contextid'   => $ctx->id,
                                                                'id'          => $courseid,
                                                                'group'       => $groupid));
            $fontsize = str_replace(',', '.', ($smallest + (($count - $mincounts) * $fontstep)));
            $a[] = html_writer::tag('a', $group->name, array('href'  => $groupurl,
                                                             'title' => $group->name,
                                                             'class' => 'group-link-' . $group->id,
                                                             'style' => 'font-size: ' . $fontsize . 'pt'));
        }
        if (empty($a)) {
            return '';
        }
        return '<ul class="sb-tag-cloud"><li>'
             . implode('</li><li>', $a)
             . '</li></ul>';
    }
}