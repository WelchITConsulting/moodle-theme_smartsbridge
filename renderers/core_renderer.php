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
 * Filename : core_renderer
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 03 Jan 2015
 */

class theme_smartsbridge_core_renderer extends core_renderer
{
    /**************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************/

    /**
     * This renders a notification message.
     * Uses bootstrap compatible html.
     */
    public function notification($message, $classes = 'notifyproblem')
    {
        $message = clean_text($message);
        switch($classes) {
            case 'notifyproblem':
                $type = 'alert alert-danger';
                break;
            case 'notifysuccess':
                $type = 'alert alert-success';
                break;
            case 'notifymessage':
                $type = 'alert alert-info';
                break;
            case 'redirectmessage':
                $type = 'alert alert-block alert-info';
                break;
            default:
                $type = '';
        }
        return "<div class=\"$type\">$message</div>";
    }

    /**
     * This renders the site subtitle when on  the front page.
     * Uses bootstrap compatible html.
     */
    public function page_heading($tag = 'h1')
    {
        $heading = parent::page_heading();
        if ($this->page->pagelayout == 'frontpage') {
            $heading .= '<h3>' . $this->page->theme->settings->subtitle . '</h3>';
        }
        return $heading;
    }

    /**
     * This renders the breadcrumbs.
     * Uses bootstrap compatible html.
     */
    public function navbar()
    {
        $breadcrumbs = '';
        foreach ($this->page->navbar->get_items() as $item) {
            $item->hideicon = true;
            $breadcrumbs .= '<li>' . $this->render($item) . '</li>';
        }
        return '<ol class=breadcrumb>' . $breadcrumbs . '</ol>';
    }

    /**
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function custom_menu($custommenuitems = '')
    {
        global $CFG;
        if (!empty($CFG->custommenuitems)) {
            $custommenuitems .= $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    /**
     * Overriding the custom_menu function ensures the custom menu is
     * always shown, even if no menu items are configured in the global
     * theme settings page.
     */
    public function user_menu()
    {
        global $CFG;
        $usermenu = new custom_menu('', current_language());
        return $this->render_user_menu($usermenu);
    }

    /**************************************************************************
     * PROTECTED FUNCTIONS
     **************************************************************************/

    /**
     * This renders the bootstrap top menu.
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_custom_menu(custom_menu $menu)
    {
        global $CFG, $USER;
        $content = '<ul class="nav navbar-nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
        return $content.'</ul>';
    }

    /**
     * This renders the bootstrap top menu.
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_user_menu(custom_menu $menu)
    {
        global $CFG, $USER, $DB;

        // Add the messages menu
        if (!isloggedin() || isguestuser()) {
            $messages = $this->get_user_messages();
            $messagecount = count($messages);
            $messagemenu = $menu->add($messagecount . ' ' . get_string('messages', 'message'),
                                      new moodle_url('#'),
                                      get_string('messages', 'message'),
                                      9999);
            foreach ($messages as $message) {
                $senderpicture = new user_picture($message->from);
                $senderpicture->link = false;
                $messagecontent = $this->render($senderpicture)
                                . html_writer::start_span('msg-body')
                                . html_writer::start_span('msg-title')
                                . html_writer::span($message->from->firstname . ': ', 'msg-sender')
                                . $message->text
                                . html_writer::end_span()   // msg-title
                                . html_writer::start_span('msg-time')
                                . html_writer::tag('i', '', array('class' => 'icon-time'))
                                . html_writer::span($message->date)
                                . html_writer::end_span();  // msg-body
                $messageurl = new moodle_url('/message/index.php', array('user1' => $USER->id,
                                                                         'user2' => $message->from->id));
                $messagemenu->add($messagecontent, $messageurl, $message->state);
            }
        }

        // Add the language selection menu
        $langs = get_string_manager()->get_list_of_translations();
        $addlangmenu = true;
        if ((count($langs) < 2) || empty($CFG->langmenu) ||
                (($this->page->course != SITEID) && !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }
        if ($addlangmenu) {
            $language = $menu->add(get_string('language'), new moodle_url('#'), get_string('language'), 10000);
            foreach ($langs as $langtype => $langname) {
                $language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }
        if (!$menu->has_children() && ($addlangmenu === false)) {
            return '';
        }

        // Add the user menu
        if (isloggedin()) {
            $usermenu = $menu->add(fullname($USER), new moodle_url('#'), fullname($USER), 10001);
            $usermenu->add('<i class="glyphicon glypicon-lock"></i>' . get_string('logout'),
                           new moodle_url('/login/logout.php', array('sesskey'=>sesskey(), 'alt'=>'logout')),
                           get_string('logout'));
            $usermenu->add('<i class="glyphicon glypicon-user"></i>' . get_string('viewprofile'),
                           new moodle_url('/user/profile.php', array('id'=>$USER->id)),
                           get_string('viewprofile'));
            $usermenu->add('<i class="glyphicon glypicon-cog"></i>' . get_string('editmyprofile'),
                           new moodle_url('/user/edit.php', array('id'=>$USER->id)),
                           get_string('editmyprofile'));
        } else {
            $usermenu = $menu->add(get_string('login'), new moodle_url('/login/index.php'), get_string('login'), 10001);
        }
        $content = '<ul class="nav navbar-nav navbar-right">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        // Return the menu structures
        return $content.'</ul>';
    }

    /**
     * Render the menu items recursively
     *
     * @staticvar int $submenucount
     * @param custom_menu_item $menunode
     * @param type $level
     * @return string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0)
    {
        static $submenucount = 0;
        if ($menunode->has_children()) {
            if ($level == 1) {
                $dropdowntype = 'dropdown';
            } else {
                $dropdowntype = 'dropdown-submenu';
            }
            $content = html_writer::start_tag('li', array('class' => $dropdowntype));
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_' . $submenucount;
            }
            $linkAttributes = array('href'        => $url,
                                    'class'       => 'dropdown-toggle',
                                    'data-toggle' => 'dropdown',
                                    'title'       => $menunode->get_title());
            $content .= html_writer::start_tg('a', $linkAttributes)
                      . $menunode->get_title()
                      . (($level == 1) ? '<c class="caret"></b>' : '')
                      . html_writer::end_tag('a')
                      . html_writer::start_tag('ul', array('class' => 'dropdown-menu'));
            foreach($menunode->get_children() as $node) {
                $content .= $this->render_custom_menu_item($node);
            }
            $content .= html_writer::end_tag('ul')
                      . html_writer::end_tag('li');
        } else {
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content = html_writer::start_tag('li')
                     . html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()))
                     . html_writer::end_tag('li');
        }
        return $content;
    }

    /**
     * Renders tabtree
     *
     * @param tabtree $tabtree
     * @return string
     */
    protected function render_tabtree(tabtree $tabtree)
    {
        if (empty($tabtree->subtree)) {
            return '';
        }
        $firstrow = $secondrow = '';
        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);
            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }
        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
    }

    /**
     * Renders tabobject (part of tabtree)
     *
     * This function is called from {@link core_renderer::render_tabtree()}
     * and also it calls itself when printing the $tabobject subtree recursively.
     *
     * @param tabobject $tabobject
     * @return string HTML fragment
     */
    protected function render_tabobject(tabobject $tab)
    {
        if ($tab->selected or $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
        } else {
            if (!($tab->link instanceof moodle_url)) {
                // Backward compatibility when link was passed as quoted string.
                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
            } else {
                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
            }
            return html_writer::tag('li', $link);
        }
    }

    /**************************************************************************
     * PRIVATE FUNCTIONS
     **************************************************************************/

    /**
     * Gets an array of unread message objects for the current user
     *
     * @global type $USER
     * @global type $DB
     * @return array The array of new messages
     */
    private function get_new_user_messages()
    {
        global $USER, $DB;
        $messagelist = array();
        $messagessql = 'SELECT id, smallmessage, useridfrom, useridto, '
                     . 'timecreated, fullmessageformat, notification FROM {message} '
                     . 'WHERE useridto = :userid ORDER BY timecreated DESC LIMIT 5';
        $messages = $DB->get_records_sql($messagessql, array('userid' => $USER->id));
        foreach($messages as $message) {
            $messageContent = new stdClass();
            if ($message->notification) {
                $messageContent->text = get_string('unreadnotification', 'message');
            } else {
                if ($message->fullmessageformat == FORMAT_HTML) {
                    $message->smallmessage = html_to_text($message->smallmessage);
                }
                if (core_text::strlen($message->smallmessage) > 15) {
                    $messageContent->text = core_text::substr($message->smallmessage, 0, 15) . '...';
                } else {
                    $messageContent->text = $message->smallmessage;
                }
            }
            $elapsedTime = time() - $message->timecreated;
            if ($elapsedTime < 10800) {
                $messageContent->date = format_time($elapsedTime);
            } else {
                $messageContent->date = userdate($message->timecreated, get_string('strftimetime', 'langconfig'));
            }
            $messageContent->from = $DB->get_record('user', array('id' => $message->useridfrom));
            $messagelist[] = $messageContent;
        }
        return $messagelist;
    }
}
