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
 * Filename : login
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 06 Mar 2015
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/login/lib.php');
?>
<div class="loginbox clearfix twocolumns">
    <div class="loginpanel">
        <h2><?php print_string('login'); ?></h2>
        <div class="subcontent loginsub">
        <?php
            if (!empty($errormsg)) :
                echo html_writer::start_div('loginerrors')
                   . html_writer::link('#', $errormsg, array('id'    => 'loginerrormessage',
                                                             'class' => 'accesshide'))
                   . $OUTPUT->error_text($errormsg)
                   . html_writer::end_div();
            endif;
        ?>
            <form method="post" action="<?php echo $CFG->httpswwwroot ?>/login/index.php" id="login" <?php echo $autocomplete; ?>>
                <div class="loginform">
                    <div class="form-label"><label for="username"><?php print_string('username'); ?></label></div>
                    <div class="form-input">
                        <input type="text" name="username" id="username" size="15" value="<?php p($frm->username); ?>" />
                    </div>
                    <div class="clearer"><!-- --></div>
                    <div class="form-label"><label for="password"><?php print_string('password'); ?></label></div>
                    <div class="form-input">
                        <input type="password" name="password" id="username" size="15" value="" <?php echo $autocomplete; ?> />
                    </div>
                </div>
                <div class="clearer"><!-- --></div>
                <?php if (isset($CFG->rememberusername) && ($CFG->rememberusername == 2)): ?>
                <div class="rememberpass">
                    <input type="checkbox" name="rememberusername" id="rememberusername" value="1" <?php if ($frm->username) {echo 'checked="checked"';} ?> />
                    <label for="rememberusername"><?php print_string('rememberusername', 'admin') ?></label>
                </div>
                <?php endif; ?>
                <div class="clearer"><!-- --></div>
                <input type="submit" id="loginbtn" value="<?php print_string('login'); ?>" />
                <div class="forgetpass"><a href="/login/forgot_password.php"><?php print_string('forgotten'); ?></a></div>
            </form>
            <div class="desc"><?php
                echo get_string('cookiesenabled')
                   . $OUTPUT->help_icon('cookiesenabled');
            ?></div>
        </div>
        <?php if ($CFG->guestloginbutton and !isguestuser()) : ?>
        <div class="subcontent guestsub">
            <div class="desc"><?php print_string("someallowguest") ?></div>
            <form action="index.php" method="post" id="guestlogin">
                <div class="guestform">
                    <input type="hidden" name="username" value="guest" />
                    <input type="hidden" name="password" value="guest" />
                    <input type="submit" value="<?php print_string("loginguest") ?>" />
                </div>
            </form>
        </div>
        <?php endif ?>
    </div>
<?php if ($show_instructions) : ?>
    <div class="signuppanel">
<!--        <h2><?php print_string("firsttime") ?></h2> -->
        <div class="subcontent">
<?php
        if (is_enabled_auth('none')) : // instructions override the rest for security reasons
            print_string("loginstepsnone");
        elseif ($CFG->registerauth == 'email') :
            if (!empty($CFG->auth_instructions)) :
                echo format_text($CFG->auth_instructions);
            else :
                print_string("loginsteps", "", "signup.php");
            endif;
?>
            <div class="signupform">
                <form action="signup.php" method="get" id="signup">
                    <div><input type="submit" value="<?php print_string("startsignup") ?>" /></div>
                </form>
            </div>
<?php
        elseif (!empty($CFG->registerauth)) :
            echo format_text($CFG->auth_instructions);
?>
            <div class="signupform">
                <form action="signup.php" method="get" id="signup">
                    <div><input type="submit" value="<?php print_string("startsignup") ?>" /></div>
                </form>
              </div>
<?php
        else :
            echo format_text($CFG->auth_instructions);
        endif;
?>
        </div>
    </div>
<?php
endif;
if (!empty($potentialidps)) :
?>
    <div class="subcontent potentialidps">
        <h6><?php print_string('potentialidps', 'auth'); ?></h6>
        <div class="potentialidplist">
<?php
    foreach ($potentialidps as $idp) :
        echo  '<div class="potentialidp"><a href="' . $idp['url']->out() . '" title="' . $idp['name'] . '">' . $OUTPUT->render($idp['icon'], $idp['name']) . $idp['name'] . '</a></div>';
    endforeach;
?>
        </div>
    </div>
<?php endif; ?>
</div>
