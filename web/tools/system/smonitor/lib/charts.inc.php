<?php
/*
 * $Id$
 * Copyright (C) 2008-2010 Voice Sistem SRL
 *
 * This file is part of opensips-cp, a free Web Control Panel Application for 
 * OpenSIPS SIP server.
 *
 * opensips-cp is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * opensips-cp is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


// http://www.maani.us/charts/

function InsertChart( $flash_file, $library_path, $php_source, $width , $height, $bg_color, $transparent=false, $license="J1X-3KMWKRL.HSK5T4Q79KLYCK07EK" )
{
	$php_source=urlencode($php_source);
	$library_path=urlencode($library_path);

	$html="<OBJECT classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' ";
	$html.="WIDTH='".$width."' HEIGHT='".$height."' id='charts' ALIGN=''>";
	$u=(strpos ($flash_file,"?")===false)? "?" : ((substr($flash_file, -1)==="&")? "":"&");
	$html.="<PARAM NAME='movie' VALUE='".$flash_file.$u."library_path=".$library_path."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="'> <PARAM NAME='quality' VALUE='high'> <PARAM NAME='bgcolor' VALUE='#".$bg_color."'> ";
	if($transparent){$html.="<PARAM NAME='wmode' VALUE='transparent'> ";}
	$html.="<EMBED src='".$flash_file.$u."library_path=".$library_path."&php_source=".$php_source;
	if($license!=null){$html.="&license=".$license;}
	$html.="' quality='high' bgcolor='#".$bg_color."' WIDTH='".$width."' HEIGHT='".$height."' NAME='charts' ALIGN='' swLiveConnect='true' ";
	if($transparent){$html.="wmode='transparent' ";}
	$html.="TYPE='application/x-shockwave-flash' PLUGINSPAGE='http://www.macromedia.com/go/getflashplayer'></EMBED></OBJECT>";
	return $html;
}
?>
