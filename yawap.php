<?php
/*
Plugin Name: YAWAP
Plugin URI: http://yawap.net
Description: Yet Another WordPress Allopass Plugin
Version: 1.02
Author: Jeriel BELAICH
Author URI: http://yawap.net
License:
    Copyright 2011-2012  Jeriel BELAICH  (email : jeriel@yawap.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function yawap_insert( $atts, $content ) {
	
	$AUTH = $atts['auth'];
	list($ids, $idd, $site) = split("/", $AUTH);
	
	// Détection du champ RECALL
	$RECALL = $_GET["RECALL"];
	if( trim($RECALL) == "" ) {
		list($language, $country) = split( "-", get_bloginfo ('language') );
		return "
<!-- Begin Allopass Checkout-Button Code -->
<center>
<script type=\"text/javascript\" src=\"https://payment.allopass.com/buy/checkout.apu?ids=$ids&idd=$idd&ap_ca_idc[0]=1134&ap_ca_mnt[0]=10&lang=$language\"></script>
<noscript>
<a href=\"https://payment.allopass.com/buy/buy.apu?ids=$ids&idd=$idd&ap_ca_idc[0]=1134&ap_ca_mnt[0]=10\" style=\"border:0\">
<img src=\"https://payment.allopass.com/static/buy/button/fr/162x56.png\" style=\"border:0\" alt=\"Buy now!\" />
</a>
</noscript>
</center>
<!-- End Allopass Checkout-Button Code -->";
	} else {
		$RECALL = urlencode( $RECALL );
		$AUTH = urlencode( $AUTH );
		
		$r = @file( "http://payment.allopass.com/api/checkcode.apu?code=$RECALL&auth=$AUTH" );
		if( substr( $r[0],0,2 ) != "OK" ) {
			return "incorrect code";
		} else {
			return $content;
		}
	}
}

add_shortcode('yawap', 'yawap_insert');