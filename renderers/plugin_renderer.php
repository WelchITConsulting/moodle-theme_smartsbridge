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
 * Filename : plugin_renderer
 * Author   : John Welch <jwelch@welchitconsulting.co.uk>
 * Created  : 02 Mar 2015
 */

class theme_smartsbridge_plugin_renderer_base extends plugin_renderer_base
{
    public function index_page($detected, array $actions)
    {
        return '<p>theme_smartsbridge_renderer_base</p>';
    }
}
