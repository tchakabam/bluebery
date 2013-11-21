<?
/*
Copyright 2006 Stephan Hesse Stevomania

    This file is part of bluebery.

    bluebery is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    bluebery is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with bluebery; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/

//
//PARAMETERS
//

//bluebery tables and table columns physical names...
$content_table = "b_content";
$content_col = "b_content0";
$date_col = "b_content1";
$key_col = "b_content2";
$type_col = "b_content3";
$lang_col = "b_content4";
$auth_table = "b_auth";

//language
$DEFAULT_LANG = "en";

//key factory param for tries to find unused key
$MAX_TRIES = 1000;

/*
//DEFINE CONSTANTS IN PHP
define (CONTENT_TABLE, $content_table);
define (CONTENT_COL, $content_col);
define (DATE_COL, $date_col);
define (KEY_COL, $key_col);
define (TYPE_COL, $type_col);
define (LANG_COL, $lang_col);
define (AUTH_COL, $auth_table);
define (DEFAULT_LANG, $DEFAULT_LANG);
*/
?>