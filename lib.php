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
 * Filename : lib
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 03 Jan 2015
 */

function smartsbridge_grid($hassidepre, $hassidepost)
{
    if ($hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-6 col-sm-push-3 col-md-7 col-md-push-3 col-lg-6 col-lg-push-2',
                         'pre'     => 'col-md-3 col-md-pull-7 col-lg-2 col-lg-pull-6',
                         'post'    => 'col-sm-3 col-md-2');
    } else if ($hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-sm-9 col-sm-push-3 col-md-9 col-md-push-3 col-lg-10 col-lg-push-2',
                         'pre'     => 'col-md-3 col-md-pull-9 col-lg-2 col-lg-pull-10',
                         'post'    => 'emtpy');
    } else if (!$hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-9 col-md-10',
                         'pre'     => 'empty',
                         'post'    => 'col-sm-3 col-md-2');
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if (!$hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-md-12',
                         'pre'     => 'empty',
                         'post'    => 'empty');
    }
    return $regions;
}
