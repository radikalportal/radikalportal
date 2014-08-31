<?php
/**
 * Constants used by this plugin
 * 
 * @package Flare
 * @author dtelepathy
 */

/*
Copyright 2012 digital-telepathy  (email : support@digital-telepathy.com)

This file is part of Flare.

SlideDeck is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

SlideDeck is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with SlideDeck.  If not, see <http://www.gnu.org/licenses/>.
*/

// Plugin Version
if( !defined( 'FLARE_VERSION' ) ) define( 'FLARE_VERSION', "1.2.7" );

// Environment - change to "development" to load .dev.js JavaScript files (DON'T FORGET TO TURN IT BACK BEFORE USING IN PRODUCTION)
if( !defined( 'FLARE_ENVIRONMENT' ) ) define( 'FLARE_ENVIRONMENT', 'development' );

// Is this an AJAX request?
if( !defined( 'FLARE_IS_AJAX_REQUEST' ) ) define( 'FLARE_IS_AJAX_REQUEST', ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) );

// Minimum time allowed between requesting new statistics numbers. Default is 30 seconds. Do NOT set this to 0 or it will take a while to load the stats numbers on every page load.
if( !defined( 'FLARE_STATS_CACHE_LENGTH' ) ) define( 'FLARE_STATS_CACHE_LENGTH', 30 );
