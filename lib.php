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
        $regions = array('content' => 'col-sm-6 col-sm-push-3 col-md-8 col-md-push-2',
                         'pre'     => 'col-sm-3 col-sm-pull-6 col-md-2 col-md-pull-8',
                         'post'    => 'col-sm-3 col-md-2');
    } else if ($hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-sm-9 col-sm-push-3 col-md-10 col-md-push-2',
                         'pre'     => 'col-sm-3 col-sm-pull-9 col-md-2 col-md-pull-10',
                         'post'    => 'emtpy');
    } else if (!$hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-9 col-md-10',
                         'pre'     => 'empty',
                         'pre'     => 'col-sm-3 col-md-2');
        $regions['post'] = 'col-sm-4 col-md-3';
    } else if (!$hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-md-12',
                         'pre'     => 'empty',
                         'post'    => 'empty');
    }
    return $regions;
}
