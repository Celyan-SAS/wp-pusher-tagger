<?php
/**
 * @package WP Pusher tagger
 * @author Yann Dubois
 * @version 0.1.0
 */

/*
 Plugin Name: WP Pusher tagger
Plugin URI: http://www.celyan.com
Description: Generate GitHub code tag when the code is pushed on the production server
Version: 0.1.0
Author: Yann Dubois
Author URI: http://www.yann.com/
License: GPL2
*/

/**
 * @copyright 2013  Yann Dubois ( email : yann _at_ abc.fr )
 *
 *  Original development of this plugin was kindly funded by Auxane Concept
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 Revision 0.1.0:
 - Original alpha release 00
 */

/** Class includes **/
include_once( dirname( __FILE__ ) . '/inc/celyan_wppt.inc.php' );	// main class

/** Instantiate plugins's main class **/
global $celyan_wppt;
$celyan_wppt = new celyanWppt();
?>