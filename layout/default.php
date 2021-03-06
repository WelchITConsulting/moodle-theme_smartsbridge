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
 * Filename : default
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 03 Jan 2015
 */

$isLoggedIn = isloggedin();

$hassidepre = $isLoggedIn && $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $isLoggedIn && $PAGE->blocks->region_has_content('side-post', $OUTPUT);

$knownregionpre = $isLoggedIn && $PAGE->blocks->is_known_region('side-pre');
$knownregionpost = $isLoggedIn && $PAGE->blocks->is_known_region('side-post');

$regions = smartsbridge_grid($hassidepre, $hassidepost);
$PAGE->set_popup_notification_allowed(false);
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('bootstrap', 'theme_smartsbridge');
echo $OUTPUT->doctype(); ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon(); ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body <?php echo $OUTPUT->body_attributes(); ?>>
    <?php echo $OUTPUT->standard_top_of_body_html() ?>
    <div id="site-content">
        <header class="site-header container-fluid">
            <div class="site-brand">
                <a class="site-logo" href="<?php echo $CFG->wwwroot;?>"><span><?php echo $SITE->shortname; ?></span></a>
            </div>
            <nav role="navigation" class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#moodle-navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div id="moodle-navbar" class="navbar-collapse collapse">
                        <?php echo $OUTPUT->custom_menu(); ?>
                        <?php echo $OUTPUT->user_menu(); ?>
                        <ul class="nav pull-right">
                            <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <div id="page" class="container-fluid">
            <header id="page-header" class="clearfix">
                <div id="page-navbar" class="clearfix">
                    <nav class="breadcrumb-nav" role="navigation" aria-label="breadcrumb"><?php echo $OUTPUT->navbar(); ?></nav>
                    <div class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></div>
                </div>
                <div id="course-header">
                    <?php echo $OUTPUT->course_header(); ?>
                </div>
            </header>
            <div id="page-content" class="row">
                <div id="region-main" class="<?php echo $regions['content']; ?>">
                    <?php
                    echo $OUTPUT->course_content_header()
                       . $OUTPUT->main_content()
                       . $OUTPUT->course_content_footer();
                    ?>
                </div>
                <?php
                if ($knownregionpre) {
                    echo $OUTPUT->blocks('side-pre', $regions['pre']);
                }?>
                <?php
                if ($knownregionpost) {
                    echo $OUTPUT->blocks('side-post', $regions['post']);
                }?>
            </div>
        </div>
    </div>
    <footer class="site-footer" >
        <div class="container-fluid">
            <div id="course-footer" class="row-fluid"><?php echo $OUTPUT->course_footer(); ?></div>
            <div class="row-fluid">
                <div id="copyright" class="col-md-8"><?php
                    printf(get_string('copyright', 'theme_smartsbridge'),
                           date('Y'),
                           $SITE->fullname);
                    printf('<br>' . get_string('designedby', 'theme_smartsbridge'),
                           '<a href="http://welchitconsulting.com/">Welch IT Consulting</a>');
                    ?></div>
                <div class="col-md-4 site-links">
                <?php
                    echo $OUTPUT->login_info()
                       . $OUTPUT->standard_footer_html();
                ?>
                </div>
            </div>
        </div>
    </footer>
    <?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
